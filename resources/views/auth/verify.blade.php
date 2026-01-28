@extends('layouts.auth')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Verifikasi Email - Dinas ESDM Kalimantan Timur</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: "Inter", sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            text-align: center;
            border: 1px solid #e5e7eb;
            position: relative;
        }
        .verify-title {
            font-family: "Patrick Hand", cursive; /* Closest to the image's handwritten style */
            font-size: 32px;
            color: #1f2937;
            margin-bottom: 24px;
        }
        .verify-text {
            color: #4b5563;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .email-box {
            border: 2px solid #6b7280;
            border-radius: 12px;
            padding: 12px 24px;
            display: inline-block;
            font-size: 18px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 30px;
            font-family: "Patrick Hand", cursive;
        }
        .steps-container {
            text-align: left;
            margin-bottom: 30px;
            display: inline-block;
            max-width: 100%;
        }
        .steps-label {
            text-align: center;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 10px;
        }
        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }
        .btn-resend {
            background: white;
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: 12px 32px;
            font-size: 15px;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            max-width: 400px;
            font-weight: 500;
        }
        .btn-resend:hover {
            background-color: #f9fafb;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            color: #111827;
        }
        .footer-note {
            margin-top: 40px;
            text-align: left;
            font-size: 13px;
            color: #6b7280;
        }
        .footer-list {
            margin-top: 5px;
            padding-left: 20px;
            line-height: 1.5;
        }
        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <h1 class="verify-title">Verifikasi Email Anda</h1>
        
        @if (session('resent'))
            <div class="alert-success" role="alert">
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <p class="verify-text">
            Terima kasih telah mendaftar di Data ESDM Kaltim!<br>
            Kami telah mengirimkan link verifikasi ke alamat email Anda.
        </p>

        <div class="email-box">
            {{ auth()->user()->email }}
        </div>

        <div class="steps-container">
            <div class="steps-label">Langkah Verifikasi:</div>
            <ol class="steps-list">
                <li>1. Buka inbox email Anda di <strong>{{ auth()->user()->email }}</strong></li>
                <li>2. Cari email dari <strong>dataesdmkaltim</strong></li>
                <li>3. Klik tombol "Verifikasi Email" dalam email tersebut</li>
                <li>4. Anda akan diarahkan kembali dan dapat mengakses dashboard</li>
            </ol>
        </div>

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-resend">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="back-link">
            &lt; Kembali ke Login
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <div class="footer-note">
            <strong>Tidak menerima email?</strong>
            <ul class="footer-list">
                <li>Periksa folder Spam/Junk Anda</li>
                <li>Pastikan email <strong>{{ auth()->user()->email }}</strong> benar</li>
                <li>Klik tombol "Kirim Ulang" di atas</li>
            </ul>
        </div>
    </div>
</body>
</html>
@endsection
