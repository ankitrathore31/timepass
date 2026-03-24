{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- resources/views/games/show.blade.php                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@extends('dashboard.layouts.app')
@section('title', $game->name)

@section('content')
<div class="container">

  {{-- Header --}}
  <div style="background:{{ $game->gradient }};border-radius:24px;padding:28px 22px;color:white;margin-bottom:20px;text-align:center;position:relative;overflow:hidden;">
    <div style="font-size:4rem;margin-bottom:8px;">{{ $game->emoji }}</div>
    <h2 style="font-family:'Fredoka One',cursive;font-size:1.6rem;margin-bottom:4px;">{{ $game->name }}</h2>
    <p style="font-size:0.85rem;opacity:.88;font-weight:700;">{{ $game->description }}</p>
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:14px;">
      <span style="background:rgba(255,255,255,.22);border-radius:20px;padding:5px 14px;font-size:0.8rem;font-weight:900;">🪙 Win: +{{ $game->coin_reward }} coins</span>
      <span style="background:rgba(255,255,255,.22);border-radius:20px;padding:5px 14px;font-size:0.8rem;font-weight:900;">⚡ XP: +{{ $game->xp_reward }}</span>
      <span style="background:rgba(255,255,255,.22);border-radius:20px;padding:5px 14px;font-size:0.8rem;font-weight:900;">🎮 {{ $todayPlays }}/{{ $game->daily_play_limit }} plays today</span>
    </div>
  </div>

  @if(!$canPlay)
  <div style="background:#FEF9EC;border:2px solid #FDE68A;border-radius:16px;padding:16px;text-align:center;margin-bottom:16px;">
    <p style="font-weight:900;color:#92400E;">⏰ Daily limit reached! Come back tomorrow for more coins.</p>
  </div>
  @endif

  {{-- Game area --}}
  <div style="background:white;border-radius:24px;padding:20px;box-shadow:0 6px 24px rgba(0,0,0,.08);margin-bottom:20px;" id="gameArea">
    @if($canPlay)
    <div id="gameContainer" style="text-align:center;padding:20px 0;">
      <div style="font-size:3rem;margin-bottom:12px;">{{ $game->emoji }}</div>
      <h3 style="font-family:'Fredoka One',cursive;font-size:1.3rem;margin-bottom:8px;">Ready to play?</h3>
      <p style="color:var(--muted);font-weight:600;font-size:0.88rem;margin-bottom:20px;">Earn up to {{ $game->coin_reward }} coins per game</p>
      <button class="btn-primary" onclick="startGame()" style="margin:0 auto;">🚀 Start Game</button>
    </div>
    @else
    <div style="text-align:center;padding:20px;">
      <div style="font-size:3rem;margin-bottom:12px;">⏰</div>
      <p style="font-weight:800;color:var(--muted);">Come back tomorrow to play again!</p>
    </div>
    @endif
  </div>

  {{-- Leaderboard for this game --}}
  @if($leaderboard->count())
  <div class="section-hd"><h3>🏅 Top Scores</h3></div>
  @foreach($leaderboard as $i => $session)
  <div style="background:white;border-radius:14px;padding:12px 15px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,.05);margin-bottom:10px;">
    <div style="font-family:'Fredoka One',cursive;font-size:1.1rem;width:28px;text-align:center;">{{ ['🥇','🥈','🥉'][$i] ?? ($i+1) }}</div>
    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--p1),var(--orange));display:flex;align-items:center;justify-content:center;font-weight:900;color:white;flex-shrink:0;font-size:0.9rem;">{{ strtoupper(substr($session->user->name,0,1)) }}</div>
    <div>
      <div style="font-weight:900;font-size:0.84rem;">{{ $session->user->username ?? $session->user->name }}</div>
    </div>
    <div style="margin-left:auto;font-weight:900;color:var(--p2);font-size:0.84rem;">{{ number_format($session->score) }} pts</div>
  </div>
  @endforeach
  @endif

</div>


<script>
const GAME_SLUG = '{{ $game->slug }}';
const SAVE_URL  = '{{ route("games.save", $game->slug) }}';
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;

function startGame() {
  // Each game can have its own JS — here we demonstrate a simple score button
  // In production, each game slug would load specific game logic
  document.getElementById('gameContainer').innerHTML = `
    <p style="font-weight:900;font-size:1rem;margin-bottom:16px;">🎮 Playing: <strong>{{ $game->name }}</strong></p>
    <p style="color:var(--muted);font-weight:700;font-size:0.85rem;margin-bottom:20px;">
      (Your actual game JS goes here per game slug)
    </p>
    <button class="btn-primary" onclick="submitResult(Math.floor(Math.random()*100)+50, true, 30)" style="margin:0 auto 10px;">
      ✅ Simulate Win (+{{ $game->coin_reward }} coins)
    </button>
    <br>
    <button class="btn-outline" onclick="submitResult(10, false, 15)" style="margin:0 auto;">
      ❌ Simulate Loss (partial coins)
    </button>
  `;
}

function submitResult(score, won, duration) {
  fetch(SAVE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ score, won, duration_seconds: duration })
  })
  .then(r => r.json())
  .then(data => {
    if (data.error) { alert(data.error); return; }
    updateNavCoins(data.total_coins);
    document.getElementById('gameContainer').innerHTML = `
      <div style="text-align:center;padding:10px;">
        <div style="font-size:3.5rem;margin-bottom:10px;">${won ? '🏆' : '😅'}</div>
        <h3 style="font-family:'Fredoka One',cursive;font-size:1.4rem;margin-bottom:6px;">${data.message}</h3>
        <div style="display:inline-block;background:linear-gradient(135deg,#FFE66D,#F97316);color:#1A1A2E;padding:8px 20px;border-radius:20px;font-family:'Fredoka One',cursive;font-size:1.1rem;margin:10px 0;">
          🪙 +${data.coins_earned} Coins Earned!
        </div>
        <br><br>
        <button class="btn-primary" onclick="startGame()" style="margin:0 auto;">🔄 Play Again</button>
      </div>`;
  })
  .catch(() => alert('Network error. Please try again.'));
}
</script>

@endsection
