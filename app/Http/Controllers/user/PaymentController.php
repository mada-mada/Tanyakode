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
use Midtrans\Config; // Tetap butuh Config untuk Server Key

// --- PENTING: LIBRARY HTTP UNTUK MANUAL REQUEST ---
use Illuminate\Support\Facades\Http; 
// -------------------------------------------------

class PaymentController extends Controller
{
    public function __construct()
    {
        // Setup Config Dasar
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        
        // Matikan Sanitizer (Penyebab error Array Key 10023)
        Config::$isSanitized = false; 
        Config::$is3ds = false;
    }

    public function index()
    {
        $courses = Course::all();
        return view('user.courses.index', compact('courses'));
    }

    // --- FUNGSI SHOW (YANG TADI HILANG) ---
    public function show($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // Ambil riwayat order user
        $orders = Order::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Cek apakah ada transaksi pending
        $pendingTransaction = Order::where('user_id', Auth::id())
                                ->where('course_id', $course->id)
                                ->where('payment_status', 'pending')
                                ->first();

        // Jika ada pending, ambil token lamanya
        $snapToken = $pendingTransaction ? $pendingTransaction->snap_token : null;

        return view('user.courses.show', compact('course', 'snapToken', 'pendingTransaction', 'orders'));
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = auth()->user();
        $course = Course::find($request->course_id);

        $voucher = Voucher::where('code', $request->code)
                    ->where('user_id', $user->id)
                    ->where('is_active', true)
                    ->first();

        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'Voucher tidak valid.'], 404);
        }

        $discount = $voucher->amount;
        $finalPrice = max($course->price - $discount, 0);

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher valid!',
            'data' => [
                'original_price' => $course->price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'voucher_id' => $voucher->id 
            ]
        ]);
    }

    // --- FUNGSI PROCESS PAYMENT (FIXED MANUAL REQUEST) ---
    public function processPayment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'voucher_id' => 'nullable|exists:vouchers,id'
        ]);

        $user = Auth::user();
        $course = Course::find($request->course_id);
        
        $existing = Order::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->where('payment_status', 'pending')
                    ->first();
        
        if($existing) {
            return redirect()->route('user.courses.show', $course->slug)->with('error', 'Selesaikan pembayaran sebelumnya.');
        }

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

        DB::beginTransaction();
        try {
            $orderId = 'INV-' . time() . '-' . Str::random(5);

            // 1. Simpan ke Database
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
                
                // --- KODE MANUAL (BYPASS SDK) ---
                $serverKey = config('services.midtrans.serverKey');
                $isProduction = config('services.midtrans.isProduction');

                $url = $isProduction 
                    ? 'https://app.midtrans.com/snap/v1/transactions' 
                    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

                $response = Http::withBasicAuth($serverKey, '')
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->withOptions(['verify' => false]) // Bypass SSL Localhost
                    ->post($url, [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => $finalAmount
                        ],
                        'customer_details' => [
                            'first_name' => $user->name,
                            'email' => $user->email,
                        ]
                    ]);

                if ($response->failed()) {
                    throw new \Exception("Gagal koneksi ke Midtrans: " . $response->body());
                }

                $jsonBody = $response->json();
                $snapToken = $jsonBody['token'] ?? null;
                
                if (!$snapToken) {
                     throw new \Exception("Token tidak ditemukan. Response: " . $response->body());
                }

                $transaction->update(['snap_token' => $snapToken]);
                // ---------------------------------

            } else {
                DB::commit();
                return redirect()->route('user.payment.success', ['order_id' => $orderId, 'transaction_status' => 'settlement']);
            }

            DB::commit();
            return redirect()->route('user.courses.show', $course->slug);

        } catch (\Exception $e) {
            DB::rollBack();
            dd("ERROR PAYMENT: " . $e->getMessage());
        }
    }

    public function retry($id)
    {
        $oldTransaction = Order::findOrFail($id);
        $course = $oldTransaction->course; 
        $oldTransaction->update(['payment_status' => 'cancelled']);
        return redirect()->route('user.courses.show', $course->slug)->with('info', 'Buat pesanan baru.');
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');

        if ($orderId) {
            $trx = Order::where('reference_id', $orderId)->first();
            if ($trx && in_array($transactionStatus, ['settlement', 'capture'])) {
                $trx->update(['payment_status' => 'settlement']);
            }
        }
        return view('user.payment.success', ['order_id' => $orderId, 'status' => $transactionStatus]);
    }

    public function failed(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Order::where('reference_id', $orderId)->first();
        if ($transaction) {
            $transaction->update(['payment_status' => 'failed']);
        }
        return redirect()->route('user.courses.index')->with('error', 'Pembayaran Gagal.');
    }
}