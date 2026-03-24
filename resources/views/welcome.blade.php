<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TimePass – Play. Connect. Earn.</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root {
  --p1: #FF6B6B;
  --p2: #FF8E53;
  --teal: #4ECDC4;
  --yellow: #FFE66D;
  --purple: #A855F7;
  --blue: #3B82F6;
  --green: #10B981;
  --pink: #EC4899;
  --bg: #FFF8F0;
  --white: #ffffff;
  --text: #1A1A2E;
  --muted: #6B7280;
  --border: #E5E7EB;
  --r: 22px;
  --nav-h: 68px;
  --bottom-h: 70px;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html { scroll-behavior: smooth; }

body {
  font-family: 'Nunito', sans-serif;
  background: var(--bg);
  color: var(--text);
  overflow-x: hidden;
}

/* ====== CANVAS ====== */
#bg-canvas {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
}

/* ====== TOP NAV ====== */
.top-nav {
  position: sticky;
  top: 0;
  z-index: 500;
  height: var(--nav-h);
  background: rgba(255,255,255,0.88);
  backdrop-filter: blur(24px);
  border-bottom: 2.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  box-shadow: 0 3px 24px rgba(0,0,0,0.07);
}

.brand {
  font-family: 'Fredoka One', cursive;
  font-size: 1.9rem;
  background: linear-gradient(135deg, var(--p1), var(--purple), var(--blue));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-decoration: none;
  letter-spacing: 1px;
}

.nav-links { display: flex; align-items: center; gap: 6px; }
.nav-links a {
  padding: 7px 16px;
  border-radius: 30px;
  font-weight: 700;
  font-size: 0.88rem;
  text-decoration: none;
  color: var(--muted);
  transition: all 0.25s;
}
.nav-links a:hover { background: var(--bg); color: var(--p1); }
.nav-links .btn-join {
  background: linear-gradient(135deg, var(--p1), var(--purple));
  color: white;
  padding: 8px 22px;
  border-radius: 30px;
}
.nav-links .btn-join:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,107,0.4); color: white; }
.nav-links .btn-login { color: var(--text); }

.points-pill {
  background: linear-gradient(135deg, var(--yellow), var(--p2));
  color: var(--text);
  padding: 5px 14px;
  border-radius: 30px;
  font-size: 0.8rem;
  font-weight: 900;
  display: flex;
  align-items: center;
  gap: 5px;
  box-shadow: 0 3px 12px rgba(255,180,0,0.35);
}

/* ====== BOTTOM NAV ====== */
.bottom-nav {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  height: var(--bottom-h);
  background: rgba(255,255,255,0.96);
  backdrop-filter: blur(20px);
  border-top: 2.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-around;
  z-index: 500;
  box-shadow: 0 -4px 24px rgba(0,0,0,0.08);
}

.bottom-nav a {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
  text-decoration: none;
  color: var(--muted);
  font-size: 0.63rem;
  font-weight: 800;
  transition: all 0.25s;
  padding: 4px 14px;
}

.bottom-nav a .ico {
  width: 44px; height: 44px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  transition: all 0.3s;
}

.bottom-nav a.active .ico,
.bottom-nav a:hover .ico {
  background: linear-gradient(135deg, var(--p1), var(--purple));
  color: white;
  transform: translateY(-5px) scale(1.1);
  box-shadow: 0 8px 22px rgba(255,107,107,0.38);
}

.bottom-nav a.active { color: var(--p1); }
.dot-badge {
  position: absolute;
  top: 0; right: 8px;
  width: 9px; height: 9px;
  background: var(--p1);
  border: 2px solid white;
  border-radius: 50%;
}

/* ====== MAIN ====== */
main {
  position: relative;
  z-index: 1;
  padding-bottom: calc(var(--bottom-h) + 24px);
}

/* ====== HERO ====== */
.hero {
  min-height: calc(100vh - var(--nav-h));
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 60px 24px 40px;
  position: relative;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, rgba(255,107,107,0.12), rgba(168,85,247,0.12));
  border: 2px solid rgba(168,85,247,0.25);
  border-radius: 30px;
  padding: 8px 20px;
  font-size: 0.82rem;
  font-weight: 800;
  color: var(--purple);
  margin-bottom: 28px;
  animation: floatY 3s ease-in-out infinite;
}

.hero h1 {
  font-family: 'Fredoka One', cursive;
  font-size: clamp(2.8rem, 7vw, 5.5rem);
  line-height: 1.1;
  margin-bottom: 20px;
  background: linear-gradient(135deg, var(--p1) 0%, var(--purple) 50%, var(--blue) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero h1 span {
  display: block;
  background: linear-gradient(135deg, var(--teal), var(--green));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero p {
  font-size: 1.15rem;
  color: var(--muted);
  max-width: 560px;
  margin: 0 auto 36px;
  line-height: 1.7;
  font-weight: 600;
}

.hero-btns {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 56px;
}

.btn-hero-primary {
  background: linear-gradient(135deg, var(--p1), var(--purple));
  color: white;
  border: none;
  border-radius: 16px;
  padding: 16px 36px;
  font-size: 1rem;
  font-weight: 900;
  font-family: 'Nunito', sans-serif;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 8px 30px rgba(255,107,107,0.35);
}

.btn-hero-primary:hover { transform: translateY(-3px) scale(1.03); box-shadow: 0 14px 40px rgba(255,107,107,0.45); color: white; }

.btn-hero-secondary {
  background: white;
  color: var(--text);
  border: 2.5px solid var(--border);
  border-radius: 16px;
  padding: 14px 36px;
  font-size: 1rem;
  font-weight: 900;
  font-family: 'Nunito', sans-serif;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-hero-secondary:hover { border-color: var(--teal); color: var(--teal); transform: translateY(-2px); }

/* Floating cards around hero */
.hero-stats {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  justify-content: center;
}

.stat-pill {
  background: white;
  border-radius: 20px;
  padding: 14px 22px;
  display: flex;
  align-items: center;
  gap: 10px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.09);
  animation: floatY 3s ease-in-out infinite;
}

.stat-pill:nth-child(2) { animation-delay: 0.5s; }
.stat-pill:nth-child(3) { animation-delay: 1s; }

.stat-icon {
  width: 42px; height: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
}

.stat-info strong { display: block; font-size: 1.15rem; font-weight: 900; }
.stat-info span { font-size: 0.75rem; color: var(--muted); font-weight: 700; }

/* ====== SECTION COMMON ====== */
section { padding: 72px 24px; position: relative; }

.section-label {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(168,85,247,0.1);
  border: 1.5px solid rgba(168,85,247,0.3);
  border-radius: 30px;
  padding: 5px 16px;
  font-size: 0.78rem;
  font-weight: 800;
  color: var(--purple);
  margin-bottom: 16px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.section-title {
  font-family: 'Fredoka One', cursive;
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  line-height: 1.15;
  margin-bottom: 12px;
}

.section-sub {
  font-size: 1rem;
  color: var(--muted);
  font-weight: 600;
  max-width: 520px;
  line-height: 1.65;
}

.container { max-width: 1100px; margin: 0 auto; }

/* ====== FEATURES ====== */
.features-section { background: white; }

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
  margin-top: 48px;
}

.feat-card {
  background: var(--bg);
  border-radius: var(--r);
  padding: 28px 24px;
  transition: all 0.3s;
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
  cursor: pointer;
}

.feat-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: var(--r);
  background: linear-gradient(135deg, var(--c1), var(--c2));
  opacity: 0;
  transition: opacity 0.3s;
}

.feat-card:hover::before { opacity: 1; }
.feat-card:hover { transform: translateY(-6px); box-shadow: 0 16px 48px rgba(0,0,0,0.12); }
.feat-card:hover .feat-text { color: white; }
.feat-card:hover .feat-sub { color: rgba(255,255,255,0.8); }
.feat-card:hover .feat-icon { background: rgba(255,255,255,0.25); }

.feat-icon {
  width: 58px; height: 58px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.7rem;
  background: white;
  margin-bottom: 18px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
  position: relative;
  transition: all 0.3s;
}

.feat-text {
  font-size: 1.05rem;
  font-weight: 900;
  margin-bottom: 6px;
  position: relative;
  transition: color 0.3s;
}

.feat-sub {
  font-size: 0.85rem;
  color: var(--muted);
  font-weight: 600;
  line-height: 1.5;
  position: relative;
  transition: color 0.3s;
}

/* ====== GAMES SECTION ====== */
.games-section { background: linear-gradient(160deg, #FFF0F8 0%, #F0F4FF 100%); }

.games-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 18px;
  margin-top: 48px;
}

.game-card {
  background: white;
  border-radius: var(--r);
  overflow: hidden;
  transition: all 0.3s;
  box-shadow: 0 6px 24px rgba(0,0,0,0.07);
  cursor: pointer;
}

.game-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 20px 56px rgba(0,0,0,0.14); }

.game-thumb {
  height: 130px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3.8rem;
  position: relative;
  overflow: hidden;
}

.game-thumb::after {
  content: '';
  position: absolute;
  inset: 0;
  background: inherit;
  opacity: 0.35;
}

.game-info { padding: 16px; }
.game-info h4 { font-weight: 900; font-size: 0.95rem; margin-bottom: 4px; }
.game-info p { font-size: 0.78rem; color: var(--muted); font-weight: 600; }

.game-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 10px;
}

.live-badge {
  background: var(--green);
  color: white;
  font-size: 0.62rem;
  font-weight: 900;
  padding: 3px 10px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 4px;
}

.live-dot { width: 6px; height: 6px; background: white; border-radius: 50%; animation: pulse 1.2s ease-in-out infinite; }

.coin-reward {
  font-size: 0.8rem;
  font-weight: 800;
  color: var(--p2);
  display: flex;
  align-items: center;
  gap: 3px;
}

/* ====== SOCIAL / FEED ====== */
.social-section { background: white; }

.social-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  margin-top: 48px;
  align-items: start;
}

@media (max-width: 768px) { .social-layout { grid-template-columns: 1fr; } }

.feed-preview {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.post-card {
  background: var(--bg);
  border-radius: 18px;
  padding: 18px;
  transition: all 0.3s;
  border: 2px solid transparent;
}

.post-card:hover { border-color: var(--teal); transform: translateX(4px); }

.post-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}

.post-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  font-weight: 900;
  color: white;
  flex-shrink: 0;
}

.post-name { font-weight: 900; font-size: 0.88rem; }
.post-time { font-size: 0.73rem; color: var(--muted); font-weight: 600; }
.post-body { font-size: 0.88rem; font-weight: 600; color: var(--text); line-height: 1.55; }

.post-actions {
  display: flex;
  gap: 14px;
  margin-top: 12px;
}

.post-action {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 0.8rem;
  font-weight: 800;
  color: var(--muted);
  cursor: pointer;
  transition: color 0.2s;
}

.post-action:hover { color: var(--p1); }

/* Right side features */
.social-features { display: flex; flex-direction: column; gap: 16px; }

.social-feat-card {
  background: var(--bg);
  border-radius: 18px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  transition: all 0.3s;
  border: 2px solid transparent;
}

.social-feat-card:hover { border-color: var(--purple); transform: translateX(-4px); }

.social-feat-icon {
  width: 50px; height: 50px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.social-feat-text h4 { font-weight: 900; font-size: 0.92rem; margin-bottom: 3px; }
.social-feat-text p { font-size: 0.8rem; color: var(--muted); font-weight: 600; }

/* ====== REWARDS ====== */
.rewards-section { background: linear-gradient(160deg, #FFFBF0 0%, #FFF0FF 100%); }

.rewards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 18px;
  margin-top: 48px;
}

.reward-card {
  background: white;
  border-radius: var(--r);
  padding: 28px 20px;
  text-align: center;
  transition: all 0.3s;
  box-shadow: 0 6px 24px rgba(0,0,0,0.07);
  position: relative;
  overflow: hidden;
}

.reward-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 5px;
  background: var(--bar-color);
}

.reward-card:hover { transform: translateY(-8px); box-shadow: 0 20px 56px rgba(0,0,0,0.13); }

.reward-emoji {
  font-size: 3rem;
  margin-bottom: 12px;
  display: block;
  animation: floatY 3s ease-in-out infinite;
}

.reward-card h4 { font-weight: 900; font-size: 1rem; margin-bottom: 6px; }
.reward-card p { font-size: 0.8rem; color: var(--muted); font-weight: 600; line-height: 1.5; }

.reward-pts {
  display: inline-block;
  margin-top: 12px;
  background: linear-gradient(135deg, var(--yellow), var(--p2));
  color: var(--text);
  padding: 4px 14px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 900;
}

/* ====== LEADERBOARD ====== */
.lb-section { background: white; }

.lb-list { display: flex; flex-direction: column; gap: 12px; margin-top: 48px; max-width: 600px; }

.lb-item {
  background: var(--bg);
  border-radius: 16px;
  padding: 14px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  transition: all 0.25s;
}

.lb-item:hover { transform: translateX(6px); }
.lb-item.top1 { background: linear-gradient(135deg, rgba(255,230,109,0.3), rgba(249,115,22,0.15)); border: 2px solid rgba(255,200,0,0.4); }
.lb-item.top2 { background: linear-gradient(135deg, rgba(209,213,219,0.4), rgba(156,163,175,0.15)); border: 2px solid rgba(200,200,200,0.4); }
.lb-item.top3 { background: linear-gradient(135deg, rgba(205,127,50,0.2), rgba(180,100,20,0.1)); border: 2px solid rgba(180,120,50,0.3); }

.lb-rank {
  font-family: 'Fredoka One', cursive;
  font-size: 1.3rem;
  width: 36px;
  text-align: center;
  flex-shrink: 0;
}

.lb-avatar {
  width: 44px; height: 44px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  font-weight: 900;
  color: white;
  flex-shrink: 0;
}

.lb-name { font-weight: 900; font-size: 0.93rem; }
.lb-sub { font-size: 0.76rem; color: var(--muted); font-weight: 600; }
.lb-pts { margin-left: auto; font-weight: 900; font-size: 0.88rem; color: var(--p2); display: flex; align-items: center; gap: 4px; }

/* ====== CTA ====== */
.cta-section {
  background: linear-gradient(135deg, var(--p1), var(--purple), var(--blue));
  padding: 80px 24px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.cta-section h2 {
  font-family: 'Fredoka One', cursive;
  font-size: clamp(2rem, 5vw, 3.5rem);
  color: white;
  margin-bottom: 14px;
  position: relative;
}

.cta-section p {
  color: rgba(255,255,255,0.85);
  font-size: 1.05rem;
  font-weight: 600;
  max-width: 480px;
  margin: 0 auto 36px;
  line-height: 1.65;
  position: relative;
}

.btn-cta {
  background: white;
  color: var(--p1);
  border: none;
  border-radius: 16px;
  padding: 16px 40px;
  font-size: 1.05rem;
  font-weight: 900;
  font-family: 'Nunito', sans-serif;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  position: relative;
  box-shadow: 0 8px 32px rgba(0,0,0,0.25);
}

.btn-cta:hover { transform: translateY(-3px) scale(1.04); box-shadow: 0 14px 48px rgba(0,0,0,0.3); color: var(--p1); }

/* ====== FOOTER ====== */
footer {
  background: var(--text);
  color: rgba(255,255,255,0.75);
  padding: 48px 24px 32px;
  font-size: 0.85rem;
  font-weight: 600;
}

.footer-inner {
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 32px;
}

@media (max-width: 700px) { .footer-inner { grid-template-columns: 1fr 1fr; } }

.footer-brand-text {
  font-family: 'Fredoka One', cursive;
  font-size: 1.7rem;
  background: linear-gradient(135deg, var(--p1), var(--purple));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 10px;
}

footer h5 { color: white; font-weight: 800; font-size: 0.88rem; margin-bottom: 14px; }
footer a { color: rgba(255,255,255,0.65); text-decoration: none; display: block; margin-bottom: 8px; transition: color 0.2s; }
footer a:hover { color: var(--p1); }
.footer-bottom { text-align: center; margin-top: 36px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.8rem; color: rgba(255,255,255,0.4); }

/* ====== ANIMATIONS ====== */
@keyframes floatY {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(0.85); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes spinSlow {
  to { transform: rotate(360deg); }
}

.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: all 0.65s cubic-bezier(0.22, 1, 0.36, 1);
}

.reveal.visible { opacity: 1; transform: translateY(0); }

/* ====== RESPONSIVE TWEAKS ====== */
@media (max-width: 640px) {
  .nav-links a:not(.btn-join):not(.btn-login) { display: none; }
  .hero h1 { font-size: 2.4rem; }
}
</style>
</head>
<body>

<canvas id="bg-canvas"></canvas>

<!-- ====== TOP NAV ====== -->
<nav class="top-nav">
  <a href="#" class="brand">TimePass</a>
  <div class="nav-links">
    <a href="#games">🎮 Games</a>
    <a href="#social">💬 Social</a>
    <a href="#rewards">🏆 Rewards</a>
    <a href="{{route('login')}}" class="btn-login">Login</a>
    <a href="{{ route('register')}}" class="btn-join">Join Free 🚀</a>
  </div>
</nav>

<main>

<!-- ====== HERO ====== -->
<section class="hero">
  <div class="hero-badge">🎉 New: Daily Challenges & Bigger Rewards!</div>

  <h1>
    Play. Chat. Win.
    <span>All in One Place!</span>
  </h1>

  <p>TimePass is your ultimate social playground — play addictive games, vibe with friends, and stack up real rewards every single day.</p>

  <div class="hero-btns">
    <a href="#" class="btn-hero-primary">🎮 Start Playing Free</a>
    <a href="#features" class="btn-hero-secondary">✨ See Features</a>
  </div>

  <div class="hero-stats">
    <div class="stat-pill">
      <div class="stat-icon" style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">🎮</div>
      <div class="stat-info">
        <strong>50+</strong>
        <span>Fun Games</span>
      </div>
    </div>
    <div class="stat-pill">
      <div class="stat-icon" style="background:linear-gradient(135deg,#4ECDC4,#3B82F6);">👥</div>
      <div class="stat-info">
        <strong>2M+</strong>
        <span>Active Users</span>
      </div>
    </div>
    <div class="stat-pill">
      <div class="stat-icon" style="background:linear-gradient(135deg,#FFE66D,#F97316);">🏆</div>
      <div class="stat-info">
        <strong>₹50L+</strong>
        <span>Rewards Given</span>
      </div>
    </div>
  </div>
</section>

<!-- ====== FEATURES ====== -->
<section class="features-section" id="features">
  <div class="container">
    <div class="reveal">
      <div class="section-label">⚡ Everything You Need</div>
      <h2 class="section-title">Why Everyone Loves TimePass</h2>
      <p class="section-sub">From gaming tournaments to real-time chat — we've packed in all the fun you can handle.</p>
    </div>

    <div class="features-grid">
      <div class="feat-card reveal" style="--c1:#FF6B6B;--c2:#FF8E53;" tabindex="0">
        <div class="feat-icon">🎮</div>
        <div class="feat-text">50+ Mini Games</div>
        <div class="feat-sub">Quizzes, puzzles, arcade games and live multiplayer battles — always something new.</div>
      </div>
      <div class="feat-card reveal" style="--c1:#4ECDC4;--c2:#3B82F6;" tabindex="0">
        <div class="feat-icon">💬</div>
        <div class="feat-text">Real-Time Chat</div>
        <div class="feat-sub">DM friends, group chats, voice notes, fun stickers and meme-ready GIFs.</div>
      </div>
      <div class="feat-card reveal" style="--c1:#A855F7;--c2:#EC4899;" tabindex="0">
        <div class="feat-icon">📸</div>
        <div class="feat-text">Social Feed</div>
        <div class="feat-sub">Share your wins, moments and highlights. React, comment and go viral.</div>
      </div>
      <div class="feat-card reveal" style="--c1:#FFE66D;--c2:#F97316;" tabindex="0">
        <div class="feat-icon">🏆</div>
        <div class="feat-text">Real Rewards</div>
        <div class="feat-sub">Earn coins every day. Redeem for gift cards, cash, premium badges and more.</div>
      </div>
      <div class="feat-card reveal" style="--c1:#10B981;--c2:#4ECDC4;" tabindex="0">
        <div class="feat-icon">🔥</div>
        <div class="feat-text">Daily Streaks</div>
        <div class="feat-sub">Keep your streak alive to unlock bonus points and exclusive content.</div>
      </div>
      <div class="feat-card reveal" style="--c1:#EC4899;--c2:#A855F7;" tabindex="0">
        <div class="feat-icon">🌟</div>
        <div class="feat-text">Achievements</div>
        <div class="feat-sub">Level up, collect badges and show off your profile to the world.</div>
      </div>
    </div>
  </div>
</section>

<!-- ====== GAMES ====== -->
<section class="games-section" id="games">
  <div class="container">
    <div class="reveal">
      <div class="section-label">🎮 Play & Win</div>
      <h2 class="section-title">Hot Games Right Now 🔥</h2>
      <p class="section-sub">Jump into a game instantly — no download needed. Compete, earn and climb the leaderboard.</p>
    </div>

    <div class="games-grid">
      <div class="game-card reveal">
        <div class="game-thumb" style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">🧩</div>
        <div class="game-info">
          <h4>Word Puzzle Mania</h4>
          <p>Find hidden words before time runs out</p>
          <div class="game-meta">
            <span class="live-badge"><span class="live-dot"></span>LIVE</span>
            <span class="coin-reward">🪙 +50 coins</span>
          </div>
        </div>
      </div>
      <div class="game-card reveal">
        <div class="game-thumb" style="background:linear-gradient(135deg,#4ECDC4,#3B82F6);">🧠</div>
        <div class="game-info">
          <h4>Brain Quiz Battle</h4>
          <p>1v1 trivia with real opponents</p>
          <div class="game-meta">
            <span class="live-badge"><span class="live-dot"></span>LIVE</span>
            <span class="coin-reward">🪙 +80 coins</span>
          </div>
        </div>
      </div>
      <div class="game-card reveal">
        <div class="game-thumb" style="background:linear-gradient(135deg,#A855F7,#EC4899);">🎯</div>
        <div class="game-info">
          <h4>Spin & Score</h4>
          <p>Spin the wheel, unlock surprise rewards</p>
          <div class="game-meta">
            <span class="live-badge" style="background:#F97316;"><span class="live-dot"></span>HOT</span>
            <span class="coin-reward">🪙 +30 coins</span>
          </div>
        </div>
      </div>
      <div class="game-card reveal">
        <div class="game-thumb" style="background:linear-gradient(135deg,#FFE66D,#F97316);">🃏</div>
        <div class="game-info">
          <h4>Flash Card Blitz</h4>
          <p>Memory-matching madness against friends</p>
          <div class="game-meta">
            <span class="live-badge" style="background:#6B7280;"><span class="live-dot"></span>SOON</span>
            <span class="coin-reward">🪙 +60 coins</span>
          </div>
        </div>
      </div>
      <div class="game-card reveal">
        <div class="game-thumb" style="background:linear-gradient(135deg,#10B981,#4ECDC4);">🐍</div>
        <div class="game-info">
          <h4>Snake Turbo</h4>
          <p>Classic snake — supercharged with powerups</p>
          <div class="game-meta">
            <span class="live-badge"><span class="live-dot"></span>LIVE</span>
            <span class="coin-reward">🪙 +40 coins</span>
          </div>
        </div>
      </div>
      <div class="game-card reveal">
        <div class="game-thumb" style="background:linear-gradient(135deg,#EC4899,#A855F7);">🎲</div>
        <div class="game-info">
          <h4>Lucky Dice Dash</h4>
          <p>Roll dice, grab territory, beat everyone</p>
          <div class="game-meta">
            <span class="live-badge"><span class="live-dot"></span>LIVE</span>
            <span class="coin-reward">🪙 +70 coins</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ====== SOCIAL ====== -->
<section class="social-section" id="social">
  <div class="container">
    <div class="reveal">
      <div class="section-label">💬 Connect & Vibe</div>
      <h2 class="section-title">Your Social Playground 🌈</h2>
      <p class="section-sub">Share your wins, chat with friends, join communities and never miss a moment.</p>
    </div>

    <div class="social-layout">
      <!-- Feed preview -->
      <div class="feed-preview">
        <div class="post-card reveal">
          <div class="post-header">
            <div class="post-avatar" style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">R</div>
            <div>
              <div class="post-name">RahulX99</div>
              <div class="post-time">2 min ago · 🔥 7-day streak</div>
            </div>
          </div>
          <div class="post-body">Just hit 10,000 coins! 🎉 Brain Quiz is literally addictive. Who wants to battle me? 🧠⚡</div>
          <div class="post-actions">
            <span class="post-action">❤️ 142</span>
            <span class="post-action">💬 28</span>
            <span class="post-action">🔁 Share</span>
          </div>
        </div>
        <div class="post-card reveal">
          <div class="post-header">
            <div class="post-avatar" style="background:linear-gradient(135deg,#A855F7,#EC4899);">P</div>
            <div>
              <div class="post-name">PriyaVibes</div>
              <div class="post-time">18 min ago · 🎮 Playing Word Mania</div>
            </div>
          </div>
          <div class="post-body">Redeemed my coins for a ₹500 Amazon voucher 😍 THIS APP IS REAL!! Don't sleep on TimePass!!</div>
          <div class="post-actions">
            <span class="post-action">❤️ 389</span>
            <span class="post-action">💬 64</span>
            <span class="post-action">🔁 Share</span>
          </div>
        </div>
        <div class="post-card reveal">
          <div class="post-header">
            <div class="post-avatar" style="background:linear-gradient(135deg,#4ECDC4,#3B82F6);">A</div>
            <div>
              <div class="post-name">Aryan_Gamer</div>
              <div class="post-time">1 hr ago · 🏆 #1 on leaderboard</div>
            </div>
          </div>
          <div class="post-body">Reached Level 50 🎊 Anyone else in the top 100? Drop your rank below 👇</div>
          <div class="post-actions">
            <span class="post-action">❤️ 712</span>
            <span class="post-action">💬 103</span>
            <span class="post-action">🔁 Share</span>
          </div>
        </div>
      </div>

      <!-- Social features -->
      <div class="social-features">
        <div class="social-feat-card reveal">
          <div class="social-feat-icon" style="background:linear-gradient(135deg,#FF6B6B22,#FF8E5322);">💌</div>
          <div class="social-feat-text">
            <h4>Instant DMs</h4>
            <p>Text, voice notes & fun stickers with any friend in real time.</p>
          </div>
        </div>
        <div class="social-feat-card reveal">
          <div class="social-feat-icon" style="background:linear-gradient(135deg,#A855F722,#EC489922);">👥</div>
          <div class="social-feat-text">
            <h4>Group Hangouts</h4>
            <p>Create or join squads, share memes and plan game nights together.</p>
          </div>
        </div>
        <div class="social-feat-card reveal">
          <div class="social-feat-icon" style="background:linear-gradient(135deg,#4ECDC422,#3B82F622);">📣</div>
          <div class="social-feat-text">
            <h4>Communities</h4>
            <p>Join clubs by interest — gaming, anime, memes, sports and more.</p>
          </div>
        </div>
        <div class="social-feat-card reveal">
          <div class="social-feat-icon" style="background:linear-gradient(135deg,#10B98122,#4ECDC422);">🔔</div>
          <div class="social-feat-text">
            <h4>Activity Feed</h4>
            <p>See when friends win a game, level up or unlock a rare badge.</p>
          </div>
        </div>
        <div class="social-feat-card reveal">
          <div class="social-feat-icon" style="background:linear-gradient(135deg,#FFE66D22,#F9731622);">🎙️</div>
          <div class="social-feat-text">
            <h4>Live Rooms</h4>
            <p>Host or join live audio rooms — roast, quiz or just chill with the gang.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ====== REWARDS ====== -->
<section class="rewards-section" id="rewards">
  <div class="container">
    <div class="reveal">
      <div class="section-label">🏆 Earn Real Value</div>
      <h2 class="section-title">Rewards That Actually Matter 💰</h2>
      <p class="section-sub">Every game you play, every streak you keep — it all adds up. Redeem for real rewards.</p>
    </div>

    <div class="rewards-grid">
      <div class="reward-card reveal" style="--bar-color:linear-gradient(90deg,#FF6B6B,#FF8E53);">
        <span class="reward-emoji">🎁</span>
        <h4>Gift Cards</h4>
        <p>Amazon, Flipkart, Swiggy, Zomato and 20+ more brands.</p>
        <span class="reward-pts">From 500 pts</span>
      </div>
      <div class="reward-card reveal" style="--bar-color:linear-gradient(90deg,#10B981,#4ECDC4);">
        <span class="reward-emoji" style="animation-delay:0.4s">💸</span>
        <h4>UPI Cashback</h4>
        <p>Redeem directly to your bank via UPI. Instant transfer.</p>
        <span class="reward-pts">From 1,000 pts</span>
      </div>
      <div class="reward-card reveal" style="--bar-color:linear-gradient(90deg,#A855F7,#EC4899);">
        <span class="reward-emoji" style="animation-delay:0.8s">🌟</span>
        <h4>Premium Badge</h4>
        <p>Show off your status with an exclusive animated profile badge.</p>
        <span class="reward-pts">From 300 pts</span>
      </div>
      <div class="reward-card reveal" style="--bar-color:linear-gradient(90deg,#FFE66D,#F97316);">
        <span class="reward-emoji" style="animation-delay:1.2s">⚡</span>
        <h4>Powerups</h4>
        <p>Unlock hints, extra lives and boosters for your fav games.</p>
        <span class="reward-pts">From 100 pts</span>
      </div>
    </div>
  </div>
</section>

<!-- ====== LEADERBOARD ====== -->
<section class="lb-section">
  <div class="container">
    <div style="display:flex;gap:48px;flex-wrap:wrap;align-items:flex-start;">
      <div class="reveal" style="flex:1;min-width:260px;">
        <div class="section-label">🏅 Top Players</div>
        <h2 class="section-title">This Week's Leaderboard</h2>
        <p class="section-sub">Compete with millions. Win exclusive rewards for reaching the top.</p>
        <a href="#" class="btn-hero-primary" style="margin-top:24px;display:inline-flex;">View Full Board →</a>
      </div>

      <div class="lb-list" style="flex:1.4;min-width:280px;">
        <div class="lb-item top1 reveal">
          <div class="lb-rank">🥇</div>
          <div class="lb-avatar" style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">A</div>
          <div><div class="lb-name">Aryan_Gamer</div><div class="lb-sub">Level 50 · 18 games played</div></div>
          <div class="lb-pts">🪙 48,200</div>
        </div>
        <div class="lb-item top2 reveal">
          <div class="lb-rank">🥈</div>
          <div class="lb-avatar" style="background:linear-gradient(135deg,#A855F7,#EC4899);">P</div>
          <div><div class="lb-name">PriyaVibes</div><div class="lb-sub">Level 44 · 14 games played</div></div>
          <div class="lb-pts">🪙 41,700</div>
        </div>
        <div class="lb-item top3 reveal">
          <div class="lb-rank">🥉</div>
          <div class="lb-avatar" style="background:linear-gradient(135deg,#F97316,#FFE66D);">R</div>
          <div><div class="lb-name">RahulX99</div><div class="lb-sub">Level 38 · 12 games played</div></div>
          <div class="lb-pts">🪙 37,500</div>
        </div>
        <div class="lb-item reveal">
          <div class="lb-rank" style="color:#6B7280;font-size:1rem;">4</div>
          <div class="lb-avatar" style="background:linear-gradient(135deg,#4ECDC4,#3B82F6);">S</div>
          <div><div class="lb-name">Sreyoshi22</div><div class="lb-sub">Level 35 · 10 games played</div></div>
          <div class="lb-pts">🪙 31,800</div>
        </div>
        <div class="lb-item reveal">
          <div class="lb-rank" style="color:#6B7280;font-size:1rem;">5</div>
          <div class="lb-avatar" style="background:linear-gradient(135deg,#10B981,#4ECDC4);">M</div>
          <div><div class="lb-name">Mohit_Pro</div><div class="lb-sub">Level 31 · 9 games played</div></div>
          <div class="lb-pts">🪙 28,400</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ====== CTA ====== -->
<section class="cta-section">
  <h2>Ready to Join the Fun? 🎉</h2>
  <p>Sign up free in 10 seconds. No credit card. No boring stuff. Just pure fun, friends and rewards.</p>
  <a href="#" class="btn-cta">🚀 Create Free Account</a>
</section>

<!-- ====== FOOTER ====== -->
<footer>
  <div class="footer-inner">
    <div>
      <div class="footer-brand-text">TimePass</div>
      <p style="margin-bottom:14px;line-height:1.6;">Your social gaming paradise. Play, connect, earn — every single day. 🎮</p>
      <div style="display:flex;gap:10px;margin-top:8px;">
        <a href="#" style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:white;font-size:0.95rem;"><i class="fab fa-instagram"></i></a>
        <a href="#" style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:white;font-size:0.95rem;"><i class="fab fa-twitter"></i></a>
        <a href="#" style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:white;font-size:0.95rem;"><i class="fab fa-discord"></i></a>
        <a href="#" style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:white;font-size:0.95rem;"><i class="fab fa-youtube"></i></a>
      </div>
    </div>
    <div>
      <h5>Games</h5>
      <a href="#">All Games</a>
      <a href="#">Leaderboard</a>
      <a href="#">Tournaments</a>
      <a href="#">New Games</a>
    </div>
    <div>
      <h5>Social</h5>
      <a href="#">Friends</a>
      <a href="#">Communities</a>
      <a href="#">Live Rooms</a>
      <a href="#">Messages</a>
    </div>
    <div>
      <h5>Company</h5>
      <a href="#">About Us</a>
      <a href="#">Blog</a>
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
    </div>
  </div>
  <div class="footer-bottom">© 2025 TimePass. Made with ❤️ in India. All rights reserved.</div>
</footer>

</main>

<!-- ====== BOTTOM NAV ====== -->
<nav class="bottom-nav">
  <a href="#" class="active" style="position:relative;">
    <div class="ico">🏠</div>
    <span>Home</span>
  </a>
  <a href="#games" style="position:relative;">
    <div class="ico">🎮</div>
    <span>Games</span>
  </a>
  <a href="#social" style="position:relative;">
    <div class="ico">💬</div>
    <span>Social</span>
    <div class="dot-badge"></div>
  </a>
  <a href="#rewards" style="position:relative;">
    <div class="ico">🏆</div>
    <span>Rewards</span>
  </a>
  <a href="#" style="position:relative;">
    <div class="ico">👤</div>
    <span>Profile</span>
  </a>
</nav>

<!-- Three.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
// ====== 3D BG: Gaming-themed floating shapes ======
(function() {
  const canvas = document.getElementById('bg-canvas');
  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.1, 100);
  camera.position.z = 7;

  const palette = [0xFF6B6B, 0x4ECDC4, 0xFFE66D, 0xA855F7, 0x3B82F6, 0xEC4899, 0x10B981, 0xF97316];

  const objects = [];

  // Game controller / diamond shapes
  const geos = [
    new THREE.OctahedronGeometry(0.35, 0),
    new THREE.BoxGeometry(0.45, 0.45, 0.45),
    new THREE.TetrahedronGeometry(0.38, 0),
    new THREE.SphereGeometry(0.28, 8, 8),
    new THREE.TorusGeometry(0.28, 0.1, 8, 16),
    new THREE.ConeGeometry(0.25, 0.5, 6),
  ];

  for (let i = 0; i < 40; i++) {
    const geo = geos[Math.floor(Math.random() * geos.length)];
    const mat = new THREE.MeshPhongMaterial({
      color: palette[Math.floor(Math.random() * palette.length)],
      transparent: true,
      opacity: 0.22,
      shininess: 120,
      wireframe: Math.random() > 0.65,
    });
    const mesh = new THREE.Mesh(geo, mat);
    mesh.position.set(
      (Math.random() - 0.5) * 20,
      (Math.random() - 0.5) * 14,
      (Math.random() - 0.5) * 8
    );
    mesh.rotation.set(Math.random() * Math.PI, Math.random() * Math.PI, 0);
    mesh.userData = {
      vx: (Math.random() - 0.5) * 0.006,
      vy: (Math.random() - 0.5) * 0.006,
      rx: (Math.random() - 0.5) * 0.012,
      ry: (Math.random() - 0.5) * 0.012,
    };
    scene.add(mesh);
    objects.push(mesh);
  }

  // Glowing particles
  const ptGeo = new THREE.BufferGeometry();
  const ptCount = 260;
  const ptPos = new Float32Array(ptCount * 3);
  for (let i = 0; i < ptCount * 3; i++) ptPos[i] = (Math.random() - 0.5) * 50;
  ptGeo.setAttribute('position', new THREE.BufferAttribute(ptPos, 3));
  scene.add(new THREE.Points(ptGeo, new THREE.PointsMaterial({ color: 0xFFE66D, size: 0.07, transparent: true, opacity: 0.55 })));

  // Lights
  scene.add(new THREE.AmbientLight(0xffffff, 0.7));
  const l1 = new THREE.PointLight(0xFF6B6B, 2, 20); l1.position.set(4, 4, 4); scene.add(l1);
  const l2 = new THREE.PointLight(0x4ECDC4, 2, 20); l2.position.set(-4, -4, 3); scene.add(l2);
  const l3 = new THREE.PointLight(0xA855F7, 1.5, 20); l3.position.set(0, 5, -2); scene.add(l3);

  let mouse = { x: 0, y: 0 };
  document.addEventListener('mousemove', e => {
    mouse.x = (e.clientX / window.innerWidth - 0.5) * 0.6;
    mouse.y = -(e.clientY / window.innerHeight - 0.5) * 0.6;
  });

  let t = 0;
  function animate() {
    requestAnimationFrame(animate);
    t += 0.008;
    objects.forEach((o, i) => {
      o.position.x += o.userData.vx;
      o.position.y += o.userData.vy;
      o.rotation.x += o.userData.rx;
      o.rotation.y += o.userData.ry;
      if (Math.abs(o.position.x) > 11) o.userData.vx *= -1;
      if (Math.abs(o.position.y) > 8) o.userData.vy *= -1;
    });
    // Gentle light pulse
    l1.intensity = 1.5 + Math.sin(t) * 0.5;
    l2.intensity = 1.5 + Math.cos(t * 0.8) * 0.5;
    camera.position.x += (mouse.x - camera.position.x) * 0.025;
    camera.position.y += (mouse.y - camera.position.y) * 0.025;
    renderer.render(scene, camera);
  }
  animate();

  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });
})();

// ====== SCROLL REVEAL ======
const observer = new IntersectionObserver(entries => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) {
      e.target.style.transitionDelay = (i * 0.06) + 's';
      e.target.classList.add('visible');
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ====== ACTIVE NAV ======
document.querySelectorAll('.bottom-nav a').forEach(a => {
  a.addEventListener('click', function() {
    document.querySelectorAll('.bottom-nav a').forEach(x => x.classList.remove('active'));
    this.classList.add('active');
  });
});

// ====== GAME CARD HOVER ======
document.querySelectorAll('.game-card').forEach(card => {
  card.addEventListener('click', () => {
    card.style.transform = 'scale(0.96)';
    setTimeout(() => card.style.transform = '', 180);
  });
});
</script>
</body>
</html>