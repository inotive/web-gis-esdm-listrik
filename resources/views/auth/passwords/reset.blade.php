@extends('layouts.auth')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Buat Password Baru' }} - Dinas ESDM Kalimantan Timur</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; height: 100vh; overflow: hidden; }
        .login-container { display: flex; height: 100vh; width: 100%; }
        .login-form-section { flex: 1; padding: 40px; background-color: white; display: grid; place-items: center; }
        .login-content { max-width: 440px; width: 100%; margin: 0 auto; }
        .login-title { font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 8px; text-align: center; letter-spacing: -0.025em; }
        .login-subtitle { font-size: 16px; color: #535862; margin-bottom: 32px; text-align: center; line-height: 1.5; }
        
        .success-text { color: #059669; font-size: 14px; margin-bottom: 30px; text-align: center; }

        .form-label { display: block; font-size: 14px; font-weight: 600; color: #414651; margin-bottom: 8px; }
        .form-input { width: 100%; padding: 14px 16px; border: 2px solid #d5d7da; border-radius: 12px; font-size: 16px; transition: all 0.3s; }
        .form-input:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); }
        
        .password-wrapper { position: relative; margin-bottom: 20px; }
        .password-toggle { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #535862; font-size: 20px; display: grid; place-items: center; transition: all 0.3s; }
        .password-toggle:hover { color: #059669; }

        .login-button { width: 100%; padding: 16px 24px; background: linear-gradient(135deg, #059669 0%, #10B981 100%); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-top: 10px; box-shadow: 0 4px 14px 0 rgba(5, 150, 105, 0.35); }
        .login-button:hover { background: linear-gradient(135deg, #047857 0%, #059669 100%); transform: translateY(-2px); box-shadow: 0 6px 20px 0 rgba(5, 150, 105, 0.45); }
        
        .is-invalid { border-color: #ef4444; }
        .invalid-feedback { color: #ef4444; font-size: 13px; margin-top: 4px; }

        .login-image-section { flex: 1.2; background: #f0f2f5; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .login-image-section img { width: 100%; height: 100%; object-fit: cover; }
        @media (max-width: 1024px) { .login-image-section { display: none; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form-section">
            <div class="login-content">
                <h1 class="login-title">Buat Password Baru</h1>
                <p class="login-subtitle">Masukkan password baru Anda untuk mengamankan akun</p>

                <div class="success-text">✓ Kode OTP berhasil diverifikasi. Silakan buat password baru.</div>

                <form method="POST" action="{{ route('password.update-new') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-input @error('password') is-invalid @enderror" placeholder="Masukkan Password Baru" required autofocus>
                            <div class="password-toggle" onclick="togglePassword('password')"><i class="ri-eye-off-line"></i></div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirm" class="form-input" placeholder="Masukkan Password Baru" required>
                            <div class="password-toggle" onclick="togglePassword('password_confirm')"><i class="ri-eye-off-line"></i></div>
                        </div>
                    </div>

                    <button type="submit" class="login-button">Reset Password</button>
                </form>
            </div>
        </div>
        <div class="login-image-section">
             <img src="{{ asset('assets/bg.png') }}" alt="Background"> 
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ $errors->first() }}",
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#059669'
                });
            @endif

            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('status') }}",
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#059669'
                });
            @endif
        });
    </script>
</body>
</html>
@endsection
