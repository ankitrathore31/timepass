<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'name', 'description', 'emoji', 'coins_required',
        'category', 'partner', 'color_bar', 'is_active', 'stock',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isAvailable(): bool
    {
        return $this->stock === -1 || $this->stock > 0;
    }
}
