@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Detail Permohonan')

@push('styles')
    <style>
        .card {
            border: 1px solid #F1F1F4;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-top: 18px;
            background: #fff;
        }

        .card-header {
            padding-bottom: 16px;
            border-bottom: 1px solid #F1F1F4;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        .info-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #F1F1F4;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #64748B;
            font-size: 14px;
        }

        .info-value {
            color: #111827;
            font-size: 14px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-aktif {
            background: #ECFDF5;
            color: #059669;
        }

        .status-menunggu {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-proses {
            background: #E0F2FE;
            color: #0284C7;
        }

        .status-expired {
            background: #FEE2E2;
            color: #DC2626;
        }

        .status-ditolak {
            background: #FEE2E2;
            color: #DC2626;
        }

        /* Custom Colors */
        .status-dibatalkan {
            background: #cdced1ff !important;
            color: #494848ff !important;
        }

        .status-kedaluwarsa {
            background: #FEF3C7 !important;
            color: #B45309 !important;
        }

        .question-item {
            background: #FCFCFD;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .question-number {
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            margin-bottom: 12px;
        }

        .answer-value {
            color: #374151;
            font-size: 14px;
            line-height: 1.6;
        }

        .answer-value ul {
            margin: 8px 0;
            padding-left: 20px;
        }

        .document-item {
            background: #FCFCFD;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .document-info {
            flex: 1;
        }

        .document-name {
            font-weight: 600;
            color: #111827;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .document-meta {
            font-size: 12px;
            color: #64748B;
        }

        .btn-download {
            padding: 8px 16px;
            background: #3B82F6;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.18s ease;
        }

        .btn-download:hover {
            background: #2563EB;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            background: #fff;
            color: #64748B;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.18s ease;
        }

        .btn-back:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 24px 0 16px 0;
            padding-bottom: 12px;
            border-bottom: 2px solid #F1F1F4;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748B;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            padding: 10px 20px;
            background: #3B82F6;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #2563EB;
        }

        .btn-danger {
            padding: 8px 16px;
            background: #EF4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-danger:hover {
            background: #DC2626;
        }

        .btn-success {
            padding: 10px 20px;
            background: #10B981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-success:hover {
            background: #059669;
        }

        .document-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .form-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid #F1F1F4;
        }

        /* Modal Styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            z-index: 9999;
            padding: 18px;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .modal-overlay.show {
            display: flex !important;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-overlay .modal {
            max-width: 600px;
            width: calc(100% - 36px);
            margin: auto;
            background: #ffffff !important;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
            z-index: 10000;
            flex-shrink: 0;
            min-height: 100px;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
            pointer-events: auto;
            height: auto;
            animation: slideDown 0.3s ease;
        }

        .modal-overlay.show .modal {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #E2E8F0;
            background: #F9FAFB;
        }

        .modal-header h3 {
            margin: 0;
            font-weight: 800;
            font-size: 20px;
            color: #111827;
            letter-spacing: -0.2px;
        }

        .btn-close-modal {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #E2E8F0;
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-close-modal:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
        }

        .btn-close-modal i {
            font-size: 18px;
            color: #64748B;
        }

        .modal-body {
            padding: 24px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #F1F5F9;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #F9FAFB;
        }

        .btn-modal-primary {
            flex: 1;
            height: 44px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            background: #3B82F6;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.18s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-modal-primary:hover {
            background: #2563EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-modal-primary:active {
            transform: scale(0.98);
        }

        .btn-modal-cancel {
            flex: 1;
            height: 44px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #64748B;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.18s ease;
        }

        .btn-modal-cancel:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            color: #475569;
        }

        /* Loading State */
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-left: -9px;
            margin-top: -9px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spinner 0.7s linear infinite;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    @php
        $userRole = Auth::user()->roles()->first()->name ?? null;
        $isAdmin = in_array($userRole, ['admin', 'superadmin']);
    @endphp

    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">
                Detail Permohonan
                <span style="font-size: 14px; font-weight: normal; color: #64748B;"> -
                    {{ $permohonanUser->type_name }}</span>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.permohonan.index') }}" class="btn-back">
                <i class="ri-arrow-left-line"></i>
                Kembali ke Daftar Permohonan
            </a>
        </div>
    </div>

    <section class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="card-title" style="margin: 0;">Informasi Permohonan</h2>
            @if (
                $permohonanUser->status !== 'selesai' &&
                    $permohonanUser->status !== 'ditolak' &&
                    $permohonanUser->status !== 'dibatalkan')
                <div style="display: flex; gap: 12px;">
                    @can('permohonan.approve')
                        <button type="button" class="btn-danger" data-open="#modalReject">
                            <i class="ri-close-line"></i>
                            Tolak Permohonan
                        </button>
                    @endcan

                    @if ($permohonanUser->status === 'pending')
                        @can('permohonan.process')
                            {{-- Button Proses Permohonan --}}
                            <button type="button" class="btn-primary" style="background: #0ea5e9;" data-open="#modalProgress">
                                <i class="ri-loader-4-line"></i>
                                Proses Permohonan
                            </button>
                        @endcan
                    @elseif ($permohonanUser->status === 'proses')
                        @can('permohonan.approve')
                            {{-- Button Setujui Permohonan --}}
                            <button type="button" class="btn-success" data-open="#modalApprove">
                                <i class="ri-check-line"></i>
                                Setujui Permohonan
                            </button>
                        @endcan
                    @endif
                </div>
            @endif

            @if (!$isAdmin && $permohonanUser->status === 'pending')
                <button type="button" class="btn-danger" style="background: #64748B;" data-open="#modalCancel">
                    <i class="ri-prohibited-line"></i>
                    Batalkan Permohonan
                </button>
            @endif
        </div>

        <div class="info-row">
            <div class="info-label">Nama Permohonan</div>
            <div class="info-value"><strong>{{ $permohonanUser->type_name }}</strong></div>
        </div>

        <div class="info-row">
            <div class="info-label">Nama Perusahaan</div>
            <div class="info-value">{{ $permohonanUser->user->company_name ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Status</div>
            <div class="info-value">
                @php
                    $statusClass = 'status-aktif';
                    $statusText = ucfirst($permohonanUser->status ?? 'Belum diisi');
                    $statusMap = [
                        'pending' => ['text' => 'Menunggu Verifikasi', 'class' => 'status-menunggu'],
                        'proses' => ['text' => 'Sedang Diproses', 'class' => 'status-proses'],
                        'selesai' => ['text' => 'Aktif', 'class' => 'status-aktif'],
                        'ditolak' => ['text' => 'Ditolak', 'class' => 'status-ditolak'],
                        'dibatalkan' => ['text' => 'Dibatalkan', 'class' => 'status-dibatalkan'],
                        'expired' => ['text' => 'Kedaluwarsa', 'class' => 'status-kedaluwarsa'],
                    ];
                    if (isset($permohonanUser->status) && isset($statusMap[$permohonanUser->status])) {
                        $statusText = $statusMap[$permohonanUser->status]['text'];
                        $statusClass = $statusMap[$permohonanUser->status]['class'];
                    }
                @endphp

                @if ($permohonanUser->status)
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                @else
                    <span style="color: #9CA3AF; font-style: italic;">Belum diisi</span>
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Pengaju</div>
            <div class="info-value">{{ $permohonanUser->applicant_name }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Tanggal Dibuat</div>
            <div class="info-value">{{ $permohonanUser->created_at->translatedFormat('d F Y, H:i') }}</div>
        </div>

        @if ($permohonanUser->keterangan)
            <div class="info-row">
                <div class="info-label">Keterangan Admin</div>
                <div class="info-value">{{ $permohonanUser->keterangan }}</div>
            </div>
        @endif


    </section>

    <section class="card">
        <h2 class="section-title">Detail Pengajuan</h2>

        @if ($permohonanUser->permohonan)
            @foreach ($permohonanUser->permohonan->questions as $index => $question)
                @php
                    $questionId = $question->id;
                    $answer = $jawaban[$questionId] ?? null;
                @endphp

                <div class="question-item">
                    <div class="question-number">
                        {{ $index + 1 }}. {{ $question->pertanyaan }}
                        @if ($question->wajib)
                            <span style="color:#ef4444">*</span>
                        @endif
                    </div>

                    <div class="answer-value">
                        @if ($answer === null || $answer === '')
                            <span style="color: #94A3B8; font-style: italic;">Tidak diisi</span>
                        @else
                            @switch($question->tipe)
                                @case('text')
                                @case('textarea')

                                @case('number')
                                @case('date')
                                    {{ $answer }}
                                @break

                                @case('radio')
                                    @php
                                        $option = $question->options->firstWhere('id', $answer);
                                    @endphp
                                    {{ $option ? $option->opsi : $answer }}
                                @break

                                @case('checkbox')
                                    @php
                                        $answerArray = is_array($answer) ? $answer : [$answer];
                                        $selectedOptions = $question->options->whereIn('id', $answerArray);
                                    @endphp
                                    @if ($selectedOptions->count() > 0)
                                        <ul>
                                            @foreach ($selectedOptions as $option)
                                                <li>{{ $option->opsi }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ implode(', ', $answerArray) }}
                                    @endif
                                @break

                                @case('file')
                                    @if ($answer)
                                        @php
                                            // Assumption: Answer contains filename
                                            $fileUrl = asset('storage/permohonan-jawaban/' . $answer);
                                            $ext = pathinfo($answer, PATHINFO_EXTENSION);
                                        @endphp
                                        <button type="button" class="btn-primary" style="padding: 6px 12px; font-size: 13px;"
                                            onclick="showFilePreview('{{ $fileUrl }}', '{{ $ext }}')">
                                            <i class="ri-eye-line"></i> Lihat File
                                        </button>
                                    @endif
                                @break

                                @case('file_multiple')
                                    @if ($answer)
                                        @php
                                            $files = is_array($answer)
                                                ? $answer
                                                : json_decode($answer, true) ?? [$answer];
                                        @endphp
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                            @foreach ($files as $file)
                                                @php
                                                    $fileUrl = asset('storage/permohonan-jawaban/' . $file);
                                                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                                                @endphp
                                                <button type="button" class="btn-primary"
                                                    style="padding: 6px 12px; font-size: 13px;"
                                                    onclick="showFilePreview('{{ $fileUrl }}', '{{ $ext }}')">
                                                    <i class="ri-eye-line"></i> Lihat File {{ $loop->iteration }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                @break

                                @default
                                    {{ is_array($answer) ? implode(', ', $answer) : $answer }}
                            @endswitch
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            @if ($permohonanUser->jawaban)
                @foreach ($permohonanUser->jawaban as $key => $value)
                    <div class="question-item">
                        <div class="question-number">
                            {{ $loop->iteration }}. {{ $key }}
                        </div>
                        <div class="answer-value">
                            {{ is_array($value) ? implode(', ', $value) : $value }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>Tidak ada data jawaban.</p>
                </div>
            @endif
        @endif
    </section>

    @if ($permohonanUser->documents && $permohonanUser->documents->count() > 0)
        <section class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 class="section-title" style="margin: 0;">Dokumen Persetujuan</h2>
                @if ($isAdmin)
                    <button type="button" class="btn-primary" data-open="#modalAddDocument">
                        <i class="ri-add-line"></i>
                        Tambah Dokumen
                    </button>
                @endif
            </div>

            @foreach ($permohonanUser->documents as $document)
                <div class="document-item">
                    <div class="document-info">
                        <div class="document-name">{{ $document->nama }}</div>
                        <div class="document-meta">
                            @if ($document->masa_berlaku)
                                Masa Berlaku: {{ $document->masa_berlaku->translatedFormat('d F Y') }}
                                @if ($document->masa_berlaku->isPast())
                                    <span style="color: #EF4444; font-weight: 600;">(Kedaluwarsa)</span>
                                @elseif($document->masa_berlaku->isToday())
                                    <span style="color: #F59E0B; font-weight: 600;">(Berakhir Hari Ini)</span>
                                @elseif($document->masa_berlaku->diffInDays(now()) <= 30)
                                    <span style="color: #F59E0B; font-weight: 600;">(Akan Berakhir dalam
                                        {{ $document->masa_berlaku->diffInDays(now()) }} hari)</span>
                                @endif
                            @else
                                Tidak ada masa berlaku
                            @endif
                        </div>
                    </div>
                    <div class="document-actions">
                        @if ($document->dokumen && $document->dokumen->path)
                            <a href="{{ asset('storage/permohonan-documents/' . $document->dokumen->path) }}"
                                target="_blank" class="btn-download" download>
                                <i class="ri-download-line"></i>
                                Download
                            </a>
                        @else
                            <span style="color: #94A3B8; font-size: 12px;">Dokumen tidak tersedia</span>
                        @endif
                        @if ($isAdmin)
                            <form
                                action="{{ route('admin.permohonan-user.document.delete', [$permohonanId, $permohonanUser->id, $document->id]) }}"
                                method="POST" class="form-delete-document" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                    <i class="ri-delete-bin-line"></i>
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
    @else
        <section class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 class="section-title" style="margin: 0;">Dokumen Persetujuan</h2>
                @if ($isAdmin)
                    <button type="button" class="btn-primary" data-open="#modalAddDocument">
                        <i class="ri-add-line"></i>
                        Tambah Dokumen
                    </button>
                @endif
            </div>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <p>Tidak ada dokumen persetujuan</p>
            </div>
        </section>
    @endif

    <!-- Modal Approve Permohonan -->
    @if ($isAdmin && $permohonanUser->status === 'proses')
        <div class="modal-overlay" id="modalApprove">
            <div class="modal" style="max-width: 700px;">
                <div class="modal-header">
                    <h3>Setujui Permohonan</h3>
                    <button type="button" class="btn-close-modal" data-close>
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <form action="{{ route('admin.permohonan-user.approve', [$permohonanId, $permohonanUser->id]) }}"
                    method="POST" enctype="multipart/form-data" id="formApprove">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $permohonanUser->keterangan) }}</textarea>
                        </div>

                        <div id="documentContainer">
                            <div class="form-section">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                    <h3 style="font-size: 16px; font-weight: 600; margin: 0;">Tambah Dokumen Persetujuan
                                    </h3>
                                    <button type="button" class="btn-primary" id="btnAddDocument"
                                        style="background: #6B7280; padding: 8px 16px; font-size: 13px;">
                                        <i class="ri-add-line"></i>
                                        Tambah Dokumen Lain
                                    </button>
                                </div>
                                <div class="document-form-item"
                                    style="background: #F9FAFB; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #E2E8F0;">
                                    <div class="form-group">
                                        <label class="form-label">Nama Dokumen</label>
                                        <input type="text" name="documents[0][nama]" class="form-control"
                                            placeholder="Contoh: Surat Izin Operasional" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">File Dokumen</label>
                                        <input type="file" name="documents[0][file]" class="form-control"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                        <small style="color: #6B7280; font-size: 12px;">Format: PDF, DOC, DOCX, JPG, PNG
                                            (Max: 10MB)</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Masa Berlaku (Opsional)</label>
                                        <input type="date" name="documents[0][masa_berlaku]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-close>
                            <i class="ri-close-line"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn-modal-primary" style="background: #10B981;">
                            <i class="ri-check-line"></i>
                            Setujui Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Process Permohonan (NEW) -->
    @if ($isAdmin && $permohonanUser->status === 'pending')
        <div class="modal-overlay" id="modalProgress">
            <div class="modal" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>Konfirmasi Proses</h3>
                    <button type="button" class="btn-close-modal" data-close>
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <form action="{{ route('admin.permohonan-user.progress', [$permohonanId, $permohonanUser->id]) }}"
                    method="POST" id="formProgress">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center" style="padding: 20px 0;">
                            <p style="font-size: 16px; margin-bottom: 0;">Apakah anda yakin ingin memproses permohonan ini?
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer" style="justify-content: center; gap: 12px;">
                        <button type="button" class="btn-modal-cancel" data-close>
                            Tidak
                        </button>
                        <button type="submit" class="btn-modal-primary" style="background: #0ea5e9;">
                            Yakin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Reject Permohonan -->
    @if ($isAdmin && $permohonanUser->status !== 'selesai' && $permohonanUser->status !== 'ditolak')
        <div class="modal-overlay" id="modalReject">
            <div class="modal" style="max-width: 600px;">
                <div class="modal-header">
                    <h3>Tolak Permohonan</h3>
                    <button type="button" class="btn-close-modal" data-close>
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <form action="{{ route('admin.permohonan-user.reject', [$permohonanId, $permohonanUser->id]) }}"
                    method="POST" id="formReject">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Alasan Penolakan <span style="color: #EF4444;">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="5"
                                placeholder="Masukkan alasan penolakan permohonan (wajib diisi)" required>{{ old('keterangan') }}</textarea>
                            <small style="color: #6B7280; font-size: 12px;">Mohon berikan alasan yang jelas mengapa
                                permohonan ditolak</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-close>
                            <i class="ri-close-line"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn-modal-primary" style="background: #EF4444;">
                            <i class="ri-close-line"></i>
                            Tolak Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Cancel Permohonan -->
    @if (!$isAdmin && $permohonanUser->status === 'pending')
        <div class="modal-overlay" id="modalCancel">
            <div class="modal" style="max-width: 600px;">
                <div class="modal-header">
                    <h3>Batalkan Permohonan</h3>
                    <button type="button" class="btn-close-modal" data-close>
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <form action="{{ route('admin.permohonan-user.cancel', [$permohonanId, $permohonanUser->id]) }}"
                    method="POST" id="formCancel">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Alasan Pembatalan</label>
                            <textarea name="keterangan" class="form-control" rows="5" placeholder="Masukkan alasan pembatalan (opsional)">{{ old('keterangan') }}</textarea>
                            <small style="color: #6B7280; font-size: 12px;">Berikan alasan jika diperlukan</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-close>
                            <i class="ri-close-line"></i>
                            Tutup
                        </button>
                        <button type="submit" class="btn-modal-primary" style="background: #64748B;">
                            <i class="ri-prohibited-line"></i>
                            Batalkan Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Tambah Dokumen -->
    <div class="modal-overlay" id="modalAddDocument">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Dokumen Baru</h3>
                <button type="button" class="btn-close-modal" data-close>
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form action="{{ route('admin.permohonan-user.document.add', [$permohonanId, $permohonanUser->id]) }}"
                method="POST" enctype="multipart/form-data" id="formAddDocument">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" name="nama" class="form-control"
                            placeholder="Contoh: Surat Izin Operasional" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">File Dokumen</label>
                        <input type="file" name="file" class="form-control"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <small style="color: #6B7280; font-size: 12px;">Format: PDF, DOC, DOCX, JPG, PNG (Max:
                            10MB)</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Masa Berlaku (Opsional)</label>
                        <input type="date" name="masa_berlaku" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-close>
                        <i class="ri-close-line"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn-modal-primary">
                        <i class="ri-add-line"></i>
                        Tambah Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal File Preview -->
    <div class="modal-overlay" id="modalFilePreview">
        <div class="modal" style="max-width: 900px; height: 85vh;">
            <div class="modal-header">
                <h3>Preview File</h3>
                <button type="button" class="btn-close-modal" data-close>
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body"
                style="height: calc(100% - 78px); padding: 0; display: flex; align-items: center; justify-content: center; background: #333;">
                <div id="fileContent"
                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showFilePreview(url, ext) {
            const modal = document.getElementById('modalFilePreview');
            const content = document.getElementById('fileContent');

            // Clear previous content
            content.innerHTML = '';

            let html = '';
            const extension = ext.toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                html = `<img src="${url}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
            } else if (extension === 'pdf') {
                html = `<iframe src="${url}" style="width: 100%; height: 100%; border: none;"></iframe>`;
            } else {
                html = `
                    <div style="text-align: center; color: #fff;">
                        <i class="ri-file-line" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
                        <p>Preview tidak tersedia untuk format file ini.</p>
                        <a href="${url}" target="_blank" class="btn-primary" style="display: inline-flex; margin-top: 16px;">
                            <i class="ri-download-line" style="margin-right: 8px;"></i> Download File
                        </a>
                    </div>
                `;
            }

            content.innerHTML = html;

            // Use existing openModal function
            const openModal = (selector) => {
                const modal = document.querySelector(selector);
                if (modal) {
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }
            };

            openModal('#modalFilePreview');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle form submit - disable button until process complete
            const formApprove = document.getElementById('formApprove');
            const formReject = document.getElementById('formReject');
            const formAddDocument = document.getElementById('formAddDocument');

            const handleFormSubmit = (form) => {
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const cancelBtn = form.querySelector('.btn-modal-cancel');

                    if (submitBtn) {
                        // Disable submit button
                        submitBtn.disabled = true;
                        submitBtn.classList.add('btn-loading');
                    }

                    if (cancelBtn) {
                        cancelBtn.disabled = true;
                    }
                });
            };

            // Initialize form submit handlers
            if (formApprove) {
                handleFormSubmit(formApprove);
            }

            if (formReject) {
                handleFormSubmit(formReject);
            }

            if (formAddDocument) {
                handleFormSubmit(formAddDocument);
            }

            const formProgress = document.getElementById('formProgress');
            if (formProgress) {
                handleFormSubmit(formProgress);
            }

            // Modal functionality
            const openModal = (selector) => {
                const modal = document.querySelector(selector);
                if (modal) {
                    // Reset button state when opening modal
                    const form = modal.querySelector('form');
                    if (form) {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const cancelBtn = form.querySelector('.btn-modal-cancel');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('btn-loading');
                        }
                        if (cancelBtn) {
                            cancelBtn.disabled = false;
                        }
                    }

                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    // Force browser to apply the display change
                    void modal.offsetHeight;
                    // Ensure modal box is visible
                    const modalBox = modal.querySelector('.modal');
                    if (modalBox) {
                        modalBox.style.display = 'block';
                        modalBox.style.visibility = 'visible';
                        modalBox.style.opacity = '1';
                    }
                }
            };

            const closeModal = (modal) => {
                if (modal) {
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                    // Reset form and button state
                    const form = modal.querySelector('form');
                    if (form) {
                        form.reset();
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const cancelBtn = form.querySelector('.btn-modal-cancel');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('btn-loading');
                        }
                        if (cancelBtn) {
                            cancelBtn.disabled = false;
                        }
                    }
                }
            };

            // Open modal on button click
            document.addEventListener('click', function(e) {
                const opener = e.target.closest('[data-open]');
                if (opener) {
                    e.preventDefault();
                    const modalSelector = opener.getAttribute('data-open');
                    openModal(modalSelector);
                    return;
                }

                // Close modal on backdrop click
                if (e.target.classList.contains('modal-overlay')) {
                    const modal = e.target;
                    closeModal(modal);
                    return;
                }

                // Close modal on close button
                if (e.target.hasAttribute('data-close') || e.target.closest('[data-close]')) {
                    const modal = e.target.closest('.modal-overlay') || document.querySelector(
                        '.modal-overlay.show');
                    if (modal) {
                        closeModal(modal);
                    }
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.querySelector('.modal-overlay.show');
                    closeModal(modal);
                }
            });

            // Success notification
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });
                // Close modal if open
                setTimeout(() => {
                    const openModalEl = document.querySelector('.modal-overlay.show');
                    if (openModalEl) {
                        closeModal(openModalEl);
                    }
                }, 100);
            @endif

            // Error notification
            @if (session('error') || $errors->any())
                // Reset button state on error
                const openModalEl = document.querySelector('.modal-overlay.show');
                if (openModalEl) {
                    const form = openModalEl.querySelector('form');
                    if (form) {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const cancelBtn = form.querySelector('.btn-modal-cancel');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('btn-loading');
                        }
                        if (cancelBtn) {
                            cancelBtn.disabled = false;
                        }
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') ?? $errors->first() }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });
            @endif

            // Add document form (for approve modal)
            let documentCount = 1;
            const btnAddDocument = document.getElementById('btnAddDocument');
            if (btnAddDocument) {
                btnAddDocument.addEventListener('click', function() {
                    const container = document.getElementById('documentContainer');
                    if (!container) return;

                    const formSection = container.querySelector('.form-section');
                    if (!formSection) return;

                    const newDocument = document.createElement('div');
                    newDocument.className = 'document-form-item';
                    newDocument.style.cssText =
                        'background: #F9FAFB; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #E2E8F0;';
                    newDocument.innerHTML = `
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="font-size: 14px; font-weight: 600; margin: 0; color: #111827;">Dokumen ${documentCount + 1}</h4>
            <button type="button" class="btn-danger" onclick="this.closest('.document-form-item').remove()" style="padding: 6px 12px; font-size: 12px; height: auto;">
              <i class="ri-delete-bin-line"></i> Hapus
            </button>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Dokumen</label>
            <input type="text" name="documents[${documentCount}][nama]" class="form-control" placeholder="Contoh: Surat Izin Operasional" required>
          </div>
          <div class="form-group">
            <label class="form-label">File Dokumen</label>
            <input type="file" name="documents[${documentCount}][file]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
            <small style="color: #6B7280; font-size: 12px;">Format: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</small>
          </div>
          <div class="form-group">
            <label class="form-label">Masa Berlaku (Opsional)</label>
            <input type="date" name="documents[${documentCount}][masa_berlaku]" class="form-control">
          </div>
        `;
                    formSection.appendChild(newDocument);
                    documentCount++;

                    // Scroll to new document
                    newDocument.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                });
            }

            // Reset document count when modal approve is opened
            document.addEventListener('click', function(e) {
                if (e.target.closest('[data-open="#modalApprove"]')) {
                    documentCount = 1;
                    // Reset document container to only have first document
                    const container = document.getElementById('documentContainer');
                    if (container) {
                        const formSection = container.querySelector('.form-section');
                        if (formSection) {
                            const documentItems = formSection.querySelectorAll('.document-form-item');
                            // Keep only first document, remove others
                            documentItems.forEach((item, index) => {
                                if (index > 0) {
                                    item.remove();
                                }
                            });
                        }
                    }
                }
            });

            // Delete document confirmation
            const deleteForms = document.querySelectorAll('.form-delete-document');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
