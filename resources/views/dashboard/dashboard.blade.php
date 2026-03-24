@extends('dashboard.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="container">

  {{-- Welcome Banner --}}
  <div style="background:linear-gradient(135deg,var(--p1),var(--orange),var(--blue));border-radius:24px;padding:24px 22px;color:white;margin-bottom:20px;position:relative;overflow:hidden;">
    <div style="position:absolute;right:-10px;bottom:-20px;font-size:6rem;opacity:.12;">🎮</div>
    <h2 style="font-family:'Fredoka One',cursive;font-size:1.55rem;margin-bottom:4px;">
      Welcome back, {{ $user->name }}! 👋
    </h2>
    <p style="font-size:0.85rem;opacity:.88;font-weight:700;">
      @if($user->streak_days > 0) You're on a {{ $user->streak_days }}-day streak! Keep it going 🔥
      @else Start playing to build your streak! @endif
    </p>
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;">
      <span style="background:rgba(255,255,255,.2);border-radius:20px;padding:5px 12px;font-size:0.78rem;font-weight:800;">🔥 {{ $user->streak_days }}-day streak</span>
      <span style="background:rgba(255,255,255,.2);border-radius:20px;padding:5px 12px;font-size:0.78rem;font-weight:800;">🏆 Rank #{{ $userRank }}</span>
      <span style="background:rgba(255,255,255,.2);border-radius:20px;padding:5px 12px;font-size:0.78rem;font-weight:800;">⚡ Level {{ $user->level }}</span>
    </div>
  </div>

  {{-- Stats --}}
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
    <div style="background:white;border-radius:18px;padding:16px 10px;text-align:center;box-shadow:0 4px 14px rgba(0,0,0,.06);">
      <div style="font-size:1.5rem;margin-bottom:4px;">🪙</div>
      <strong style="display:block;font-size:1.1rem;font-weight:900;">{{ $user->formatted_coins }}</strong>
      <span style="font-size:0.68rem;color:var(--muted);font-weight:700;">Coins</span>
    </div>
    <div style="background:white;border-radius:18px;padding:16px 10px;text-align:center;box-shadow:0 4px 14px rgba(0,0,0,.06);">
      <div style="font-size:1.5rem;margin-bottom:4px;">🎮</div>
      <strong style="display:block;font-size:1.1rem;font-weight:900;">{{ $user->gameSessions->count() }}</strong>
      <span style="font-size:0.68rem;color:var(--muted);font-weight:700;">Games</span>
    </div>
    <div style="background:white;border-radius:18px;padding:16px 10px;text-align:center;box-shadow:0 4px 14px rgba(0,0,0,.06);">
      <div style="font-size:1.5rem;margin-bottom:4px;">🏅</div>
      <strong style="display:block;font-size:1.1rem;font-weight:900;">{{ $user->badges->count() }}</strong>
      <span style="font-size:0.68rem;color:var(--muted);font-weight:700;">Badges</span>
    </div>
  </div>

  {{-- Quick Play --}}
  <div class="section-hd">
    <h3>🎮 Quick Play</h3>
    <a href="{{ route('games.index') }}" class="see-all">See all →</a>
  </div>
  <div style="display:flex;gap:12px;overflow-x:auto;padding-bottom:8px;scrollbar-width:none;margin-bottom:20px;">
    @foreach($quickGames as $game)
    <a href="{{ route('games.show', $game->slug) }}" style="min-width:128px;background:white;border-radius:18px;overflow:hidden;box-shadow:0 4px 14px rgba(0,0,0,.07);text-decoration:none;color:var(--text);flex-shrink:0;transition:all .3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
      <div style="height:78px;display:flex;align-items:center;justify-content:center;font-size:2.3rem;background:{{ $game->gradient }};"></div>
      <div style="padding:10px;">
        <h4 style="font-size:0.78rem;font-weight:900;margin-bottom:2px;">{{ $game->name }}</h4>
        <div style="font-size:0.7rem;font-weight:800;color:var(--p2);">🪙 +{{ $game->coin_reward }}</div>
      </div>
    </a>
    @endforeach
  </div>

  {{-- Leaderboard preview --}}
  <div class="section-hd">
    <h3>🏅 Top Players</h3>
    <a href="{{ route('leaderboard') }}" class="see-all">Full board →</a>
  </div>
  @foreach($leaderboard as $i => $player)
  <div style="background:{{ $i===0 ? 'linear-gradient(135deg,rgba(255,230,109,.18),rgba(249,115,22,.1))' : 'white' }};border-radius:16px;padding:12px 16px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,.05);margin-bottom:10px;{{ $i===0 ? 'border:2px solid rgba(255,200,0,.3);' : '' }}">
    <div style="font-family:'Fredoka One',cursive;font-size:1.15rem;width:30px;text-align:center;">{{ ['🥇','🥈','🥉'][$i] ?? ($i+1) }}</div>
    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--p1),var(--orange));display:flex;align-items:center;justify-content:center;font-weight:900;color:white;flex-shrink:0;">{{ strtoupper(substr($player->name,0,1)) }}</div>
    <div>
      <div style="font-weight:900;font-size:0.86rem;">{{ $player->username ?? $player->name }}</div>
      <div style="font-size:0.7rem;color:var(--muted);font-weight:600;">Level {{ $player->level }}</div>
    </div>
    <div style="margin-left:auto;font-weight:900;font-size:0.85rem;color:var(--p2);">🪙 {{ number_format($player->coins) }}</div>
  </div>
  @endforeach

</div>
@endsection