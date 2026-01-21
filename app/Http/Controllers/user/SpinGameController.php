<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpinReward;
use App\Models\SpinLog;
use App\Models\Voucher; // Pastikan Model Voucher ada
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SpinGameController extends Controller
{
    public function index()
    {
        // Ambil data hadiah untuk ditampilkan di roda JS
        $rewards = SpinReward::where('is_active', true)->get();
        
        // Cek sisa kesempatan hari ini
        $todaySpins = SpinLog::where('user_id', Auth::id())
                        ->whereDate('created_at', Carbon::today())
                        ->count();
                        
        $maxSpinsPerDay = 3; // Atur batas harian disini (misal: 1 kali per hari)
        $canSpin = $todaySpins < $maxSpinsPerDay;

        return view('user.spin_game.index', compact('rewards', 'canSpin'));
    }

    public function spinProcess()
    {
        $user = Auth::user();
        $maxSpinsPerDay = 3;

        // 1. Cek Validasi Limit Harian
        $todaySpins = SpinLog::where('user_id', $user->id)
                        ->whereDate('created_at', Carbon::today())
                        ->count();

        if ($todaySpins >= $maxSpinsPerDay) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kesempatan habis. Coba lagi besok!'
            ], 403);
        }

        // 2. Logika Probabilitas (Weighted Random)
        $rewards = SpinReward::where('is_active', true)->get();
        $totalWeight = $rewards->sum('probability');
        $random = rand(1, $totalWeight);
        $currentWeight = 0;
        $winningReward = null;

        foreach ($rewards as $reward) {
            $currentWeight += $reward->probability;
            if ($random <= $currentWeight) {
                $winningReward = $reward;
                break;
            }
        }

        // 3. Simpan Transaksi Database
        DB::beginTransaction();
        try {
            // Catat Log
            SpinLog::create([
                'user_id' => $user->id,
                'spin_reward_id' => $winningReward->id,
                'spin_date' => now(),
                'created_at' => now(),
            ]);

            // Jika hadiahnya Voucher, buatkan Vouchernya
            if ($winningReward->type == 'voucher') {
               Voucher::create([
                     'code' => 'SPIN-' . strtoupper(Str::random(5)),
                     'user_id' => $user->id,
                     'discount_amount' => $winningReward->voucher_amount, // SESUAI DB
                     'valid_until' => now()->addDays(7), // atau sesuai aturan bisnismu
                     'is_active' => true,
                ]);

            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'reward_id' => $winningReward->id,
                'message' => $winningReward->type == 'zonk' ? 'Anda kurang beruntung!' : 'Selamat! Anda dapat voucher.'
            ]);

        // ... kode sebelumnya (DB::beginTransaction, logic spin, dll) ...

} catch (\Exception $e) {
    DB::rollBack();
    
    // --- UBAH BAGIAN INI ---
    return response()->json([
        'status' => 'error',
        'message' => 'DEBUG ERROR: ' . $e->getMessage(), // Tampilkan pesan error asli
        'line' => $e->getLine(), // Tampilkan baris error (opsional)
        'file' => $e->getFile()  // Tampilkan file error (opsional)
    ], 500);
}
    }
}