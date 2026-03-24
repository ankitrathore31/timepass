<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount',
        'source', 'description',
        'transactable_type', 'transactable_id',
    ];

    public function user()          { return $this->belongsTo(User::class); }
    public function transactable()  { return $this->morphTo(); }
}
