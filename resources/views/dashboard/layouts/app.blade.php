{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TimePass') – Play. Connect. Earn.</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --p1: #FF6B6B;
            --p2: #FF8E53;
            --teal: #4ECDC4;
            --yellow: #FFE66D;
            --orange: #F97316;
            --blue: #3B82F6;
            --green: #10B981;
            --bg: #FFF8F0;
            --white: #fff;
            --text: #1A1A2E;
            --muted: #6B7280;
            --border: #E5E7EB;
            --r: 20px;
            --nav-h: 66px;
            --bottom-h: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            padding-bottom: var(--bottom-h);
        }

        /* NAV */
        .top-nav {
            position: sticky;
            top: 0;
            z-index: 500;
            height: var(--nav-h);
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
        }

        .brand {
            font-family: 'Fredoka One', cursive;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--p1), var(--orange), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .coins-pill {
            background: linear-gradient(135deg, var(--yellow), var(--p2));
            color: var(--text);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 3px 12px rgba(255, 180, 0, 0.3);
        }

        .avatar-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--p1), var(--orange));
            border: none;
            color: white;
            font-weight: 900;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* BOTTOM NAV */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-h);
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(18px);
            border-top: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-around;
            z-index: 500;
            box-shadow: 0 -3px 20px rgba(0, 0, 0, 0.07);
        }

        .bnav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.62rem;
            font-weight: 800;
            transition: all .25s;
            padding: 4px 14px;
        }

        .bnav-item .ico {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all .3s;
        }

        .bnav-item.active .ico,
        .bnav-item:hover .ico {
            background: linear-gradient(135deg, var(--p1), var(--orange));
            color: white;
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.35);
        }

        .bnav-item.active {
            color: var(--p1);
        }

        /* TOAST */
        .toast-wrap {
            position: fixed;
            top: 78px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            z-index: 9000;
            transition: all .4s;
            opacity: 0;
            pointer-events: none;
        }

        .toast-wrap.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .toast-inner {
            padding: 11px 22px;
            border-radius: 30px;
            font-weight: 900;
            font-size: 0.85rem;
            white-space: nowrap;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .toast-success {
            background: linear-gradient(135deg, var(--green), var(--teal));
            color: white;
        }

        .toast-error {
            background: linear-gradient(135deg, var(--p1), var(--p2));
            color: white;
        }

        /* SECTION UTIL */
        .container {
            /* max-width: 480px; */
            margin: 0 auto;
            padding: 20px 16px;
        }

        .section-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-hd h3 {
            font-family: 'Fredoka One', cursive;
            font-size: 1.2rem;
        }

        .see-all {
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--blue);
            text-decoration: none;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--p1), var(--orange));
            color: white;
            border: none;
            border-radius: 14px;
            padding: 13px 28px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.95rem;
            font-weight: 900;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
            box-shadow: 0 6px 22px rgba(255, 107, 107, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
            color: white;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: var(--text);
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 11px 24px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: var(--p1);
            color: var(--p1);
        }

        @yield('extra-styles')
    </style>
    @stack('styles')
</head>

<body>

    {{-- TOP NAV --}}
    <nav class="top-nav">
        <a href="{{ route('dashboard') }}" class="brand">TimePass</a>
        <div class="nav-right">
            <div class="coins-pill">🪙 <span id="navCoinCount">{{ number_format(auth()->user()->coins) }}</span></div>
            <a href="{{ route('profile.index') }}" class="avatar-btn">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </a>
        </div>
    </nav>

    {{-- TOAST --}}
    @if (session('toast_success'))
        <div class="toast-wrap show" id="toastEl">
            <div class="toast-inner toast-success">{{ session('toast_success') }}</div>
        </div>
    @elseif(session('toast_error'))
        <div class="toast-wrap show" id="toastEl">
            <div class="toast-inner toast-error">{{ session('toast_error') }}</div>
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    @yield('content')

    {{-- BOTTOM NAV --}}
    <nav class="bottom-nav">

        <a href="{{ route('dashboard') }}" class="bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="ico">🏠</div>
            <span>Home</span>
        </a>

        <a href="{{-- route('videos.index') --}}" class="bnav-item ">

            <div class="icon-wrap">
                <i class="fas fa-play"></i>
            </div>
            <span>Reels</span>

        </a>
        <a href="{{ route('games.index') }}" class="bnav-item {{ request()->routeIs('games.*') ? 'active' : '' }}">
            <div class="ico">🎮</div>
            <span>Games</span>
        </a>



        <a href="{{ route('rewards.index') }}"
            class="bnav-item {{ request()->routeIs('rewards.*') ? 'active' : '' }}">
            <div class="ico">🏆</div>
            <span>Rewards</span>
        </a>

        <a href="{{ route('profile.index') }}"
            class="bnav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <div class="ico">👤</div>
            <span>Profile</span>
        </a>

    </nav>

    <script>
        // Auto-hide toast
        const toast = document.getElementById('toastEl');
        if (toast) {
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Global coin updater (called after game AJAX)
        function updateNavCoins(n) {
            document.getElementById('navCoinCount').textContent = n.toLocaleString('en-IN');
        }
    </script>
    @stack('scripts')
</body>

</html>
