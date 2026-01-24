@extends('admin.layouts.app')

@section('title', 'Pilih Jenis Permohonan')

@push('styles')
    <style>
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .permohonan-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: block;
        }

        .permohonan-card:hover {
            border-color: var(--accent-2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .permohonan-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--accent-2) 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .permohonan-icon i {
            font-size: 24px;
            color: #fff;
        }

        .permohonan-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .permohonan-description {
            font-size: 14px;
            color: #64748B;
            line-height: 1.6;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748B;
        }

        .empty-state i {
            font-size: 64px;
            color: #CBD5E1;
            margin-bottom: 16px;
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Pilih Jenis Permohonan</div>
        </div>
    </div>

    @if ($permohonans->count() > 0)
        <div class="card-grid">
            @foreach ($permohonans as $permohonan)
                <a href="{{ route('admin.pengajuan-permohonan.create', ['permohonan_id' => $permohonan->id]) }}"
                    class="permohonan-card">
                    <div class="permohonan-icon">
                        <i class="ri-file-text-line"></i>
                    </div>
                    <div class="permohonan-title">{{ $permohonan->nama }}</div>
                    <div class="permohonan-description">
                        @if ($permohonan->keterangan)
                            {{ $permohonan->keterangan }}
                        @else
                            Klik untuk mengajukan permohonan {{ strtolower($permohonan->nama) }}
                        @endif
                    </div>
                    @if ($permohonan->questions_count > 0)
                        <div style="margin-top: 12px; font-size: 12px; color: #64748B;">
                            <i class="ri-question-line"></i> {{ $permohonan->questions_count }} pertanyaan
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="ri-file-list-3-line"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                Tidak Ada Jenis Permohonan
            </h3>
            <p>Belum ada jenis permohonan yang tersedia untuk Anda saat ini.</p>
        </div>
    @endif
@endsection
