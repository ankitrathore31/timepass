<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // ── Toggle Follow / Unfollow ───────────────────────────────
    public function toggle(User $user)
    {
        /** @var User $me */
        $me = Auth::user();

        if ($me->id === $user->id) {
            return response()->json(['error' => 'Cannot follow yourself'], 422);
        }

        $follow = Follow::where('follower_id', $me->id)
                        ->where('following_id', $user->id)
                        ->first();

        if ($follow) {
            // Unfollow
            $follow->delete();
            $me->decrement('following_count');
            $user->decrement('followers_count');
            return response()->json(['following' => false, 'followers_count' => $user->fresh()->followers_count]);
        }

        // Follow
        $status = $user->is_private ? 'pending' : 'accepted';
        Follow::create(['follower_id' => $me->id, 'following_id' => $user->id, 'status' => $status]);

        if ($status === 'accepted') {
            $me->increment('following_count');
            $user->increment('followers_count');
            User::notify($user->id, $me->id, 'follow', "👤 {$me->name} started following you");
        } else {
            User::notify($user->id, $me->id, 'follow_request', "👤 {$me->name} requested to follow you");
        }

        return response()->json([
            'following'       => true,
            'status'          => $status,
            'followers_count' => $user->fresh()->followers_count,
        ]);
    }

    // ── Followers list ─────────────────────────────────────────
    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);
        return response()->json($followers);
    }

    // ── Following list ─────────────────────────────────────────
    public function following(User $user)
    {
        $following = $user->following()->paginate(20);
        return response()->json($following);
    }
}
