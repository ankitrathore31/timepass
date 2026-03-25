<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReelComment extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'reel_id', 'parent_id', 'body', 'likes_count'];

    public function user()    { return $this->belongsTo(User::class); }
    public function reel()    { return $this->belongsTo(Reel::class); }
    public function replies() { return $this->hasMany(ReelComment::class, 'parent_id')->latest(); }
    public function parent()  { return $this->belongsTo(ReelComment::class, 'parent_id'); }
}
