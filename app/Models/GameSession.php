<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = [
        'user_id', 'game_id', 'score',
        'coins_earned', 'won', 'duration_seconds', 'meta',
    ];

    protected $casts = [
        'won'  => 'boolean',
        'meta' => 'array',
    ];

    public function user()  { return $this->belongsTo(User::class); }
    public function game()  { return $this->belongsTo(Game::class); }
}
