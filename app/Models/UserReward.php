<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    protected $fillable = [
        'user_id', 'reward_id', 'status', 'voucher_code', 'fulfilled_at',
    ];

    protected $casts = ['fulfilled_at' => 'datetime'];

    public function user()   { return $this->belongsTo(User::class); }
    public function reward() { return $this->belongsTo(Reward::class); }
}