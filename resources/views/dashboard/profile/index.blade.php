@extends('dashboard.layouts.app')
@section('title', 'Profile')

@section('content')
<div class="container">

  {{-- Hero --}}
  <div style="background:linear-gradient(135deg,var(--p1),var(--orange),var(--blue));border-radius:24px;padding:28px 22px;color:white;margin-bottom:20px;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-10px;right:-10px;font-size:5rem;opacity:.1;">✨</div>
    <div style="width:76px;height:76px;border-radius:50%;background:rgba(255,255,255,.25);border:3px solid rgba(255,255,255,.5);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 12px;font-weight:900;color:white;">
      {{ strtoupper(substr($user->name,0,1)) }}
    </div>
    <h2 style="font-family:'Fredoka One',cursive;font-size:1.5rem;margin-bottom:2px;">{{ $user->name }}</h2>
    <p style="font-size:0.8rem;opacity:.82;font-weight:700;">{{ $user->email }} @if($user->city) · {{ $user->city }} @endif</p>
    <div style="display:inline-block;background:rgba(255,255,255,.2);border-radius:20px;padding:4px 14px;font-size:0.78rem;font-weight:900;margin-top:8px;">⚡ Level {{ $user->level }} · {{ number_format($user->xp) }} XP</div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(255,255,255,.15);border-radius:14px;margin-top:16px;overflow:hidden;">
      <div style="padding:10px;text-align:center;background:rgba(255,255,255,.1);"><strong style="display:block;font-size:1.05rem;font-weight:900;">{{ $totalGamesPlayed }}</strong><span style="font-size:0.66rem;opacity:.8;font-weight:700;">Games</span></div>
      <div style="padding:10px;text-align:center;background:rgba(255,255,255,.1);"><strong style="display:block;font-size:1.05rem;font-weight:900;">{{ number_format($user->coins) }}</strong><span style="font-size:0.66rem;opacity:.8;font-weight:700;">Coins</span></div>
      <div style="padding:10px;text-align:center;background:rgba(255,255,255,.1);"><strong style="display:block;font-size:1.05rem;font-weight:900;">{{ $user->streak_days }}🔥</strong><span style="font-size:0.66rem;opacity:.8;font-weight:700;">Streak</span></div>
    </div>
  </div>

  {{-- Badges --}}
  @if($user->badges->count())
  <div class="section-hd"><h3>🏅 Badges</h3></div>
  <div style="display:flex;gap:10px;overflow-x:auto;padding-bottom:6px;margin-bottom:20px;scrollbar-width:none;">
    @foreach($user->badges as $badge)
    <div style="flex-shrink:0;text-align:center;">
      <div style="width:54px;height:54px;border-radius:14px;background:white;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 4px 12px rgba(0,0,0,.1);margin-bottom:4px;border:2px solid var(--border);">{{ $badge->emoji }}</div>
      <span style="font-size:0.62rem;font-weight:800;color:var(--muted);">{{ Str::limit($badge->name, 10) }}</span>
    </div>
    @endforeach
  </div>
  @endif

  {{-- Edit Profile Form --}}
  <div class="section-hd"><h3>✏️ Edit Profile</h3></div>
  <div style="background:white;border-radius:20px;padding:20px;box-shadow:0 4px 14px rgba(0,0,0,.06);margin-bottom:16px;">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.76rem;font-weight:900;color:var(--text);margin-bottom:5px;text-transform:uppercase;letter-spacing:.3px;">Full Name</label>
        <input type="text" name="name" value="{{ old('name',$user->name) }}" style="width:100%;background:var(--bg);border:2px solid var(--border);border-radius:12px;padding:11px 14px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:700;outline:none;" required>
        @error('name')<p style="color:var(--p1);font-size:0.75rem;font-weight:700;margin-top:3px;">{{ $message }}</p>@enderror
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.76rem;font-weight:900;color:var(--text);margin-bottom:5px;text-transform:uppercase;letter-spacing:.3px;">Username</label>
        <input type="text" name="username" value="{{ old('username',$user->username) }}" style="width:100%;background:var(--bg);border:2px solid var(--border);border-radius:12px;padding:11px 14px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:700;outline:none;">
        @error('username')<p style="color:var(--p1);font-size:0.75rem;font-weight:700;margin-top:3px;">{{ $message }}</p>@enderror
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.76rem;font-weight:900;color:var(--text);margin-bottom:5px;text-transform:uppercase;letter-spacing:.3px;">City</label>
        <input type="text" name="city" value="{{ old('city',$user->city) }}" placeholder="Delhi, Mumbai…" style="width:100%;background:var(--bg);border:2px solid var(--border);border-radius:12px;padding:11px 14px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:700;outline:none;">
      </div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">✅ Save Changes</button>
    </form>
  </div>

  {{-- Change Password --}}
  <div class="section-hd"><h3>🔒 Change Password</h3></div>
  <div style="background:white;border-radius:20px;padding:20px;box-shadow:0 4px 14px rgba(0,0,0,.06);margin-bottom:16px;">
    <form method="POST" action="{{ route('profile.password') }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.76rem;font-weight:900;color:var(--text);margin-bottom:5px;text-transform:uppercase;">Current Password</label>
        <input type="password" name="current_password" style="width:100%;background:var(--bg);border:2px solid var(--border);border-radius:12px;padding:11px 14px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:700;outline:none;" required>
        @error('current_password')<p style="color:var(--p1);font-size:0.75rem;font-weight:700;margin-top:3px;">{{ $message }}</p>@enderror
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.76rem;font-weight:900;color:var(--text);margin-bottom:5px;text-transform:uppercase;">New Password</label>
        <input type="password" name="password" style="width:100%;background:var(--bg);border:2px solid var(--border);border-radius:12px;padding:11px 14px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:700;outline:none;" required>
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.76rem;font-weight:900;color:var(--text);margin-bottom:5px;text-transform:uppercase;">Confirm Password</label>
        <input type="password" name="password_confirmation" style="width:100%;background:var(--bg);border:2px solid var(--border);border-radius:12px;padding:11px 14px;font-family:'Nunito',sans-serif;font-size:0.88rem;font-weight:700;outline:none;" required>
      </div>
      <button type="submit" class="btn-outline" style="width:100%;justify-content:center;">🔒 Update Password</button>
    </form>
  </div>

  {{-- Logout --}}
  <form method="POST" action="{{ route('logout') }}" style="margin-bottom:30px;">
    @csrf
    <button type="submit" style="width:100%;background:white;border:2px solid #FECACA;color:var(--p1);border-radius:14px;padding:13px;font-family:'Nunito',sans-serif;font-size:0.92rem;font-weight:900;cursor:pointer;transition:all .25s;"
            onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='white'">
      🚪 Logout
    </button>
  </form>

</div>
@endsection