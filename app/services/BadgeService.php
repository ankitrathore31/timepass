<?php
namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class BadgeService
{
    public function checkAndAward(User $user): void
    {
        $badges = Badge::all();

        foreach ($badges as $badge) {
            if ($user->badges->contains($badge->id)) continue;

            $earned = match ($badge->condition_key) {
                'streak_7'        => $user->streak_days >= 7,
                'streak_30'       => $user->streak_days >= 30,
                'level_10'        => $user->level >= 10,
                'level_50'        => $user->level >= 50,
                'games_played_10' => $user->gameSessions()->count() >= 10,
                'games_played_50' => $user->gameSessions()->count() >= 50,
                'quiz_win_5'      => $user->gameSessions()
                                         ->whereHas('game', fn($q) => $q->where('category', 'quiz'))
                                         ->where('won', true)->count() >= 5,
                'coins_1000'      => $user->coins >= 1000,
                default           => false,
            };

            if ($earned) {
                $user->badges()->attach($badge->id);
            }
        }
    }
}