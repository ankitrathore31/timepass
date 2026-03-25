<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id', 'actor_id', 'type',
        'notifiable_type', 'notifiable_id',
        'body', 'is_read',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function user()       { return $this->belongsTo(User::class); }
    public function actor()      { return $this->belongsTo(User::class, 'actor_id'); }
    public function notifiable() { return $this->morphTo(); }
}