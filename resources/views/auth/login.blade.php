@extends('layouts.auth')

@section('content')
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <title>Login - Dinas ESDM Kalimantan Timur</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            * {
                -webkit-font-smoothing: antialiased;
                box-sizing: border-box;
            }

            :root {
                --white: rgba(255, 255, 255, 1);
                --gray-900: rgba(24, 29, 39, 1);
                --gray-600: rgba(83, 88, 98, 1);
                --gray-700: rgba(65, 70, 81, 1);
                --gray-500: rgba(113, 118, 128, 1);
                --gray-300: rgba(213, 215, 218, 1);
                --gray-100: #F3F4F6;
                --primary: #059669;
                --primary-dark: #047857;
                --primary-light: #10B981;
                --primary-bg: rgba(5, 150, 105, 0.08);
                --shadow-xs: 0px 1px 2px 0px rgba(10, 13, 18, 0.05);
                --shadow-md: 0px 4px 8px -2px rgba(10, 13, 18, 0.1);
                --shadow-lg: 0px 12px 24px -4px rgba(10, 13, 18, 0.12);
                --border-radius: 12px;
                --transition: all 0.3s ease;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                height: 100%;
                font-family: "Inter", Helvetica, Arial, sans-serif;
                background-color: #f8f9fa;
                overflow: hidden;
            }

            .login-container {
                display: flex;
                height: 100vh;
                width: 100%;
                overflow: hidden;
            }

            .login-form-section {
                flex: 1;
                position: relative;
                display: grid;
                place-items: center;
                padding: 40px;
                background-color: var(--white);
                overflow: hidden;
            }

            .login-image-section {
                flex: 1.2;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f0f2f5;
                overflow: hidden;
                position: relative;
            }

            .login-image-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
                z-index: 1;
            }

            .login-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .header {
                position: absolute;
                top: 32px;
                left: 40px;
                right: 40px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .footer {
                position: absolute;
                bottom: 32px;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
                font-size: 14px;
                color: var(--gray-500);
                width: calc(100% - 80px);
            }

            .footer a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 500;
            }

            .footer a:hover {
                text-decoration: underline;
            }

            .logo-container {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .logo {
                width: 48px;
                height: 58px;
            }

            .logo-text {
                display: flex;
                flex-direction: column;
            }

            .logo-title {
                font-weight: 700;
                color: var(--gray-900);
                font-size: 15px;
                max-width: 220px;
                line-height: 1.3;
            }

            .logo-subtitle {
                font-weight: 500;
                color: var(--gray-600);
                font-size: 13px;
                margin-top: 2px;
            }

            .login-content {
                max-width: 440px;
                width: 100%;
                margin: 0 auto;
                background: transparent;
            }

            .login-title {
                font-size: 36px;
                font-weight: 700;
                color: var(--gray-900);
                margin-bottom: 8px;
                text-align: left;
            }

            .login-subtitle {
                font-size: 16px;
                color: var(--gray-600);
                margin-bottom: 40px;
                text-align: left;
                line-height: 1.5;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .form-label {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: var(--gray-700);
                margin-bottom: 8px;
            }

            .password-wrapper {
                position: relative;
            }

            .password-toggle {
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: var(--gray-500);
                font-size: 20px;
                display: grid;
                place-items: center;
                transition: var(--transition);
            }

            .password-toggle:hover {
                color: var(--primary);
            }

            .form-input {
                width: 100%;
                padding: 16px 18px;
                border: 2px solid var(--gray-300);
                border-radius: var(--border-radius);
                font-size: 16px;
                transition: var(--transition);
                background-color: var(--white);
            }

            .form-input::placeholder {
                color: var(--gray-500);
            }

            .form-input:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 4px var(--primary-bg);
            }

            input[type="password"],
            input.password-text-shown {
                padding-right: 50px;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 32px;
            }

            .remember-me {
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
            }

            .checkbox {
                width: 20px;
                height: 20px;
                border-radius: 6px;
                border: 2px solid var(--gray-300);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: var(--transition);
            }

            .checkbox:hover {
                border-color: var(--primary);
            }

            .checkbox.checked {
                background-color: var(--primary);
                border-color: var(--primary);
            }

            .checkbox.checked::after {
                content: "✓";
                color: white;
                font-size: 13px;
                font-weight: 600;
            }

            .remember-text {
                font-size: 14px;
                color: var(--gray-700);
                font-weight: 500;
            }

            .forgot-password {
                font-size: 14px;
                color: var(--primary);
                text-decoration: none;
                font-weight: 600;
            }

            .forgot-password:hover {
                text-decoration: underline;
            }

            .login-button {
                width: 100%;
                padding: 16px 24px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                color: white;
                border: none;
                border-radius: var(--border-radius);
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: var(--transition);
                box-shadow: 0 4px 14px 0 rgba(5, 150, 105, 0.35);
            }

            .login-button:hover {
                background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
                box-shadow: 0 6px 20px 0 rgba(5, 150, 105, 0.45);
                transform: translateY(-2px);
            }

            .login-button:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px 0 rgba(5, 150, 105, 0.3);
            }

            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                margin-top: 24px;
                color: var(--gray-600);
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
                transition: var(--transition);
            }

            .back-link:hover {
                color: var(--primary);
            }

            .back-link i {
                font-size: 18px;
            }

            /* Decorative elements */
            .decoration {
                position: absolute;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: var(--primary-bg);
                z-index: 0;
            }

            .decoration-1 {
                top: -60px;
                right: -60px;
            }

            .decoration-2 {
                bottom: -80px;
                left: -80px;
                width: 160px;
                height: 160px;
            }

            @media (max-width: 1024px) {
                .login-container {
                    flex-direction: column;
                }

                .login-image-section {
                    display: none;
                }

                .login-form-section {
                    padding: 24px;
                }

                .header {
                    left: 24px;
                    right: 24px;
                    top: 24px;
                }

                .footer {
                    width: calc(100% - 48px);
                    bottom: 24px;
                }

                .login-content {
                    max-width: 100%;
                }

                .login-title {
                    font-size: 28px;
                }

                .logo-title {
                    font-size: 13px;
                }

                .logo {
                    width: 40px;
                    height: 50px;
                }
            }

            .login-link {
                text-align: center;
                margin-top: 24px;
                font-size: 15px;
                color: var(--gray-600);
            }

            .login-link a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 600;
            }

            .login-link a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="login-container">
            <div class="login-form-section">
                <!-- Decorative circles -->
                <div class="decoration decoration-1"></div>
                <div class="decoration decoration-2"></div>

                <div class="header">
                    <div class="logo-container">
                        <img class="logo" src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo Dinas ESDM" />
                        <div class="logo-text">
                            <div class="logo-title">Dinas Energi dan Sumber Daya Mineral</div>
                            <div class="logo-subtitle">Provinsi Kalimantan Timur</div>
                        </div>
                    </div>
                </div>

                <div class="login-content">
                    <h1 class="login-title">Selamat Datang</h1>
                    <p class="login-subtitle">Silakan masuk ke akun Anda untuk melanjutkan ke dashboard sistem.</p>

                    <form id="loginForm" method="POST" action="{{ route('login-post') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">Username</label>
                            <input class="form-input" type="text" id="name" name="name" placeholder="Masukkan username Anda"
                                required />
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <div class="password-wrapper">
                                <input class="form-input" type="password" id="password" name="password"
                                    placeholder="Masukkan kata sandi Anda" required />
                                <div class="password-toggle" id="togglePassword">
                                    <i class="ri-eye-off-line"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="remember-me" onclick="toggleRemember()">
                                <div class="checkbox" id="remember-checkbox"></div>
                                <span class="remember-text">Ingat saya</span>
                            </div>
                            <a href="{{ route('password.request') }}" class="forgot-password">Lupa Password?</a>
                        </div>

                        <button type="submit" class="login-button">
                            <span>Masuk Sekarang</span>
                        </button>

                        <div class="login-link">
                            Belum memiliki akun? <a href="{{ route('register') }}">Daftar</a>
                        </div>

                        <div style="text-align: center;">
                            <a href="{{ url('/') }}" class="back-link">
                                <i class="ri-arrow-left-line"></i>
                                <span>Kembali ke Beranda</span>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="footer">
                    <p>© 2025 <a href="#">Dinas ESDM Kalimantan Timur</a></p>
                </div>
            </div>

            <div class="login-image-section">
                <img class="login-image" src="{{ asset('assets/bg.png') }}" alt="Background Kalimantan Timur" />
            </div>
        </div>

        {{-- SweetAlert untuk notifikasi --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function toggleRemember() {
                const checkbox = document.getElementById('remember-checkbox');
                checkbox.classList.toggle('checked');
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Password Toggle
                const togglePassword = document.getElementById('togglePassword');
                const passwordInput = document.getElementById('password');
                const icon = togglePassword.querySelector('i');

                togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    if (type === 'text') {
                        icon.classList.remove('ri-eye-off-line');
                        icon.classList.add('ri-eye-line');
                        passwordInput.classList.add('password-text-shown');
                    } else {
                        icon.classList.remove('ri-eye-line');
                        icon.classList.add('ri-eye-off-line');
                        passwordInput.classList.remove('password-text-shown');
                    }
                });

                @if(session('logout_success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Logout Berhasil',
                        text: @json(session('logout_success')),
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#059669'
                    });
                @endif

                @if($errors->has('message') || session('login_error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: @json(session('login_error') ?? $errors->first('message')),
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#059669'
                    });
                @endif
                    });
        </script>
    </body>

    </html>
@endsection