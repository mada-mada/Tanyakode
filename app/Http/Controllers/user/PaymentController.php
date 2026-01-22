<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Voucher;
use App\Models\User;
use App\Models\Order; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 

class PaymentController extends Controller
{
    public function show($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        return view('user.courses.show', compact('course'));
    }

    // =========================
    // CHECK VOUCHER
    // =========================
    public function checkVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        $voucher = Voucher::where('code', $request->code)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher tidak valid / sudah kadaluarsa.'
            ], 404);
        }

        $discount = (int) $voucher->discount_amount;
        $finalPrice = max((int) $course->price - $discount, 0);

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher berhasil digunakan!',
            'data' => [
                'original_price' => (int) $course->price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'voucher_id' => $voucher->id
            ]
        ]);
    }

    // =========================
    // PROSES PEMBAYARAN
    // =========================
    public function processPayment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'voucher_id' => 'nullable|exists:vouchers,id'
        ]);

        try {
            $user = Auth::user();
            $course = Course::findOrFail($request->course_id);

            // 1. HITUNG HARGA DULU
            $finalAmount = (int) $course->price;
            $voucherUsed = null;

            if ($request->filled('voucher_id')) {
                $voucher = Voucher::where('id', $request->voucher_id)
                    ->where('user_id', $user->id)
                    ->where('is_active', true)
                    ->first();

                if ($voucher) {
                    $finalAmount = max((int) $course->price - (int) $voucher->discount_amount, 0);
                    $voucherUsed = $voucher;
                }
            }

            // 2. CEK TRANSAKSI PENDING & AUTO-UPDATE STATUS
            $existing = Order::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('payment_status', 'pending')
                ->first();

            if ($existing) {
                // Konfigurasi Server Key
                $serverKey = config('services.midtrans.serverKey');
                $isProduction = config('services.midtrans.isProduction');
                $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

                // Cek Status ke API Midtrans (Get Status)
                $response = Http::withBasicAuth($serverKey, '')
                    ->withOptions(['verify' => false])
                    ->get("$baseUrl/v2/{$existing->reference_id}/status");

                if ($response->successful()) {
                    $status = $response->json()['transaction_status'] ?? 'pending';
                    
                    // Jika di Midtrans sudah sukses (settlement/capture), update database kita!
                    if ($status == 'settlement' || $status == 'capture') {
                        $existing->update(['payment_status' => 'settlement']);
                        
                        return response()->json([
                            'status' => 'paid',
                            'message' => 'Pembayaran Anda telah berhasil dikonfirmasi. Halaman akan dimuat ulang.'
                        ]);
                    }
                    // Jika di Midtrans sudah kadaluarsa/batal, batalkan lokal juga
                    else if ($status == 'expire' || $status == 'cancel') {
                        $existing->update(['payment_status' => 'failed']);
                        // Lanjut ke bawah untuk buat order baru...
                    }
                    // Jika masih pending tapi harganya SAMA, kembalikan token lama
                    else if ((int)$existing->total_amount === $finalAmount) {
                         return response()->json([
                            'status' => 'pending',
                            'snap_token' => $existing->snap_token,
                            'order_id' => $existing->reference_id
                        ]);
                    } else {
                        // Harga beda, cancel yang lama
                        $existing->update(['payment_status' => 'cancelled']);
                    }
                }
            }

            // 3. BUAT ORDER BARU (Jika tidak ada pending yang valid)
            DB::beginTransaction();

            $orderId = 'INV-' . time() . '-' . Str::random(5);

            $transaction = Order::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'reference_id' => $orderId,
                'total_amount' => $finalAmount,
                'payment_status' => ($finalAmount == 0) ? 'settlement' : 'pending',
                'snap_token' => null
            ]);

            if ($voucherUsed) {
                $voucherUsed->update(['is_active' => false]);
            }

            if ($finalAmount > 0) {
                // Request Snap Token Baru...
                $serverKey = config('services.midtrans.serverKey');
                $isProduction = config('services.midtrans.isProduction');
                $url = $isProduction 
                    ? 'https://app.midtrans.com/snap/v1/transactions' 
                    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

                $authString = base64_encode($serverKey . ':');

                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $authString,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->withOptions(['verify' => false])
                ->post($url, [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $finalAmount
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                    'item_details' => [[
                        'id' => $course->id,
                        'price' => $finalAmount,
                        'quantity' => 1,
                        'name' => substr($course->title, 0, 50)
                    ]]
                ]);

                if ($response->failed()) throw new \Exception("Gagal koneksi Midtrans");

                $snapToken = $response->json()['token'];
                $transaction->update(['snap_token' => $snapToken]);
                
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_id' => $orderId
                ]);

            } else {
                // Gratis
                DB::commit();
                return response()->json(['status' => 'free', 'redirect_url' => route('user.payment.success')]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PAYMENT ERROR: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    // =========================
    // SUCCESS CALLBACK
    // =========================
    public function success(Request $request)
{
    $course = null;

    if ($request->has('order_id')) {
        $order = Order::where('reference_id', $request->order_id)->with('course')->first();

        if ($order) {
            // Update status jadi lunas
            $order->update(['payment_status' => 'settlement']);

            // Ambil data course dari relasi order
            $course = $order->course;
        }
    }

    // Jika course ketemu, tampilkan halaman show course lagi
    if ($course) {
        return redirect()->route('user.courses.show', $course->slug)->with('success', 'Pembayaran Berhasil!');
    }

    // Jika tidak ketemu (akses langsung tanpa order_id), lempar ke daftar kursus
    return redirect()->route('user.courses.index');
   }
}
