@extends('admin.layouts.app')

@section('title', 'Edit Dokumen Aset')
@section('page-title', 'Edit Dokumen Aset')

@section('content')
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dokumen-asset.update', $dokumen) }}" method="post" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">Asset <span class="text-danger">*</span></label>
                <select name="asset_id" class="form-control" required>
                    @foreach($assets as $a)
                        <option value="{{ $a->id }}" {{ old('asset_id', $dokumen->asset_id)==$a->id?'selected':'' }}>
                            {{ $a->kode_asset }} — {{ $a->nama_asset }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Sertifikat</label>
                    <input type="text" name="no_sertif" value="{{ old('no_sertif', $dokumen->no_sertif) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl. Sertifikat</label>
                    <input type="date" name="tgl_sertif" value="{{ old('tgl_sertif', optional($dokumen->tgl_sertif)->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Sertifikat</label>
                    <input type="text" name="nama_sertifikat" value="{{ old('nama_sertifikat', $dokumen->nama_sertifikat) }}" class="form-control">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label class="form-label">No. Dokumen</label>
                    <input type="text" name="no_dokumen" value="{{ old('no_dokumen', $dokumen->no_dokumen) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl. Dokumen</label>
                    <input type="date" name="tanggal_dokumen" value="{{ old('tanggal_dokumen', optional($dokumen->tanggal_dokumen)->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl. Perolehan</label>
                    <input type="date" name="tanggal_oleh" value="{{ old('tanggal_oleh', optional($dokumen->tanggal_oleh)->format('Y-m-d')) }}" class="form-control">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label class="form-label">Tgl. Buku</label>
                    <input type="date" name="tanggal_buku" value="{{ old('tanggal_buku', optional($dokumen->tanggal_buku)->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Sertifikat</label>
                    <input type="text" name="sts_sertif" value="{{ old('sts_sertif', $dokumen->sts_sertif) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Konfirmasi</label><br>
                    <input type="checkbox" name="has_konfir" value="1" {{ old('has_konfir', $dokumen->has_konfir) ? 'checked' : '' }}> Sudah dikonfirmasi
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Keterangan</label>
                <textarea name="ket_sertif" class="form-control" rows="3">{{ old('ket_sertif', $dokumen->ket_sertif) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Bukti</label>
                <div>
                    <label class="me-3"><input type="radio" name="sertifikat_type" value="file"> File</label>
                    <label><input type="radio" name="sertifikat_type" value="link"> Link</label>
                </div>
                <small class="text-muted">Pilih jika ingin mengganti ke tipe lain.</small>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Upload File Baru</label>
                    <input type="file" name="file_sertif" class="form-control">
                    @if($dokumen->file_sertif)
                        <div class="mt-2">
                            <a href="{{ route('admin.dokumen-asset.view', $dokumen) }}" target="_blank">Lihat file saat ini</a>
                            <div class="form-check mt-1">
                                <input type="checkbox" name="remove_file_sertif" value="1" class="form-check-input" id="rmfile">
                                <label class="form-check-label" for="rmfile">Hapus file</label>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link Sertifikat</label>
                    <input type="url" name="link_sertif" value="{{ old('link_sertif', $dokumen->link_sertif) }}" class="form-control" placeholder="https://...">
                    @if($dokumen->link_sertif)
                        <div class="mt-2"><a href="{{ $dokumen->link_sertif }}" target="_blank" rel="noopener">Buka link saat ini</a></div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('admin.dokumen-asset.index') }}" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
