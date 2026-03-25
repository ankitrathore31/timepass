<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Show a user's public profile.
     * If it's the auth user themselves → redirect to their own profile page.
     * Otherwise → show the Instagram-style public view.
     *
     * Route: GET /users/{username}  →  name('users.profile')
     */
    public function show(string $username)
    {
        $user = User::where('username', $username)
                    ->orWhere('id', $username)   // fallback for ID-based links
                    ->firstOrFail();

        // ── Own profile → redirect to the profile settings/reels page ──
        if (Auth::id() === $user->id) {
            return redirect()->route('profile.index');
        }

        /** @var User $authUser */
        $authUser   = Auth::user();
        $isFollowing = $authUser->isFollowing($user);

        // Reels: only show if public OR the viewer follows the private user
        $reels = collect();
        if (!$user->is_private || $isFollowing) {
            $reels = Reel::where('user_id', $user->id)
                         ->where('visibility', 'public')
                         ->latest()
                         ->paginate(12);
        }

        return view('dashboard.users.profile', compact('user', 'isFollowing', 'reels'));
    }

    /**
     * Toggle follow / unfollow.
     * Route: POST /users/{id}/follow  →  name('users.follow')
     */
    public function toggleFollow(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'Cannot follow yourself'], 422);
        }

        if ($authUser->isFollowing($user)) {
            // Unfollow
            $authUser->following()->detach($user->id);
            $user->decrement('followers_count');
            $authUser->decrement('following_count');
            $following = false;
        } else {
            // Follow
            $authUser->following()->attach($user->id);
            $user->increment('followers_count');
            $authUser->increment('following_count');
            $following = true;

            // Notify the followed user
            // UserNotification::create([...]) — wire up as needed
        }

        return response()->json([
            'following'        => $following,
            'followers_count'  => $user->fresh()->followers_count,
        ]);
    }

    /**
     * Return a user's followers list (JSON).
     * Route: GET /users/{id}/followers  →  name('users.followers')
     */
    public function followers(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $followers = $user->followers()   // users who follow $user
                          ->paginate(30);

        // Attach is_following flag so the sheet can show correct button state
        $data = $followers->getCollection()->map(function ($u) use ($authUser) {
            return [
                'id'          => $u->id,
                'name'        => $u->name,
                'username'    => $u->username,
                'avatar_url'  => $u->avatar_url,
                'is_self'     => $u->id === $authUser->id,
                'is_following'=> $authUser->isFollowing($u),
            ];
        });

        return response()->json([
            'data'         => $data,
            'current_page' => $followers->currentPage(),
            'last_page'    => $followers->lastPage(),
        ]);
    }

    /**
     * Return a user's following list (JSON).
     * Route: GET /users/{id}/following  →  name('users.following')
     */
    public function following(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $following = $user->following()   // users that $user follows
                          ->paginate(30);

        $data = $following->getCollection()->map(function ($u) use ($authUser) {
            return [
                'id'          => $u->id,
                'name'        => $u->name,
                'username'    => $u->username,
                'avatar_url'  => $u->avatar_url,
                'is_self'     => $u->id === $authUser->id,
                'is_following'=> $authUser->isFollowing($u),
            ];
        });

        return response()->json([
            'data'         => $data,
            'current_page' => $following->currentPage(),
            'last_page'    => $following->lastPage(),
        ]);
    }
}