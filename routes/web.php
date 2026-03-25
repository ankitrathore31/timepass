<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// ── Auth (Guest only) ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
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
        Route::post('/{reward}/redeem', [RewardController::class, 'redeem'])->name('redeem');
    });

    // Profile
    // Route::prefix('profile')->name('profile.')->group(function () {
    //     Route::get('/',                   [ProfileController::class, 'index'])->name('index');
    //     Route::post('/update',            [ProfileController::class, 'update'])->name('update');
    //     Route::post('/change-password',   [ProfileController::class, 'changePassword'])->name('password');
    // });

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

// ── Reels ──────────────────────────────────────────────────
Route::prefix('reels')->name('reels.')->group(function () {
    Route::get('/user-reel',              [ReelController::class, 'index'])->name('index');
    Route::post('/user-reels',             [ReelController::class, 'store'])->name('store');
    Route::delete('/{reel}',     [ReelController::class, 'destroy'])->name('destroy');
    Route::post('/{reel}/like',  [ReelController::class, 'toggleLike'])->name('like');
    Route::post('/{reel}/view',  [ReelController::class, 'recordView'])->name('view');
    Route::post('/{reel}/share', [ReelController::class, 'share'])->name('share');

    // Comments
    Route::get('/{reel}/comments', [ReelController::class, 'comments'])->name('comments');
    Route::post('/{reel}/comments', [ReelController::class, 'storeComment'])->name('comments.store');
});

// ── Follows ────────────────────────────────────────────────
Route::post('/users/{user}/follow',     [FollowController::class, 'toggle'])->name('users.follow');
Route::get('/users/{user}/followers',   [FollowController::class, 'followers'])->name('users.followers');
Route::get('/users/{user}/following',   [FollowController::class, 'following'])->name('users.following');

// ── Notifications ──────────────────────────────────────────
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/notifiction',          [NotificationController::class, 'index'])->name('index');
    Route::post('/read',     [NotificationController::class, 'markRead'])->name('read');
    Route::get('/count',     [NotificationController::class, 'unreadCount'])->name('count');
});

// ── Profile (own) ──────────────────────────────────────────
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/user-profile',                    [ProfileController::class, 'index'])->name('index');
    Route::post('/update',             [ProfileController::class, 'update'])->name('update');
    Route::post('/change-password',    [ProfileController::class, 'changePassword'])->name('password');
    Route::get('/check-username',      [ProfileController::class, 'checkUsername'])->name('check.username');
});

// ── Public user profile ────────────────────────────────────
Route::get('/u/{user:username}', [ProfileController::class, 'show'])->name('users.profile');

Route::middleware(['auth'])->group(function () {

    // ── Public user profile (view other users) ──
    // Clicking avatar/username in reels goes here.
    // If it's your own username → controller redirects to profile.index
    Route::get('/users/{username}',           [UserProfileController::class, 'show'])->name('users.profile');

    // ── Follow / Unfollow (POST, expects JSON) ──
    Route::post('/users/{user}/follow',       [UserProfileController::class, 'toggleFollow'])->name('users.follow');

    // ── Followers & Following lists (JSON for the bottom sheet) ──
    Route::get('/users/{user}/followers',     [UserProfileController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following',     [UserProfileController::class, 'following'])->name('users.following');
});
