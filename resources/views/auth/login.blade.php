@extends('layouts.auth')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Login - Dinas ESDM Kalimantan Timur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" />
    <style>
        * { -webkit-font-smoothing: antialiased; box-sizing: border-box; }

        :root {
            --white: rgba(255, 255, 255, 1);
            --gray-900: rgba(24, 29, 39, 1);
            --gray-600: rgba(83, 88, 98, 1);
            --gray-700: rgba(65, 70, 81, 1);
            --gray-500: rgba(113, 118, 128, 1);
            --gray-300: rgba(213, 215, 218, 1);
            --primary: #071325;
            --primary-light: #3e79d1;
            --shadow-xs: 0px 1px 2px 0px rgba(10, 13, 18, 0.05);
            --shadow-md: 0px 4px 8px -2px rgba(10, 13, 18, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Inter", Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .login-container { display: flex; min-height: 100vh; width: 100%; }

        /* ⬇️ Bagian yang diubah untuk memusatkan konten */
        .login-form-section {
            flex: 1;
            position: relative;
            display: grid;                 /* pusatkan dengan grid */
            place-items: center;           /* horizontal & vertikal */
            padding: 32px;
            background-color: var(--white);
        }

        .login-image-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f2f5;
            overflow: hidden;
        }
        .login-image { width: 100%; height: 100%; object-fit: cover; }

        /* Header & footer diposisikan absolut agar form tetap di tengah */
        .header {
            position: absolute;
            top: 24px;
            left: 24px;
            right: 24px;
            display: flex;
            align-items: center;
        }

        .footer {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-size: 14px;
            color: var(--gray-600);
            width: calc(100% - 48px);
        }
        .footer a { color: var(--primary-light); text-decoration: none; }
        .footer a:hover { text-decoration: underline; }

        .logo-container { display: flex; align-items: center; gap: 16px; }
        .logo { width: 40px; height: 50px; }
        .logo-text { display: flex; flex-direction: column; }
        .logo-title { font-weight: 700; color: var(--gray-900); font-size: 16px; }
        .logo-subtitle { font-weight: 400; color: var(--gray-900); font-size: 14px; }

        .login-content {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;               /* hilangkan margin-top agar tepat di tengah */
            background: transparent;
        }

        .login-title { font-size: 32px; font-weight: 600; color: var(--gray-900); margin-bottom: 8px; text-align: left; }
        .login-subtitle { font-size: 16px; color: var(--gray-600); margin-bottom: 32px; text-align: left; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: var(--gray-700); margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 12px 14px; border: 1px solid var(--gray-300);
            border-radius: var(--border-radius); font-size: 16px;
            transition: var(--transition); background-color: var(--white);
            box-shadow: var(--shadow-xs);
        }
        .form-input:focus { outline: none; border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(62, 121, 209, 0.1); }

        .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .remember-me { display: flex; align-items: center; gap: 8px; }
        .checkbox {
            width: 16px; height: 16px; border-radius: 4px;
            border: 1px solid var(--gray-300); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .checkbox.checked { background-color: var(--primary); border-color: var(--primary); }
        .checkbox.checked::after { content: "✓"; color: white; font-size: 12px; }
        .remember-text { font-size: 14px; color: var(--gray-700); }
        .forgot-password { font-size: 14px; color: var(--primary-light); text-decoration: none; font-weight: 500; }
        .forgot-password:hover { text-decoration: underline; }

        .login-button {
            width: 100%; padding: 12px 18px; background-color: var(--primary);
            color: var(--white); border: none; border-radius: var(--border-radius);
            font-size: 16px; font-weight: 600; cursor: pointer;
            transition: var(--transition); box-shadow: var(--shadow-xs);
        }
        .login-button:hover { background-color: #0a1a33; box-shadow: var(--shadow-md); }
        .login-button:active { transform: translateY(1px); }

        @media (max-width: 1024px) {
            .login-container { flex-direction: column; }
            .login-image-section { display: none; }
            .header { left: 16px; right: 16px; }
            .footer { width: calc(100% - 32px); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form-section">
            <div class="header">
                <div class="logo-container">
                    <img class="logo" src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo Dinas ESDM" />
                    <div class="logo-text">
                        <div class="logo-title">BPKAD</div>
                        <div class="logo-subtitle">Provinsi Kalimantan Timur</div>
                    </div>
                </div>
            </div>

            <!-- ⬇️ Form akan berada tepat di tengah -->
            <div class="login-content">
                <h1 class="login-title">Masuk</h1>
                <p class="login-subtitle">Hallo, Silakan masuk untuk melanjutkan.</p>

                <form id="loginForm" method="POST" action="{{ route('login-post') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="name">Username</label>
                        <input class="form-input" type="text" id="name" name="name" placeholder="Masukkan username Anda" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Kata Sandi</label>
                        <input class="form-input" type="password" id="password" name="password" placeholder="••••••••" required />
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <div class="checkbox" id="remember-checkbox"></div>
                            <span class="remember-text">Ingat saya selama 30 hari</span>
                        </div>
                        {{-- <a href="#" class="forgot-password">Lupa kata sandi?</a> --}}
                    </div>

                    <button type="submit" class="login-button">Masuk Sekarang</button>
                </form>
            </div>

            <div class="footer">
                <p>© 2025 <a href="#">Dinas ESDM Kalimantan Timur</a></p>
            </div>
        </div>

        <div class="login-image-section">
            <img class="login-image" src="{{ asset('assets/media/bpkad.jpg') }}" alt="Background Kalimantan Timur" />
        </div>
    </div>

    {{-- SweetAlert untuk notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rememberCheckbox = document.getElementById('remember-checkbox');
            rememberCheckbox.addEventListener('click', function() {
                this.classList.toggle('checked');
            });

            @if(session('logout_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Logout Berhasil',
                    text: @json(session('logout_success')),
                    confirmButtonText: 'Ok',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
            @endif

            @if($errors->has('message') || session('login_error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: @json(session('login_error') ?? $errors->first('message')),
                    confirmButtonText: 'Tutup',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
            @endif
        });
    </script>
</body>
</html>
@endsection
