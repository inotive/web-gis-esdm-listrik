@extends('layouts.auth')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Verifikasi Email - Dinas ESDM Kalimantan Timur</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { -webkit-font-smoothing: antialiased; box-sizing: border-box; }
        :root {
            --white: #ffffff;
            --gray-900: #111827;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-500: #6B7280;
            --gray-300: #D1D5DB;
            --gray-200: #E5E7EB;
            --gray-100: #F3F4F6;
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10B981;
            --primary-bg: rgba(5, 150, 105, 0.08);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }
        html, body {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff; overflow: hidden;
        }
        .login-container { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        .login-form-section {
            flex: 1; position: relative; display: grid; place-items: center;
            padding: 40px; background-color: var(--white); overflow-y: auto;
        }
        .login-image-section {
            flex: 1.2; display: flex; align-items: center; justify-content: center;
            background-color: #f0f2f5; overflow: hidden; position: relative;
        }
        .login-image-section img { width: 100%; height: 100%; object-fit: cover; }
        
        .header { position: absolute; top: 32px; left: 40px; right: 40px; display: flex; align-items: center; justify-content: space-between; }
        .logo-container { display: flex; align-items: center; gap: 16px; }
        .logo { width: 48px; height: 58px; }
        .logo-text { display: flex; flex-direction: column; }
        .logo-title { font-weight: 700; color: var(--gray-900); font-size: 15px; max-width: 220px; line-height: 1.3; }
        .logo-subtitle { font-weight: 500; color: var(--gray-600); font-size: 13px; margin-top: 2px; }
        
        .footer { position: absolute; bottom: 32px; width: 100%; text-align: center; color: var(--gray-500); font-size: 14px; }
        .footer a { color: var(--primary); text-decoration: none; font-weight: 500; }

        .login-content { max-width: 480px; width: 100%; margin: 0 auto; padding-top: 80px; padding-bottom: 60px; }
        
        .verify-title { font-size: 32px; font-weight: 700; color: var(--gray-900); margin-bottom: 12px; text-align: center; letter-spacing: -0.025em; }
        .verify-text { color: var(--gray-600); font-size: 16px; margin-bottom: 32px; text-align: center; line-height: 1.6; }
        
        .email-box {
            background: var(--gray-100); border: 1px solid var(--gray-300); border-radius: 12px;
            padding: 16px; text-align: center; font-size: 18px; font-weight: 600;
            color: var(--gray-900); margin-bottom: 32px;
        }
        
        .steps-container { background: var(--white); border: 1px solid var(--gray-200); border-radius: 16px; padding: 24px; margin-bottom: 32px; box-shadow: var(--shadow-sm); }
        .steps-title { font-weight: 700; color: var(--gray-900); margin-bottom: 16px; font-size: 15px; }
        .steps-list { padding-left: 0; list-style: none; margin: 0; counter-reset: step-counter; }
        .steps-list li { position: relative; padding-left: 36px; margin-bottom: 12px; color: var(--gray-600); font-size: 14px; line-height: 1.5; }
        .steps-list li:last-child { margin-bottom: 0; }
        .steps-list li::before {
            counter-increment: step-counter; content: counter(step-counter);
            position: absolute; left: 0; top: 0; width: 24px; height: 24px;
            background: var(--primary-bg); color: var(--primary);
            border-radius: 50%; font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        .btn-resend {
            width: 100%; padding: 16px 24px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white; border: none; border-radius: 12px;
            font-size: 16px; font-weight: 600; cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 14px 0 rgba(5, 150, 105, 0.35);
        }
        .btn-resend:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 6px 20px 0 rgba(5, 150, 105, 0.45);
            transform: translateY(-2px);
        }

        .back-link { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 24px; color: var(--gray-600); text-decoration: none; font-size: 14px; font-weight: 500; transition: var(--transition); }
        .back-link:hover { color: var(--primary); }

        .decoration { position: absolute; width: 200px; height: 200px; border-radius: 50%; background: var(--primary-bg); z-index: 0; pointer-events: none; }
        .decoration-1 { top: -60px; right: -60px; }
        .decoration-2 { bottom: -80px; left: -80px; width: 160px; height: 160px; }
        
        @media (max-width: 1024px) {
            .login-container { flex-direction: column; overflow-y: auto; }
            .login-image-section { display: none; }
            .login-form-section { padding: 24px; overflow: visible; }
            .header { position: relative; top: 0; left: 0; right: 0; margin-bottom: 40px; }
            .footer { position: relative; bottom: 0; margin-top: 40px; width: 100%; left: 0; transform: none; }
            .login-content { padding-top: 0; padding-bottom: 0; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Section -->
        <div class="login-form-section">
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
                <h1 class="verify-title">Verifikasi Email Anda</h1>
                <p class="verify-text">
                    Terima kasih telah mendaftar di Data ESDM Kaltim!<br>
                    Kami telah mengirimkan link verifikasi ke alamat email Anda.
                </p>

                <div class="email-box">
                    {{ auth()->user()->email ?? 'email@example.com' }}
                </div>

                <div class="steps-container">
                    <div class="steps-title">Langkah Verifikasi:</div>
                    <ol class="steps-list">
                        <li>Buka inbox email Anda di <strong>{{ auth()->user()->email ?? '' }}</strong></li>
                        <li>Cari email dari <strong>dataesdmkaltim</strong></li>
                        <li>Klik tombol "Verifikasi Email" dalam email tersebut</li>
                        <li>Anda akan diarahkan kembali dan dapat mengakses dashboard</li>
                    </ol>
                </div>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn-resend">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="back-link">
                    <i class="ri-arrow-left-line"></i> Kembali ke Login
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

             <div class="footer">
                <p>© 2025 <a href="#">Dinas ESDM Kalimantan Timur</a></p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="login-image-section">
            <img src="{{ asset('assets/bg.png') }}" alt="Background Kalimantan Timur" />
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('resent'))
                Swal.fire({
                    icon: 'success',
                    title: 'Terkirim!',
                    text: 'Link verifikasi baru telah dikirim ke alamat email Anda.',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#059669'
                });
            @endif
        });
    </script>
</body>
</html>
@endsection
