<?php
// ══════════════════════════════════════════════════════════════
// app/Http/Controllers/GameController.php
// ══════════════════════════════════════════════════════════════
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * List all games (with optional category filter).
     */
    public function index(Request $request)
    {
        $query = Game::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $games = $query
            ->orderByRaw("FIELD(status, 'live', 'soon', 'maintenance')")
            ->orderBy('total_plays', 'desc')
            ->get();

        return view('dashboard.games.index', compact('games'));
    }

    /**
     * Show the play page for a single game.
     */
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

    /**
     * Save a game result (called via AJAX from JS game engine).
     * Calculates coins proportional to score and awards them.
     */
    public function saveResult(Request $request, Game $game)
    {
        $request->validate([
            'score'            => 'required|integer|min:0',
            'won'              => 'required|boolean',
            'duration_seconds' => 'required|integer|min:0',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── Daily play limit ─────────────────────────────────
        $todayPlays = GameSession::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->whereDate('created_at', today())
            ->count();

        if ($todayPlays >= $game->daily_play_limit) {
            return response()->json(['error' => 'Daily play limit reached.'], 422);
        }

        // ── Coin calculation ─────────────────────────────────
        // Win  → up to full coin_reward based on score vs expected max
        // Loss → 15% consolation coins
        $maxScore = $this->expectedMaxScore($game->slug);
        $ratio    = $maxScore > 0 ? min($request->score / $maxScore, 1.0) : ($request->boolean('won') ? 1.0 : 0.15);

        $coinsEarned = $request->boolean('won')
            ? (int) max(5, round($game->coin_reward * $ratio))
            : (int) max(3, round($game->coin_reward * 0.15));

        // ── Persist session ──────────────────────────────────
        $session = GameSession::create([
            'user_id'          => $user->id,
            'game_id'          => $game->id,
            'score'            => $request->score,
            'coins_earned'     => $coinsEarned,
            'won'              => $request->boolean('won'),
            'duration_seconds' => $request->duration_seconds,
            'meta'             => $request->meta ?? null,
        ]);

        // ── Award coins & XP ─────────────────────────────────
        $game->increment('total_plays');
        $user->addCoins($coinsEarned, 'game_win', "🎮 Played {$game->name}", $session);

        // ── Badge check ──────────────────────────────────────
        app(\App\Services\BadgeService::class)->checkAndAward($user);

        return response()->json([
            'coins_earned' => $coinsEarned,
            'total_coins'  => $user->fresh()->coins,
            'message'      => $request->boolean('won') ? '🏆 You won!' : '🎮 Good try!',
        ]);
    }

    /**
     * Expected maximum score per game slug (used for coin scaling).
     * Tune these as your game difficulty changes.
     */
    private function expectedMaxScore(string $slug): int
    {
        return match ($slug) {
            'snake-turbo'  => 150,   // 10pts per apple, 15 apples = full coins
            'word-puzzle'  => 120,   // 15pts per word × 8 words
            'brain-quiz'   => 80,    // 10pts per question × 8 questions
            'memory-cards' => 120,   // 20–moves per pair × 8 pairs
            'spin-win'     => 50,    // max prize value
            default        => 100,
        };
    }
}
