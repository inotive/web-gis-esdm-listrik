@extends('admin.layouts.app')

@section('title', 'Detail Permohonan')

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
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #F1F1F4;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 200px;
            font-weight: 600;
            color: #64748B;
            font-size: 14px;
        }

        .info-value {
            flex: 1;
            color: #111827;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-selesai {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .badge-proses {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .badge-ditolak {
            background: #FEE2E2;
            color: #DC2626;
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

        /* Approval Section Highlight Styles */
        .approval-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 3px solid #5a67d8;
            border-radius: 16px;
            padding: 28px;
            margin-top: 18px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.35);
            position: relative;
            overflow: hidden;
        }

        .approval-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .approval-header {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .approval-header i {
            font-size: 28px;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .approval-info {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .approval-info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #E2E8F0;
        }

        .approval-info-row:last-child {
            border-bottom: none;
        }

        .approval-info-label {
            width: 180px;
            font-weight: 700;
            color: #5a67d8;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .approval-info-label i {
            font-size: 16px;
        }

        .approval-info-value {
            flex: 1;
            color: #1a202c;
            font-size: 14px;
            font-weight: 600;
        }

        .approval-documents {
            position: relative;
            z-index: 1;
        }

        .approval-section .document-item {
            background: rgba(255, 255, 255, 0.97);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .approval-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #10b981;
            color: #fff;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .approval-badge i {
            font-size: 16px;
        }

        /* File Preview Styles */
        .file-preview-container {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .file-thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #E2E8F0;
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-thumbnail:hover {
            border-color: #667eea;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .file-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-thumbnail-icon {
            font-size: 36px;
            color: #64748B;
        }

        .file-thumbnail-icon.pdf {
            color: #EF4444;
        }

        .file-thumbnail-icon.word {
            color: #2563EB;
        }

        .file-thumbnail-icon.excel {
            color: #10B981;
        }

        .file-thumbnail-icon.video {
            color: #8B5CF6;
        }

        .file-thumbnail-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .file-thumbnail:hover .file-thumbnail-overlay {
            background: rgba(0, 0, 0, 0.5);
        }

        .file-thumbnail-overlay i {
            color: white;
            font-size: 24px;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease;
        }

        .file-thumbnail:hover .file-thumbnail-overlay i {
            opacity: 1;
            transform: scale(1);
        }

        /* Modal Styles */
        .preview-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .preview-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .preview-modal-content img {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .preview-modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .preview-modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .preview-modal-info {
            position: absolute;
            bottom: -60px;
            left: 0;
            right: 0;
            color: white;
            text-align: center;
            font-size: 14px;
        }

        .preview-modal-info .file-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .preview-modal-actions {
            position: absolute;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
        }

        .preview-modal-btn {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .preview-modal-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">
                Detail Permohonan
                <span style="font-size: 14px; font-weight: normal; color: #64748B;"> -
                    {{ $permohonanUser->permohonan->nama }}</span>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.pengajuan-permohonan.index') }}" class="btn-back">
                <i class="ri-arrow-left-line"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- APPROVAL SECTION - MOVED TO TOP WITH HIGHLIGHT --}}
    @if (
        ($permohonanUser->documents && $permohonanUser->documents->count() > 0) ||
            $permohonanUser->approved_by ||
            $permohonanUser->approved_at)
        <section class="approval-section">
            <h2 class="approval-header">
                <i class="ri-file-shield-2-line"></i>
                Dokumen Persetujuan
                @if ($permohonanUser->approved_by)
                    <span class="approval-badge">
                        <i class="ri-checkbox-circle-fill"></i>
                        Disetujui
                    </span>
                @endif
            </h2>

            {{-- Approval Information --}}
            @if ($permohonanUser->approved_by || $permohonanUser->approved_at || $permohonanUser->approval_notes)
                <div class="approval-info">
                    @if ($permohonanUser->approver)
                        <div class="approval-info-row">
                            <div class="approval-info-label">
                                <i class="ri-user-star-line"></i>
                                Disetujui Oleh
                            </div>
                            <div class="approval-info-value">{{ $permohonanUser->approver->name }}</div>
                        </div>
                    @endif

                    @if ($permohonanUser->approved_at)
                        <div class="approval-info-row">
                            <div class="approval-info-label">
                                <i class="ri-time-line"></i>
                                Waktu Persetujuan
                            </div>
                            <div class="approval-info-value">
                                {{ $permohonanUser->approved_at->translatedFormat('l, d F Y - H:i') }} WIB
                                <span style="color: #64748B; font-weight: normal; font-size: 12px;">
                                    ({{ $permohonanUser->approved_at->diffForHumans() }})
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($permohonanUser->approval_notes)
                        <div class="approval-info-row">
                            <div class="approval-info-label">
                                <i class="ri-message-3-line"></i>
                                Catatan Persetujuan
                            </div>
                            <div class="approval-info-value">{{ $permohonanUser->approval_notes }}</div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Approval Documents with Preview --}}
            @if ($permohonanUser->documents && $permohonanUser->documents->count() > 0)
                <div class="approval-documents">
                    @foreach ($permohonanUser->documents as $document)
                        @php
                            $filePath =
                                $document->dokumen && $document->dokumen->path
                                    ? asset('storage/permohonan-documents/' . $document->dokumen->path)
                                    : null;
                            $fileName = $document->nama;
                            $fileExtension = $filePath
                                ? strtolower(pathinfo($document->dokumen->path, PATHINFO_EXTENSION))
                                : '';

                            // Determine file type
                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            $isPdf = $fileExtension === 'pdf';
                            $isWord = in_array($fileExtension, ['doc', 'docx']);
                            $isExcel = in_array($fileExtension, ['xls', 'xlsx', 'csv']);
                            $isVideo = in_array($fileExtension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv']);
                        @endphp

                        <div class="document-item">
                            <div class="file-preview-container">
                                {{-- File Thumbnail --}}
                                @if ($filePath)
                                    <div class="file-thumbnail"
                                        @if ($isImage) onclick="openPreviewModal('{{ $filePath }}', '{{ $fileName }}', '{{ $fileExtension }}')" @endif>
                                        @if ($isImage)
                                            <img src="{{ $filePath }}" alt="{{ $fileName }}">
                                            <div class="file-thumbnail-overlay">
                                                <i class="ri-eye-line"></i>
                                            </div>
                                        @elseif ($isPdf)
                                            <i class="ri-file-pdf-line file-thumbnail-icon pdf"></i>
                                        @elseif ($isWord)
                                            <i class="ri-file-word-line file-thumbnail-icon word"></i>
                                        @elseif ($isExcel)
                                            <i class="ri-file-excel-line file-thumbnail-icon excel"></i>
                                        @elseif ($isVideo)
                                            <i class="ri-video-line file-thumbnail-icon video"></i>
                                        @else
                                            <i class="ri-file-line file-thumbnail-icon"></i>
                                        @endif
                                    </div>
                                @endif

                                {{-- File Info --}}
                                <div class="document-info">
                                    <div class="document-name">
                                        <i class="ri-file-text-line" style="color: #667eea; margin-right: 6px;"></i>
                                        {{ $fileName }}
                                        @if ($fileExtension)
                                            <span
                                                style="color: #94A3B8; font-size: 11px; font-weight: normal; text-transform: uppercase;">
                                                ({{ $fileExtension }})
                                            </span>
                                        @endif
                                    </div>
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
                            </div>

                            {{-- Download Button --}}
                            @if ($filePath)
                                <a href="{{ $filePath }}" target="_blank" class="btn-download" download>
                                    <i class="ri-download-line"></i>
                                    Download
                                </a>
                            @else
                                <span style="color: #94A3B8; font-size: 12px;">Dokumen tidak tersedia</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="background: rgba(255, 255, 255, 0.95); border-radius: 12px; padding: 30px;">
                    <i class="ri-file-forbid-line" style="font-size: 48px; color: #94A3B8;"></i>
                    <p style="color: #64748B; margin: 12px 0 0 0;">Belum ada dokumen persetujuan yang diunggah</p>
                </div>
            @endif
        </section>
    @endif

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">Informasi Permohonan</h2>
        </div>

        <div class="info-row">
            <div class="info-label">Nama Permohonan</div>
            <div class="info-value"><strong>{{ $permohonanUser->permohonan->nama }}</strong></div>
        </div>

        <div class="info-row">
            <div class="info-label">Status</div>
            <div class="info-value">
                @php
                    $statusClass = 'badge-selesai';
                    $statusText = ucfirst($permohonanUser->status);
                    $statusMap = [
                        'pending' => ['text' => 'Menunggu Verifikasi', 'class' => 'badge-pending'],
                        'proses' => ['text' => 'Sedang Diproses', 'class' => 'badge-proses'],
                        'selesai' => ['text' => 'Selesai', 'class' => 'badge-selesai'],
                        'ditolak' => ['text' => 'Ditolak', 'class' => 'badge-ditolak'],
                    ];
                    if (isset($statusMap[$permohonanUser->status])) {
                        $statusText = $statusMap[$permohonanUser->status]['text'];
                        $statusClass = $statusMap[$permohonanUser->status]['class'];
                    }
                @endphp
                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Tanggal Dibuat</div>
            <div class="info-value">{{ $permohonanUser->created_at->translatedFormat('d F Y, H:i') }}</div>
        </div>

        @if ($permohonanUser->keterangan)
            <div class="info-row">
                <div class="info-label">Keterangan</div>
                <div class="info-value">{{ $permohonanUser->keterangan }}</div>
            </div>
        @endif
    </section>

    <section class="card">
        <h2 class="section-title">Detail Pengajuan</h2>

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
                                {{-- Single File Upload Preview --}}
                                @php
                                    $filePath = $answer ? asset('storage/' . $answer) : null;
                                    $fileName = $answer ? basename($answer) : 'File';
                                    $fileExtension = $filePath ? strtolower(pathinfo($answer, PATHINFO_EXTENSION)) : '';

                                    // Determine file type
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                    $isPdf = $fileExtension === 'pdf';
                                    $isWord = in_array($fileExtension, ['doc', 'docx']);
                                    $isExcel = in_array($fileExtension, ['xls', 'xlsx', 'csv']);
                                    $isVideo = in_array($fileExtension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv']);
                                @endphp

                                @if ($filePath)
                                    <div class="file-preview-container" style="margin-top: 8px;">
                                        <div class="file-thumbnail"
                                            @if ($isImage) onclick="openPreviewModal('{{ $filePath }}', '{{ $fileName }}', '{{ $fileExtension }}')" style="cursor: pointer;" @endif>
                                            @if ($isImage)
                                                <img src="{{ $filePath }}" alt="{{ $fileName }}">
                                                <div class="file-thumbnail-overlay">
                                                    <i class="ri-eye-line"></i>
                                                </div>
                                            @elseif ($isPdf)
                                                <i class="ri-file-pdf-line file-thumbnail-icon pdf"></i>
                                            @elseif ($isWord)
                                                <i class="ri-file-word-line file-thumbnail-icon word"></i>
                                            @elseif ($isExcel)
                                                <i class="ri-file-excel-line file-thumbnail-icon excel"></i>
                                            @elseif ($isVideo)
                                                <i class="ri-video-line file-thumbnail-icon video"></i>
                                            @else
                                                <i class="ri-file-line file-thumbnail-icon"></i>
                                            @endif
                                        </div>
                                        <div style="margin-left: 12px; flex: 1;">
                                            <div style="font-weight: 500; color: #1E293B; margin-bottom: 4px;">
                                                <i class="ri-file-text-line" style="color: #667eea; margin-right: 6px;"></i>
                                                {{ $fileName }}
                                                @if ($fileExtension)
                                                    <span
                                                        style="color: #94A3B8; font-size: 11px; font-weight: normal; text-transform: uppercase;">
                                                        ({{ $fileExtension }})
                                                    </span>
                                                @endif
                                            </div>
                                            <a href="{{ $filePath }}" target="_blank" class="btn-download"
                                                style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 13px;"
                                                download>
                                                <i class="ri-download-line"></i>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #94A3B8; font-style: italic;">File tidak tersedia</span>
                                @endif
                            @break

                            @case('file_multiple')
                                {{-- Multiple Files Upload Preview --}}
                                @php
                                    $files = is_array($answer) ? $answer : [$answer];
                                @endphp

                                @if (count($files) > 0)
                                    <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 8px;">
                                        @foreach ($files as $fileIndex => $file)
                                            @php
                                                $filePath = $file ? asset('storage/' . $file) : null;
                                                $fileName = $file ? basename($file) : 'File ' . ($fileIndex + 1);
                                                $fileExtension = $filePath
                                                    ? strtolower(pathinfo($file, PATHINFO_EXTENSION))
                                                    : '';

                                                // Determine file type
                                                $isImage = in_array($fileExtension, [
                                                    'jpg',
                                                    'jpeg',
                                                    'png',
                                                    'gif',
                                                    'webp',
                                                    'svg',
                                                ]);
                                                $isPdf = $fileExtension === 'pdf';
                                                $isWord = in_array($fileExtension, ['doc', 'docx']);
                                                $isExcel = in_array($fileExtension, ['xls', 'xlsx', 'csv']);
                                                $isVideo = in_array($fileExtension, [
                                                    'mp4',
                                                    'avi',
                                                    'mov',
                                                    'wmv',
                                                    'flv',
                                                    'mkv',
                                                ]);
                                            @endphp

                                            @if ($filePath)
                                                <div class="file-preview-container">
                                                    <div class="file-thumbnail"
                                                        @if ($isImage) onclick="openPreviewModal('{{ $filePath }}', '{{ $fileName }}', '{{ $fileExtension }}')" style="cursor: pointer;" @endif>
                                                        @if ($isImage)
                                                            <img src="{{ $filePath }}" alt="{{ $fileName }}">
                                                            <div class="file-thumbnail-overlay">
                                                                <i class="ri-eye-line"></i>
                                                            </div>
                                                        @elseif ($isPdf)
                                                            <i class="ri-file-pdf-line file-thumbnail-icon pdf"></i>
                                                        @elseif ($isWord)
                                                            <i class="ri-file-word-line file-thumbnail-icon word"></i>
                                                        @elseif ($isExcel)
                                                            <i class="ri-file-excel-line file-thumbnail-icon excel"></i>
                                                        @elseif ($isVideo)
                                                            <i class="ri-video-line file-thumbnail-icon video"></i>
                                                        @else
                                                            <i class="ri-file-line file-thumbnail-icon"></i>
                                                        @endif
                                                    </div>
                                                    <div style="margin-left: 12px; flex: 1;">
                                                        <div style="font-weight: 500; color: #1E293B; margin-bottom: 4px;">
                                                            <i class="ri-file-text-line"
                                                                style="color: #667eea; margin-right: 6px;"></i>
                                                            {{ $fileName }}
                                                            @if ($fileExtension)
                                                                <span
                                                                    style="color: #94A3B8; font-size: 11px; font-weight: normal; text-transform: uppercase;">
                                                                    ({{ $fileExtension }})
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <a href="{{ $filePath }}" target="_blank" class="btn-download"
                                                            style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 13px;"
                                                            download>
                                                            <i class="ri-download-line"></i>
                                                            Download
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color: #94A3B8; font-style: italic;">Tidak ada file yang diunggah</span>
                                @endif
                            @break

                            @default
                                {{ is_array($answer) ? implode(', ', $answer) : $answer }}
                        @endswitch
                    @endif
                </div>
            </div>
        @endforeach
    </section>

    {{-- Preview Modal --}}
    <div id="previewModal" class="preview-modal">
        <div class="preview-modal-content">
            <span class="preview-modal-close" onclick="closePreviewModal()">&times;</span>
            <img id="previewImage" src="" alt="">
            <div class="preview-modal-info">
                <div class="file-name" id="previewFileName"></div>
                <div id="previewFileType"></div>
            </div>
            <div class="preview-modal-actions">
                <a id="previewDownload" href="" download class="preview-modal-btn">
                    <i class="ri-download-line"></i> Download
                </a>
                <button onclick="closePreviewModal()" class="preview-modal-btn">
                    <i class="ri-close-line"></i> Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview Modal Functions
        function openPreviewModal(imageSrc, fileName, fileType) {
            const modal = document.getElementById('previewModal');
            const img = document.getElementById('previewImage');
            const fileNameEl = document.getElementById('previewFileName');
            const fileTypeEl = document.getElementById('previewFileType');
            const downloadLink = document.getElementById('previewDownload');

            img.src = imageSrc;
            fileNameEl.textContent = fileName;
            fileTypeEl.textContent = fileType.toUpperCase();
            downloadLink.href = imageSrc;
            downloadLink.download = fileName;

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('previewModal');
            if (event.target === modal) {
                closePreviewModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePreviewModal();
            }
        });

        // Success message
        document.addEventListener('DOMContentLoaded', function() {
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
            @endif
        });
    </script>
@endpush
