@extends('admin.layouts.app')

@section('title', $title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">{{ $title }}</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.perizinan.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label class="form-label">Upload File Excel</label>
                        <div class="input-group">
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx, .xls, .csv" required>
                        </div>
                        <small class="text-muted">Format yang didukung: .xlsx, .xls, .csv</small>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Petunjuk Import:</strong>
                            <a href="{{ route('admin.perizinan.import.template') }}" class="btn btn-sm btn-light border">
                                <i class="ri-file-download-line"></i> Download Template
                            </a>
                        </div>
                        <ul class="mb-0 pl-3">
                            <li>Pastikan format file sesuai dengan template yang disediakan.</li>
                            <li>Kolom wajib: <b>nama</b> (sebagai Nama Pemohon/Perusahaan) dan <b>jenis</b>.</li>
                            <li>Kolom tanggal (tanggal, tanggal_terbit, tanggal_akhir) sebaiknya diisi dengan format tanggal Excel atau YYYY-MM-DD.</li>
                            <li>Jika perusahaan belum ada, sistem akan mencoba membuatnya otomatis berdasarkan kolom <b>nama</b>.</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('admin.perizinan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary" style="background-color: var(--accent-2);">
                            <i class="ri-upload-cloud-line"></i> Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
