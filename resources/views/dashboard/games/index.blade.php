{{-- resources/views/dashboard/games/index.blade.php --}}
@extends('dashboard.layouts.app')
@section('title', 'Games')

@section('content')
<div class="container">
  <div style="margin-bottom:16px;">
    <h2 style="font-family:'Fredoka One',cursive;font-size:1.55rem;">🎮 All Games</h2>
    <p style="font-size:0.82rem;color:var(--muted);font-weight:600;">Tap to play — real games, real coins!</p>
  </div>

  {{-- Filter --}}
  <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:8px;margin-bottom:16px;scrollbar-width:none;">
    @foreach(['All','puzzle','quiz','arcade','luck'] as $cat)
    <a href="{{ $cat === 'All' ? route('games.index') : route('games.index', ['category' => $cat]) }}"
       style="white-space:nowrap;
              background:{{ (!request('category') && $cat==='All') || request('category')===$cat ? 'linear-gradient(135deg,var(--p1),var(--orange))' : 'white' }};
              color:{{ (!request('category') && $cat==='All') || request('category')===$cat ? 'white' : 'var(--text)' }};
              border:2px solid var(--border);border-radius:20px;padding:7px 16px;
              font-size:0.8rem;font-weight:800;text-decoration:none;flex-shrink:0;transition:all .25s;">
      {{ ucfirst($cat) }}
    </a>
    @endforeach
  </div>

  {{-- Grid --}}
  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
    @foreach($games as $game)
    <a href="{{ $game->status === 'live' ? route('games.show', $game->slug) : '#' }}"
       style="background:white;border-radius:20px;overflow:hidden;
              box-shadow:0 4px 14px rgba(0,0,0,.07);text-decoration:none;
              color:var(--text);transition:all .3s;
              {{ $game->status !== 'live' ? 'opacity:.6;cursor:default;pointer-events:none;' : '' }}"
       onmouseover="{{ $game->status === 'live' ? 'this.style.transform=\"translateY(-4px)\";this.style.boxShadow=\"0 12px 30px rgba(0,0,0,.14)\"' : '' }}"
       onmouseout="this.style.transform='';this.style.boxShadow=''">

      <div style="height:96px;display:flex;align-items:center;justify-content:center;
                  font-size:2.8rem;background:{{ $game->gradient }};position:relative;">
        {{ $game->emoji }}
        @if($game->status === 'live')
        <span style="position:absolute;top:8px;right:8px;background:rgba(255,255,255,.25);
                     backdrop-filter:blur(8px);border-radius:20px;padding:2px 8px;
                     font-size:0.58rem;font-weight:900;color:white;display:flex;align-items:center;gap:3px;">
          <span style="width:5px;height:5px;background:#4ade80;border-radius:50%;animation:pulse 1.2s infinite;display:inline-block;"></span>LIVE
        </span>
        @elseif($game->status === 'soon')
        <span style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,.3);
                     border-radius:20px;padding:2px 8px;font-size:0.58rem;font-weight:900;color:white;">SOON</span>
        @endif
      </div>

      <div style="padding:12px;">
        <h4 style="font-weight:900;font-size:0.86rem;margin-bottom:3px;">{{ $game->name }}</h4>
        <p style="font-size:0.7rem;color:var(--muted);font-weight:600;">{{ Str::limit($game->description, 42) }}</p>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;">
          <span style="font-size:0.7rem;font-weight:700;color:var(--muted);">
            ⏱ {{ $game->play_time_seconds ?? 60 }}s · {{ $game->daily_play_limit }}x/day
          </span>
          <span style="font-size:0.74rem;font-weight:800;color:var(--p2);">🪙 +{{ $game->coin_reward }}</span>
        </div>
      </div>
    </a>
    @endforeach
  </div>
</div>

<style>
@keyframes pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(.85);}}
</style>
@endsection
