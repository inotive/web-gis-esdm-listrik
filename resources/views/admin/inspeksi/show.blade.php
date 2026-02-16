@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Detail Inspeksi')

@push('styles')
    <style>
        /* Main Container */
        .detail-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 18px;
        }

        /* Card Styles */
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #F1F5F9;
        }

        .content-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #F1F5F9;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #F1F5F9;
        }

        .section-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #17C653 0%, #22C55E 100%);
            border-radius: 12px;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(23, 198, 83, 0.25);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .info-item {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px 18px;
            transition: all 0.2s;
        }

        .info-item:hover {
            border-color: #17C653;
            box-shadow: 0 4px 12px rgba(23, 198, 83, 0.1);
            transform: translateY(-2px);
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-label i {
            font-size: 14px;
            color: #17C653;
        }

        .info-value {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
            line-height: 1.4;
        }

        .info-value.empty {
            color: #9CA3AF;
            font-style: italic;
            font-weight: 400;
        }

        /* Content Block */
        .content-block {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            margin-top: 8px;
            line-height: 1.8;
            color: #374151;
            font-size: 15px;
            white-space: pre-wrap;
            min-height: 120px;
        }

        .content-block.empty {
            color: #9CA3AF;
            font-style: italic;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100px;
        }

        /* File Download */
        .file-section {
            display: flex;
            align-items: center;
            gap: 16px;
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border: 1px solid #BFDBFE;
            border-radius: 12px;
            padding: 20px;
            margin-top: 8px;
        }

        .file-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 12px;
            color: #0077B6;
            font-size: 28px;
            box-shadow: 0 2px 8px rgba(0, 119, 182, 0.15);
        }

        .file-info {
            flex: 1;
        }

        .file-label {
            font-size: 13px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .file-name {
            font-size: 15px;
            color: #0F172A;
            font-weight: 600;
        }

        .file-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #0077B6 0%, #005f8e 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.25);
        }

        .file-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 119, 182, 0.35);
            color: white;
        }

        .file-download i {
            font-size: 18px;
        }

        .file-section.empty {
            background: #F9FAFB;
            border: 1px dashed #E5E7EB;
            justify-content: center;
        }

        .file-section.empty .file-icon {
            background: #F3F4F6;
            color: #9CA3AF;
        }

        .empty-text {
            color: #9CA3AF;
            font-style: italic;
            font-size: 14px;
        }

        /* Action Buttons */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: white;
            color: #4B5563;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
            color: #1F2937;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #17C653 0%, #22C55E 100%);
            color: white;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(23, 198, 83, 0.25);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(23, 198, 83, 0.35);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-card,
            .content-card {
                padding: 20px;
            }

            .file-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Detail Inspeksi</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.inspeksi.index') }}" class="btn-back">
                <i class="ri-arrow-left-line"></i>
                Kembali
            </a>

            @if (auth()->user()->hasRole('perusahaan'))
                @if ($inspeksi->feedback)
                    <button class="btn-edit" style="background:#10B981; cursor:default; border:none; color:white;">
                        <i class="ri-check-line"></i>
                        Feedback Terkirim
                    </button>
                @else
                    <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#modalFeedback">
                        <i class="ri-message-2-line"></i>
                        Berikan Feedback
                    </button>
                @endif
            @endif
        </div>
    </div>

    <div class="detail-wrapper">
        {{-- Informasi Inspeksi --}}
        <div class="info-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="ri-file-list-3-line"></i>
                </div>
                <h3 class="section-title">Informasi Inspeksi</h3>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <i class="ri-calendar-line"></i>
                        Tanggal Inspeksi
                    </div>
                    <div class="info-value">{{ $inspeksi->tanggal->translatedFormat('d F Y') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="ri-file-text-line"></i>
                        Referensi Izin
                    </div>
                    <div class="info-value">{{ $inspeksi->referensi_izin }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="ri-building-line"></i>
                        Perusahaan
                    </div>
                    <div class="info-value">{{ $inspeksi->perusahaan->nama ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="ri-user-line"></i>
                        Ditambahkan Oleh
                    </div>
                    <div class="info-value">{{ $inspeksi->pengguna->name ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="ri-time-line"></i>
                        Tanggal Dibuat
                    </div>
                    <div class="info-value">{{ $inspeksi->created_at->translatedFormat('d F Y, H:i') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="ri-refresh-line"></i>
                        Terakhir Diupdate
                    </div>
                    <div class="info-value">{{ $inspeksi->updated_at->translatedFormat('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Berita Acara --}}
        <div class="content-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="ri-article-line"></i>
                </div>
                <h3 class="section-title">Berita Acara</h3>
            </div>

            @if ($inspeksi->berita_acara)
                <div class="content-block">{{ $inspeksi->berita_acara }}</div>
            @else
                <div class="content-block empty">
                    <span class="empty-text">Belum ada berita acara</span>
                </div>
            @endif
        </div>

        {{-- Lampiran --}}
        <div class="content-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="ri-attachment-2"></i>
                </div>
                <h3 class="section-title">Lampiran</h3>
            </div>

            @if ($inspeksi->lampiran)
                <div class="file-section">
                    <div class="file-icon">
                        <i class="ri-file-pdf-line"></i>
                    </div>
                    <div class="file-info">
                        <div class="file-label">File Lampiran</div>
                        <div class="file-name">{{ basename($inspeksi->lampiran) }}</div>
                    </div>
                    <a href="{{ Storage::url($inspeksi->lampiran) }}" target="_blank" class="file-download">
                        <i class="ri-download-2-line"></i>
                        Download
                    </a>
                </div>
            @else
                <div class="file-section empty">
                    <div class="file-icon">
                        <i class="ri-file-forbid-line"></i>
                    </div>
                    <span class="empty-text">Tidak ada lampiran</span>
                </div>
            @endif
        </div>

        {{-- Catatan --}}
        <div class="content-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="ri-sticky-note-line"></i>
                </div>
                <h3 class="section-title">Catatan</h3>
            </div>

            @if ($inspeksi->catatan)
                <div class="content-block">{{ $inspeksi->catatan }}</div>
            @else
                <div class="content-block empty">
                    <span class="empty-text">Tidak ada catatan tambahan</span>
                </div>
            @endif
        </div>

        {{-- Feedback Perusahaan --}}
        @if ($inspeksi->feedback)
            <div class="content-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="ri-message-2-line"></i>
                    </div>
                    <h3 class="section-title">Feedback Perusahaan</h3>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="ri-user-line"></i> Nama</div>
                        <div class="info-value">{{ $inspeksi->feedback->nama }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="ri-briefcase-line"></i> Posisi</div>
                        <div class="info-value">{{ $inspeksi->feedback->posisi }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="ri-phone-line"></i> Kontak</div>
                        <div class="info-value">{{ $inspeksi->feedback->kontak }}</div>
                    </div>
                </div>

                <div class="content-block mt-3">
                    <div class="info-label mb-2"><i class="ri-sticky-note-line"></i> Catatan</div>
                    {{ $inspeksi->feedback->catatan }}
                </div>

                @if ($inspeksi->feedback->file_upload)
                    <div class="file-section mt-3">
                        <div class="file-icon"><i class="ri-file-text-line"></i></div>
                        <div class="file-info">
                            <div class="file-label">Lampiran Feedback</div>
                            <div class="file-name">Dokumen Pendukung</div>
                        </div>
                        <a href="{{ Storage::url($inspeksi->feedback->file_upload) }}" target="_blank"
                            class="file-download">
                            <i class="ri-download-2-line"></i> Download
                        </a>
                    </div>
                @endif
            </div>
        @endif

        {{-- Modal Feedback --}}
        @if (auth()->user()->hasRole('perusahaan') && !$inspeksi->feedback)
            <div class="modal fade" id="modalFeedback" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('admin.inspeksi.feedback.store', $inspeksi) }}" method="POST"
                            enctype="multipart/form-data" id="formFeedback">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Berikan Feedback</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" required
                                            value="{{ auth()->user()->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Posisi <span class="text-danger">*</span></label>
                                        <input type="text" name="posisi" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Kontak (HP/Email) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="kontak" class="form-control" required
                                            value="{{ auth()->user()->email }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Catatan <span class="text-danger">*</span></label>
                                        <textarea name="catatan" rows="4" class="form-control" required></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">File Pendukung (Opsional)</label>
                                        <input type="file" name="file_upload" class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        <small class="text-muted">Max: 5MB</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kirim Feedback</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#formFeedback').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Kirim Feedback?',
                    text: "Pastikan data yang Anda masukkan sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endpush
