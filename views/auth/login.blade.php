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
                <div class="login-alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
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