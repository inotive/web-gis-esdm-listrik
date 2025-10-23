@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Data jabatan - Dinas ESDM')

@section('page-title', 'jabatan')

@section('breadcrumb')
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">jabatan</li>
@endsection

@push('styles')
<style>
    .table thead tr {
        background-color: #f9fafb;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table th {
        color: #6b7280;
        font-weight: 600;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .search-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .search-filter .filter-left {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .search-filter .filter-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-left: auto;
    }

    .search-filter .search-input {
        width: 220px;
        max-width: 100%;
    }
</style>
@endpush

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="fw-semibold mb-2">Terjadi kesalahan:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-information fs-2x text-danger me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">Gagal!</h4>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-shield-tick fs-2x text-success me-4">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Berhasil!</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title align-items-start flex-column mb-0">
                    <span class="card-label fw-bold fs-3">Daftar jabatan</span>
                </h3>
                <small class="text-muted">Total data: {{ $jabatans->total() }}</small>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm btn-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#kt_modal_tambah_jabatan">
                    <i class="ki-duotone ki-plus fs-3 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span>Tambah jabatan</span>
                </button>
            </div>
        </div>

        <div class="card-body py-4">
            <div class="filter-container mb-4">
                <form method="GET" class="w-100">
                    <div class="search-filter">
                        <div class="filter-left">
                            <select name="status" class="form-select form-select-sm w-auto">
                                <option value="">Semua Status</option>
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-sm btn-light-primary">Terapkan</button>

                            @if(request()->filled('search') || request()->filled('status'))
                                <a href="{{ route('admin.jabatan.index') }}" class="btn btn-sm btn-light">Reset</a>
                            @endif
                        </div>

                        <div class="filter-right">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm search-input" placeholder="Cari data pejabat" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>No. Telp</th>
                            <th>Email</th>
                            <th>Alamat Kantor</th>
                            <th>Status</th>
                            <th style="width: 130px;">Dibuat</th>
                            <th class="text-center" style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jabatans as $index => $jabatan)
                            <tr>
                                <td class="text-center">{{ $jabatans->firstItem() + $index }}</td>
                                <td class="fw-semibold text-gray-900">{{ $jabatan->nama_jabatan }}</td>
                                <td>{{ $jabatan->nip ?? '-' }}</td>
                                <td>{{ $jabatan->jabatan }}</td>
                                <td>{{ $jabatan->no_telp ?? '-' }}</td>
                                <td>{{ $jabatan->email ?? '-' }}</td>
                                <td @if($jabatan->alamat_kantor) title="{{ $jabatan->alamat_kantor }}" @endif>
                                    {{ $jabatan->alamat_kantor ? Str::limit($jabatan->alamat_kantor, 70) : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-light-{{ $jabatan->status === 'aktif' ? 'success' : 'danger' }} text-{{ $jabatan->status === 'aktif' ? 'success' : 'danger' }} fw-semibold">
                                        {{ ucfirst($jabatan->status) }}
                                    </span>
                                </td>
                                <td>{{ optional($jabatan->created_at)->translatedFormat('d M Y') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-icon btn-sm btn-light-warning btn-active-warning me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_jabatan_{{ $jabatan->id }}" title="Edit">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>

                                    <form action="{{ route('admin.jabatan.destroy', $jabatan) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-active-danger btn-delete" title="Hapus">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            @include('admin.jabatan.component.modal', [
                                'jabatan' => $jabatan,
                                'statusOptions' => $statusOptions,
                            ])
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-10">
                                    Belum ada data jabatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4">
                <span class="text-muted">
                    Menampilkan {{ $jabatans->firstItem() ?? 0 }} - {{ $jabatans->lastItem() ?? 0 }} dari {{ $jabatans->total() }} data
                </span>
                {{ $jabatans->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@include('admin.jabatan.component.modal-tambah', ['statusOptions' => $statusOptions])

@endsection

@push('scripts')
<script>
    $(function () {
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
