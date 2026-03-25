<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ── Show Login Page ────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // ── Handle Login ───────────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->updateStreak();   // daily login + streak logic

        return redirect()->intended(route('dashboard'))
                         ->with('toast_success', '🎉 Welcome back, ' . $user->name . '!');
    }

    // ── Show Register Page ─────────────────────────────────────
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // ── Handle Register ────────────────────────────────────────
    public function register(Request $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username ?? Str::slug($request->name) . rand(10, 99),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'coins'    => 100,   // welcome bonus
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
                         ->with('toast_success', '🎉 Welcome to TimePass, ' . $user->name . '!');
    }

    // ── Logout ─────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('toast_success', '👋 Logged out successfully!');
    }
}
