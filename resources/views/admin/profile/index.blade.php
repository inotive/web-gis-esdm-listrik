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
<li class="breadcrumb-item text-muted">{{ $title }}</li>
<!--end::Item-->
@endsection

@push('styles')
<!--begin::Vendor Stylesheets(used for this page only)-->
<!--end::Vendor Stylesheets-->
@endpush

@section('content')
<div class="row col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">{{ $title }}</span>
                </h3>
            </div>
            <form method="POST" action="{{ route('admin.profile.profile-update', auth()->user()->id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="input-group mb-5">
                    <div class="col-xl-12 mb-2 text-center">
                        <!--begin::Image input-->
                        <div class="image-input image-input-circle" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-125px h-125px"
                            style="background-image: url({{ $data->image != null ? asset('storage/profile/' . $data->image) : asset('assets/media/svg/avatars/blank.svg') }} )">
                        </div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span
                                        class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="image" value=""/>
                                <!--end::Inputs-->
                            </label>
                            <!--end::Edit button-->

                            <!--begin::Cancel button-->
                            <span
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Cancel avatar">
                                <i class="fa-solid fa-xmark fs-2"></i>
                            </span>
                            <!--end::Cancel button-->

                            <!--begin::Remove button-->
                            <span
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Remove avatar">
                                <i class="fa-solid fa-xmark fs-2"></i>
                            </span>
                            <!--end::Remove button-->
                        </div>
                        <!--end::Image input-->
                    </div>

                </div>
                <div class="card-body py-4">
                    <!--begin::Form-->
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="username" class="form-label fw-bold">Username</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="text" class="form-control form-control-solid" id="username" name="username"
                                value="{{ $data->username }}" placeholder="Masukkan Username" required
                                aria-label="Username" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="email" class="form-label fw-bold">Email</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="email" class="form-control form-control-solid" id="email" name="email"
                                value="{{ $data->email }}" placeholder="Masukkan Email" required
                                aria-label="Email" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="inputNama" class="form-label fw-bold">Nama Lengkap</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="text" class="form-control form-control-solid" name="name" id="inputNama"
                                value="{{ $data->name }}" required placeholder="Masukkan Nama Lengkap"
                                aria-label="Name" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="password" class="form-label fw-bold">Password</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="password" class="form-control form-control-solid" id="password" name="password" placeholder="Masukkan Password"
                                aria-label="Password" aria-describedby="basic-addon1" value="{{ !empty($data) ? old('password', null) : old('password') }}" />
                                @if (!empty($data))
                                <div class="form-text text-muted mt-2">
                                    <i class="ki-duotone ki-information-5 fs-2 text-warning me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    Kosongkan jika anda tidak ingin mengubah password!
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="input-group mb-5">
                        <div class="col-xl-12 mb-2">
                            <label for="role" class="form-label fw-bold">Role</label>
                        </div>
                        <div class="col-xl-12">
                            <input type="text" class="form-control form-control-solid" id="role" name="role" value="{{ $data->role_select->name }}" readonly>
                        </div>
                    </div>
                    <!--end::Form-->
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('admin.dashboard') }}"
                        class="btn btn-light btn-active-light-primary me-2">
                        <i class="ki-duotone ki-arrow-left fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Kembali
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i class="ki-duotone ki-check fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
   
</div>
   
@endsection

@push('scripts')
<!--begin::Vendors Javascript(used for this page only)-->
<!--end::Vendors Javascript-->
<script>
    @if (Session::has('pesan')) 
        toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}")
    @endif
</script>
@endpush
