<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – TimePass</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        /* same variables as login */
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
            background: linear-gradient(145deg, #FFF0E8, #FFF8F0, #F0F4FF);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 28px;
            padding: 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            animation: slideUp .6s ease;
        }

        .brand {
            font-family: 'Fredoka One', cursive;
            font-size: 2.2rem;
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
            font-size: 0.8rem;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            display: block;
            font-size: 0.76rem;
            font-weight: 900;
            color: var(--text);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .form-control {
            width: 100%;
            background: var(--bg);
            border: 2px solid var(--border);
            border-radius: 13px;
            padding: 11px 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            outline: none;
            transition: all .25s;
            color: var(--text);
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
            font-size: 0.74rem;
            font-weight: 700;
            margin-top: 3px;
        }

        .pass-wrap {
            position: relative;
        }

        .pass-wrap .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            color: var(--muted);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--green), var(--teal));
            color: white;
            border: none;
            border-radius: 14px;
            padding: 13px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.95rem;
            font-weight: 900;
            cursor: pointer;
            transition: all .3s;
            margin-top: 8px;
            box-shadow: 0 6px 22px rgba(16, 185, 129, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--p1);
            font-weight: 900;
            text-decoration: none;
        }

        .welcome-note {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.08), rgba(249, 115, 22, 0.08));
            border: 1.5px solid rgba(255, 107, 107, 0.2);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 18px;
            text-align: center;
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
        <div class="tagline">Join 2M+ players today 🎮</div>
        <div class="welcome-note">🎁 Get <strong>100 free coins</strong> just for signing up!</div>

        @if ($errors->any())
            <div
                style="background:#FEF2F2;border:1.5px solid #FECACA;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;font-weight:700;color:#B91C1C;">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    placeholder="Rahul Sharma" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                    class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="coolplayer99"
                    value="{{ old('username') }}">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="you@timepass.in"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="pass-wrap">
                    <input type="password" name="password" id="passReg"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Min 8 characters" style="padding-right:42px;" required>
                    <button type="button" class="toggle-eye"
                        onclick="document.getElementById('passReg').type=document.getElementById('passReg').type==='password'?'text':'password'">👁️</button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Repeat password" required>
            </div>

            <button type="submit" class="btn-submit">🎉 Create Free Account</button>
        </form>
        <div class="auth-footer">Already have an account? <a href="{{ route('login') }}">Login →</a></div>
    </div>
</body>

</html>