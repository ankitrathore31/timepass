{{-- resources/views/dashboard/profile/index.blade.php --}}
@extends('dashboard.layouts.app')
@section('title', 'Profile')

@push('styles')
<style>
:root {
  --p1:#FF6B6B; --p2:#FF8E53; --orange:#F97316; --blue:#3B82F6;
  --green:#10B981; --teal:#4ECDC4; --yellow:#FFE66D;
  --bg:#FFF8F0; --border:#E5E7EB; --text:#1A1A2E; --muted:#6B7280;
}

/* ── Profile hero ── */
.profile-hero {
  background: linear-gradient(145deg,#FF6B6B,#FF8E53,#F97316);
  padding: 24px 20px 0; color: white; position: relative; overflow: hidden;
}
.profile-hero::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.05'%3E%3Ccircle cx='20' cy='20' r='3'/%3E%3C/g%3E%3C/svg%3E");
}
.hero-top { display:flex; align-items:flex-start; gap:16px; position:relative; }
.hero-avatar-wrap { position:relative; flex-shrink:0; }
.hero-avatar {
  width:84px; height:84px; border-radius:50%;
  border:3px solid rgba(255,255,255,0.8);
  background:rgba(255,255,255,0.25);
  display:flex; align-items:center; justify-content:center;
  font-size:2rem; font-weight:900; color:white; overflow:hidden; cursor:pointer;
}
.hero-avatar img { width:100%; height:100%; object-fit:cover; }
.avatar-edit-btn {
  position:absolute; bottom:0; right:0;
  width:28px; height:28px; border-radius:50%; border:2px solid white;
  background:var(--orange); color:white; font-size:0.7rem;
  display:flex; align-items:center; justify-content:center; cursor:pointer;
}
.hero-meta { flex:1; padding-top:4px; }
.hero-name { font-family:'Fredoka One',cursive; font-size:1.4rem; margin-bottom:1px; }
.hero-username { font-size:0.82rem; opacity:.85; font-weight:700; }
.hero-bio { font-size:0.8rem; opacity:.82; font-weight:600; line-height:1.4; margin-top:6px; }
.hero-website { font-size:0.75rem; opacity:.8; font-weight:700; margin-top:3px; text-decoration:underline; color:white; }
.level-chip { display:inline-block; background:rgba(255,255,255,.2); border-radius:20px; padding:3px 12px; font-size:0.74rem; font-weight:900; margin-top:8px; }

.hero-stats { display:flex; gap:0; margin-top:16px; border-top:1px solid rgba(255,255,255,.2); }
.hero-stat { flex:1; text-align:center; padding:12px 8px; cursor:pointer; transition:background .2s; }
.hero-stat:hover { background:rgba(255,255,255,.1); }
.hero-stat strong { display:block; font-size:1.05rem; font-weight:900; }
.hero-stat span { font-size:0.66rem; opacity:.82; font-weight:700; }

/* ── Tabs ── */
.profile-tabs {
  display:flex; background:white; border-bottom:2px solid var(--border);
  position:sticky; top:var(--nav-h,56px); z-index:100;
}
.profile-tab {
  flex:1; text-align:center; padding:13px 8px; font-size:0.82rem; font-weight:800;
  color:var(--muted); cursor:pointer; text-decoration:none; transition:all .25s;
  border-bottom:3px solid transparent; margin-bottom:-2px;
}
.profile-tab.active { color:var(--p1); border-bottom-color:var(--p1); }

/* ── Reels grid ── */
.reels-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:2px; }
.reel-thumb {
  aspect-ratio:9/16; position:relative; overflow:hidden; cursor:pointer; background:#111;
}
.reel-thumb img, .reel-thumb video { width:100%; height:100%; object-fit:cover; display:block; }
.reel-thumb-overlay {
  position:absolute; inset:0; background:rgba(0,0,0,0); transition:background .2s;
  display:flex; align-items:center; justify-content:center;
}
.reel-thumb:hover .reel-thumb-overlay { background:rgba(0,0,0,0.35); }
.reel-type-badge {
  position:absolute; top:6px; left:6px; background:rgba(0,0,0,.5);
  color:white; border-radius:6px; padding:2px 6px; font-size:0.6rem; font-weight:800;
}
.reel-stat-badge {
  position:absolute; bottom:6px; left:6px; color:white;
  font-size:0.68rem; font-weight:800; text-shadow:0 1px 3px rgba(0,0,0,.7);
  display:flex; align-items:center; gap:4px;
}
.reel-play-btn {
  width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.9);
  display:flex; align-items:center; justify-content:center; font-size:0.9rem;
  opacity:0; transition:opacity .2s;
}
.reel-thumb:hover .reel-play-btn { opacity:1; }

/* ── Empty reels placeholder with upload button ── */
.reels-empty-state {
  text-align:center; padding:48px 20px; color:var(--muted);
}

/* ── Upload Reel Modal (Profile) ── */
.profile-upload-modal {
  position:fixed; inset:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(6px);
  z-index:1000; display:none; align-items:flex-end; justify-content:center;
}
.profile-upload-modal.open { display:flex; }
.profile-upload-sheet {
  background:white; border-radius:28px 28px 0 0; width:100%; max-width:500px;
  padding:24px 20px 32px; animation:slideUpSheet .35s ease; max-height:92vh; overflow-y:auto;
}
@keyframes slideUpSheet { from{transform:translateY(100%);}to{transform:translateY(0);} }
.profile-upload-sheet h3 {
  font-family:'Fredoka One',cursive; font-size:1.3rem; margin-bottom:16px; text-align:center;
}
.puz-drop-zone {
  border:2.5px dashed #E5E7EB; border-radius:18px; padding:32px 16px;
  text-align:center; cursor:pointer; transition:all .25s; margin-bottom:14px; background:#FFF8F0;
}
.puz-drop-zone:hover,.puz-drop-zone.drag-over { border-color:var(--p1); background:rgba(255,107,107,0.05); }
.puz-drop-zone input { display:none; }
.puz-icon { font-size:2.5rem; margin-bottom:8px; }
.puz-text { font-weight:800; font-size:0.9rem; color:var(--text); }
.puz-sub  { font-size:0.75rem; color:var(--muted); font-weight:600; margin-top:4px; }

#puzPreview { display:none; border-radius:14px; overflow:hidden; margin-bottom:14px; position:relative; }
#puzPreview video, #puzPreview img { width:100%; max-height:260px; object-fit:cover; display:block; }
#puzRemove {
  position:absolute; top:8px; right:8px;
  background:rgba(0,0,0,0.5); color:white; border:none; border-radius:50%;
  width:28px; height:28px; cursor:pointer; font-size:0.85rem;
  display:flex; align-items:center; justify-content:center;
}
.puz-field { margin-bottom:12px; }
.puz-field label {
  display:block; font-size:0.76rem; font-weight:900; text-transform:uppercase;
  letter-spacing:.3px; margin-bottom:5px; color:var(--text);
}
.puz-field textarea, .puz-field input[type=text] {
  width:100%; background:#FFF8F0; border:2px solid var(--border); border-radius:12px;
  padding:11px 14px; font-family:'Nunito',sans-serif; font-size:0.88rem; font-weight:700;
  outline:none; resize:none; transition:border-color .25s; box-sizing:border-box;
}
.puz-field textarea:focus, .puz-field input:focus { border-color:var(--p1); }
.puz-progress { display:none; margin-bottom:10px; }
.puz-progress-bar-wrap { background:#E5E7EB; border-radius:10px; height:7px; overflow:hidden; }
.puz-progress-bar { height:100%; background:linear-gradient(90deg,#FF6B6B,#FF8E53); width:0%; transition:width .3s; border-radius:10px; }
.puz-progress-text { font-size:0.75rem; font-weight:700; color:var(--muted); margin-top:5px; text-align:center; }
.btn-puz-submit {
  width:100%; background:linear-gradient(135deg,#FF6B6B,#FF8E53); color:white;
  border:none; border-radius:14px; padding:13px; font-family:'Nunito',sans-serif;
  font-size:0.95rem; font-weight:900; cursor:pointer; transition:all .3s; margin-top:4px;
}
.btn-puz-submit:disabled { opacity:.5; cursor:default; }

/* ── Game History ── */
.game-history-list { display:flex; flex-direction:column; gap:10px; padding:16px; }
.game-hist-item {
  background:white; border-radius:16px; padding:13px 14px;
  display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px rgba(0,0,0,.05);
}
.gh-icon { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.gh-info { flex:1; }
.gh-name { font-weight:900; font-size:0.88rem; margin-bottom:2px; }
.gh-sub { font-size:0.72rem; color:var(--muted); font-weight:600; }
.gh-right { text-align:right; flex-shrink:0; }
.gh-score { font-weight:900; font-size:0.88rem; color:var(--p2); }
.gh-coins { font-size:0.72rem; font-weight:800; color:var(--green); }
.win-badge { display:inline-block; background:#D1FAE5; color:#065F46; font-size:0.6rem; font-weight:900; padding:2px 7px; border-radius:20px; margin-left:4px; }
.loss-badge { background:#FEE2E2; color:#991B1B; }

/* ── Settings ── */
.settings-body { padding:16px; display:flex; flex-direction:column; gap:14px; }
.settings-card { background:white; border-radius:20px; overflow:hidden; box-shadow:0 3px 12px rgba(0,0,0,.06); }
.settings-card-hd {
  padding:14px 18px; border-bottom:1.5px solid var(--border);
  font-family:'Fredoka One',cursive; font-size:1rem;
  display:flex; align-items:center; gap:8px;
}
.settings-group { padding:16px 18px; display:flex; flex-direction:column; gap:12px; }
.s-field { display:flex; flex-direction:column; gap:5px; }
.s-label { font-size:0.74rem; font-weight:900; text-transform:uppercase; letter-spacing:.3px; color:var(--text); }
.s-input {
  width:100%; background:var(--bg); border:2px solid var(--border); border-radius:12px;
  padding:11px 14px; font-family:'Nunito',sans-serif; font-size:0.88rem; font-weight:700;
  outline:none; transition:all .25s; color:var(--text); box-sizing:border-box;
}
.s-input:focus { border-color:var(--p1); background:white; }
.s-input.available { border-color:var(--green); }
.s-input.taken { border-color:var(--p1); }
.s-hint { font-size:0.7rem; font-weight:700; min-height:16px; }
.s-hint.ok { color:var(--green); }
.s-hint.err { color:var(--p1); }
.btn-save {
  width:100%; background:linear-gradient(135deg,var(--p1),var(--orange));
  color:white; border:none; border-radius:13px; padding:12px;
  font-family:'Nunito',sans-serif; font-size:0.92rem; font-weight:900;
  cursor:pointer; transition:all .3s;
}
.btn-save:hover { transform:translateY(-1px); }
.btn-save-secondary { background:linear-gradient(135deg,var(--blue),var(--teal)); }
.danger-zone { border-top:1.5px solid #FEE2E2; }
.btn-logout {
  width:100%; background:none; border:none; color:var(--p1);
  font-family:'Nunito',sans-serif; font-size:0.9rem; font-weight:900;
  cursor:pointer; padding:14px; text-align:left; display:flex; align-items:center; gap:8px;
  transition:background .2s;
}
.btn-logout:hover { background:#FEF2F2; }

.badges-strip { display:flex; gap:10px; overflow-x:auto; padding:12px 16px 8px; scrollbar-width:none; }
.badges-strip::-webkit-scrollbar { display:none; }
.badge-item { flex-shrink:0; text-align:center; }
.badge-emoji-box {
  width:52px; height:52px; border-radius:14px; background:white;
  display:flex; align-items:center; justify-content:center; font-size:1.5rem;
  box-shadow:0 3px 10px rgba(0,0,0,.1); border:2px solid var(--border); margin-bottom:4px;
}
.badge-label { font-size:0.62rem; font-weight:800; color:var(--muted); }

/* ── Avatar sheet ── */
.avatar-sheet {
  position:fixed; inset:0; background:rgba(0,0,0,.6); backdrop-filter:blur(4px);
  z-index:900; display:none; align-items:flex-end; justify-content:center;
}
.avatar-sheet.open { display:flex; }
.avatar-inner {
  background:white; border-radius:24px 24px 0 0; width:100%; max-width:480px;
  padding:24px 20px 32px; animation:slideUpSheet .3s ease;
}
.avatar-options { display:flex; flex-direction:column; gap:10px; margin-top:14px; }
.avatar-opt {
  background:var(--bg); border:2px solid var(--border); border-radius:14px;
  padding:14px 18px; font-family:'Nunito',sans-serif; font-size:0.9rem; font-weight:800;
  cursor:pointer; transition:all .25s; display:flex; align-items:center; gap:10px; text-align:left;
}
.avatar-opt:hover { border-color:var(--p1); background:rgba(255,107,107,.05); }
</style>
@endpush

@section('content')
<div style="padding-bottom:var(--bottom-h,60px);">

{{-- ── Hero ── --}}
<div class="profile-hero">
  <div class="hero-top">
    <div class="hero-avatar-wrap">
      <div class="hero-avatar" onclick="openAvatarSheet()">
        @if($user->avatar)
          <img src="{{ Storage::url($user->avatar) }}" alt="Avatar">
        @else
          {{ strtoupper(substr($user->name,0,1)) }}
        @endif
      </div>
      <div class="avatar-edit-btn" onclick="openAvatarSheet()">✏️</div>
    </div>
    <div class="hero-meta">
      <div class="hero-name">{{ $user->name }}</div>
      <div class="hero-username">@{{ $user->username }}</div>
      @if($user->bio)
        <div class="hero-bio">{{ $user->bio }}</div>
      @endif
      @if($user->website)
        <a href="{{ $user->website }}" target="_blank" class="hero-website">🔗 {{ $user->website }}</a>
      @endif
      <div class="level-chip">⚡ Lv {{ $user->level }} · {{ number_format($user->xp) }} XP</div>
    </div>
  </div>

  <div class="hero-stats">
    <div class="hero-stat" onclick="window.location='{{ route('profile.index', ['tab'=>'reels']) }}'">
      <strong>{{ $user->reels_count }}</strong>
      <span>Reels</span>
    </div>
    <div class="hero-stat">
      <strong>{{ number_format($user->followers_count) }}</strong>
      <span>Followers</span>
    </div>
    <div class="hero-stat">
      <strong>{{ number_format($user->following_count) }}</strong>
      <span>Following</span>
    </div>
    <div class="hero-stat">
      <strong>🪙 {{ number_format($user->coins) }}</strong>
      <span>Coins</span>
    </div>
  </div>
</div>

{{-- ── Tabs ── --}}
<div class="profile-tabs">
  <a class="profile-tab {{ $tab === 'reels'    ? 'active' : '' }}" href="{{ route('profile.index', ['tab'=>'reels']) }}">🎬 Reels</a>
  <a class="profile-tab {{ $tab === 'games'    ? 'active' : '' }}" href="{{ route('profile.index', ['tab'=>'games']) }}">🎮 Games</a>
  <a class="profile-tab {{ $tab === 'settings' ? 'active' : '' }}" href="{{ route('profile.index', ['tab'=>'settings']) }}">⚙️ Settings</a>
</div>

{{-- ══════════ TAB: REELS ══════════ --}}
@if($tab === 'reels')
  @if($reels && $reels->count())
  <div class="reels-grid">
    @foreach($reels as $reel)
    <a href="{{ route('reels.index') }}" class="reel-thumb">
      @if($reel->type === 'video')
        @if($reel->thumbnail_path)
          <img src="{{ $reel->thumbnail_url }}" alt="reel" loading="lazy">
        @else
          <video src="{{ $reel->file_url }}" preload="none" muted></video>
        @endif
        <span class="reel-type-badge">▶</span>
      @else
        <img src="{{ $reel->file_url }}" alt="reel" loading="lazy">
      @endif
      <div class="reel-thumb-overlay">
        <div class="reel-play-btn">▶️</div>
      </div>
      <div class="reel-stat-badge">❤️ {{ number_format($reel->likes_count) }}</div>
    </a>
    @endforeach
  </div>
  {{ $reels->links() }}

  {{-- Upload more button below grid --}}
  <div style="padding:16px;text-align:center;">
    <button onclick="openProfileUploadModal()"
            style="background:linear-gradient(135deg,var(--p1),var(--orange));color:white;border:none;border-radius:14px;padding:12px 28px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:900;cursor:pointer;">
      + Upload New Reel
    </button>
  </div>

  @else
  {{-- Empty state with upload button ── KEY FIX ── --}}
  <div class="reels-empty-state">
    <div style="font-size:3.5rem;margin-bottom:14px;">🎬</div>
    <h3 style="font-family:'Fredoka One',cursive;font-size:1.2rem;margin-bottom:6px;color:var(--text);">No Reels Yet</h3>
    <p style="font-size:0.85rem;font-weight:600;">Share your first reel and go viral!</p>
    {{-- This button opens the MODAL — not a link to another page ── --}}
    <button onclick="openProfileUploadModal()"
            style="display:inline-block;margin-top:16px;background:linear-gradient(135deg,var(--p1),var(--orange));color:white;border:none;border-radius:13px;padding:11px 24px;font-weight:900;font-size:0.88rem;cursor:pointer;">
      + Upload Now
    </button>
  </div>
  @endif

{{-- ══════════ TAB: GAMES ══════════ --}}
@elseif($tab === 'games')

  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;padding:16px 16px 8px;">
    <div style="background:white;border-radius:16px;padding:14px 10px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.05);">
      <div style="font-size:1.4rem;margin-bottom:3px;">🎮</div>
      <strong style="font-size:1rem;font-weight:900;display:block;">{{ $totalGamesPlayed }}</strong>
      <span style="font-size:0.66rem;color:var(--muted);font-weight:700;">Played</span>
    </div>
    <div style="background:white;border-radius:16px;padding:14px 10px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.05);">
      <div style="font-size:1.4rem;margin-bottom:3px;">🏆</div>
      <strong style="font-size:1rem;font-weight:900;display:block;">{{ $totalWins }}</strong>
      <span style="font-size:0.66rem;color:var(--muted);font-weight:700;">Wins</span>
    </div>
    <div style="background:white;border-radius:16px;padding:14px 10px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.05);">
      <div style="font-size:1.4rem;margin-bottom:3px;">🪙</div>
      <strong style="font-size:1rem;font-weight:900;display:block;">{{ number_format($user->coins) }}</strong>
      <span style="font-size:0.66rem;color:var(--muted);font-weight:700;">Coins</span>
    </div>
  </div>

  @if($user->badges->count())
  <div style="padding:4px 16px 0;">
    <h3 style="font-family:'Fredoka One',cursive;font-size:1rem;">🏅 Badges Earned</h3>
  </div>
  <div class="badges-strip">
    @foreach($user->badges as $badge)
    <div class="badge-item">
      <div class="badge-emoji-box">{{ $badge->emoji }}</div>
      <div class="badge-label">{{ Str::limit($badge->name,9) }}</div>
    </div>
    @endforeach
  </div>
  @endif

  <div style="padding:8px 16px 2px;">
    <h3 style="font-family:'Fredoka One',cursive;font-size:1rem;">📋 Game History</h3>
  </div>

  @if($sessions && $sessions->count())
  <div class="game-history-list">
    @foreach($sessions as $s)
    <div class="game-hist-item">
      <div class="gh-icon" style="background:{{ $s->game->gradient ?? 'linear-gradient(135deg,#FF6B6B,#FF8E53)' }};">
        {{ $s->game->emoji ?? '🎮' }}
      </div>
      <div class="gh-info">
        <div class="gh-name">
          {{ $s->game->name ?? 'Game' }}
          <span class="{{ $s->won ? 'win-badge' : 'win-badge loss-badge' }}">{{ $s->won ? 'WIN' : 'LOSS' }}</span>
        </div>
        <div class="gh-sub">{{ $s->created_at->diffForHumans() }} · {{ $s->duration_seconds }}s</div>
      </div>
      <div class="gh-right">
        <div class="gh-score">{{ number_format($s->score) }} pts</div>
        <div class="gh-coins">+{{ $s->coins_earned }} 🪙</div>
      </div>
    </div>
    @endforeach
    <div style="padding:0 0 8px;">{{ $sessions->withQueryString()->links() }}</div>
  </div>
  @else
  <div style="text-align:center;padding:40px 20px;color:var(--muted);">
    <div style="font-size:3rem;margin-bottom:12px;">🎮</div>
    <p style="font-weight:700;font-size:0.88rem;">No games played yet.<br>Go play and earn coins!</p>
    <a href="{{ route('games.index') }}" style="display:inline-block;margin-top:14px;background:linear-gradient(135deg,var(--p1),var(--orange));color:white;border-radius:13px;padding:10px 22px;font-weight:900;font-size:0.85rem;text-decoration:none;">Play Now →</a>
  </div>
  @endif

{{-- ══════════ TAB: SETTINGS ══════════ --}}
@else
<div class="settings-body">

  <div class="settings-card">
    <div class="settings-card-hd">✏️ Edit Profile</div>
    <form class="settings-group" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      <div class="s-field">
        <div class="s-label">Full Name</div>
        <input type="text" name="name" class="s-input" value="{{ old('name',$user->name) }}" required>
        @error('name')<div class="s-hint err">{{ $message }}</div>@enderror
      </div>
      <div class="s-field">
        <div class="s-label">Username</div>
        <input type="text" name="username" class="s-input" id="usernameInput"
               value="{{ old('username',$user->username) }}"
               oninput="checkUsername(this)" required>
        <div class="s-hint" id="usernameHint"></div>
        @error('username')<div class="s-hint err">{{ $message }}</div>@enderror
      </div>
      <div class="s-field">
        <div class="s-label">Bio</div>
        <textarea name="bio" class="s-input" rows="3" maxlength="300"
                  style="resize:none;" placeholder="Tell the world about yourself…">{{ old('bio',$user->bio) }}</textarea>
      </div>
      <div class="s-field">
        <div class="s-label">Website</div>
        <input type="url" name="website" class="s-input" placeholder="https://yoursite.com"
               value="{{ old('website',$user->website) }}">
      </div>
      <div class="s-field">
        <div class="s-label">City</div>
        <input type="text" name="city" class="s-input" placeholder="Delhi, Mumbai…"
               value="{{ old('city',$user->city) }}">
      </div>
      <button type="submit" class="btn-save">✅ Save Profile</button>
    </form>
  </div>

  <div class="settings-card">
    <div class="settings-card-hd">🔒 Change Password</div>
    <form class="settings-group" method="POST" action="{{ route('profile.password') }}">
      @csrf
      <div class="s-field">
        <div class="s-label">Current Password</div>
        <input type="password" name="current_password" class="s-input" required placeholder="Enter current password">
        @error('current_password')<div class="s-hint err">{{ $message }}</div>@enderror
      </div>
      <div class="s-field">
        <div class="s-label">New Password</div>
        <input type="password" name="password" class="s-input" required placeholder="Min 8 characters" id="newPass">
      </div>
      <div class="s-field">
        <div class="s-label">Confirm Password</div>
        <input type="password" name="password_confirmation" class="s-input" required
               placeholder="Repeat new password" oninput="checkPassMatch(this)">
        <div class="s-hint" id="passMatchHint"></div>
      </div>
      <button type="submit" class="btn-save btn-save-secondary">🔒 Update Password</button>
    </form>
  </div>

  <div class="settings-card">
    <div class="settings-card-hd">🔔 Privacy</div>
    <div class="settings-group">
      <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;">
        <span>
          <div style="font-size:0.88rem;font-weight:800;color:var(--text);">Private Account</div>
          <div style="font-size:0.74rem;color:var(--muted);font-weight:600;">Only approved followers can see your reels</div>
        </span>
        <input type="checkbox" {{ $user->is_private ? 'checked' : '' }}
               onchange="togglePrivate(this)"
               style="width:18px;height:18px;accent-color:var(--p1);cursor:pointer;">
      </label>
    </div>
  </div>

  <div class="settings-card">
    <div class="settings-card-hd" style="color:var(--p1);">⚠️ Account</div>
    <div class="danger-zone">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">🚪 Logout</button>
      </form>
    </div>
  </div>

</div>
@endif

</div>{{-- end padding-bottom wrapper --}}

{{-- ══ PROFILE UPLOAD MODAL ══ --}}
<div class="profile-upload-modal" id="profileUploadModal">
  <div class="profile-upload-sheet">
    <h3>🎬 Upload Reel / Meme</h3>

    <div class="puz-drop-zone" id="puzDropZone"
         onclick="document.getElementById('puzFileInput').click()"
         ondragover="event.preventDefault();this.classList.add('drag-over')"
         ondragleave="this.classList.remove('drag-over')"
         ondrop="puzHandleDrop(event)">
      {{-- Hidden file input — NOT inside a form yet so we control submission manually --}}
      <input type="file" id="puzFileInput" accept="video/*,image/*" onchange="puzPreviewFile(this)">
      <div class="puz-icon">🎬</div>
      <div class="puz-text">Tap or drag to upload</div>
      <div class="puz-sub">Video (MP4, MOV) or Photo · Max 100MB</div>
    </div>

    <div id="puzPreview">
      <video id="puzPreviewVid" controls style="display:none;"></video>
      <img   id="puzPreviewImg" style="display:none;">
      <button type="button" id="puzRemove" onclick="puzClearPreview()">✕</button>
    </div>

    <div class="puz-field">
      <label>Caption</label>
      <textarea id="puzCaption" rows="3" placeholder="Write a caption… #hashtag @mention"></textarea>
    </div>
    <div class="puz-field">
      <label>Hashtags</label>
      <input type="text" id="puzHashtags" placeholder="#gaming #timepass #fun">
    </div>

    <div class="puz-progress" id="puzProgress">
      <div class="puz-progress-bar-wrap">
        <div class="puz-progress-bar" id="puzProgressBar"></div>
      </div>
      <div class="puz-progress-text" id="puzProgressText">Uploading…</div>
    </div>

    <button class="btn-puz-submit" id="puzSubmitBtn" disabled onclick="puzSubmit()">
      🚀 Share Reel
    </button>

    <button onclick="closeProfileUploadModal()"
            style="width:100%;margin-top:10px;background:none;border:none;color:#6B7280;font-size:0.85rem;font-weight:800;cursor:pointer;padding:8px;">
      Cancel
    </button>
  </div>
</div>

{{-- ── Avatar Sheet ── --}}
<div class="avatar-sheet" id="avatarSheet">
  <div class="avatar-inner">
    <h3 style="font-family:'Fredoka One',cursive;font-size:1.2rem;margin-bottom:4px;">📸 Change Avatar</h3>
    <p style="font-size:0.8rem;color:var(--muted);font-weight:600;">Choose a photo from your device</p>
    <div class="avatar-options">
      <label class="avatar-opt">
        <input type="file" id="avatarFileInput" accept="image/*" style="display:none;" onchange="uploadAvatar(this)">
        📷 Upload from Camera / Gallery
      </label>
      <button class="avatar-opt" onclick="closeAvatarSheet()" style="color:var(--p1);">Cancel</button>
    </div>
    <div id="avatarProgress" style="display:none;margin-top:12px;">
      <div style="background:var(--border);border-radius:10px;height:6px;overflow:hidden;">
        <div id="avatarProgressBar" style="height:100%;background:linear-gradient(90deg,var(--p1),var(--orange));width:0%;transition:width .3s;border-radius:10px;"></div>
      </div>
      <p style="font-size:0.74rem;font-weight:700;color:var(--muted);margin-top:4px;text-align:center;">Uploading…</p>
    </div>
  </div>
</div>

<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
// Correct store URL from named route
const STORE_URL = '{{ route("reels.store") }}';

// ══ Profile Upload Modal ══════════════════════════════════
function openProfileUploadModal() {
  document.getElementById('profileUploadModal').classList.add('open');
}
function closeProfileUploadModal() {
  document.getElementById('profileUploadModal').classList.remove('open');
  puzClearPreview();
  document.getElementById('puzCaption').value  = '';
  document.getElementById('puzHashtags').value = '';
  document.getElementById('puzProgress').style.display  = 'none';
  document.getElementById('puzProgressBar').style.width = '0%';
  document.getElementById('puzSubmitBtn').disabled = true;
}

function puzHandleDrop(e) {
  e.preventDefault();
  document.getElementById('puzDropZone').classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (!file) return;
  const dt = new DataTransfer();
  dt.items.add(file);
  document.getElementById('puzFileInput').files = dt.files;
  puzPreviewFile(document.getElementById('puzFileInput'));
}

function puzPreviewFile(input) {
  const file = input.files[0];
  if (!file) return;
  const isVideo = file.type.startsWith('video/');
  const url     = URL.createObjectURL(file);

  document.getElementById('puzPreview').style.display  = 'block';
  document.getElementById('puzDropZone').style.display = 'none';

  if (isVideo) {
    const v = document.getElementById('puzPreviewVid');
    v.src = url; v.style.display = 'block';
    document.getElementById('puzPreviewImg').style.display = 'none';
  } else {
    const img = document.getElementById('puzPreviewImg');
    img.src = url; img.style.display = 'block';
    document.getElementById('puzPreviewVid').style.display = 'none';
  }
  document.getElementById('puzSubmitBtn').disabled = false;
}

function puzClearPreview() {
  document.getElementById('puzPreview').style.display  = 'none';
  document.getElementById('puzDropZone').style.display = 'block';
  document.getElementById('puzPreviewVid').src = '';
  document.getElementById('puzPreviewImg').src = '';
  document.getElementById('puzFileInput').value = '';
  document.getElementById('puzSubmitBtn').disabled = true;
}

function puzSubmit() {
  const file = document.getElementById('puzFileInput').files[0];
  if (!file) { showToast('⚠️ Please select a file first'); return; }

  const btn      = document.getElementById('puzSubmitBtn');
  const formData = new FormData();
  formData.append('_token',   CSRF);
  formData.append('file',     file);
  formData.append('caption',  document.getElementById('puzCaption').value.trim());
  formData.append('hashtags', document.getElementById('puzHashtags').value.trim());

  btn.disabled = true;
  document.getElementById('puzProgress').style.display = 'block';

  const xhr = new XMLHttpRequest();
  xhr.open('POST', STORE_URL);
  xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
  xhr.setRequestHeader('Accept', 'application/json');

  xhr.upload.onprogress = ev => {
    if (ev.lengthComputable) {
      const pct = Math.round(ev.loaded / ev.total * 100);
      document.getElementById('puzProgressBar').style.width  = pct + '%';
      document.getElementById('puzProgressText').textContent = `Uploading… ${pct}%`;
    }
  };

  xhr.onload = () => {
    try {
      const data = JSON.parse(xhr.responseText);
      if (data.success) {
        showToast('🎬 Reel uploaded successfully!');
        closeProfileUploadModal();
        setTimeout(() => location.reload(), 900);
      } else {
        const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Upload failed');
        showToast('❌ ' + msg);
        btn.disabled = false;
      }
    } catch {
      showToast('❌ Upload failed. Please try again.');
      btn.disabled = false;
    }
  };

  xhr.onerror = () => {
    showToast('❌ Network error. Please try again.');
    btn.disabled = false;
  };

  xhr.send(formData);
}

// Backdrop close
document.getElementById('profileUploadModal').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeProfileUploadModal();
});

// ══ Username check ══════════════════════════════════════
let usernameTimer = null;
function checkUsername(input) {
  const hint = document.getElementById('usernameHint');
  const val  = input.value.trim();
  input.classList.remove('available','taken');
  if (val.length < 3) { hint.textContent = ''; return; }
  if (val === '{{ $user->username }}') { hint.textContent = ''; return; }
  clearTimeout(usernameTimer);
  hint.textContent = '⏳ Checking…'; hint.className = 's-hint';
  usernameTimer = setTimeout(() => {
    fetch(`/profile/check-username?username=${encodeURIComponent(val)}`)
    .then(r => r.json())
    .then(data => {
      if (data.available) {
        hint.textContent = '✅ Available!'; hint.className = 's-hint ok';
        input.classList.add('available');
      } else {
        hint.textContent = '❌ Already taken'; hint.className = 's-hint err';
        input.classList.add('taken');
      }
    });
  }, 500);
}

// ══ Password match ══════════════════════════════════════
function checkPassMatch(input) {
  const hint = document.getElementById('passMatchHint');
  if (input.value === document.getElementById('newPass').value) {
    hint.textContent = '✅ Passwords match'; hint.className = 's-hint ok';
  } else {
    hint.textContent = '❌ Do not match'; hint.className = 's-hint err';
  }
}

// ══ Avatar sheet ════════════════════════════════════════
function openAvatarSheet()  { document.getElementById('avatarSheet').classList.add('open'); }
function closeAvatarSheet() { document.getElementById('avatarSheet').classList.remove('open'); }

function uploadAvatar(input) {
  const file = input.files[0]; if (!file) return;
  const formData = new FormData();
  formData.append('avatar', file);
  formData.append('name',     '{{ $user->name }}');
  formData.append('username', '{{ $user->username }}');
  formData.append('_token', CSRF);

  document.getElementById('avatarProgress').style.display = 'block';
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '/profile/update');
  xhr.upload.onprogress = e => {
    if (e.lengthComputable)
      document.getElementById('avatarProgressBar').style.width = (e.loaded/e.total*100) + '%';
  };
  xhr.onload = () => {
    closeAvatarSheet();
    showToast('✅ Avatar updated!');
    setTimeout(() => location.reload(), 800);
  };
  xhr.send(formData);
}
document.getElementById('avatarSheet').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeAvatarSheet();
});

// ══ Toggle private ═══════════════════════════════════════
function togglePrivate(checkbox) {
  fetch('/profile/update', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
    body: JSON.stringify({ is_private: checkbox.checked, name: '{{ $user->name }}', username: '{{ $user->username }}' })
  })
  .then(() => showToast(checkbox.checked ? '🔒 Account set to private' : '🌐 Account set to public'));
}

// ══ Toast ════════════════════════════════════════════════
function showToast(msg) {
  const existing = document.getElementById('profileToast');
  if (existing) existing.remove();
  const t = document.createElement('div');
  t.id = 'profileToast';
  t.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%) translateY(-20px);background:linear-gradient(135deg,#10B981,#4ECDC4);color:white;padding:11px 22px;border-radius:30px;font-weight:900;font-size:0.85rem;z-index:9999;opacity:0;transition:all .4s;pointer-events:none;white-space:nowrap;box-shadow:0 8px 24px rgba(0,0,0,.2);';
  t.textContent = msg;
  document.body.appendChild(t);
  requestAnimationFrame(() => { t.style.opacity='1'; t.style.transform='translateX(-50%) translateY(0)'; });
  setTimeout(() => { t.style.opacity='0'; t.style.transform='translateX(-50%) translateY(-20px)'; setTimeout(()=>t.remove(),400); }, 2800);
}

@if(session('toast_success'))
showToast('{{ session('toast_success') }}');
@endif
</script>
@endsection