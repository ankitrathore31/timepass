<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'emoji',
        'color_from', 'color_to', 'coin_reward', 'xp_reward',
        'status', 'category', 'daily_play_limit', 'total_plays',
    ];

    public function sessions()
    {
        return $this->hasMany(GameSession::class);
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function getGradientAttribute(): string
    {
        return "linear-gradient(135deg, {$this->color_from}, {$this->color_to})";
    }
}


