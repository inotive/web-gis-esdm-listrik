@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Tambah Inspeksi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-top: 18px;
        }

        .form-section {
            margin-bottom: 24px;
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E5E7EB;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            color: #1F2937;
            background: #FCFCFD;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #17C653;
            box-shadow: 0 0 0 3px rgba(23, 198, 83, 0.1);
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            border: 2px dashed #D1D5DB;
            border-radius: 8px;
            background: #F9FAFB;
            cursor: pointer;
            transition: all 0.2s;
            color: #6B7280;
            font-size: 14px;
        }

        .file-input-label:hover {
            border-color: #17C653;
            background: #F0FDF4;
            color: #047857;
        }

        .file-input-label i {
            font-size: 18px;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .file-name {
            margin-top: 8px;
            font-size: 12px;
            color: #6B7280;
        }

        .form-helper {
            font-size: 12px;
            color: #6B7280;
            margin-top: 4px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            padding-top: 24px;
            border-top: 1px solid #E5E7EB;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: #17C653;
            color: white;
        }

        .btn-primary:hover {
            background: #15B34B;
        }

        .btn-secondary {
            background: #F3F4F6;
            color: #4B5563;
        }

        .btn-secondary:hover {
            background: #E5E7EB;
        }

        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 8px !important;
            background: #FCFCFD !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 12px !important;
            color: #1F2937 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #17C653 !important;
            box-shadow: 0 0 0 3px rgba(23, 198, 83, 0.1) !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Tambah Inspeksi</div>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.inspeksi.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <h3 class="form-section-title">Informasi Inspeksi</h3>

                <div class="form-group">
                    <label class="form-label">
                        Tanggal <span class="required">*</span>
                    </label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}"
                        required>
                    @error('tanggal')
                        <div class="form-helper" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Referensi Izin <span class="required">*</span>
                    </label>
                    <input type="text" name="referensi_izin" class="form-control" placeholder="Contoh: No. 123/ESDM/2024"
                        value="{{ old('referensi_izin') }}" required>
                    @error('referensi_izin')
                        <div class="form-helper" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Perusahaan <span class="required">*</span>
                    </label>
                    <select name="perusahaan_id" class="form-control select2" required>
                        <option value="">Pilih Perusahaan</option>
                        @foreach ($perusahaans as $p)
                            <option value="{{ $p->id }}" @selected(old('perusahaan_id') == $p->id)>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('perusahaan_id')
                        <div class="form-helper" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Dokumen</h3>

                <div class="form-group">
                    <label class="form-label">Lampiran</label>
                    <div class="file-input-wrapper">
                        <label for="lampiran" class="file-input-label">
                            <i class="ri-attachment-2"></i>
                            <span>Upload Lampiran (PDF, JPG, PNG, DOC - Max 5MB)</span>
                        </label>
                        <input type="file" name="lampiran" id="lampiran" class="file-input"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <div class="file-name" id="lampiran_name"></div>
                    </div>
                    @error('lampiran')
                        <div class="form-helper" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Berita Acara</h3>

                <div class="form-group">
                    <label class="form-label">Berita Acara</label>
                    <textarea name="berita_acara" class="form-control" rows="6"
                        placeholder="Masukkan isi berita acara inspeksi (opsional)">{{ old('berita_acara') }}</textarea>
                    @error('berita_acara')
                        <div class="form-helper" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Catatan</h3>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" placeholder="Masukkan catatan inspeksi (opsional)">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="form-helper" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i>
                    Simpan
                </button>
                <a href="{{ route('admin.inspeksi.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Pilih Perusahaan',
                allowClear: true,
                minimumResultsForSearch: 0 // Always show search box
            });

            // File input handler for lampiran
            document.getElementById('lampiran').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || '';
                document.getElementById('lampiran_name').textContent = fileName ?
                    `File dipilih: ${fileName}` : '';
            });
        });
    </script>
@endpush
