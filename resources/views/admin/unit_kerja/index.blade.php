@extends('admin.layouts.app')

@section('title', 'Unit Kerja Pengelola - Dinas ESDM')
@section('page-title', 'Unit Kerja Pengelola')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Unit Kerja Pengelola</li>
@endsection

@push('styles')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    .table thead tr {
        background-color: #f9fafb;
    }
</style>
@endpush

@section('content')
{{-- Alert Success --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-shield-tick fs-2x text-success me-4"></i>
            <div>
                <h4 class="mb-1 text-success">Berhasil!</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Alert Error --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-information fs-2x text-danger me-4"></i>
            <div>
                <h4 class="mb-1 text-danger">Terjadi Kesalahan</h4>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3 py-4">
        <div>
            <h3 class="card-title fw-bold mb-0">Daftar Unit Kerja</h3>
            <small class="text-muted">Kelola unit kerja pengelola asset</small>
        </div>
        <a href="{{ route('admin.unit-kerja.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="ki-duotone ki-plus fs-3"></i>
            <span>Tambah Unit Kerja</span>
        </a>
    </div>

    <div class="card-body">
        {{-- Filter dan Search --}}
        <form method="GET" class="row align-items-end gy-3 gx-3 mb-5">
            <div class="col-lg-5 col-md-6">
                <label for="search" class="form-label fw-semibold">Pencarian</label>
                <input type="text" id="search" name="search" class="form-control form-control-sm"
                    placeholder="Cari nama, kode, atau deskripsi..." value="{{ $search }}">
            </div>

            <div class="col-lg-2 col-md-3">
                <label for="per_page" class="form-label fw-semibold">Per Halaman</label>
                <select id="per_page" name="per_page" class="form-select form-select-sm">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-success w-100">
                    <i class="ki-duotone ki-filter fs-4 me-1"></i> Terapkan
                </button>
                <a href="{{ route('admin.unit-kerja.index') }}" class="btn btn-sm btn-light w-100">
                    <i class="ki-duotone ki-refresh fs-4 me-1"></i> Reset
                </a>
            </div>
        </form>

        {{-- Tabel Data --}}
        <div class="table-responsive">
            <table class="table table-striped table-row-dashed align-middle gy-4">
                <thead class="bg-light fw-bold text-muted">
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th style="width: 140px;">Kode</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th class="text-center" style="width: 150px;">Jumlah Asset</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unitKerjaList as $unit)
                        <tr>
                            <td class="text-center">{{ ($unitKerjaList->currentPage() - 1) * $unitKerjaList->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $unit->kode_unit }}</td>
                            <td class="fw-semibold">{{ $unit->nama_unit }}</td>
                            <td>{{ $unit->deskripsi ?? '-' }}</td>
                            <td class="text-center">{{ $unit->assets_count }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.unit-kerja.edit', $unit) }}"
                                    class="btn btn-icon btn-light-warning btn-sm me-1" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                                <form action="{{ route('admin.unit-kerja.destroy', $unit) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-icon btn-light-danger btn-sm btn-delete"
                                        title="Hapus">
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada unit kerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mt-4">
            <span class="text-muted">
                Menampilkan {{ $unitKerjaList->firstItem() ?? 0 }} - {{ $unitKerjaList->lastItem() ?? 0 }} dari {{ $unitKerjaList->total() }} data
            </span>
            {{ $unitKerjaList->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-delete').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const form = button.closest('form');

                Swal.fire({
                    title: 'Hapus unit kerja ini?',
                    text: 'Unit kerja yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
