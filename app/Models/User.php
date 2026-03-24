<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'email', 'password',
        'avatar', 'coins', 'level', 'xp',
        'streak_days', 'last_login_date', 'city', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_date'   => 'date',
        'coins'             => 'integer',
        'level'             => 'integer',
        'xp'                => 'integer',
        'streak_days'       => 'integer',
        'password'          => 'hashed',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function gameSessions()
    {
        return $this->hasMany(GameSession::class);
    }

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=FF6B6B&color=fff';
    }

    public function getFormattedCoinsAttribute(): string
    {
        return number_format($this->coins);
    }

    // ── Helpers ────────────────────────────────────────────────

    public function addCoins(int $amount, string $source, string $description = '', $transactable = null): void
    {
        $this->increment('coins', $amount);
        $this->increment('xp', (int) ($amount * 0.4));
        $this->updateLevel();

        CoinTransaction::create([
            'user_id'          => $this->id,
            'type'             => 'earn',
            'amount'           => $amount,
            'source'           => $source,
            'description'      => $description,
            'transactable_type' => $transactable ? get_class($transactable) : null,
            'transactable_id'  => $transactable?->id,
        ]);
    }

    public function spendCoins(int $amount, string $source, string $description = '', $transactable = null): bool
    {
        if ($this->coins < $amount) return false;

        $this->decrement('coins', $amount);

        CoinTransaction::create([
            'user_id'          => $this->id,
            'type'             => 'spend',
            'amount'           => -$amount,
            'source'           => $source,
            'description'      => $description,
            'transactable_type' => $transactable ? get_class($transactable) : null,
            'transactable_id'  => $transactable?->id,
        ]);

        return true;
    }

    public function updateStreak(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        if ($this->last_login_date?->toDateString() === $today) return;

        if ($this->last_login_date?->toDateString() === $yesterday) {
            $this->increment('streak_days');
        } else {
            $this->streak_days = 1;
        }

        $this->last_login_date = $today;
        $this->save();

        // Streak bonus coins
        if ($this->streak_days % 7 === 0) {
            $this->addCoins(50, 'streak_bonus', "🔥 {$this->streak_days}-day streak bonus!");
        } else {
            $this->addCoins(10, 'daily_login', 'Daily login bonus');
        }
    }

    private function updateLevel(): void
    {
        $newLevel = (int) floor(sqrt($this->xp / 100)) + 1;
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
            $this->save();
        }
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
