<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReelView extends Model
{
    protected $fillable = ['user_id', 'reel_id', 'ip_address'];
    public function reel() { return $this->belongsTo(Reel::class); }
}
