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
use Illuminate\Support\Facades\Log; // Tambahan untuk cek error di storage/logs

class PaymentController extends Controller
{
    // HAPUS function __construct() karena kita pakai HTTP Client manual
    // agar tidak error jika library midtrans belum terinstall via composer

    public function show($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        return view('user.courses.show', compact('course'));
    }

    public function processPayment(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'voucher_id' => 'nullable|exists:vouchers,id'
        ]);

        try {
            $user = Auth::user();
            $course = Course::find($request->course_id);
            
            // 2. Cek Transaksi Pending (Return Token Lama jika ada)
            $existing = Order::where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->where('payment_status', 'pending')
                        ->first();
            
            if($existing && $existing->snap_token) {
                return response()->json([
                    'status' => 'pending',
                    'snap_token' => $existing->snap_token,
                    'order_id' => $existing->reference_id
                ]);
            }

            // 3. Hitung Harga
            $finalAmount = (int) $course->price;
            $voucherUsed = null;

            if ($request->filled('voucher_id')) {
                $voucher = Voucher::where('id', $request->voucher_id)
                            ->where('user_id', $user->id)
                            ->where('is_active', true)
                            ->first();
                if ($voucher) {
                    $finalAmount = max((int)$course->price - (int)$voucher->amount, 0);
                    $voucherUsed = $voucher;
                }
            }

            // 4. Mulai Transaksi Database
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

            // 5. Request Token ke Midtrans (Jika berbayar)
            if ($finalAmount > 0) {
                
                // Ambil Config (Pastikan sesuai services.php Anda)
                $serverKey = config('services.midtrans.serverKey');
                $isProduction = config('services.midtrans.isProduction');

                // DEBUG: Cek apakah Server Key terbaca
                if(empty($serverKey)) {
                    throw new \Exception("Server Key Midtrans belum diatur di .env atau config.");
                }

                $url = $isProduction 
                    ? 'https://app.midtrans.com/snap/v1/transactions' 
                    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

                // Encode Server Key ke Base64 untuk Basic Auth
                $authString = base64_encode($serverKey . ':');

                // Kirim Request
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $authString,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->withOptions(['verify' => false]) // Bypass SSL di Localhost (Penting!)
                ->post($url, [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $finalAmount
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                    'item_details' => [
                        [
                            'id' => $course->id,
                            'price' => $finalAmount,
                            'quantity' => 1,
                            'name' => substr($course->title, 0, 50) // Midtrans max name length 50
                        ]
                    ]
                ]);

                // Cek Jika Request Gagal
                if ($response->failed()) {
                    Log::error('Midtrans Error:', $response->json()); // Log error ke file
                    throw new \Exception("Gagal menghubungi Midtrans: " . $response->body());
                }

                $jsonBody = $response->json();
                $snapToken = $jsonBody['token'] ?? null;
                
                if (!$snapToken) {
                     throw new \Exception("Token tidak ditemukan dalam respon Midtrans.");
                }

                // Update Token ke Database
                $transaction->update(['snap_token' => $snapToken]);
                
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_id' => $orderId
                ]);

            } else {
                // Jika Gratis
                DB::commit();
                return response()->json([
                    'status' => 'free',
                    'redirect_url' => route('user.payment.success', ['order_id' => $orderId, 'transaction_status' => 'settlement'])
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // Catat error sebenarnya di storage/logs/laravel.log
            Log::error('PAYMENT ERROR: ' . $e->getMessage()); 
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Function Success (Halaman Redirect)
    public function success(Request $request)
    {
        // Logika update status database sederhana
        if ($request->has('order_id')) {
            $order = Order::where('reference_id', $request->order_id)->first();
            if($order) {
                $order->update(['payment_status' => 'settlement']);
            }
        }
        return view('user.courses.learning');
    }
}