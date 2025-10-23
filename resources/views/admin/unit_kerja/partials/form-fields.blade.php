@php
    $current = $unitKerja ?? null;
    $selected = static fn (string $field) => old($field, $current->{$field} ?? '');
@endphp

<div class="row g-4">
    <div class="col-md-4">
        <label for="kode_unit" class="form-label fw-semibold">Kode Unit <span class="text-danger">*</span></label>
        <input type="text" id="kode_unit" name="kode_unit" class="form-control" value="{{ $selected('kode_unit') }}" required maxlength="50" placeholder="Contoh: UK-001">
    </div>
    <div class="col-md-8">
        <label for="nama_unit" class="form-label fw-semibold">Nama Unit <span class="text-danger">*</span></label>
        <input type="text" id="nama_unit" name="nama_unit" class="form-control" value="{{ $selected('nama_unit') }}" required maxlength="150" placeholder="Masukkan nama unit kerja">
    </div>
    <div class="col-12">
        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" maxlength="500" placeholder="Masukkan deskripsi (opsional)">{{ $selected('deskripsi') }}</textarea>
    </div>
</div>
