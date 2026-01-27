@extends('layouts.auth')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Lupa Password' }} - Dinas ESDM Kalimantan Timur</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    <style>
        /* Reusing styles from login.blade.php for consistency */
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: "Inter", sans-serif; background-color: #f8f9fa; height: 100vh; overflow: hidden; }
        .login-container { display: flex; height: 100vh; width: 100%; }
        .login-form-section { flex: 1; padding: 40px; background-color: white; display: grid; place-items: center; position: relative; }
        
        .login-content { max-width: 440px; width: 100%; margin: 0 auto; }
        .login-title { font-size: 32px; font-weight: 700; color: #181d27; margin-bottom: 8px; font-family: "Patrick Hand", cursive; text-align: center; } /* Matching the sketch font vibe */
        .login-subtitle { font-size: 16px; color: #535862; margin-bottom: 32px; text-align: center; line-height: 1.5; }
        
        .form-label { display: block; font-size: 14px; font-weight: 600; color: #414651; margin-bottom: 8px; }
        .form-input { width: 100%; padding: 14px 16px; border: 2px solid #d5d7da; border-radius: 12px; font-size: 16px; transition: all 0.3s; }
        .form-input:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); }
        
        .login-button { width: 100%; padding: 14px; background: #059669; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-top: 24px; }
        .login-button:hover { background: #047857; }
        
        .back-link { display: block; text-align: center; margin-top: 24px; color: #535862; text-decoration: none; font-size: 14px; font-weight: 500; }
        .back-link:hover { color: #059669; }

        .note-box { border: 1px solid #d5d7da; border-radius: 12px; padding: 16px; margin-top: 32px; font-size: 13px; color: #535862; line-height: 1.5; }
        
        /* Error state */
        .is-invalid { border-color: #ef4444; }
        .invalid-feedback { color: #ef4444; font-size: 13px; margin-top: 4px; }

        /* Background section matching login */
        .login-image-section { flex: 1.2; background: #f0f2f5; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .login-image-section img { width: 100%; height: 100%; object-fit: cover; }
        @media (max-width: 1024px) { .login-image-section { display: none; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form-section">
            <div class="login-content">
                <h1 class="login-title">Lupa Password?</h1>
                <p class="login-subtitle">Masukkan email anda dan kami akan mengirimkan kode OTP untuk reset password</p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="email@example.com" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">Kirim Kode OTP</button>

                    <a href="{{ route('login') }}" class="back-link">&lt; Kembali ke Login</a>
                </form>

                <div class="note-box">
                    <strong>! Catatan</strong><br>
                    Kode OTP akan dikirim ke email yang terdaftar. Pastikan email yang anda masukkan sudah benar dan cek folder spam jika tidak menemukan email.
                </div>
            </div>
        </div>
        <div class="login-image-section">
             <img src="{{ asset('assets/bg.png') }}" alt="Background"> 
        </div>
    </div>
</body>
</html>
@endsection
