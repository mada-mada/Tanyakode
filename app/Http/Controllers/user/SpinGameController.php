<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpinReward;
use App\Models\SpinLog;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SpinGameController extends Controller
{
    public function index()
    {
        $rewards = SpinReward::where('is_active', true)
            ->orderBy('id')
            ->get();
        
        $todaySpins = SpinLog::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->count();
                        
        $maxSpinsPerDay = 1;
        $canSpin = $todaySpins < $maxSpinsPerDay;

        return view('user.spin_game.index', compact('rewards', 'canSpin', 'todaySpins', 'maxSpinsPerDay'));
    }

    public function spinProcess(Request $request)
    {
        $user = Auth::user();
        $maxSpinsPerDay = 1;

        $todaySpins = SpinLog::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($todaySpins >= $maxSpinsPerDay) {
            return response()->json([
                'success' => false,
                'message' => 'Kesempatan spin hari ini sudah habis. Coba lagi besok!'
            ], 403);
        }

        $rewards = SpinReward::where('is_active', true)
            ->orderBy('id')
            ->get();
        
        if ($rewards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada hadiah yang tersedia.'
            ], 400);
        }

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

        if (!$winningReward) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menentukan hadiah.'
            ], 500);
        }

        DB::beginTransaction();
        try {
            $spinLog = SpinLog::create([
                'user_id' => $user->id,
                'spin_reward_id' => $winningReward->id,
                'spin_date' => now(),
                'result_type' => $winningReward->type,
                'reward_detail' => $winningReward->name,
                'created_at' => now(),
            ]);

            if ($winningReward->type == 'voucher' && $winningReward->voucher_amount > 0) {
                Voucher::create([
                    'code' => 'SPIN-' . strtoupper(Str::random(8)),
                    'user_id' => $user->id,
                    'discount_amount' => $winningReward->voucher_amount,
                    'discount_type' => 'fixed',
                    'valid_until' => now()->addDays(30),
                    'is_used' => false,
                ]);
            }

            if ($winningReward->type == 'free_course') {
                $freeCourse = DB::table('courses')
                    ->where('price', 0)
                    ->where('is_premium', 0)
                    ->first();
                
                if ($freeCourse) {
                    $alreadyEnrolled = DB::table('course_enrollments')
                        ->where('user_id', $user->id)
                        ->where('course_id', $freeCourse->id)
                        ->exists();

                    if (!$alreadyEnrolled) {
                        DB::table('course_enrollments')->insert([
                            'user_id' => $user->id,
                            'course_id' => $freeCourse->id,
                            'progress_percentage' => 0,
                            'status' => 'active',
                            'is_paid' => 1,
                            'enrolled_at' => now(),
                            'created_at' => now()
                        ]);
                    }
                }
            }

            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'spin_reward',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Spin Wheel Result 🎁',
                    'message' => $this->getRewardMessage($winningReward),
                    'reward_type' => $winningReward->type,
                    'reward_name' => $winningReward->name
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            $degree = $this->calculateDegree($winningReward->id);

            $response = [
                'success' => true,
                'type' => $winningReward->type,
                'amount' => (float) $winningReward->voucher_amount,
                'degree' => $degree,
                'message' => $this->getRewardMessage($winningReward),
                'reward_name' => $winningReward->name,
                'reward_color' => $winningReward->color,
                'remaining_spins' => $maxSpinsPerDay - ($todaySpins + 1)
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    private function getRewardMessage($reward)
    {
        switch ($reward->type) {
            case 'voucher':
                return 'Selamat! Anda mendapatkan voucher Rp ' . number_format($reward->voucher_amount, 0, ',', '.');
            case 'free_course':
                return 'Selamat! Anda mendapatkan akses course gratis!';
            case 'zonk':
                return 'Maaf, Anda kurang beruntung. Coba lagi besok!';
            default:
                return 'Selamat! Anda mendapatkan hadiah!';
        }
    }

    private function calculateDegree($rewardId)
    {
        $rewards = SpinReward::where('is_active', true)
            ->orderBy('id')
            ->get();
        
        $segments = $rewards->pluck('id')->values();
        
        $index = $segments->search($rewardId);
        
        if ($index === false) {
            return 0;
        }
        
        $segmentAngle = 360 / $segments->count();
        return ($index * $segmentAngle) + ($segmentAngle / 2);
    }
}