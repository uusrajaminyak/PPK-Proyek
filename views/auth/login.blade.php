<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <meta name="description" content="Masuk ke akun Fasilita UNDIP untuk reservasi dan pelaporan fasilitas kampus.">
    <title>Masuk — Fasilita UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
    <style>
        /* ─── Reset & base ─────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        .login-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        /* ─── Watermark: LEFT side ──────────────────────────────── */
        .login-watermark {
            position: fixed;
            bottom: -5%;
            left: -8%;
            width: 55vw;
            max-width: 700px;
            min-width: 340px;
            pointer-events: none;
            user-select: none;
            z-index: 0;
        }

        /* ─── Main wrapper ──────────────────────────────────────── */
        .login-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem 2rem;
            position: relative;
            z-index: 1;
        }

        /* ─── Back link: top-left, OUTSIDE card ─────────────────── */
        .login-back-link {
            position: fixed;
            top: 1.5rem;
            left: 1.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: #001348;
            text-decoration: none;
            z-index: 10;
            transition: opacity 0.15s;
        }
        .login-back-link:hover { opacity: 0.7; }

        /* ─── Header above card: single image banner ────────────── */
        .login-brand-header {
            margin-bottom: 1.5rem;
            text-align: center;
            display: flex;
            justify-content: center;
        }
        
        .login-brand-header img {
            width: 100%;
            max-width: 340px;
            height: auto;
            object-fit: contain;
        }

        /* ─── Card ──────────────────────────────────────────────── */
        .login-card {
            background: #fff;
            border-radius: 14px;
            border: 1.5px solid #d1d5db;
            padding: 2rem 2rem 1.75rem;
            width: 100%;
            max-width: 380px;
        }

        /* ─── Form fields ───────────────────────────────────────── */
        .login-field { margin-bottom: 1.1rem; }
        .login-field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.4rem;
        }
        .login-input-wrap { position: relative; }
        .login-input-wrap .field-icon {
            position: absolute;
            top: 50%;
            left: 0.85rem;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            display: flex;
            align-items: center;
        }
        .login-input-wrap input {
            width: 100%;
            padding: 0.65rem 0.85rem 0.65rem 2.5rem;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .login-input-wrap input:focus {
            border-color: #001348;
            box-shadow: 0 0 0 3px rgba(0,19,72,0.08);
        }
        .login-input-wrap input.has-error { border-color: #ef4444; }
        .login-input-wrap input[type="password"],
        .login-input-wrap input[type="text"] { padding-right: 2.75rem; }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.15s;
        }
        .toggle-password:hover { color: #001348; }
        .field-error { margin-top: 0.3rem; font-size: 0.78rem; color: #ef4444; }

        /* ─── Remember row ──────────────────────────────────────── */
        .login-remember-row {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            margin-bottom: 1.35rem;
            font-size: 0.84rem;
            color: #374151;
            cursor: pointer;
        }
        .login-remember-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #001348;
            cursor: pointer;
        }

        /* ─── Submit ────────────────────────────────────────────── */
        .login-btn {
            width: 100%;
            padding: 0.8rem 1rem;
            background: #001348;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.01em;
        }
        .login-btn:hover { background: #0a1e55; }
        .login-btn:active { transform: scale(0.98); }

        /* ─── Register link ─────────────────────────────────────── */
        .login-register {
            margin-top: 1.1rem;
            text-align: center;
            font-size: 0.84rem;
            color: #6b7280;
        }
        .login-register a {
            color: #001348;
            font-weight: 700;
            text-decoration: none;
        }
        .login-register a:hover { text-decoration: underline; }

        /* ─── Footer ────────────────────────────────────────────── */
        .login-footer {
            background: #001348;
            color: rgba(255,255,255,0.8);
            text-align: center;
            font-size: 0.78rem;
            padding: 1rem;
            position: relative;
            z-index: 1;
        }

        /* ─── Responsive ────────────────────────────────────────── */
        @media (max-width: 480px) {
            .login-card { padding: 1.5rem 1.25rem; }
            .login-watermark { width: 85vw; left: -15%; }
            .login-back-link { top: 1rem; left: 1rem; }
        }
    </style>
</head>
<body class="login-page">

    {{-- ── BACK LINK: fixed top-left, outside card ───────────── --}}
    <a class="login-back-link" href="{{ route('home') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Kembali ke Beranda
    </a>

    {{-- ── WATERMARK: left side ───────────────────────────────── --}}
    <img
        class="login-watermark"
        src="{{ asset('images/landing/undip-watermark.png') }}"
        alt=""
        aria-hidden="true"
    >

    {{-- ── MAIN ────────────────────────────────────────────────── --}}
    <main class="login-main">

        {{-- Brand header above card: single horizontal image --}}
        <div class="login-brand-header">
            <img src="{{ asset('images/fasilita-header.png') }}" alt="Fasilita UNDIP - Sistem Reservasi & Pelaporan Fasilitas Kampus">
        </div>

        {{-- Login card --}}
        <div class="login-card">

            {{-- Validation errors --}}
            @if ($errors->any())
                <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:0.65rem 0.9rem;margin-bottom:1rem;font-size:0.82rem;color:#991b1b;">
                    @foreach ($errors->all() as $error)
                        <p style="margin:0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="login-field">
                    <label for="email">Email</label>
                    <div class="login-input-wrap">
                        <span class="field-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input
                            id="email" name="email" type="email"
                            autocomplete="email" required
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email"
                            class="{{ $errors->has('email') ? 'has-error' : '' }}"
                        >
                    </div>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="login-input-wrap">
                        <span class="field-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            id="password" name="password" type="password"
                            autocomplete="current-password" required
                            placeholder="Masukkan Password"
                            class="{{ $errors->has('password') ? 'has-error' : '' }}"
                        >
                        <button type="button" class="toggle-password" aria-label="Tampilkan/sembunyikan password" onclick="togglePassword()">
                            <svg id="eye-show" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-hide" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                {{-- Ingat Saya --}}
                <label class="login-remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    Ingat Saya
                </label>

                {{-- Submit --}}
                <button type="submit" class="login-btn">Masuk</button>
            </form>

            <p class="login-register">
                Belum punya akun? <a href="#">Daftar Akun</a>
            </p>

        </div>
    </main>

    {{-- ── FOOTER ──────────────────────────────────────────────── --}}
    <footer class="login-footer">
        Fasilita UNDIP &mdash; Sistem Reservasi &amp; Pelaporan Fasilitas Kampus Universitas Diponegoro &copy; 2026
    </footer>

    <script>
        function togglePassword() {
            const input   = document.getElementById('password');
            const eyeShow = document.getElementById('eye-show');
            const eyeHide = document.getElementById('eye-hide');
            const isHidden = input.type === 'password';
            input.type            = isHidden ? 'text'  : 'password';
            eyeShow.style.display = isHidden ? 'none'  : '';
            eyeHide.style.display = isHidden ? ''      : 'none';
        }
    </script>

</body>
</html>