<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
      
    }

    // Menampilkan Daftar Course 
    public function index()
    {
        $courses = Course::all(); // Mengambil semua course
        return view('user.courses.index', compact('courses'));
    }

    // Menampilkan Detail Course & Tombol Bayar
 public function show($slug)
{
    // 1. Ambil Data Course
    $course = Course::where('slug', $slug)->firstOrFail();

    $orders = Transaction::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->orderBy('created_at', 'desc')
                ->get();

    // 2. Konfigurasi Midtrans
    Config::$serverKey = config('services.midtrans.serverKey');
    Config::$isProduction = config('services.midtrans.isProduction');
    Config::$isSanitized = config('services.midtrans.isSanitized');
    Config::$is3ds = config('services.midtrans.is3ds');

    // 3. Cek Transaksi Pending (Untuk tombol Bayar Sekarang)
    $transaction = Transaction::where('user_id', Auth::id())
                            ->where('course_id', $course->id)
                            ->where('payment_status', 'pending')
                            ->first();

    $snapToken = null;

    if ($transaction) {
        // Jika ada transaksi pending, pakai token lama
        $snapToken = $transaction->snap_token;
    } else {
        // Jika tidak ada pending, Buat Transaksi Baru
        $orderId = 'INV-' . time() . '-' . Str::random(5);
        $grossAmount = (int) round($course->price);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            dd([
                'Pesan Error' => $e->getMessage(),
                'Server Key Config' => Config::$serverKey,
            ]);
        }

        // Simpan Transaksi Baru ke Database
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'reference_id' => $orderId,
            'total_amount' => $grossAmount,
            'payment_status' => 'pending',
            'snap_token' => $snapToken  
        ]);
        
        // Refresh list orders agar transaksi baru ini langsung muncul di list bawah
        $orders = Transaction::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->orderBy('created_at', 'desc')
                ->get();
    }

    // 4. Kirim semua variabel ke View
    // Pastikan 'orders' ada di sini
    return view('user.courses.show', compact('course', 'snapToken', 'transaction', 'orders'));
}

    public function retry($id)
{
    // 1. Ganti 'Order' jadi 'Transaction'
    $oldTransaction = Transaction::findOrFail($id);

    // 2. Buat instance Transaction BARU
    $newTransaction = new Transaction();
    
    // 3. Salin data dari transaksi lama
    $newTransaction->user_id = $oldTransaction->user_id;
    $newTransaction->course_id = $oldTransaction->course_id;
    $newTransaction->total_amount = $oldTransaction->total_amount; // Sesuaikan nama kolom (amount/total_amount?)
    
    // 4. Generate Reference ID Baru
    $newTransaction->reference_id = 'INV-' . time() . '-' . Str::random(5);
    
    $newTransaction->payment_status = 'pending';
    $newTransaction->save();

    // 5. Buat Snap Token Baru
    $params = [
        'transaction_details' => [
            'order_id' => $newTransaction->reference_id, // Gunakan reference_id yang baru
            'gross_amount' => $newTransaction->total_amount,
        ],
        'customer_details' => [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        
        // Simpan token ke database
        $newTransaction->snap_token = $snapToken;
        $newTransaction->save();
        
        // Ganti 'user.courses.show' dengan nama route yang sesuai untuk halaman detail course Anda
        // Dan pastikan parameternya slug course
        return redirect()->route('user.courses.show', $oldTransaction->course->slug)
                        ->with('success', 'Silakan lanjutkan pembayaran baru.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal membuat transaksi baru: ' . $e->getMessage());
    }
}
    public function success(Request $request)
{
    // Midtrans akan mengirimkan data order_id dll lewat query string
    // contoh: /user/payment/success?order_id=ORD-123&status_code=200&transaction_status=settlement
    
    $orderId = $request->query('order_id');
    $statusCode = $request->query('status_code');
    $transactionStatus = $request->query('transaction_status');

    // OPSI 1: Tampilkan pesan sederhana dulu (untuk debugging)
    // return "Pembayaran Berhasil! Order ID: " . $orderId;

    // OPSI 2: Tampilkan View (Disarankan)
    // Pastikan Anda nanti membuat file view: resources/views/user/payment/success.blade.php
    return view('user.payment.success', [
        'order_id' => $orderId,
        'status' => $transactionStatus
    ]);
}
public function failed(Request $request)
{
    $orderId = $request->query('order_id');

    // Pastikan mencari berdasarkan kolom yang benar (reference_id)
    $transaction = Transaction::where('reference_id', $orderId)->first();

    if ($transaction) {
        $transaction->payment_status = 'failed';
        $transaction->save();
    }

    return redirect()->back()->with('error', 'Pembayaran dibatalkan.');
}
}