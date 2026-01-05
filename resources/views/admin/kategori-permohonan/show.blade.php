@extends('admin.layouts.app')

@section('title', $title)

@push('styles')
    <style>
        /* Back Button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 16px;
        }

        .btn-back:hover {
            background: #E2E8F0;
            color: #1E293B;
        }

        /* Card Styles */
        .detail-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            padding: 24px;
            margin-bottom: 24px;
        }

        /* Header with Icon */
        .detail-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 20px;
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #0077B6 0%, #00B4D8 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            flex-shrink: 0;
        }

        .header-info h2 {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #ECFDF5;
            color: #059669;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
        }

        /* Grid Layout */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .detail-item {
            padding: 16px;
            background: #F8FAFC;
            border-radius: 10px;
        }

        .detail-item-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .detail-item-value {
            font-size: 14px;
            color: #1F2937;
            font-weight: 600;
        }

        /* Section Title */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        .section-title i {
            color: #0077B6;
        }

        .section-title .badge {
            background: #0077B6;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Question List */
        .question-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .question-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px;
            transition: all 0.2s;
        }

        .question-card:hover {
            border-color: #0077B6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .q-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .q-number {
            width: 28px;
            height: 28px;
            background: #F0F9FF;
            color: #0077B6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .q-text {
            font-size: 15px;
            color: #1E293B;
            font-weight: 600;
            line-height: 1.5;
        }

        .q-tags {
            display: flex;
            gap: 8px;
        }

        .tag-type {
            padding: 4px 10px;
            background: #F1F5F9;
            color: #64748B;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .tag-required {
            padding: 4px 10px;
            background: #FEF2F2;
            color: #EF4444;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Options */
        .q-options {
            padding-left: 44px;
        }

        .option-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 13px;
            color: #475569;
        }

        .option-dot {
            width: 6px;
            height: 6px;
            background: #CBD5E1;
            border-radius: 50%;
        }

        .option-note {
            color: #94A3B8;
            font-style: italic;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .q-options {
                padding-left: 0;
                margin-top: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.kategori-permohonan.index') }}" class="btn-back">
        <i class="ri-arrow-left-line"></i>
        Kembali ke Daftar Kategori
    </a>

    <div class="page-head">
        <div>
            <div class="page-meta">Detail Kategori Permohonan</div>
            <div class="page-title">{{ $permohonan->nama }}</div>
        </div>
        {{-- <div class="page-actions">
            <a href="{{ route('admin.kategori-permohonan.edit', $permohonan) }}" class="btn btn-primary">
                <i class="ri-edit-line"></i>
                Edit Kategori
            </a>
        </div> --}}
    </div>

    <!-- Basic Info Card -->
    <div class="detail-card">
        <div class="detail-header">
            <div class="header-icon">
                <i class="ri-file-list-3-line"></i>
            </div>
            <div class="header-info">
                <h2>{{ $permohonan->nama }}</h2>
                <span class="status-badge">
                    <i class="ri-checkbox-circle-fill"></i>
                    {{ $permohonan->jenis_permohonan }}
                </span>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-item-label">Nama Kategori</div>
                <div class="detail-item-value">{{ $permohonan->nama }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Jenis Permohonan</div>
                <div class="detail-item-value">{{ $permohonan->jenis_permohonan }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Jumlah Pertanyaan</div>
                <div class="detail-item-value">{{ $permohonan->questions->count() }} Item</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Keterangan</div>
                <div class="detail-item-value">{{ $permohonan->keterangan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Questions Section -->
    <div class="section-title">
        <i class="ri-question-answer-line"></i>
        <span>Daftar Pertanyaan</span>
        <span class="badge">{{ $permohonan->questions->count() }}</span>
    </div>

    @if ($permohonan->questions->isEmpty())
        <div class="detail-card text-center py-5">
            <i class="ri-question-line" style="font-size: 48px; color: #E2E8F0; margin-bottom: 16px;"></i>
            <p class="text-muted mb-0">Belum ada pertanyaan yang dikonfigurasi.</p>
        </div>
    @else
        <div class="question-list">
            @foreach ($permohonan->questions as $question)
                <div class="question-card">
                    <div class="q-header">
                        <div class="d-flex gap-3">
                            <div class="q-number">{{ $question->urutan }}</div>
                            <div>
                                <div class="q-text">{{ $question->pertanyaan }}</div>
                            </div>
                        </div>
                        <div class="q-tags">
                            <span class="tag-type">{{ $question->tipe }}</span>
                            @if ($question->wajib)
                                <span class="tag-required">Wajib</span>
                            @endif
                        </div>
                    </div>

                    @if ($question->options->isNotEmpty())
                        <div class="q-options">
                            @foreach ($question->options as $option)
                                <div class="option-item">
                                    <div class="option-dot"></div>
                                    <span>{{ $option->opsi }}</span>
                                    @if ($option->keterangan)
                                        <span class="option-note">({{ $option->keterangan }})</span>
                                    @endif
                                    @if ($option->wajib)
                                        <span class="text-danger" title="Wajib dipilih">*</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <!-- Empty script stack just in case -->
@endpush
