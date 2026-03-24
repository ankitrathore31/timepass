<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user()->load(['gameSessions', 'badges', 'coinTransactions']);

        $quickGames = Game::live()->orderBy('total_plays', 'desc')->take(5)->get();

        $leaderboard = User::orderBy('coins', 'desc')
                           ->take(5)
                           ->get(['id', 'name', 'username', 'avatar', 'coins', 'level']);

        $userRank = User::where('coins', '>', $user->coins)->count() + 1;

        $todaySessionCount = $user->gameSessions()
                                  ->whereDate('created_at', today())
                                  ->count();

        return view('dashboard.dashboard', compact(
            'user', 'quickGames', 'leaderboard', 'userRank', 'todaySessionCount'
        ));
    }
}
