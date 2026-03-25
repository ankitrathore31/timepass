<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\Reel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ── Own profile (3 tabs: Reels, Games, Settings) ───────────
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load(['badges']);
        $tab  = $request->get('tab', 'reels');

        $reels    = null;
        $sessions = null;

        if ($tab === 'reels') {
            $reels = Reel::where('user_id', $user->id)->latest()->paginate(9);
        } elseif ($tab === 'games') {
            $sessions = GameSession::with('game')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(20);
        }

        $totalGamesPlayed = GameSession::where('user_id', $user->id)->count();
        $totalWins        = GameSession::where('user_id', $user->id)->where('won', true)->count();

        return view('dashboard.profile.index', compact(
            'user',
            'tab',
            'reels',
            'sessions',
            'totalGamesPlayed',
            'totalWins'
        ));
    }

    // ── Public profile (other user) ────────────────────────────
    public function show(User $user)
    {
        /** @var User $me */
        $me        = Auth::user();
        $isFollowing = $me->isFollowing($user);
        $tab       = request()->get('tab', 'reels');

        $reels = null;
        if ($tab === 'reels' && ($user->id === $me->id || !$user->is_private || $isFollowing)) {
            $reels = Reel::where('user_id', $user->id)->public()->latest()->paginate(9);
        }

        return view('dashboard.profile.UserProfile', compact('user', 'me', 'isFollowing', 'tab', 'reels'));
    }

    // ── Update profile ─────────────────────────────────────────
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|min:3|max:50|alpha_dash|unique:users,username,' . $user->id,
            'bio'      => 'nullable|string|max:300',
            'website'  => 'nullable|url|max:200',
            'city'     => 'nullable|string|max:100',
            'avatar'   => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($request->only('name', 'username', 'bio', 'website', 'city'));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'user' => $user->fresh()]);
        }
        return back()->with('toast_success', '✅ Profile updated!');
    }

    // ── Change password ────────────────────────────────────────
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('toast_success', '🔒 Password changed!');
    }

    // ── Check username availability (AJAX) ─────────────────────
    public function checkUsername(Request $request)
    {
        $taken = User::where('username', $request->username)
            ->where('id', '!=', Auth::id())
            ->exists();
        return response()->json(['available' => !$taken]);
    }
}
