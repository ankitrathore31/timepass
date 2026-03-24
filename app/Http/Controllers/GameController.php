<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // List all games
    public function index(Request $request)
    {
        $query = Game::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $games = $query->orderByRaw("FIELD(status,'live','soon','maintenance')")
                       ->orderBy('total_plays', 'desc')
                       ->get();

        return view('dashboard.games.index', compact('games'));
    }

    // Show single game play page
    public function show(Game $game)
    {
        abort_if($game->status === 'maintenance', 503, 'Game under maintenance.');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $todayPlays = GameSession::where('user_id', $user->id)
                                  ->where('game_id', $game->id)
                                  ->whereDate('created_at', today())
                                  ->count();

        $canPlay = $todayPlays < $game->daily_play_limit;

        $leaderboard = GameSession::with('user')
                                   ->where('game_id', $game->id)
                                   ->orderBy('score', 'desc')
                                   ->take(5)
                                   ->get();

        return view('dashboard.games.show', compact('game', 'canPlay', 'todayPlays', 'leaderboard'));
    }

    // Save result after a game is played (called via AJAX)
    public function saveResult(Request $request, Game $game)
    {
        $request->validate([
            'score'            => 'required|integer|min:0',
            'won'              => 'required|boolean',
            'duration_seconds' => 'required|integer|min:0',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Daily limit check
        $todayPlays = GameSession::where('user_id', $user->id)
                                  ->where('game_id', $game->id)
                                  ->whereDate('created_at', today())
                                  ->count();

        if ($todayPlays >= $game->daily_play_limit) {
            return response()->json(['error' => 'Daily limit reached.'], 422);
        }

        $coinsEarned = $request->boolean('won') ? $game->coin_reward : (int) ($game->coin_reward * 0.2);

        $session = GameSession::create([
            'user_id'          => $user->id,
            'game_id'          => $game->id,
            'score'            => $request->score,
            'coins_earned'     => $coinsEarned,
            'won'              => $request->boolean('won'),
            'duration_seconds' => $request->duration_seconds,
            'meta'             => $request->meta ?? null,
        ]);

        $game->increment('total_plays');
        $user->addCoins($coinsEarned, 'game_win', "🎮 Played {$game->name}", $session);

        // Badge check
        app(\App\Services\BadgeService::class)->checkAndAward($user);

        return response()->json([
            'coins_earned'  => $coinsEarned,
            'total_coins'   => $user->fresh()->coins,
            'message'       => $request->boolean('won') ? '🏆 You won!' : '🎮 Good try!',
        ]);
    }
}
