@php
    $current = $statusHukumAsset ?? null;
    $selected = static fn (string $field) => old($field, $current->{$field} ?? '');
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">Nama Status Hukum <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="{{ $selected('name') }}" required maxlength="150" placeholder="Masukkan nama status hukum">
    </div>
    <div class="col-md-6">
        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" maxlength="500" placeholder="Masukkan deskripsi (opsional)">{{ $selected('deskripsi') }}</textarea>
    </div>
</div>
