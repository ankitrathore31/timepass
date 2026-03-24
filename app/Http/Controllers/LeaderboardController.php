<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'weekly');

        $query = User::orderBy('coins', 'desc');

        if ($period === 'weekly') {
            // rank by coins earned this week
            $query = User::select('users.*')
                ->selectRaw('COALESCE(SUM(ct.amount),0) as period_coins')
                ->leftJoin('coin_transactions as ct', function ($join) {
                    $join->on('ct.user_id', '=', 'users.id')
                         ->where('ct.type', 'earn')
                         ->where('ct.created_at', '>=', now()->startOfWeek());
                })
                ->groupBy('users.id')
                ->orderBy('period_coins', 'desc');
        }

        $topPlayers = $query->take(50)->get();
        $authUser   = Auth::user();
        $userRank   = $topPlayers->search(fn($u) => $u->id === $authUser->id) + 1;

        return view('dashboard.leaderboard', compact('topPlayers', 'userRank', 'period'));
    }
}
