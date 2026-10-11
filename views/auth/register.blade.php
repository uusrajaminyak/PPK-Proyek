<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f7f8">
    <meta name="description" content="Daftar akun Fasilita FSM untuk reservasi dan pelaporan fasilitas Fakultas Sains dan Matematika.">
    <title>Daftar Akun — Fasilita FSM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
    <style>
        .register-page {
            overflow: hidden;
        }

        .register-page .site-footer {
            flex-shrink: 0;
        }

        .register-page .login-main {
            justify-content: flex-start;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .register-page .login-title {
            margin-bottom: 0.6rem;
        }

        .register-page .register-card {
            padding: 1rem 1.25rem;
        }

        .register-page .register-field {
            margin-bottom: 0.65rem;
        }

        .register-page .register-input-wrap input {
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
        }

        .register-page .register-btn {
            margin-top: 0.7rem;
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }

        .register-page .login-links {
            margin-top: 0.65rem;
        }
    </style>
</head>
<body class="login-page register-page">
    <div class="hero-watermark login-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    <main class="login-main">
        <h1 class="login-title">Daftar Akun</h1>

        <section class="register-card" aria-labelledby="register-title">
            <h2 id="register-title" class="sr-only">Daftar akun Fasilita</h2>

            @if ($errors->any())
                <div class="login-alert" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                    <div class="register-field">
                    <label for="email">Email</label>
                    <div class="register-input-wrap">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email"
                            class="{{ $errors->has('email') ? 'has-error' : '' }}"
                        >
                    </div>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="register-field">
                    <label for="password">Password</label>
                    <div class="register-input-wrap">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            placeholder="Masukkan Password"
                            class="with-toggle {{ $errors->has('password') ? 'has-error' : '' }}"
                        >
                        <button
                            type="button"
                            class="toggle-password"
                            aria-label="Tampilkan atau sembunyikan password"
                            onclick="toggleVisibility('password', 'eye-show-pass', 'eye-hide-pass')"
                        >
                            <svg id="eye-show-pass" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-hide-pass" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="register-field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="register-input-wrap">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            placeholder="Konfirmasi Password"
                            class="with-toggle {{ $errors->has('password_confirmation') ? 'has-error' : '' }}"
                        >
                        <button
                            type="button"
                            class="toggle-password"
                            aria-label="Tampilkan atau sembunyikan konfirmasi password"
                            onclick="toggleVisibility('password_confirmation', 'eye-show-confirm', 'eye-hide-confirm')"
                        >
                            <svg id="eye-show-confirm" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-hide-confirm" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="register-btn">Buat Akun</button>
            </form>
            <div class="login-links">
                <a href="{{ route('login') }}">Kembali ke Login</a>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <script>
        function toggleVisibility(inputId, showIconId, hideIconId) {
            const input = document.getElementById(inputId);
            const eyeShow = document.getElementById(showIconId);
            const eyeHide = document.getElementById(hideIconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeShow.style.display = isHidden ? '' : 'none';
            eyeHide.style.display = isHidden ? 'none' : '';
        }
    </script>

</body>
</html>
