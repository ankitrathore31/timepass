<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\UserReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RewardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rewards = Reward::active()->orderBy('coins_required')->get();

        $transactions = $user->coinTransactions()
                              ->latest()
                              ->take(10)
                              ->get();

        return view('dashboard.reward.index', compact('user', 'rewards', 'transactions'));
    }

    public function redeem(Request $request, Reward $reward)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $reward->is_active || ! $reward->isAvailable()) {
            return back()->with('toast_error', '❌ This reward is not available.');
        }

        if ($user->coins < $reward->coins_required) {
            return back()->with('toast_error', '❌ Not enough coins!');
        }

        $userReward = UserReward::create([
            'user_id'      => $user->id,
            'reward_id'    => $reward->id,
            'status'       => 'pending',
            'voucher_code' => strtoupper(Str::random(12)),
        ]);

        $user->spendCoins(
            $reward->coins_required,
            'reward_redeem',
            "🎁 Redeemed: {$reward->name}",
            $userReward
        );

        // Decrement stock if limited
        if ($reward->stock > 0) {
            $reward->decrement('stock');
        }

        return back()->with('toast_success', "🎁 {$reward->name} redeemed! Check your email.");
    }
}
