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
<li class="breadcrumb-item text-muted">{{$title}}</li>
<!--end::Item-->
@endsection

@push('styles')
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Vendor Stylesheets-->
@endpush

@section('content')
    <div class="row col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Daftar Permission</span>
                </h3>
                <div class="card-toolbar">
                        <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_tambah">
                            <i class="ki-duotone ki-plus fs-2"></i>Tambah Data</a>
                </div>
            </div>
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    <table id="kt_datatable_dom_positioning"
                        class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start text-center">No</th>
                                <th class="min-w-150px">Group</th>
                                <th class="min-w-200px">Display Name</th>
                                <th class="min-w-200px">Hak Akses</th>
                                <th class="min-w-150px text-end rounded-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $value)
                                <tr>
                                    <td class="text-center ps-4">
                                        <span class="text-gray-800 fw-bold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info fw-bold">{{ $value->group }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-3">
                                                <div class="symbol-label bg-light-success">
                                                    <i class="ki-duotone ki-security-check fs-2 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-gray-900 fw-bold fs-6">{{ $value->display_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary fw-bold">{{ $value->name }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#"
                                            class="btn btn-icon btn-light-primary btn-active-color-primary btn-sm me-1"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_{{ $value->id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Permission">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        @include('admin.permission.component.modal', [
                                            'value' => $value,
                                        ])
                                 
                                        <button data-route="{{ route('admin.hak-akses.permission.destroy', $value->id) }}"
                                            class="btn btn-icon btn-light-danger btn-active-color-danger btn-sm"
                                            onclick="destroyItem(this)"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Permission">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        @include('admin.permission.component.modal-tambah')
    </div>
@endsection


@push('scripts')
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Vendors Javascript-->
<script>
    $("#kt_datatable_dom_positioning").DataTable({
        "language": {
            "lengthMenu": "Show _MENU_",
        },
        "dom": "<'row mb-5'" +
            "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l>" +
            "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>" +
            ">" +

            "<'table-responsive'tr>" +

            "<'row mt-5'" +
            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
            ">"
    });
    
    const destroyItem = (e) => {
        let target = $(e);
        callSwal(target.data('route'))
    }

    const callSwal = (route) => {
        Swal.fire({
                title: "Apakah Anda Yakin?",
                html: "<p style='center'>Setelah Data Dihapus maka Anda Tidak Akan Bisa Mengembalikan Data Kembali!</p>",
                icon: "warning",
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus!',
                cancelButtonText: 'Batalkan!'
            })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    (new FormElementHelper)
                    .createAttribute('hidden', '_token', '{{ csrf_token() }}')
                        .createAttribute('hidden', '_method', 'DELETE')
                        .post(route);
                } else {
                    Swal.fire({
                        title: "Aksi Dibatalkan :)",
                        icon: "info",
                    })
                }
            })
    }
    
    @if (Session::has('pesan')) 
        toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}")
    @endif
</script>
@endpush
