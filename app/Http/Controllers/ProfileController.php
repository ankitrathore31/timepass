<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['badges', 'gameSessions.game', 'userRewards.reward']);

        $totalGamesPlayed = $user->gameSessions->count();
        $totalWins        = $user->gameSessions->where('won', true)->count();
        $totalRedeemed    = $user->userRewards->count();

        return view('dashboard.profile.index', compact(
            'user', 'totalGamesPlayed', 'totalWins', 'totalRedeemed'
        ));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'city'     => 'nullable|string|max:100',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($request->only('name', 'username', 'city'));

        return back()->with('toast_success', '✅ Profile updated!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('toast_success', '🔒 Password changed successfully!');
    }
}

