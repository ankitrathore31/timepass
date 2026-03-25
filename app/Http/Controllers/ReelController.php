<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Reel;
use App\Models\ReelComment;
use App\Models\ReelLike;
use App\Models\ReelView;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReelController extends Controller
{
    // ── Feed (Instagram-style vertical scroll) ─────────────────
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get IDs of people the user follows
        $followingIds = $user->following()->pluck('users.id')->push($user->id);

        // Paginate: 10 reels per batch (infinite scroll via AJAX)
        $reels = Reel::with(['user'])
            ->where(function ($q) use ($followingIds) {
                $q->where('visibility', 'public')
                    ->orWhereIn('user_id', $followingIds);
            })
            ->latest()
            ->paginate(10);

        // Attach liked state for current user
        $likedIds = ReelLike::where('user_id', $user->id)
            ->pluck('reel_id')
            ->toArray();

        return view('dashboard.reels.index', compact('reels', 'likedIds'));
    }

    // ── Upload reel ────────────────────────────────────────────
    public function store(Request $request)
    {

        //     dd([
        // 'file' => $request->file('file'),
        // 'all'  => $request->all(),
        //     ]);
        $request->validate([
            'file' => 'required|file|max:102400',
            'caption'  => 'nullable|string|max:2200',
            'hashtags' => 'nullable|string',
        ]);



        /** @var User $user */
        $user = Auth::user();

        $file     = $request->file('file');
        $ext = strtolower($file->extension());
        $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
        $type     = $isVideo ? 'video' : 'image';
        $folder   = $isVideo ? 'reels/videos' : 'reels/images';

        // Store file
        $path = $file->store($folder, 'public');

        // Parse hashtags: "#fun #gaming" → ['fun','gaming']
        $hashtags = [];
        if ($request->filled('hashtags')) {
            preg_match_all('/#(\w+)/', $request->hashtags, $matches);
            $hashtags = $matches[1] ?? [];
        }

        $reel = Reel::create([
            'user_id'    => $user->id,
            'caption'    => $request->caption,
            'type'       => $type,
            'file_path'  => $path,
            'hashtags'   => $hashtags,
            'audio_name' => $isVideo ? 'Original Audio' : null,
            'visibility' => 'public',
        ]);

        $user->increment('reels_count');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reel'    => $this->reelData($reel, $user),
            ]);
        }

        return back()->with('toast_success', '🎬 Reel uploaded!');
    }

    // ── Delete reel ────────────────────────────────────────────
    public function destroy(Reel $reel)
    {
        $this->authorize('delete', $reel); // ReelPolicy

        Storage::disk('public')->delete($reel->file_path);
        if ($reel->thumbnail_path) Storage::disk('public')->delete($reel->thumbnail_path);

        $reel->delete();
        Auth::user()->decrement('reels_count');

        return response()->json(['success' => true]);
    }

    // ── Toggle Like ────────────────────────────────────────────
    public function toggleLike(Reel $reel)
    {
        /** @var User $user */
        $user     = Auth::user();
        $existing = ReelLike::where('user_id', $user->id)
            ->where('reel_id', $reel->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $reel->decrement('likes_count');
            $liked = false;
        } else {
            ReelLike::create(['user_id' => $user->id, 'reel_id' => $reel->id]);
            $reel->increment('likes_count');
            $liked = true;

            // Notify reel owner
            User::notify(
                $reel->user_id,
                $user->id,
                'like',
                "❤️ {$user->name} liked your reel",
                $reel
            );
        }

        return response()->json([
            'liked'       => $liked,
            'likes_count' => $reel->fresh()->likes_count,
        ]);
    }

    // ── Post Comment ───────────────────────────────────────────
    public function storeComment(Request $request, Reel $reel)
    {
        $request->validate(['body' => 'required|string|max:500']);

        /** @var User $user */
        $user = Auth::user();

        $comment = ReelComment::create([
            'user_id'   => $user->id,
            'reel_id'   => $reel->id,
            'parent_id' => $request->parent_id ?? null,
            'body'      => $request->body,
        ]);
        $reel->increment('comments_count');

        // Notify reel owner
        User::notify(
            $reel->user_id,
            $user->id,
            'comment',
            "💬 {$user->name} commented: {$comment->body}",
            $reel
        );

        return response()->json([
            'comment' => [
                'id'         => $comment->id,
                'body'       => $comment->body,
                'user_name'  => $user->name,
                'user_avatar' => $user->avatar_url,
                'time'       => 'Just now',
            ],
            'comments_count' => $reel->fresh()->comments_count,
        ]);
    }

    // ── Load Comments (paginated) ──────────────────────────────
    public function comments(Reel $reel)
    {
        $comments = ReelComment::with('user')
            ->where('reel_id', $reel->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);

        return response()->json($comments);
    }

    // ── Record View ────────────────────────────────────────────
    public function recordView(Reel $reel)
    {
        $userId = Auth::id();

        // One view per user per reel per day
        $exists = ReelView::where('reel_id', $reel->id)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->orWhere('ip_address', request()->ip());
            })
            ->whereDate('created_at', today())
            ->exists();

        if (!$exists) {
            ReelView::create([
                'reel_id'    => $reel->id,
                'user_id'    => $userId,
                'ip_address' => request()->ip(),
            ]);
            $reel->increment('views_count');
        }

        return response()->json(['views_count' => $reel->fresh()->views_count]);
    }

    // ── Share (increment counter) ──────────────────────────────
    public function share(Reel $reel)
    {
        $reel->increment('shares_count');
        return response()->json([
            'shares_count' => $reel->fresh()->shares_count,
            'share_url'    => route('reels.show', $reel->id),
        ]);
    }

    // ── Helper ─────────────────────────────────────────────────
    private function reelData(Reel $reel, User $user): array
    {
        return [
            'id'             => $reel->id,
            'file_url'       => $reel->file_url,
            'thumbnail_url'  => $reel->thumbnail_url,
            'type'           => $reel->type,
            'caption'        => $reel->caption,
            'likes_count'    => $reel->likes_count,
            'comments_count' => $reel->comments_count,
            'views_count'    => $reel->views_count,
            'is_liked'       => $reel->isLikedBy($user),
            'user'           => [
                'name'   => $reel->user->name,
                'avatar' => $reel->user->avatar_url,
            ],
        ];
    }
}
