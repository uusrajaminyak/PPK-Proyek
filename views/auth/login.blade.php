<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <meta name="description" content="Masuk ke akun Fasilita FSM untuk reservasi dan pelaporan fasilitas kampus.">
    <title>Masuk — Fasilita FSM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="login-page">
    <img
        class="login-watermark"
        src="{{ asset('images/landing/undip-blue-watermark.png') }}"
        alt=""
        aria-hidden="true"
    >

    <main class="login-main">
        <div class="login-content">
            <div class="login-brand-header">
                <img src="{{ asset('images/auth/fasilita-fsm-logo.png') }}" alt="Fasilita FSM — Sistem Reservasi & Pelaporan Fasilitas FSM UNDIP">
            </div>

            <section class="login-card" aria-label="Masuk ke akun">
                @if ($errors->any())
                    <div class="login-alert" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
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
                            <button
                                type="button"
                                class="toggle-password"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                data-show-icon="{{ asset('images/auth/Show.svg') }}"
                                data-hide-icon="{{ asset('images/auth/Hide.svg') }}"
                                style="--password-eye-icon: url('{{ asset('images/auth/Hide.svg') }}')"
                            >
                                <span id="password-visibility-icon" class="toggle-password-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <label class="login-remember-row" for="remember">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Ingat Saya</span>
                    </label>

                    <button type="submit" class="login-btn">
                        <img src="{{ asset('images/auth/Login.svg') }}" alt="" aria-hidden="true">
                        <span>Masuk</span>
                    </button>
                </form>

                <p class="login-register">Belum punya akun? <a href="#">Daftar Akun</a></p>
                <p class="login-portal-switch"><a href="#">Beralih ke portal staf/admin</a></p>
            </section>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.querySelector('.toggle-password');

        const updatePasswordToggleColor = () => {
            passwordToggle.classList.toggle('has-value', passwordInput.value.length > 0);
        };

        passwordInput.addEventListener('input', updatePasswordToggleColor);
        updatePasswordToggleColor();

        passwordToggle.addEventListener('click', () => {
            const shouldShowPassword = passwordInput.type === 'password';
            passwordInput.type = shouldShowPassword ? 'text' : 'password';
            passwordToggle.style.setProperty(
                '--password-eye-icon',
                `url("${shouldShowPassword ? passwordToggle.dataset.showIcon : passwordToggle.dataset.hideIcon}")`
            );
            passwordToggle.setAttribute('aria-pressed', String(shouldShowPassword));
            passwordToggle.setAttribute('aria-label', shouldShowPassword ? 'Sembunyikan password' : 'Tampilkan password');
        });

    </script>

</body>
</html>
