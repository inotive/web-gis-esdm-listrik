@extends('landing.layout.app')

@section('title', 'Menunggu Verifikasi')

@push('styles')
<style>
    .pending-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .pending-card {
        background: white;
        border-radius: 16px;
        padding: 48px;
        max-width: 600px;
        width: 100%;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .pending-icon {
        width: 80px;
        height: 80px;
        background: #FEF3C7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }

    .pending-icon i {
        font-size: 40px;
        color: #F59E0B;
    }

    .pending-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }

    .pending-message {
        font-size: 16px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 32px;
    }

    .pending-info {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 32px;
        text-align: left;
    }

    .pending-info-item {
        display: flex;
        align-items: start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .pending-info-item:last-child {
        margin-bottom: 0;
    }

    .pending-info-item i {
        color: #059669;
        font-size: 20px;
        margin-top: 2px;
    }

    .pending-info-text {
        font-size: 14px;
        color: #4B5563;
        line-height: 1.5;
    }

    .btn-logout {
        background: #EF4444;
        color: white;
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .btn-logout:hover {
        background: #DC2626;
        color: white;
    }

    @media (max-width: 640px) {
        .pending-card {
            padding: 32px 24px;
        }

        .pending-title {
            font-size: 20px;
        }

        .pending-message {
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="pending-container">
    <div class="pending-card">
        <div class="pending-icon">
            <i class="ri-time-line"></i>
        </div>

        <h1 class="pending-title">Akun Anda Belum Diverifikasi</h1>
        
        <p class="pending-message">
            Terima kasih telah mendaftar. Akun Anda sedang dalam proses verifikasi oleh administrator. 
            Anda akan mendapatkan akses penuh ke sistem setelah admin menyetujui pendaftaran Anda.
        </p>

        <div class="pending-info">
            <div class="pending-info-item">
                <i class="ri-checkbox-circle-line"></i>
                <div class="pending-info-text">
                    <strong>Proses Verifikasi:</strong> Administrator akan meninjau informasi akun Anda dan melakukan verifikasi dalam waktu 1-2 hari kerja.
                </div>
            </div>
            <div class="pending-info-item">
                <i class="ri-mail-line"></i>
                <div class="pending-info-text">
                    <strong>Notifikasi:</strong> Anda akan menerima pemberitahuan melalui email setelah akun Anda diverifikasi.
                </div>
            </div>
            <div class="pending-info-item">
                <i class="ri-customer-service-line"></i>
                <div class="pending-info-text">
                    <strong>Butuh Bantuan?</strong> Jika ada pertanyaan, silakan hubungi administrator melalui email atau telepon kantor.
                </div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="ri-logout-box-line"></i>
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection
