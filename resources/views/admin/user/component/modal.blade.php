{{-- modal --}}
<div class="modal fade" tabindex="-1" id="kt_modal_{{ $value->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Ubah Data User</h3>

                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.hak-akses.user.update', $value->id) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="input-group mb-5">
                    <div class="col-xl-12 mb-2 text-center">
                        <div class="image-input image-input-circle" data-kt-image-input="true">
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url({{ asset('storage/profile/' . $value->image) }})">
                            </div>

                            <label
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span
                                        class="path2"></span></i>
                                <input type="file" name="image" value="" />
                            </label>

                            <span
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Cancel avatar">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>

                            <span
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Remove avatar">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="">Username</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="test" class="form-control" name="username" value="{{ $value->username }}"
                                placeholder="Masukkan Username" id="username" required aria-label="Username"
                                aria-describedby="basic-addon1" />
                        </div>
                    </div>
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="">Email</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="test" class="form-control" name="email" value="{{ $value->email }}"
                                placeholder="Masukkan Email" id="email" required aria-label="Username"
                                aria-describedby="basic-addon1" />
                        </div>
                    </div>
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="">Nama Lengkap</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="text" class="form-control" id="inputNama" name="name"
                                value="{{ $value->name }}" required placeholder="Masukkan Nama Lengkap"
                                aria-label="Username" aria-describedby="basic-addon1" />
                        </div>
                    </div>

                    {{-- Password + toggle --}}
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="">Password</label>
                        </div>
                        <div class="col-xl-12 d-flex">
                            <input type="password" class="form-control" id="password-{{ $value->id }}" name="password"
                                placeholder="Masukkan Password"
                                aria-label="Username"
                                value="{{ !empty($value) ? old('password', null) : old('password') }}"
                                aria-describedby="toggle-password-{{ $value->id }}" />
                            <button class="password-toggle" type="button" id="toggle-password-{{ $value->id }}" aria-label="Tampilkan/sembunyikan password"
                                style="cursor:pointer;border:1px solid #e5e7eb;border-left:0;background:#fff;padding:0 .75rem;">
                                <i class="fa-solid fa-eye" id="icon-password-{{ $value->id }}"></i>
                            </button>
                        </div>
                        @if (!empty($value))
                            <i class="mt-1">Kosongkan jika anda tidak ingin mengubah password!</i>
                        @endif
                    </div>

                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="">Role</label>
                        </div>
                        <div class="col-xl-12">
                            <select class="form-select" name="role" required data-control="select2" data-placeholder="Select an option">
                                @foreach ($role as $item)
                                    <option value="{{ $item->name }}"
                                        {{ !empty($value) && $value->role_select?->id === $item->id ? 'selected' : null }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Toggle script per modal instance --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pwd = document.getElementById('password-{{ $value->id }}');
    const btn = document.getElementById('toggle-password-{{ $value->id }}');
    const ico = document.getElementById('icon-password-{{ $value->id }}');
    if (pwd && btn && ico) {
        btn.addEventListener('click', () => {
            const show = pwd.type === 'password';
            pwd.type = show ? 'text' : 'password';
            ico.classList.toggle('fa-eye');
            ico.classList.toggle('fa-eye-slash');
        });
    }
});
</script>
@endpush
{{-- end modal --}}
