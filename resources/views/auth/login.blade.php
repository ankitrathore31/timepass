{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – TimePass</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --p1: #FF6B6B;
            --p2: #FF8E53;
            --orange: #F97316;
            --blue: #3B82F6;
            --green: #10B981;
            --teal: #4ECDC4;
            --yellow: #FFE66D;
            --bg: #FFF8F0;
            --border: #E5E7EB;
            --text: #1A1A2E;
            --muted: #6B7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(145deg, #FFF0E8 0%, #FFF8F0 50%, #F0F4FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 28px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            animation: slideUp .6s cubic-bezier(.22, 1, .36, 1);
        }

        .brand {
            font-family: 'Fredoka One', cursive;
            font-size: 2.4rem;
            background: linear-gradient(135deg, var(--p1), var(--orange), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 4px;
        }

        .tagline {
            text-align: center;
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 800;
            margin-bottom: 28px;
            letter-spacing: .4px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 900;
            color: var(--text);
            margin-bottom: 6px;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            background: var(--bg);
            border: 2px solid var(--border);
            border-radius: 13px;
            padding: 12px 16px;
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            outline: none;
            transition: all .25s;
        }

        .form-control:focus {
            border-color: var(--p1);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--p1);
        }

        .invalid-feedback {
            color: var(--p1);
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 4px;
        }

        .pass-wrap {
            position: relative;
        }

        .pass-wrap .toggle-eye {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--muted);
            font-size: 0.9rem;
            background: none;
            border: none;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 8px 0;
        }

        .form-check input {
            width: 16px;
            height: 16px;
            accent-color: var(--p1);
            cursor: pointer;
        }

        .form-check label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--p1), var(--orange));
            color: white;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 900;
            cursor: pointer;
            transition: all .3s;
            margin-top: 8px;
            box-shadow: 0 6px 22px rgba(255, 107, 107, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0;
            color: var(--muted);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .social-row {
            display: flex;
            gap: 10px;
        }

        .social-btn {
            flex: 1;
            background: var(--bg);
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 10px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.78rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: var(--text);
        }

        .social-btn:hover {
            background: white;
            border-color: var(--p1);
            color: var(--p1);
        }

        .auth-footer {
            text-align: center;
            margin-top: 18px;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--p1);
            font-weight: 900;
            text-decoration: none;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1.5px solid #FECACA;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #B91C1C;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="brand">TimePass</div>
        <div class="tagline">🎮 PLAY · CONNECT · EARN</div>

        @if ($errors->any())
            <div class="alert-error">❌ {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="you@timepass.in"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="pass-wrap">
                    <input type="password" name="password" id="passField"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Enter password" style="padding-right:44px;" required>
                    <button type="button" class="toggle-eye" onclick="togglePass()">👁️</button>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#"
                    style="font-size:0.78rem;font-weight:800;color:var(--blue);text-decoration:none;">Forgot
                    password?</a>
            </div>

            <button type="submit" class="btn-submit">🚀 Login to TimePass</button>
        </form>

        <div class="divider">or continue with</div>
        <div class="social-row">
            <button class="social-btn"><img src="https://www.svgrepo.com/show/475656/google-color.svg"
                    width="16">Google</button>
            <button class="social-btn"><img src="https://www.svgrepo.com/show/512120/facebook-176.svg"
                    width="16">Facebook</button>
        </div>
        <div class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Register Free 🎉</a></div>
    </div>
    <script>
        function togglePass() {
            const f = document.getElementById('passField');
            f.type = f.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- resources/views/auth/register.blade.php                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

