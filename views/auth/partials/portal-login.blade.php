<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <meta name="description" content="Masuk ke akun Fasilita FSM untuk reservasi dan pelaporan fasilitas Fakultas Sains dan Matematika.">
    <title>{{ $portalTitle }} — Fasilita FSM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="login-page">
    <div class="hero-watermark login-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    <main class="login-main">
        <h1 class="login-title">{{ $portalTitle }}</h1>

        <section class="login-card {{ $cardClass }}" aria-labelledby="login-title">
            <h2 id="login-title" class="sr-only">Masuk ke akun Fasilita</h2>

            @if ($errors->any())
                <div class="login-alert" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ $loginAction }}">
                @csrf

                <div class="login-field">
                    <label for="email">Email</label>
                    <div class="login-input-wrap">
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

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="login-input-wrap">
                        <input
                            id="password" name="password" type="password"
                            autocomplete="current-password" required
                            placeholder="Masukkan Password"
                            class="{{ $errors->has('password') ? 'has-error' : '' }}"
                        >
                        <button type="button" class="toggle-password" aria-label="Tampilkan password" aria-pressed="false" onclick="togglePassword()">
                            <img id="password-eye" src="{{ asset('images/auth/Show.svg') }}" alt="" aria-hidden="true">
                        </button>
                    </div>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <label class="login-remember-row" for="remember">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    Ingat Saya
                </label>

                <button type="submit" class="login-btn">
                    <img src="{{ asset('images/auth/Login.svg') }}" alt="" aria-hidden="true">
                    Masuk
                </button>
            </form>

            <div class="login-links">
                @if ($showRegistration)
                    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar Akun</a></p>
                @endif
                <a href="{{ $alternatePortalUrl }}">{{ $alternatePortalLabel }}</a>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eye = document.getElementById('password-eye');
            const toggle = document.querySelector('.toggle-password');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            eye.src = isHidden
                ? @js(asset('images/auth/Hide.svg'))
                : @js(asset('images/auth/Show.svg'));
            toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            toggle.setAttribute('aria-pressed', String(isHidden));
        }
    </script>
</body>
</html>
