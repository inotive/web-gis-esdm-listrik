@extends('layouts.auth')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Verifikasi OTP' }} - Dinas ESDM Kalimantan Timur</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: "Inter", sans-serif; background-color: #f8f9fa; height: 100vh; overflow: hidden; }
        .login-container { display: flex; height: 100vh; width: 100%; }
        .login-form-section { flex: 1; padding: 40px; background-color: white; display: grid; place-items: center; }
        .login-content { max-width: 440px; width: 100%; margin: 0 auto; text-align: center; }
        .login-title { font-size: 32px; font-weight: 700; color: #181d27; margin-bottom: 8px; font-family: "Patrick Hand", cursive; }
        .login-subtitle { font-size: 16px; color: #535862; margin-bottom: 24px; line-height: 1.5; }
        
        .otp-display { 
            border: 2px solid #535862; border-radius: 8px; padding: 10px; margin-bottom: 24px; display: inline-block; width: 100%; font-family: "Patrick Hand", cursive; font-size: 18px; color: #181d27;
        }

        .otp-inputs { display: flex; gap: 10px; justify-content: center; margin-bottom: 24px; }
        .otp-input { width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #d5d7da; border-radius: 8px; font-weight: bold; }
        .otp-input:focus { border-color: #059669; outline: none; }
        /* Fallback single input if JS fails */
        .otp-input-single { width: 100%; height: 50px; text-align: center; font-size: 24px; border: 2px solid #d5d7da; border-radius: 8px; font-weight: bold; letter-spacing: 10px; }
        .otp-input-single:focus { border-color: #059669; outline: none; }

        .btn-verify { width: 100%; padding: 14px; background: white; border: 2px solid #181d27; color: #181d27; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-bottom: 24px; }
        .btn-verify:hover { background: #f0f2f5; }

        .btn-resend { background: none; border: 1px solid #535862; border-radius: 8px; padding: 8px 16px; cursor: pointer; font-size: 14px; color: #535862; margin-top: 10px; }
        .btn-resend:hover { background: #f0f2f5; color: #181d27; }

        .success-text { color: #059669; font-size: 14px; margin-bottom: 20px; }
        .error-text { color: #ef4444; font-size: 14px; margin-bottom: 20px; }
        .back-link { display: block; margin-top: 24px; color: #535862; text-decoration: none; font-size: 14px; }
        
        .login-image-section { flex: 1.2; background: #f0f2f5; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .login-image-section img { width: 100%; height: 100%; object-fit: cover; }
        @media (max-width: 1024px) { .login-image-section { display: none; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form-section">
            <div class="login-content">
                <h1 class="login-title">Verifikasi OTP</h1>
                <p class="login-subtitle">Masukkan 6 digit kode OTP yang telah dikirim ke email Anda</p>
                
                @if(session('status'))
                    <div class="success-text">! {{ session('status') }}</div>
                @else
                    <div class="success-text">! Kode OTP telah dikirim ke email anda, Silakan cek inbox atau folder spam.</div>
                @endif

                <div class="otp-display">{{ $email }}</div>

                <form method="POST" action="{{ route('password.verify-otp') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="form-group">
                        <input type="text" name="otp" class="otp-input-single @error('otp') is-invalid @enderror" maxlength="6" pattern="\d*" placeholder="000000" required autofocus autocomplete="off">
                        @error('otp')
                            <div class="error-text" style="margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-verify">Verifikasi OTP</button>
                </form>

                <p style="font-size: 14px; color: #535862;">Tidak Menerima Kode?</p>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="btn-resend">Kirim Ulang OTP</button>
                </form>

                <a href="{{ route('login') }}" class="back-link">&lt; Kembali ke Login</a>
            </div>
        </div>
        <div class="login-image-section">
             <img src="{{ asset('assets/bg.png') }}" alt="Background"> 
        </div>
    </div>
</body>
</html>
@endsection
