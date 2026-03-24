<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// ── Auth (Guest only) ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// ── Authenticated routes ───────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/user-dashboard',          [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Games
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/',                   [GameController::class, 'index'])->name('index');
        Route::get('/{game:slug}',        [GameController::class, 'show'])->name('show');
        Route::post('/{game:slug}/save',  [GameController::class, 'saveResult'])->name('save');
    });

    // Rewards
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/',               [RewardController::class, 'index'])->name('index');
        Route::post('/{reward}/redeem',[RewardController::class, 'redeem'])->name('redeem');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',                   [ProfileController::class, 'index'])->name('index');
        Route::post('/update',            [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password',   [ProfileController::class, 'changePassword'])->name('password');
    });

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});
