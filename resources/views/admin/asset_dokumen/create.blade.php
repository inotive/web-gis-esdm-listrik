@extends('admin.layouts.app')

@section('title', 'Tambah Dokumen Aset')
@section('page-title', 'Tambah Dokumen Aset')

@section('content')
<div class="container-fluid p-0">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dokumen-asset.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            {{-- ASSET --}}
            <div class="mb-3">
                <label class="form-label">Asset <span class="text-danger">*</span></label>
                <select name="asset_id" class="form-control" required>
                    <option value="">-- pilih asset --</option>
                    @foreach($assets as $a)
                        <option value="{{ $a->id }}" {{ old('asset_id')==$a->id?'selected':'' }}>
                            {{ $a->kode_asset }} — {{ $a->nama_asset }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- INFO SERTIFIKAT UTAMA --}}
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Sertifikat</label>
                    <input type="text" name="no_sertif" value="{{ old('no_sertif') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl. Sertifikat</label>
                    <input type="date" name="tgl_sertif" value="{{ old('tgl_sertif') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Sertifikat</label>
                    <input type="text" name="nama_sertifikat" value="{{ old('nama_sertifikat') }}" class="form-control">
                </div>
            </div>

            {{-- INFO DOKUMEN TAMBAHAN --}}
            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label class="form-label">No. Dokumen</label>
                    <input type="text" name="no_dokumen" value="{{ old('no_dokumen') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl. Dokumen</label>
                    <input type="date" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tgl. Perolehan</label>
                    <input type="date" name="tanggal_oleh" value="{{ old('tanggal_oleh') }}" class="form-control">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label class="form-label">Tgl. Buku</label>
                    <input type="date" name="tanggal_buku" value="{{ old('tanggal_buku') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Sertifikat</label>
                    <input type="text" name="sts_sertif" value="{{ old('sts_sertif') }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" id="has_konfir" class="form-check-input" name="has_konfir" value="1" {{ old('has_konfir') ? 'checked' : '' }}>
                        <label for="has_konfir" class="form-check-label">Sudah dikonfirmasi</label>
                    </div>
                </div>
            </div>

            {{-- KETERANGAN --}}
            <div class="mb-3 mt-3">
                <label class="form-label">Keterangan</label>
                <textarea name="ket_sertif" class="form-control" rows="3">{{ old('ket_sertif') }}</textarea>
            </div>

            {{-- JENIS BUKTI --}}
            <div class="mb-3">
                <label class="form-label">Jenis Bukti</label>
                <div>
                    <label class="me-3">
                        <input type="radio" name="sertifikat_type" value="file"
                               {{ old('sertifikat_type','file')=='file'?'checked':'' }}>
                        File
                    </label>
                    <label>
                        <input type="radio" name="sertifikat_type" value="link"
                               {{ old('sertifikat_type')=='link'?'checked':'' }}>
                        Link
                    </label>
                </div>
            </div>

            {{-- INPUT FILE / LINK (TOGGLE) --}}
            <div class="row g-3">
                {{-- FILE --}}
                <div class="col-md-6" id="fileGroup"
                     style="{{ old('sertifikat_type','file')=='file' ? '' : 'display:none' }}">
                    <label class="form-label">Upload File (PDF/JPG/PNG, maks 5MB)</label>
                    <input type="file" name="file_sertif" id="fileInput"
                           class="form-control"
                           accept="application/pdf,image/jpeg,image/png"
                           {{ old('sertifikat_type','file')=='file' ? '' : 'disabled' }}>
                    <div class="form-text">Pilih jika bukti berupa file yang diunggah.</div>
                </div>

                {{-- LINK --}}
                <div class="col-md-6" id="linkGroup"
                     style="{{ old('sertifikat_type','file')=='link' ? '' : 'display:none' }}">
                    <label class="form-label">Link Sertifikat</label>
                    <input type="url" name="link_sertif" id="linkInput"
                           value="{{ old('link_sertif') }}"
                           class="form-control" placeholder="https://..."
                           {{ old('sertifikat_type','file')=='link' ? '' : 'disabled' }}>
                    <div class="form-text">Isi jika bukti berupa tautan (URL) ke file.</div>
                </div>
            </div>

            {{-- AKSI --}}
            <div class="mt-4">
                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.dokumen-asset.index') }}" class="btn btn-light">Batal</a>
            </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const radios    = document.querySelectorAll('input[name="sertifikat_type"]');
    const fileGroup = document.getElementById('fileGroup');
    const linkGroup = document.getElementById('linkGroup');
    const fileInput = document.getElementById('fileInput');
    const linkInput = document.getElementById('linkInput');

    function toggleType(){
        const val = document.querySelector('input[name="sertifikat_type"]:checked')?.value || 'file';

        if (val === 'file') {
            // show file, hide link
            fileGroup.style.display = '';
            linkGroup.style.display = 'none';

            fileInput.disabled = false;
            // fileInput.required = true; // aktifkan jika ingin wajib upload
            linkInput.disabled = true;
            linkInput.required = false;
            linkInput.value = ''; // kosongkan saat pindah ke file
        } else {
            // show link, hide file
            fileGroup.style.display = 'none';
            linkGroup.style.display = '';

            fileInput.disabled = true;
            fileInput.required = false; // mencegah validasi
            try { fileInput.value = ''; } catch(_) {}
            linkInput.disabled = false;
            // linkInput.required = true; // aktifkan jika ingin wajib isi link
        }
    }

    radios.forEach(r => r.addEventListener('change', toggleType));
    // init on first load
    toggleType();
})();
</script>
@endpush
