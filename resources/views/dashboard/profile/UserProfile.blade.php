{{-- resources/views/dashboard/users/profile.blade.php --}}
{{-- Route: GET /users/{username}  →  name('users.profile') --}}
@extends('dashboard.layouts.app')
@section('title', '@' . $user->username)

@push('styles')
<style>
:root {
  --p1:#FF6B6B; --p2:#FF8E53; --orange:#F97316;
  --green:#10B981; --teal:#4ECDC4;
  --bg:#FFF8F0; --border:#E5E7EB; --text:#1A1A2E; --muted:#6B7280;
}

/* ─── Hero ─── */
.up-hero {
  background: linear-gradient(145deg,#FF6B6B,#FF8E53,#F97316);
  padding: 24px 20px 0; color: white; position: relative; overflow: hidden;
}
.up-hero::before {
  content:''; position:absolute; inset:0;
  background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.06'%3E%3Ccircle cx='20' cy='20' r='3'/%3E%3C/g%3E%3C/svg%3E");
}

.up-top { display:flex; align-items:flex-start; gap:16px; position:relative; }

/* Avatar */
.up-avatar {
  width:84px; height:84px; border-radius:50%; flex-shrink:0;
  border:3px solid rgba(255,255,255,0.85);
  background: linear-gradient(135deg,#FF6B6B,#FF8E53);
  display:flex; align-items:center; justify-content:center;
  font-size:2rem; font-weight:900; color:white; overflow:hidden;
}
.up-avatar img { width:100%; height:100%; object-fit:cover; }

/* Meta */
.up-meta { flex:1; padding-top:4px; }
.up-name { font-family:'Fredoka One',cursive; font-size:1.4rem; margin-bottom:2px; }
.up-handle { font-size:0.82rem; opacity:.85; font-weight:700; }
.up-bio { font-size:0.8rem; opacity:.85; font-weight:600; line-height:1.45; margin-top:6px; }
.up-website { font-size:0.75rem; font-weight:700; color:white; text-decoration:underline; margin-top:4px; display:inline-block; }
.up-level { display:inline-block; background:rgba(255,255,255,.2); border-radius:20px; padding:3px 12px; font-size:0.74rem; font-weight:900; margin-top:8px; }

/* Follow actions */
.up-actions {
  display:flex; gap:8px; margin-top:14px; position:relative;
}
.btn-follow {
  flex:1; padding:11px 0; border-radius:12px; border:none; cursor:pointer;
  font-family:'Nunito',sans-serif; font-size:0.9rem; font-weight:900;
  transition:all .25s; display:flex; align-items:center; justify-content:center; gap:6px;
}
.btn-follow.follow-state {
  background: white; color: var(--p1);
}
.btn-follow.following-state {
  background: rgba(255,255,255,0.2); color: white;
  border: 1.5px solid rgba(255,255,255,0.6);
}
.btn-message {
  width:44px; height:44px; border-radius:12px; border:1.5px solid rgba(255,255,255,0.6);
  background:rgba(255,255,255,0.15); color:white; font-size:1.2rem;
  cursor:pointer; display:flex; align-items:center; justify-content:center;
  transition:all .25s; flex-shrink:0;
}
.btn-message:hover { background:rgba(255,255,255,0.3); }

/* Stats */
.up-stats {
  display:flex; margin-top:16px; border-top:1px solid rgba(255,255,255,.2);
}
.up-stat { flex:1; text-align:center; padding:12px 8px; cursor:pointer; transition:background .2s; }
.up-stat:hover { background:rgba(255,255,255,.08); }
.up-stat strong { display:block; font-size:1.05rem; font-weight:900; }
.up-stat span   { font-size:0.66rem; opacity:.82; font-weight:700; }

/* ─── Reels grid ─── */
.up-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:2px; margin-top:0; }

.up-thumb {
  aspect-ratio:9/16; position:relative; overflow:hidden; cursor:pointer; background:#111;
  display:block;
}
.up-thumb img,
.up-thumb video  { width:100%; height:100%; object-fit:cover; display:block; }
.up-thumb-overlay {
  position:absolute; inset:0; background:rgba(0,0,0,0);
  display:flex; align-items:center; justify-content:center;
  transition:background .2s;
}
.up-thumb:hover .up-thumb-overlay { background:rgba(0,0,0,0.32); }
.up-thumb-play {
  width:34px; height:34px; border-radius:50%; background:rgba(255,255,255,.9);
  display:flex; align-items:center; justify-content:center; font-size:0.85rem;
  opacity:0; transition:opacity .2s;
}
.up-thumb:hover .up-thumb-play { opacity:1; }
.up-thumb-type {
  position:absolute; top:5px; left:5px;
  background:rgba(0,0,0,.5); color:white; border-radius:5px;
  padding:2px 5px; font-size:0.58rem; font-weight:800;
}
.up-thumb-stat {
  position:absolute; bottom:5px; left:5px;
  color:white; font-size:0.65rem; font-weight:800;
  text-shadow:0 1px 3px rgba(0,0,0,.7);
}

/* ─── Private / empty states ─── */
.up-locked {
  text-align:center; padding:56px 24px;
  display:flex; flex-direction:column; align-items:center; gap:10px;
}
.up-locked-icon { font-size:3.5rem; }
.up-locked h3 { font-family:'Fredoka One',cursive; font-size:1.15rem; color:var(--text); margin:0; }
.up-locked p  { font-size:0.85rem; color:var(--muted); font-weight:600; margin:0; }

/* ─── Followers sheet ─── */
.followers-sheet {
  position:fixed; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(4px);
  z-index:900; display:none; align-items:flex-end; justify-content:center;
}
.followers-sheet.open { display:flex; }
.followers-inner {
  background:white; border-radius:24px 24px 0 0; width:100%; max-width:500px;
  height:70vh; display:flex; flex-direction:column; animation:slideUp .3s ease;
}
@keyframes slideUp { from{transform:translateY(100%);}to{transform:translateY(0);} }
.followers-header {
  padding:16px 20px 12px; border-bottom:1.5px solid var(--border);
  display:flex; align-items:center; justify-content:space-between; flex-shrink:0;
}
.followers-header h4 { font-family:'Fredoka One',cursive; font-size:1.1rem; margin:0; }
.followers-list { flex:1; overflow-y:auto; padding:8px 0; }
.fl-item {
  display:flex; align-items:center; gap:12px; padding:11px 16px;
  cursor:pointer; transition:background .2s;
}
.fl-item:hover { background:#FFF8F0; }
.fl-av {
  width:44px; height:44px; border-radius:50%; flex-shrink:0;
  background:linear-gradient(135deg,#FF6B6B,#FF8E53);
  display:flex; align-items:center; justify-content:center;
  font-weight:900; color:white; font-size:1rem; overflow:hidden;
}
.fl-av img { width:100%; height:100%; object-fit:cover; }
.fl-info { flex:1; }
.fl-name { font-size:0.88rem; font-weight:900; color:var(--text); }
.fl-handle { font-size:0.74rem; color:var(--muted); font-weight:600; }
.fl-follow-btn {
  background:linear-gradient(135deg,var(--p1),var(--p2)); color:white;
  border:none; border-radius:20px; padding:5px 14px;
  font-family:'Nunito',sans-serif; font-size:0.74rem; font-weight:900;
  cursor:pointer; transition:all .25s; white-space:nowrap;
}
.fl-follow-btn.fl-following {
  background:rgba(0,0,0,0.06); color:var(--muted);
  border:1px solid var(--border);
}

/* ─── Reel viewer overlay ─── */
.reel-viewer {
  position:fixed; inset:0; background:#000; z-index:800;
  display:none; flex-direction:column; align-items:center; justify-content:center;
}
.reel-viewer.open { display:flex; }
.rv-close {
  position:absolute; top:16px; right:16px; z-index:10;
  background:rgba(0,0,0,.5); border:none; color:white; width:36px; height:36px;
  border-radius:50%; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;
}
.rv-media { max-width:100%; max-height:100dvh; object-fit:contain; }
</style>
@endpush

@section('content')
<div style="padding-bottom: var(--bottom-h, 60px);">

{{-- ─── Hero ─── --}}
<div class="up-hero">
  <div class="up-top">

    {{-- Avatar --}}
    <div class="up-avatar">
      @if($user->avatar)
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
      @else
        {{ strtoupper(substr($user->name, 0, 1)) }}
      @endif
    </div>

    {{-- Meta --}}
    <div class="up-meta">
      <div class="up-name">{{ $user->name }}</div>
      <div class="up-handle">@{{ $user->username }}</div>
      @if($user->bio)
        <div class="up-bio">{{ $user->bio }}</div>
      @endif
      @if($user->website)
        <a href="{{ $user->website }}" target="_blank" class="up-website">🔗 {{ $user->website }}</a>
      @endif
      <div class="up-level">⚡ Lv {{ $user->level }}</div>
    </div>
  </div>

  {{-- ── Follow / Unfollow actions ── --}}
  <div class="up-actions">
    <button class="btn-follow {{ $isFollowing ? 'following-state' : 'follow-state' }}"
            id="mainFollowBtn"
            onclick="toggleFollow()">
      @if($isFollowing)
        ✅ Following
      @else
        ➕ Follow
      @endif
    </button>
    <button class="btn-message" title="Message" onclick="showToast('💬 Message coming soon!')">💬</button>
  </div>

  {{-- Stats --}}
  <div class="up-stats">
    <div class="up-stat">
      <strong>{{ $user->reels_count }}</strong>
      <span>Reels</span>
    </div>
    <div class="up-stat" onclick="openFollowersSheet('followers')">
      <strong>{{ number_format($user->followers_count) }}</strong>
      <span>Followers</span>
    </div>
    <div class="up-stat" onclick="openFollowersSheet('following')">
      <strong>{{ number_format($user->following_count) }}</strong>
      <span>Following</span>
    </div>
  </div>
</div>{{-- end hero --}}

{{-- ─── Reels Grid ─── --}}
@if($user->is_private && !$isFollowing)
  {{-- Private account --}}
  <div class="up-locked">
    <div class="up-locked-icon">🔒</div>
    <h3>This Account is Private</h3>
    <p>Follow {{ $user->name }} to see their reels.</p>
  </div>

@elseif($reels->count())
  <div class="up-grid">
    @foreach($reels as $reel)
    <div class="up-thumb" onclick="openReelViewer('{{ $reel->file_url }}','{{ $reel->type }}')">
      @if($reel->type === 'video')
        @if($reel->thumbnail_path)
          <img src="{{ $reel->thumbnail_url }}" alt="reel" loading="lazy">
        @else
          <video src="{{ $reel->file_url }}" preload="none" muted playsinline></video>
        @endif
        <span class="up-thumb-type">▶</span>
      @else
        <img src="{{ $reel->file_url }}" alt="reel" loading="lazy">
      @endif
      <div class="up-thumb-overlay">
        <div class="up-thumb-play">▶️</div>
      </div>
      <div class="up-thumb-stat">❤️ {{ number_format($reel->likes_count) }}</div>
    </div>
    @endforeach
  </div>
  @if($reels->hasPages())
    <div style="padding:12px 16px;">{{ $reels->links() }}</div>
  @endif

@else
  <div class="up-locked">
    <div class="up-locked-icon">🎬</div>
    <h3>No Reels Yet</h3>
    <p>{{ $user->name }} hasn't posted any reels.</p>
  </div>
@endif

</div>{{-- end padding-bottom wrapper --}}

{{-- ─── Followers / Following Sheet ─── --}}
<div class="followers-sheet" id="followersSheet">
  <div class="followers-inner">
    <div class="followers-header">
      <h4 id="followersSheetTitle">Followers</h4>
      <button onclick="closeFollowersSheet()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--muted);">✕</button>
    </div>
    <div class="followers-list" id="followersList">
      <p style="text-align:center;color:var(--muted);font-weight:700;padding:24px;">Loading…</p>
    </div>
  </div>
</div>

{{-- ─── Reel Viewer ─── --}}
<div class="reel-viewer" id="reelViewer">
  <button class="rv-close" onclick="closeReelViewer()">✕</button>
  <video id="rvVideo" class="rv-media" controls loop playsinline style="display:none;"></video>
  <img   id="rvImage" class="rv-media" style="display:none;">
</div>

<script>
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const USER_ID = {{ $user->id }};
let isFollowing = {{ $isFollowing ? 'true' : 'false' }};

// ══ Follow / Unfollow ══════════════════════════════════════
function toggleFollow() {
  fetch(`/users/${USER_ID}/follow`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
  })
  .then(r => r.json())
  .then(data => {
    isFollowing = data.following;
    const btn = document.getElementById('mainFollowBtn');
    if (data.following) {
      btn.textContent = '✅ Following';
      btn.className   = 'btn-follow following-state';
      showToast('✅ Following ' + '{{ $user->name }}');
    } else {
      btn.textContent = '➕ Follow';
      btn.className   = 'btn-follow follow-state';
      showToast('Unfollowed {{ $user->name }}');
    }
  })
  .catch(() => showToast('Something went wrong'));
}

// ══ Followers / Following Sheet ════════════════════════════
let sheetType = 'followers';

function openFollowersSheet(type) {
  sheetType = type;
  document.getElementById('followersSheetTitle').textContent =
    type === 'followers' ? 'Followers' : 'Following';
  document.getElementById('followersSheet').classList.add('open');
  loadFollowersList(type);
}
function closeFollowersSheet() {
  document.getElementById('followersSheet').classList.remove('open');
}

function loadFollowersList(type) {
  const list = document.getElementById('followersList');
  list.innerHTML = '<p style="text-align:center;color:var(--muted);font-weight:700;padding:24px;">Loading…</p>';

  fetch(`/users/${USER_ID}/${type}`, {
    headers: { 'Accept': 'application/json' }
  })
  .then(r => r.json())
  .then(data => {
    const items = data.data ?? data;
    if (!items.length) {
      list.innerHTML = `<p style="text-align:center;color:var(--muted);font-weight:700;padding:24px;">No ${type} yet.</p>`;
      return;
    }
    list.innerHTML = items.map(u => `
      <div class="fl-item" onclick="window.location='/users/${u.username ?? u.id}'">
        <div class="fl-av">
          ${u.avatar_url ? `<img src="${u.avatar_url}" alt="">` : u.name[0].toUpperCase()}
        </div>
        <div class="fl-info">
          <div class="fl-name">${escHtml(u.name)}</div>
          <div class="fl-handle">@${escHtml(u.username ?? '')}</div>
        </div>
        ${u.is_self ? '' : `
        <button class="fl-follow-btn ${u.is_following ? 'fl-following' : ''}"
                id="flBtn${u.id}"
                onclick="event.stopPropagation();sheetFollow(this,${u.id})">
          ${u.is_following ? 'Following' : 'Follow'}
        </button>`}
      </div>
    `).join('');
  });
}

function sheetFollow(btn, userId) {
  fetch(`/users/${userId}/follow`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
  })
  .then(r => r.json())
  .then(data => {
    if (data.following) {
      btn.textContent = 'Following'; btn.classList.add('fl-following');
    } else {
      btn.textContent = 'Follow'; btn.classList.remove('fl-following');
    }
  });
}

// Close sheet on backdrop
document.getElementById('followersSheet').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeFollowersSheet();
});

// ══ Reel Viewer ═══════════════════════════════════════════
function openReelViewer(url, type) {
  const viewer = document.getElementById('reelViewer');
  const vid    = document.getElementById('rvVideo');
  const img    = document.getElementById('rvImage');
  viewer.classList.add('open');
  if (type === 'video') {
    vid.src = url; vid.style.display = 'block';
    img.style.display = 'none';
    vid.play().catch(() => {});
  } else {
    img.src = url; img.style.display = 'block';
    vid.style.display = 'none';
  }
}
function closeReelViewer() {
  document.getElementById('reelViewer').classList.remove('open');
  const vid = document.getElementById('rvVideo');
  vid.pause(); vid.src = '';
  document.getElementById('rvImage').src = '';
}

// ══ Toast ══════════════════════════════════════════════════
function showToast(msg) {
  const existing = document.getElementById('upToast');
  if (existing) existing.remove();
  const t = document.createElement('div');
  t.id = 'upToast';
  t.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%) translateY(-20px);background:linear-gradient(135deg,#10B981,#4ECDC4);color:white;padding:11px 22px;border-radius:30px;font-weight:900;font-size:0.85rem;z-index:9999;opacity:0;transition:all .4s;pointer-events:none;white-space:nowrap;box-shadow:0 8px 24px rgba(0,0,0,.2);';
  t.textContent = msg;
  document.body.appendChild(t);
  requestAnimationFrame(() => { t.style.opacity='1'; t.style.transform='translateX(-50%) translateY(0)'; });
  setTimeout(() => { t.style.opacity='0'; t.style.transform='translateX(-50%) translateY(-20px)'; setTimeout(()=>t.remove(),400); }, 2800);
}

function escHtml(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>
@endsection