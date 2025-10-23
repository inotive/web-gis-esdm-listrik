@extends('admin.layouts.app')

@section('title', $title . ' - BPKAD')

@section('page-title', $title)

@section('breadcrumb')
<!--begin::Item-->
<li class="breadcrumb-item">
	<span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item text-muted">Hak Akses</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item">
	<span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item text-muted">Manajemen Pengguna</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item">
	<span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item text-muted">{{$title}}</li>
<!--end::Item-->
@endsection

@push('styles')
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Vendor Stylesheets-->
<!-- Font Awesome untuk ikon mata -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  .password-toggle {
    cursor: pointer;
    border: 1px solid #e5e7eb;
    background: #fff;
    border-left: 0;
    padding: 0 .75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .password-group .form-control {
    border-right: 0;
  }
</style>
@endpush

@section('content')
    <div class="row col-12">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Form Tambah Data User</span>
                    </h3>
                </div>
                <form method="POST" action="{{ route('admin.hak-akses.user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2 text-center">
                            <!--begin::Image input-->
                            <div class="image-input image-input-circle" data-kt-image-input="true">
                                <!--begin::Image preview wrapper-->
                                <div class="image-input-wrapper w-125px h-125px"
                                    style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})"">
                                </div>
                                <!--end::Image preview wrapper-->

                                <!--begin::Edit button-->
                                <label
                                    class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                    title="Change avatar">
                                    <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <input type="file" name="image" value=""/>
                                </label>
                                <!--end::Edit button-->

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
                            <!--end::Image input-->
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Username</label>
                            </div>
                            <div class="col-xl-12">
                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username') }}" placeholder="Masukkan Username" required
                                    aria-label="Username" aria-describedby="basic-addon1" />
                            </div>
                        </div>
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Email</label>
                            </div>
                            <div class="col-xl-12">
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="Masukkan Email" required
                                    aria-label="email" aria-describedby="basic-addon1" />
                            </div>
                        </div>
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Nama Lengkap</label>
                            </div>
                            <div class="col-xl-12">
                                <input type="text" class="form-control" name="name" id="inputNama"
                                    value="{{ old('name') }}" required placeholder="Masukkan Nama Lengkap"
                                    aria-label="name" aria-describedby="basic-addon1" />
                            </div>
                        </div>

                        {{-- Password + toggle --}}
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Password</label>
                            </div>
                            <div class="col-xl-12 password-group d-flex">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password"
                                    required aria-label="password" aria-describedby="toggle-password" />
                                <button class="password-toggle" type="button" id="toggle-password" aria-label="Tampilkan/sembunyikan password">
                                    <i class="fa-solid fa-eye" id="icon-password"></i>
                                </button>
                            </div>
                        </div>

                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Role</label>
                            </div>
                            <div class="col-xl-12">
                                @php
                                    // Tentukan default role:
                                    // 1) pakai old('role') jika ada (setelah submit gagal)
                                    // 2) kalau tidak, cari role bernama "admin" (tanpa peduli huruf besar/kecil)
                                    // 3) jika tidak ada juga, pakai role pertama sebagai fallback
                                    $defaultRoleName = old('role');
                                    if (!$defaultRoleName) {
                                        $adminRole = $role->first(function ($r) {
                                            return strtolower($r->name) === 'admin';
                                        });
                                        $defaultRoleName = $adminRole->name ?? ($role->first()->name ?? '');
                                    }
                                @endphp

                                <select class="form-select" name="role" required data-control="select2" data-placeholder="Select an option">
                                    @foreach ($role as $item)
                                        <option value="{{ $item->name }}"
                                            {{ $defaultRoleName === $item->name ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <div class="row gx-4">
                            <div class="col-8">
                                <button class="btn btn-primary submit-btn w-100" type="submit">Tambah User</button>
                            </div>
                            <div class="col-4">
                                <a href="{{ route('admin.hak-akses.user.index') }}"
                                    class="w-100 btn btn-outline btn-outline-secondary btn-active-light-secondary text-dark">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Daftar user pada sistem (kolom Status di-hide) --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Daftar User Pada Sistem</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-12 g-xl-12">
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table id="kt_datatable_dom_positioning"
                                    class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 px-7">
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th class="d-none">Status</th> {{-- hidden --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr id="{{ $value->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->username }}</td>
                                                <td>{{ $value->name }}</td>
                                                @foreach ($value->getRoleNames() as $roleName)
                                                    <td>{{ $roleName }}</td>
                                                @endforeach
                                                <td class="d-none">{{ $value->status_format }}</td> {{-- hidden --}}
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Vendors Javascript-->
<script>
    $("#kt_datatable_dom_positioning").DataTable({
        "language": { "lengthMenu": "Show _MENU_" },
        "dom": "<'row'" +
            "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
            "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
            ">" +
            "<'table-responsive'tr>" +
            "<'row'" +
            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
            ">"
        ,
        columnDefs: [
            { targets: -1, visible: false } // kolom Status disembunyikan (kolom terakhir)
        ]
    });

    // Batasi input Nama
    const inputNama = $("#inputNama");
    inputNama.on("input", function() {
        const inputValue = inputNama.val();
        const nonNumericValue = inputValue.replace(/[!@#$%^&*="()_+{}\[\]:;<>,.?~\\|0-9/'-]/g, '');
        if (inputValue !== nonNumericValue) inputNama.val(nonNumericValue);
    });

    // Batasi input Username
    const username = $("#username");
    username.on("input", function() {
        const inputValue = username.val();
        const nonNumericValue = inputValue.replace(/[!@#$%^&*="()_+{}\[\]:;<>,.?~\\|0-9/'-]/g, '');
        if (inputValue !== nonNumericValue) username.val(nonNumericValue);
    });
    $('#username').on('keydown', function(e) { if (e.keyCode === 32) e.preventDefault(); });

    // Password visibility toggle
    const pwd = document.getElementById('password');
    const btn = document.getElementById('toggle-password');
    const ico = document.getElementById('icon-password');
    if (btn && pwd && ico) {
        btn.addEventListener('click', () => {
            const show = pwd.type === 'password';
            pwd.type = show ? 'text' : 'password';
            ico.classList.toggle('fa-eye');
            ico.classList.toggle('fa-eye-slash');
        });
    }

    @if (Session::has('pesan'))
        toastr.{{ Session::get('alert') }}("{!! addslashes(Session::get('pesan')) !!}")
    @endif
</script>
@endpush
