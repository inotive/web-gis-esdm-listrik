@php
    $isEditing = isset($kategoriAsset);
    $fieldValue = static fn (string $key, $default = null) => old($key, $isEditing ? ($kategoriAsset->{$key} ?? $default) : $default);
@endphp

<div class="col-md-4">
    <label class="required fw-semibold fs-7 mb-2" for="kode">Kode</label>
    <input type="text" id="kode" name="kode" class="form-control form-control-solid" value="{{ $fieldValue('kode') }}" required>
</div>
<div class="col-md-8">
    <label class="required fw-semibold fs-7 mb-2" for="name">Nama</label>
    <input type="text" id="name" name="name" class="form-control form-control-solid" value="{{ $fieldValue('name') }}" required>
</div>
<div class="col-12">
    <label class="fw-semibold fs-7 mb-2" for="deskripsi">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" class="form-control form-control-solid" rows="4" placeholder="Masukkan deskripsi kategori (opsional)">{{ $fieldValue('deskripsi') }}</textarea>
</div>
