<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Reel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'caption', 'type',
        'file_path', 'thumbnail_path', 'cover_path',
        'duration', 'audio_name', 'hashtags', 'location',
        'visibility', 'views_count', 'likes_count',
        'comments_count', 'shares_count',
        'comments_enabled', 'is_featured',
    ];

    protected $casts = [
        'hashtags'         => 'array',
        'comments_enabled' => 'boolean',
        'is_featured'      => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(ReelLike::class);
    }

    public function comments()
    {
        return $this->hasMany(ReelComment::class)->whereNull('parent_id')->latest();
    }

    public function views()
    {
        return $this->hasMany(ReelView::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeFeed($query, User $user)
    {
        $followingIds = $user->following()->pluck('users.id');
        return $query->where(function ($q) use ($followingIds, $user) {
            $q->where('visibility', 'public')
              ->orWhere(function ($q2) use ($followingIds, $user) {
                  $q2->where('visibility', 'followers')
                     ->whereIn('user_id', $followingIds->push($user->id));
              });
        })->orderBy('created_at', 'desc');
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail_path
            ? Storage::url($this->thumbnail_path)
            : asset('images/reel-placeholder.jpg');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
