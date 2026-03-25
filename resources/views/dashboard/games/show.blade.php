{{-- resources/views/dashboard/games/show.blade.php --}}
@extends('dashboard.layouts.app')
@section('title', $game->name)

@push('styles')
<style>
/* ── Shared game UI ───────────────────────────────── */
.game-wrap{background:white;border-radius:24px;padding:20px;box-shadow:0 6px 24px rgba(0,0,0,.08);margin-bottom:20px;}
.game-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;}
.game-chip{background:var(--bg);border:2px solid var(--border);border-radius:20px;padding:5px 13px;font-size:0.78rem;font-weight:800;color:var(--text);display:flex;align-items:center;gap:5px;}
.game-chip.highlight{background:linear-gradient(135deg,var(--yellow),var(--p2));border-color:transparent;}

/* countdown ring */
.timer-ring{position:relative;width:52px;height:52px;flex-shrink:0;}
.timer-ring svg{transform:rotate(-90deg);}
.timer-ring circle{fill:none;stroke-width:4;}
.timer-ring .bg{stroke:#E5E7EB;}
.timer-ring .fg{stroke:var(--p1);stroke-linecap:round;transition:stroke-dashoffset .9s linear;}
.timer-num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Fredoka One',cursive;font-size:1rem;color:var(--text);}

/* Score pop */
@keyframes scorePop{0%{transform:scale(1);}40%{transform:scale(1.35);}100%{transform:scale(1);}}
.score-pop{animation:scorePop .35s ease;}

/* overlay */
.game-overlay{position:absolute;inset:0;background:rgba(255,248,240,.96);border-radius:20px;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  z-index:10;text-align:center;padding:20px;}
.overlay-emoji{font-size:4rem;margin-bottom:10px;}
.overlay-title{font-family:'Fredoka One',cursive;font-size:1.6rem;margin-bottom:6px;}
.overlay-sub{font-size:0.9rem;color:var(--muted);font-weight:700;margin-bottom:16px;}
.coins-pop{display:inline-block;background:linear-gradient(135deg,var(--yellow),var(--p2));
  color:var(--text);padding:8px 22px;border-radius:20px;font-family:'Fredoka One',cursive;font-size:1.2rem;margin-bottom:16px;}
.btn-play-again{background:linear-gradient(135deg,var(--p1),var(--orange));color:white;border:none;
  border-radius:14px;padding:12px 28px;font-family:'Nunito',sans-serif;font-size:0.95rem;font-weight:900;
  cursor:pointer;transition:all .3s;}
.btn-play-again:hover{transform:translateY(-2px);}

/* ── SNAKE ───────────────────────────────────────── */
#snakeCanvas{display:block;margin:0 auto;border-radius:14px;border:3px solid var(--border);cursor:none;}
.snake-dpad{display:grid;grid-template-columns:repeat(3,48px);gap:6px;margin:12px auto 0;width:fit-content;}
.dpad-btn{width:48px;height:48px;background:var(--bg);border:2px solid var(--border);border-radius:12px;
  font-size:1.2rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;}
.dpad-btn:active{background:var(--p1);color:white;border-color:var(--p1);}

/* ── WORD ────────────────────────────────────────── */
.word-display{font-family:'Fredoka One',cursive;font-size:2rem;letter-spacing:10px;
  color:var(--text);text-align:center;margin:10px 0;min-height:52px;}
.letter-grid{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:12px 0;}
.letter-tile{width:42px;height:42px;background:var(--bg);border:2.5px solid var(--border);
  border-radius:11px;display:flex;align-items:center;justify-content:center;
  font-weight:900;font-size:1.05rem;cursor:pointer;transition:all .2s;user-select:none;}
.letter-tile:hover{background:var(--p1);color:white;border-color:var(--p1);transform:scale(1.08);}
.letter-tile.used{opacity:.3;pointer-events:none;}
.letter-tile.correct-flash{background:var(--green);color:white;border-color:var(--green);}
.word-input-row{display:flex;gap:8px;align-items:center;justify-content:center;margin-top:8px;}
.word-input{background:var(--bg);border:2.5px solid var(--border);border-radius:13px;
  padding:11px 16px;font-family:'Nunito',sans-serif;font-size:1rem;font-weight:800;
  outline:none;text-align:center;text-transform:uppercase;letter-spacing:4px;
  transition:all .25s;width:200px;}
.word-input:focus{border-color:var(--p1);}
.hint-box{background:linear-gradient(135deg,rgba(255,107,107,.08),rgba(249,115,22,.08));
  border:1.5px solid rgba(255,107,107,.2);border-radius:12px;padding:10px 14px;
  font-size:0.82rem;font-weight:700;color:var(--text);text-align:center;margin:8px 0;}

/* ── QUIZ ────────────────────────────────────────── */
.quiz-q-text{font-weight:900;font-size:1rem;line-height:1.55;color:var(--text);margin-bottom:14px;}
.quiz-opts{display:flex;flex-direction:column;gap:9px;}
.quiz-opt{background:var(--bg);border:2.5px solid var(--border);border-radius:14px;
  padding:12px 16px;font-weight:800;font-size:0.88rem;cursor:pointer;
  transition:all .25s;text-align:left;font-family:'Nunito',sans-serif;}
.quiz-opt:hover:not(:disabled){border-color:var(--orange);background:rgba(249,115,22,.06);}
.quiz-opt.correct{background:#D1FAE5;border-color:var(--green);color:#065F46;}
.quiz-opt.wrong{background:#FEE2E2;border-color:var(--p1);color:#991B1B;}
.quiz-progress{height:7px;background:var(--border);border-radius:10px;margin-bottom:12px;overflow:hidden;}
.quiz-progress-fill{height:100%;background:linear-gradient(90deg,var(--p1),var(--orange));border-radius:10px;transition:width .4s;}

/* ── MEMORY ──────────────────────────────────────── */
.memory-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
.mem-card{aspect-ratio:1;border-radius:12px;cursor:pointer;transition:transform .35s;
  transform-style:preserve-3d;position:relative;}
.mem-card .front,.mem-card .back{
  position:absolute;inset:0;border-radius:12px;
  display:flex;align-items:center;justify-content:center;
  backface-visibility:hidden;-webkit-backface-visibility:hidden;}
.mem-card .back{background:linear-gradient(135deg,var(--p1),var(--orange));font-size:1.5rem;}
.mem-card .front{background:var(--bg);border:2.5px solid var(--border);font-size:1.6rem;
  transform:rotateY(180deg);}
.mem-card.flipped{transform:rotateY(180deg);}
.mem-card.matched .front{background:#D1FAE5;border-color:var(--green);}
.mem-card.matched{cursor:default;}
.mem-card:not(.matched):not(.flipped) .back::after{content:'?';font-size:1.2rem;font-weight:900;color:rgba(255,255,255,.6);}

/* ── SPIN ────────────────────────────────────────── */
#spinCanvas{display:block;margin:0 auto;cursor:pointer;}
.spin-result-label{font-family:'Fredoka One',cursive;font-size:1.5rem;text-align:center;
  min-height:40px;margin:10px 0;}
.spin-btn{background:linear-gradient(135deg,var(--p1),var(--orange));color:white;border:none;
  border-radius:14px;padding:13px 32px;font-family:'Nunito',sans-serif;font-size:1rem;
  font-weight:900;cursor:pointer;transition:all .3s;display:block;margin:0 auto;}
.spin-btn:hover{transform:translateY(-2px);}
.spin-btn:disabled{opacity:.5;cursor:default;transform:none;}
</style>
@endpush

@section('content')
<div class="container">

{{-- ── Game Header ── --}}
<div style="background:{{ $game->gradient }};border-radius:24px;padding:24px 20px;color:white;margin-bottom:18px;position:relative;overflow:hidden;">
  <div style="position:absolute;right:-10px;bottom:-15px;font-size:6rem;opacity:.12;">{{ $game->emoji }}</div>
  <div style="font-size:2.5rem;margin-bottom:6px;">{{ $game->emoji }}</div>
  <h2 style="font-family:'Fredoka One',cursive;font-size:1.45rem;margin-bottom:3px;">{{ $game->name }}</h2>
  <p style="font-size:0.83rem;opacity:.88;font-weight:700;">{{ $game->description }}</p>
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;">
    <span style="background:rgba(255,255,255,.22);border-radius:20px;padding:4px 13px;font-size:0.78rem;font-weight:900;">🪙 Win: +{{ $game->coin_reward }}</span>
    <span style="background:rgba(255,255,255,.22);border-radius:20px;padding:4px 13px;font-size:0.78rem;font-weight:900;">⚡ XP: +{{ $game->xp_reward }}</span>
    <span style="background:rgba(255,255,255,.22);border-radius:20px;padding:4px 13px;font-size:0.78rem;font-weight:900;">🎮 {{ $todayPlays }}/{{ $game->daily_play_limit }} plays today</span>
  </div>
</div>

@if(!$canPlay)
<div style="background:#FEF9EC;border:2px solid #FDE68A;border-radius:16px;padding:14px;text-align:center;margin-bottom:16px;">
  <p style="font-weight:900;color:#92400E;">⏰ Daily limit reached! Come back tomorrow.</p>
</div>
@endif

{{-- ── Game Container ── --}}
<div class="game-wrap" style="position:relative;" id="gameWrap">

  {{-- Top bar --}}
  <div class="game-topbar">
    <div style="display:flex;align-items:center;gap:8px;">
      <div class="game-chip highlight">🪙 <span id="scoreDisplay">0</span></div>
      <div class="game-chip" id="livesChip" style="display:none;">❤️ <span id="livesDisplay">3</span></div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
      <div class="game-chip" id="roundChip" style="display:none;">Round <span id="roundDisplay">1</span></div>
      {{-- Timer ring --}}
      <div class="timer-ring" id="timerRing" style="display:none;">
        <svg width="52" height="52" viewBox="0 0 52 52">
          <circle class="bg" cx="26" cy="26" r="22"/>
          <circle class="fg" id="timerArc" cx="26" cy="26" r="22"
            stroke-dasharray="138.2" stroke-dashoffset="0"/>
        </svg>
        <div class="timer-num" id="timerNum">60</div>
      </div>
    </div>
  </div>

  {{-- ── START SCREEN ── --}}
  @if($canPlay)
  <div id="startScreen" style="text-align:center;padding:28px 10px;">
    <div style="font-size:3.5rem;margin-bottom:10px;">{{ $game->emoji }}</div>
    <h3 style="font-family:'Fredoka One',cursive;font-size:1.35rem;margin-bottom:6px;">Ready to play?</h3>
    <p style="color:var(--muted);font-weight:600;font-size:0.85rem;margin-bottom:8px;">
      @switch($game->slug)
        @case('snake-turbo') Eat 🍎 to grow. Avoid walls &amp; yourself. 60 seconds! @break
        @case('word-puzzle') Unscramble words before time runs out! @break
        @case('brain-quiz')  5 questions. Tap the correct answer fast! @break
        @case('memory-cards') Flip &amp; match all pairs. Fewest moves wins! @break
        @case('spin-win')   2 free spins. Best prize counts! @break
        @default            Play and earn coins!
      @endswitch
    </p>
    <p style="font-size:0.8rem;font-weight:800;color:var(--p2);margin-bottom:20px;">Earn up to 🪙 {{ $game->coin_reward }} coins</p>
    <button class="btn-play-again" onclick="GAME.start()" style="font-size:1rem;padding:14px 36px;">
      🚀 Start Game
    </button>
  </div>
  @else
  <div style="text-align:center;padding:30px;">
    <div style="font-size:3rem;margin-bottom:10px;">⏰</div>
    <p style="font-weight:800;color:var(--muted);">Come back tomorrow to play again!</p>
  </div>
  @endif

  {{-- ── GAME AREAS (hidden until started) ── --}}
  {{-- Snake --}}
  <div id="snakeArea" style="display:none;">
    <canvas id="snakeCanvas" width="288" height="288"></canvas>
    <div class="snake-dpad">
      <div></div>
      <button class="dpad-btn" ontouchstart="GAME.dir(0,-1)" onclick="GAME.dir(0,-1)">⬆️</button>
      <div></div>
      <button class="dpad-btn" ontouchstart="GAME.dir(-1,0)" onclick="GAME.dir(-1,0)">⬅️</button>
      <button class="dpad-btn" style="background:var(--bg);border-color:var(--border);" ontouchstart="GAME.pause()" onclick="GAME.pause()">⏸</button>
      <button class="dpad-btn" ontouchstart="GAME.dir(1,0)" onclick="GAME.dir(1,0)">➡️</button>
      <div></div>
      <button class="dpad-btn" ontouchstart="GAME.dir(0,1)" onclick="GAME.dir(0,1)">⬇️</button>
      <div></div>
    </div>
  </div>

  {{-- Word Puzzle --}}
  <div id="wordArea" style="display:none;">
    <div class="hint-box" id="wordHint">💡 Loading hint…</div>
    <div class="word-display" id="wordDisplay">_ _ _ _ _</div>
    <div class="letter-grid" id="letterGrid"></div>
    <div class="word-input-row">
      <input class="word-input" id="wordInput" maxlength="14" placeholder="TYPE HERE"
             oninput="GAME.checkInput()" autocomplete="off" autocapitalize="characters">
      <button onclick="GAME.clearInput()" style="background:var(--bg);border:2px solid var(--border);border-radius:11px;padding:11px 12px;cursor:pointer;font-size:1rem;">✕</button>
    </div>
    <div id="wordMsg" style="min-height:22px;text-align:center;font-weight:900;font-size:0.88rem;margin-top:6px;"></div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;">
      <span style="font-size:0.78rem;font-weight:700;color:var(--muted);">Word <span id="wordNum">1</span>/8</span>
      <button onclick="GAME.skipWord()" style="background:none;border:none;color:var(--muted);font-size:0.78rem;font-weight:800;cursor:pointer;">Skip →</button>
    </div>
  </div>

  {{-- Quiz --}}
  <div id="quizArea" style="display:none;">
    <div class="quiz-progress"><div class="quiz-progress-fill" id="quizBar" style="width:0%"></div></div>
    <div id="quizStatus" style="font-size:0.8rem;font-weight:800;color:var(--muted);margin-bottom:10px;"></div>
    <div class="quiz-q-text" id="quizQ"></div>
    <div class="quiz-opts" id="quizOpts"></div>
    <div id="quizFeedback" style="min-height:28px;margin-top:10px;font-weight:900;font-size:0.88rem;text-align:center;"></div>
  </div>

  {{-- Memory --}}
  <div id="memoryArea" style="display:none;">
    <div style="display:flex;justify-content:space-between;font-size:0.8rem;font-weight:800;color:var(--muted);margin-bottom:10px;">
      <span>Moves: <strong id="memMoves">0</strong></span>
      <span>Pairs: <strong id="memPairs">0</strong>/8</span>
    </div>
    <div class="memory-grid" id="memGrid"></div>
  </div>

  {{-- Spin --}}
  <div id="spinArea" style="display:none;text-align:center;">
    <p style="font-size:0.8rem;font-weight:800;color:var(--muted);margin-bottom:8px;">
      Spins left: <strong id="spinsLeft">2</strong>
    </p>
    <canvas id="spinCanvas" width="260" height="260"></canvas>
    <div class="spin-result-label" id="spinResultLabel">👆 Tap SPIN!</div>
    <button class="spin-btn" id="spinBtn" onclick="GAME.spin()">🎯 SPIN!</button>
  </div>

  {{-- Overlay (result / pause) --}}
  <div class="game-overlay" id="gameOverlay" style="display:none;">
    <div class="overlay-emoji" id="olEmoji">🏆</div>
    <div class="overlay-title" id="olTitle">Game Over!</div>
    <div class="overlay-sub"  id="olSub">You scored 0 points</div>
    <div class="coins-pop"    id="olCoins">🪙 +0 Coins!</div>
    <button class="btn-play-again" id="olBtn" onclick="GAME.restart()">🔄 Play Again</button>
    <a href="{{ route('games.index') }}" style="display:block;margin-top:10px;font-size:0.8rem;font-weight:800;color:var(--muted);">← Back to Games</a>
  </div>
</div>

{{-- Leaderboard --}}
@if($leaderboard->count())
<div style="margin-top:4px;">
  <h3 style="font-family:'Fredoka One',cursive;font-size:1.15rem;margin-bottom:12px;">🏅 Top Scores</h3>
  @foreach($leaderboard as $i => $session)
  <div style="background:white;border-radius:14px;padding:11px 14px;display:flex;align-items:center;gap:11px;box-shadow:0 2px 8px rgba(0,0,0,.05);margin-bottom:9px;">
    <div style="font-family:'Fredoka One',cursive;font-size:1.1rem;width:26px;text-align:center;">{{ ['🥇','🥈','🥉'][$i] ?? ($i+1) }}</div>
    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--p1),var(--orange));display:flex;align-items:center;justify-content:center;font-weight:900;color:white;font-size:0.88rem;flex-shrink:0;">{{ strtoupper(substr($session->user->name,0,1)) }}</div>
    <div style="flex:1;">
      <div style="font-weight:900;font-size:0.83rem;">{{ $session->user->username ?? $session->user->name }}</div>
    </div>
    <div style="font-weight:900;color:var(--p2);font-size:0.83rem;">{{ number_format($session->score) }} pts</div>
  </div>
  @endforeach
</div>
@endif

</div>{{-- /container --}}
@endsection

@push('scripts')
<script>
// ══════════════════════════════════════════════════════════════
//  TimePass Game Engine  –  v2.0
//  Handles: snake | word-puzzle | brain-quiz | memory-cards | spin-win
// ══════════════════════════════════════════════════════════════
const SLUG      = @json($game->slug);
const SAVE_URL  = @json(route('games.save', $game->slug));
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const MAX_COINS = {{ $game->coin_reward }};
const CAN_PLAY  = {{ $canPlay ? 'true' : 'false' }};

// ── UI helpers ────────────────────────────────────────────────
const $  = id => document.getElementById(id);
const show = id => $(id).style.display = '';
const hide = id => $(id).style.display = 'none';

let timerInterval = null;
let timeLeft = 60, totalTime = 60;
const ARC_LEN = 138.2;

function startTimer(seconds, onTick, onEnd) {
  clearInterval(timerInterval);
  timeLeft = totalTime = seconds;
  show('timerRing');
  $('timerNum').textContent = timeLeft;
  $('timerArc').style.strokeDashoffset = 0;
  timerInterval = setInterval(() => {
    timeLeft--;
    $('timerNum').textContent = timeLeft;
    $('timerArc').style.strokeDashoffset = ARC_LEN * (1 - timeLeft / totalTime);
    $('timerArc').style.stroke = timeLeft <= 10 ? '#FF6B6B' : 'var(--p1)';
    if (onTick) onTick(timeLeft);
    if (timeLeft <= 0) { clearInterval(timerInterval); onEnd(); }
  }, 1000);
}
function stopTimer() { clearInterval(timerInterval); }

function setScore(n) {
  $('scoreDisplay').textContent = n;
  $('scoreDisplay').parentElement.classList.add('score-pop');
  setTimeout(() => $('scoreDisplay').parentElement.classList.remove('score-pop'), 350);
}

function showOverlay({ emoji, title, sub, coins, won }) {
  stopTimer();
  $('olEmoji').textContent = emoji;
  $('olTitle').textContent = title;
  $('olSub').textContent   = sub;
  $('olCoins').textContent = `🪙 +${coins} Coins!`;
  $('gameOverlay').style.display = 'flex';
  saveResult(currentScore, won, Math.round(elapsed()));
  if (typeof updateNavCoins === 'function') {
    // will be updated after server responds
  }
}

let _startTime = 0;
function elapsed() { return (Date.now() - _startTime) / 1000; }

let currentScore = 0;
function addScore(n) {
  currentScore += n;
  setScore(currentScore);
}

// ── Save to server ────────────────────────────────────────────
function saveResult(score, won, duration) {
  const coinsEarned = won
    ? Math.round(MAX_COINS * Math.min(score / 100, 1))
    : Math.round(MAX_COINS * 0.15);

  $('olCoins').textContent = `🪙 +${Math.max(5, coinsEarned)} Coins!`;

  fetch(SAVE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ score, won, duration_seconds: Math.ceil(duration) })
  })
  .then(r => r.json())
  .then(data => {
    if (data.error) return;
    $('olCoins').textContent = `🪙 +${data.coins_earned} Coins!`;
    if (typeof updateNavCoins === 'function') updateNavCoins(data.total_coins);
  })
  .catch(() => {});
}

// ══════════════════════════════════════════════════════════════
//  SNAKE GAME
// ══════════════════════════════════════════════════════════════
const SnakeGame = (() => {
  const CELL = 18, COLS = 16, ROWS = 16;
  let canvas, ctx, snake, dir, nextDir, food, score, running, paused, lives, snakeInterval;

  function draw() {
    ctx.fillStyle = '#1A1A2E';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // grid
    ctx.strokeStyle = 'rgba(255,255,255,0.03)';
    ctx.lineWidth = 1;
    for (let i = 0; i <= COLS; i++) {
      ctx.beginPath(); ctx.moveTo(i*CELL,0); ctx.lineTo(i*CELL,ROWS*CELL); ctx.stroke();
    }
    for (let i = 0; i <= ROWS; i++) {
      ctx.beginPath(); ctx.moveTo(0,i*CELL); ctx.lineTo(COLS*CELL,i*CELL); ctx.stroke();
    }

    // food
    ctx.font = CELL + 'px serif';
    ctx.fillText('🍎', food.x * CELL, food.y * CELL + CELL - 1);

    // snake
    snake.forEach((s, i) => {
      const g = ctx.createLinearGradient(s.x*CELL, s.y*CELL, (s.x+1)*CELL, (s.y+1)*CELL);
      g.addColorStop(0, i === 0 ? '#FF6B6B' : '#FF8E53');
      g.addColorStop(1, i === 0 ? '#FF8E53' : '#F97316');
      ctx.fillStyle = g;
      ctx.beginPath();
      if (ctx.roundRect) ctx.roundRect(s.x*CELL+1, s.y*CELL+1, CELL-2, CELL-2, 4);
      else ctx.rect(s.x*CELL+1, s.y*CELL+1, CELL-2, CELL-2);
      ctx.fill();
      if (i === 0) {
        ctx.fillStyle = 'white';
        ctx.font = 'bold 9px sans-serif';
        ctx.fillText('●●', s.x*CELL+4, s.y*CELL+11);
      }
    });

    if (paused) {
      ctx.fillStyle = 'rgba(0,0,0,.55)';
      ctx.fillRect(0,0,canvas.width,canvas.height);
      ctx.fillStyle = 'white'; ctx.font = 'bold 18px Nunito'; ctx.textAlign = 'center';
      ctx.fillText('PAUSED – tap ⏸ to resume', canvas.width/2, canvas.height/2);
      ctx.textAlign = 'left';
    }
  }

  function randFood() {
    let pos;
    do { pos = { x: Math.floor(Math.random()*COLS), y: Math.floor(Math.random()*ROWS) }; }
    while (snake.some(s => s.x === pos.x && s.y === pos.y));
    return pos;
  }

  function tick() {
    if (!running || paused) return;
    dir = nextDir;
    const head = { x: snake[0].x + dir.x, y: snake[0].y + dir.y };

    // Wall or self collision
    if (head.x < 0 || head.x >= COLS || head.y < 0 || head.y >= ROWS || snake.some(s => s.x===head.x && s.y===head.y)) {
      lives--;
      $('livesDisplay').textContent = lives;
      if (lives <= 0) { running = false; clearInterval(snakeInterval); endGame(); return; }
      // respawn
      snake = [{ x: Math.floor(COLS/2), y: Math.floor(ROWS/2) }];
      dir = nextDir = { x: 1, y: 0 };
      draw(); return;
    }

    snake.unshift(head);
    if (head.x === food.x && head.y === food.y) {
      score += 10; addScore(10);
      food = randFood();
    } else { snake.pop(); }
    draw();
  }

  function endGame() {
    hide('snakeArea');
    showOverlay({
      emoji: score >= 50 ? '🏆' : '🐍',
      title: score >= 50 ? 'Great Snake!' : 'Game Over!',
      sub: `You scored ${score} points`,
      coins: Math.max(5, Math.round(MAX_COINS * Math.min(score / 150, 1))),
      won: score >= 30
    });
  }

  return {
    start() {
      canvas = $('snakeCanvas');
      ctx = canvas.getContext('2d');
      snake = [{ x: 8, y: 8 }]; dir = nextDir = { x: 1, y: 0 };
      food = randFood(); score = 0; running = true; paused = false; lives = 3;
      currentScore = 0; _startTime = Date.now();

      $('livesChip').style.display = 'flex';
      $('livesDisplay').textContent = 3;
      show('snakeArea');
      snakeInterval = setInterval(tick, 120);
      startTimer(60, null, () => { clearInterval(snakeInterval); running = false; endGame(); });
      draw();

      // Keyboard
      window.onkeydown = e => {
        const map = { ArrowUp:[0,-1], ArrowDown:[0,1], ArrowLeft:[-1,0], ArrowRight:[1,0] };
        if (map[e.key]) { e.preventDefault(); SnakeGame.dir(...map[e.key]); }
        if (e.key === ' ') SnakeGame.pause();
      };
    },
    dir(dx, dy) {
      if (dx===1&&dir.x===-1||dx===-1&&dir.x===1) return;
      if (dy===1&&dir.y===-1||dy===-1&&dir.y===1) return;
      nextDir = { x: dx, y: dy };
    },
    pause() {
      paused = !paused;
      draw();
    },
    restart() {
      hide('gameOverlay'); currentScore = 0; setScore(0);
      $('livesChip').style.display = 'none';
      this.start();
    }
  };
})();

// ══════════════════════════════════════════════════════════════
//  WORD PUZZLE GAME
// ══════════════════════════════════════════════════════════════
const WordGame = (() => {
  const words = [
    { word:'REACT',    hint:'⚛️ Facebook JS framework' },
    { word:'LARAVEL',  hint:'🐘 PHP web framework' },
    { word:'PYTHON',   hint:'🐍 Popular language' },
    { word:'MYSQL',    hint:'🗄️ Relational database' },
    { word:'GITHUB',   hint:'🐙 Code hosting' },
    { word:'MOBILE',   hint:'📱 Handheld device' },
    { word:'SERVER',   hint:'💻 Remote computer' },
    { word:'COOKIE',   hint:'🍪 Browser storage' },
    { word:'BLADE',    hint:'🔪 Laravel template engine' },
    { word:'COINS',    hint:'🪙 TimePass currency' },
    { word:'ARCADE',   hint:'🕹️ Classic gaming style' },
    { word:'STREAK',   hint:'🔥 Consecutive days' },
  ];

  let shuffled, idx, score, typed, solved;

  function shuffle(arr) { return [...arr].sort(() => Math.random() - .5); }

  function makeLetters(word) {
    const extras = 'AEIOUTNRS'.split('').sort(() => Math.random()-.5).slice(0, 4);
    return shuffle([...word.split(''), ...extras]);
  }

  function loadWord() {
    const w = shuffled[idx % shuffled.length];
    $('wordHint').textContent = '💡 ' + w.hint;
    $('wordDisplay').textContent = '_ '.repeat(w.word.length).trim();
    $('wordInput').value = '';
    $('wordMsg').textContent = '';
    $('wordNum').textContent = (idx + 1);

    const letters = makeLetters(w.word);
    $('letterGrid').innerHTML = letters.map((l, i) =>
      `<div class="letter-tile" id="lt${i}" onclick="WordGame.tapLetter('${l}',${i})">${l}</div>`
    ).join('');
  }

  function checkAnswer(val) {
    const w = shuffled[idx % shuffled.length];
    if (val.toUpperCase() === w.word) {
      score += 15; addScore(15);
      $('wordMsg').innerHTML = '<span style="color:var(--green)">✅ Correct! +15 pts</span>';
      // flash tiles green
      document.querySelectorAll('.letter-tile').forEach(t => t.classList.add('correct-flash'));
      idx++; solved++;
      setTimeout(loadWord, 700);
    } else if (val.length === w.word.length) {
      $('wordMsg').innerHTML = '<span style="color:var(--p1)">❌ Try again!</span>';
      $('wordInput').value = '';
    }
  }

  return {
    start() {
      shuffled = shuffle(words); idx = 0; score = 0; solved = 0;
      currentScore = 0; _startTime = Date.now();
      show('wordArea'); loadWord();
      startTimer(90, null, () => {
        hide('wordArea');
        showOverlay({
          emoji: solved >= 5 ? '🔤' : '📝',
          title: solved >= 5 ? 'Word Master!' : 'Time\'s Up!',
          sub: `Solved ${solved} words · ${score} points`,
          coins: Math.max(5, Math.round(MAX_COINS * Math.min(solved / 8, 1))),
          won: solved >= 3
        });
      });
    },
    tapLetter(letter, i) {
      const inp = $('wordInput');
      inp.value = (inp.value + letter).toUpperCase();
      document.getElementById('lt' + i)?.classList.add('used');
      this.checkInput();
    },
    checkInput() { checkAnswer($('wordInput').value.toUpperCase()); },
    clearInput() {
      $('wordInput').value = '';
      document.querySelectorAll('.letter-tile').forEach(t => t.classList.remove('used','correct-flash'));
    },
    skipWord() { idx++; loadWord(); },
    restart() {
      hide('gameOverlay'); currentScore = 0; setScore(0);
      this.start();
    }
  };
})();

// ══════════════════════════════════════════════════════════════
//  QUIZ GAME
// ══════════════════════════════════════════════════════════════
const QuizGame = (() => {
  const bank = [
    { q:"What does HTML stand for?", opts:["HyperText Markup Language","High Tech Modern Language","Hyper Transfer Markup Lib","HyperText Modern Layout"], ans:0 },
    { q:"Who created Laravel framework?", opts:["Facebook","Google","Taylor Otwell","Rasmus Lerdorf"], ans:2 },
    { q:"Which planet is called the Red Planet?", opts:["Venus","Mars","Jupiter","Saturn"], ans:1 },
    { q:"What does CSS stand for?", opts:["Cascading Style Sheets","Creative Styling Spec","Computer Style System","Coded Style Sheets"], ans:0 },
    { q:"Who invented the World Wide Web?", opts:["Bill Gates","Tim Berners-Lee","Linus Torvalds","Dennis Ritchie"], ans:1 },
    { q:"In PHP, what symbol starts a variable?", opts:["#","@","$","&"], ans:2 },
    { q:"JSON stands for?", opts:["Java Syntax Object","JavaScript Object Notation","Java Simple Output Notation","JavaScript Simple Objects"], ans:1 },
    { q:"Which is NOT a Laravel artisan command?", opts:["make:model","make:controller","make:component","make:server"], ans:3 },
    { q:"What does API stand for?", opts:["Application Programming Interface","App Protocol Interface","Application Process Integration","Advanced Programming Index"], ans:0 },
    { q:"Git 'commit' does what?", opts:["Pushes to remote","Saves a snapshot","Merges branches","Deletes history"], ans:1 },
    { q:"Which company made MySQL?", opts:["Oracle","Microsoft","IBM","Google"], ans:0 },
    { q:"What is the capital of India?", opts:["Mumbai","Kolkata","New Delhi","Bengaluru"], ans:2 },
  ];

  let qs, idx, score, answered;

  function shuffle(arr) { return [...arr].sort(() => Math.random() - .5); }

  function loadQ() {
    if (idx >= qs.length) { endGame(); return; }
    const q = qs[idx]; answered = false;
    const pct = (idx / qs.length * 100).toFixed(0);
    $('quizBar').style.width = pct + '%';
    $('quizStatus').textContent = `Question ${idx+1} of ${qs.length}`;
    $('quizQ').textContent = q.q;
    $('quizFeedback').textContent = '';

    $('quizOpts').innerHTML = q.opts.map((o, i) =>
      `<button class="quiz-opt" id="qo${i}" onclick="QuizGame.answer(${i})">${o}</button>`
    ).join('');
  }

  function endGame() {
    const won = score >= qs.length * 0.6 * 10;
    hide('quizArea');
    showOverlay({
      emoji: score >= 60 ? '🧠' : '📚',
      title: score >= 60 ? 'Quiz Champion!' : 'Good Try!',
      sub: `${score / 10} / ${qs.length} correct`,
      coins: Math.max(5, Math.round(MAX_COINS * (score / (qs.length * 10)))),
      won
    });
  }

  return {
    start() {
      qs = shuffle(bank).slice(0, 8); idx = 0; score = 0;
      currentScore = 0; _startTime = Date.now();
      show('quizArea'); show('roundChip');
      loadQ();
      startTimer(120, null, endGame);
    },
    answer(i) {
      if (answered) return;
      answered = true;
      const q = qs[idx];
      document.querySelectorAll('.quiz-opt').forEach(b => b.disabled = true);
      document.getElementById('qo' + q.ans).classList.add('correct');
      if (i !== q.ans) {
        document.getElementById('qo' + i).classList.add('wrong');
        $('quizFeedback').innerHTML = '<span style="color:var(--p1)">❌ Wrong!</span>';
      } else {
        score += 10; addScore(10);
        $('quizFeedback').innerHTML = '<span style="color:var(--green)">✅ Correct! +10 pts</span>';
      }
      $('roundDisplay').textContent = idx + 2;
      idx++;
      setTimeout(loadQ, 1300);
    },
    restart() {
      hide('gameOverlay'); hide('roundChip');
      currentScore = 0; setScore(0);
      this.start();
    }
  };
})();

// ══════════════════════════════════════════════════════════════
//  MEMORY CARDS GAME
// ══════════════════════════════════════════════════════════════
const MemoryGame = (() => {
  const emojis = ['🎮','🏆','🔥','💎','⚡','🌟','🎯','🎪'];
  let cards, flipped, matched, moves, lock, startMs;

  function build() {
    cards = [...emojis, ...emojis].sort(() => Math.random() - .5);
    flipped = []; matched = []; moves = 0; lock = false;
    $('memMoves').textContent = 0;
    $('memPairs').textContent = 0;

    $('memGrid').innerHTML = cards.map((e, i) =>
      `<div class="mem-card" id="mc${i}" onclick="MemoryGame.flip(${i})">
         <div class="back"></div>
         <div class="front">${e}</div>
       </div>`
    ).join('');
  }

  return {
    start() {
      currentScore = 0; _startTime = Date.now(); startMs = Date.now();
      show('memoryArea'); build();
      startTimer(120, null, () => {
        hide('memoryArea');
        const won = matched.length === 8;
        showOverlay({
          emoji: won ? '🃏' : '⏰',
          title: won ? 'Memory Master!' : 'Time\'s Up!',
          sub: `${matched.length}/8 pairs · ${moves} moves`,
          coins: Math.max(5, Math.round(MAX_COINS * (matched.length / 8))),
          won
        });
      });
    },
    flip(i) {
      if (lock || flipped.includes(i) || matched.includes(i)) return;
      document.getElementById('mc' + i).classList.add('flipped');
      flipped.push(i);

      if (flipped.length === 2) {
        lock = true; moves++;
        $('memMoves').textContent = moves;
        const [a, b] = flipped;
        if (cards[a] === cards[b]) {
          matched.push(a, b);
          $('memPairs').textContent = matched.length / 2;
          document.getElementById('mc' + a).classList.add('matched');
          document.getElementById('mc' + b).classList.add('matched');
          const pts = Math.max(5, 20 - moves);
          addScore(pts);
          flipped = []; lock = false;
          if (matched.length === 16) {
            stopTimer();
            hide('memoryArea');
            showOverlay({
              emoji: '🏆', title: 'All Matched!',
              sub: `${moves} moves · ${Math.round((Date.now()-startMs)/1000)}s`,
              coins: MAX_COINS, won: true
            });
          }
        } else {
          setTimeout(() => {
            document.getElementById('mc' + a)?.classList.remove('flipped');
            document.getElementById('mc' + b)?.classList.remove('flipped');
            flipped = []; lock = false;
          }, 900);
        }
      }
    },
    restart() {
      hide('gameOverlay'); currentScore = 0; setScore(0);
      this.start();
    }
  };
})();

// ══════════════════════════════════════════════════════════════
//  SPIN & WIN GAME
// ══════════════════════════════════════════════════════════════
const SpinGame = (() => {
  const prizes = [
    { label:'10 🪙',  coins:10,  color:'#FF6B6B' },
    { label:'20 🪙',  coins:20,  color:'#FF8E53' },
    { label:'50 🪙',  coins:50,  color:'#F97316' },
    { label:'5 🪙',   coins:5,   color:'#FFE66D' },
    { label:'MAX! 🪙',coins:MAX_COINS, color:'#10B981' },
    { label:'15 🪙',  coins:15,  color:'#4ECDC4' },
    { label:'30 🪙',  coins:30,  color:'#3B82F6' },
    { label:'Try Again',coins:0, color:'#E5E7EB' },
  ];

  let canvas, ctx, angle = 0, spinning = false, spinsLeft = 2, bestCoins = 0;

  function draw(a) {
    const cx = 130, cy = 130, r = 118;
    const seg = Math.PI * 2 / prizes.length;
    ctx.clearRect(0, 0, 260, 260);

    prizes.forEach((p, i) => {
      const s = a + i * seg, e = s + seg;
      ctx.beginPath(); ctx.moveTo(cx, cy); ctx.arc(cx, cy, r, s, e); ctx.closePath();
      ctx.fillStyle = p.color; ctx.fill();
      ctx.strokeStyle = 'white'; ctx.lineWidth = 2; ctx.stroke();

      ctx.save(); ctx.translate(cx, cy); ctx.rotate(s + seg/2);
      ctx.fillStyle = p.coins === 0 ? '#6B7280' : 'white';
      ctx.font = 'bold 11px Nunito'; ctx.textAlign = 'right';
      ctx.fillText(p.label, r - 8, 4); ctx.restore();
    });

    // centre
    const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, 22);
    g.addColorStop(0, '#FF6B6B'); g.addColorStop(1, '#F97316');
    ctx.beginPath(); ctx.arc(cx, cy, 22, 0, Math.PI*2);
    ctx.fillStyle = g; ctx.fill();
    ctx.strokeStyle = 'white'; ctx.lineWidth = 3; ctx.stroke();
    ctx.fillStyle = 'white'; ctx.font = 'bold 16px sans-serif';
    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
    ctx.fillText('🎯', cx, cy); ctx.textBaseline = 'alphabetic';

    // pointer
    ctx.beginPath(); ctx.moveTo(cx, cy-r-6); ctx.lineTo(cx-9, cy-r+10); ctx.lineTo(cx+9, cy-r+10);
    ctx.closePath(); ctx.fillStyle = '#1A1A2E'; ctx.fill();
  }

  return {
    start() {
      canvas = $('spinCanvas'); ctx = canvas.getContext('2d');
      spinsLeft = 2; bestCoins = 0; currentScore = 0; _startTime = Date.now();
      show('spinArea'); angle = 0; draw(0);
      hide('timerRing');
    },
    spin() {
      if (spinning || spinsLeft <= 0) return;
      spinning = true; $('spinBtn').disabled = true;
      const prize = Math.floor(Math.random() * prizes.length);
      const fullSpins = 5 + Math.floor(Math.random() * 3);
      const target = fullSpins * Math.PI*2 + prize * (Math.PI*2 / prizes.length);
      const dur = 3500, t0 = performance.now(), initA = angle;

      function step(now) {
        const elapsed = now - t0, t = Math.min(elapsed / dur, 1);
        const ease = 1 - Math.pow(1 - t, 4);
        angle = initA + target * ease;
        draw(angle);
        if (t < 1) { requestAnimationFrame(step); return; }

        spinning = false; spinsLeft--;
        $('spinsLeft').textContent = spinsLeft;
        const p = prizes[prize];
        $('spinResultLabel').textContent = p.coins > 0 ? `🎉 ${p.label} — Nice!` : '😅 Better luck next spin!';
        if (p.coins > bestCoins) bestCoins = p.coins;
        currentScore += p.coins;
        setScore(currentScore);

        if (spinsLeft <= 0) {
          setTimeout(() => {
            hide('spinArea');
            showOverlay({
              emoji: bestCoins >= 30 ? '🎯' : '🎰',
              title: bestCoins >= 30 ? 'Lucky Spinner!' : 'Spins Used!',
              sub: `Best prize: ${bestCoins} coins`,
              coins: bestCoins,
              won: bestCoins >= 20
            });
          }, 1000);
        } else {
          $('spinBtn').disabled = false;
          $('spinResultLabel').textContent += ' · Spin again!';
        }
      }
      requestAnimationFrame(step);
    },
    restart() {
      hide('gameOverlay'); currentScore = 0; setScore(0);
      this.start();
    }
  };
})();

// ══════════════════════════════════════════════════════════════
//  DISPATCHER  –  map slug → game object
// ══════════════════════════════════════════════════════════════
const gameMap = {
  'snake-turbo':   SnakeGame,
  'word-puzzle':   WordGame,
  'brain-quiz':    QuizGame,
  'memory-cards':  MemoryGame,
  'spin-win':      SpinGame,
};

const GAME = gameMap[SLUG] ?? {
  start()   { alert('Game coming soon!'); },
  restart() { location.reload(); }
};

// Expose restart on overlay button
document.getElementById('olBtn').onclick = () => GAME.restart();

// Swipe support for snake
let touchX, touchY;
document.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; touchY = e.touches[0].clientY; }, { passive:true });
document.addEventListener('touchend', e => {
  if (SLUG !== 'snake-turbo') return;
  const dx = e.changedTouches[0].clientX - touchX;
  const dy = e.changedTouches[0].clientY - touchY;
  if (Math.abs(dx) > Math.abs(dy)) SnakeGame.dir(dx > 0 ? 1 : -1, 0);
  else SnakeGame.dir(0, dy > 0 ? 1 : -1);
}, { passive:true });
</script>
@endpush
