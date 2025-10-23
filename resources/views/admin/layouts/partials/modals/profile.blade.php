<?php
use Illuminate\Support\Facades\Auth;
?>

@php
    $authUser = Auth::user();
    $profileImage = $authUser?->image
        ? asset('storage/profile/' . $authUser->image)
        : asset('assets/media/avatars/300-3.jpg');
@endphp

@if ($authUser)
<div class="modal fade" id="modalProfile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Profil Pengguna</h3>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.profile.profile-update', $authUser->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="symbol symbol-125px symbol-circle mb-3">
                                <img src="{{ $profileImage }}" alt="Foto Profil" class="object-fit-cover" />
                            </div>
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-2">Format: jpg, jpeg, png (maks. 2 MB)</small>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $authUser->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $authUser->username) }}" required>
                                @error('username')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $authUser->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Password <span class="text-muted">(opsional)</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">
                                <small class="text-muted d-block mt-2">Minimal 6 karakter bila diisi.</small>
                                @if ($errors->has('password'))
                                    <div class="text-danger small mt-1">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
