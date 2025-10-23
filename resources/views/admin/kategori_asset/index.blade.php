@extends('admin.layouts.app')

@section('title', 'Kategori Asset - Dinas ESDM')

@section('page-title', 'Kategori Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Kategori Asset</li>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-shield-tick fs-2x text-success me-4">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <div>
                <h4 class="mb-1 text-success">Berhasil!</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
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
            <div>
                <h4 class="mb-1 text-danger">Terjadi kesalahan</h4>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title align-items-start flex-column mb-0">
                    <span class="card-label fw-bold fs-3">Daftar Kategori Asset</span>
                </h3>
                <small class="text-muted">Kelola kategori untuk pengelompokan asset</small>
            </div>
            <a href="{{ route('admin.kategori-asset.create') }}" class="btn btn-sm btn-success d-flex align-items-center">
                <i class="ki-duotone ki-plus fs-3 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <span>Tambah Kategori</span>
            </a>
        </div>

        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end mb-5">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold">Pencarian</label>
                    <input type="text" id="search" name="search" class="form-control form-control-sm" placeholder="Cari nama/kode/deskripsi" value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <label for="per_page" class="form-label fw-semibold">Per Halaman</label>
                    <select id="per_page" name="per_page" class="form-select form-select-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">Terapkan</button>
                    <a href="{{ route('admin.kategori-asset.index') }}" class="btn btn-sm btn-light flex-fill">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 140px;">Kode</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 150px;">Jumlah Asset</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriAssets as $kategori)
                            <tr>
                                <td class="text-center">{{ ($kategoriAssets->currentPage() - 1) * $kategoriAssets->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $kategori->kode }}</td>
                                <td class="fw-semibold">{{ $kategori->name }}</td>
                                <td>{{ $kategori->deskripsi ?? '-' }}</td>
                                <td class="text-center">{{ $kategori->assets_count }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.kategori-asset.edit', $kategori) }}" class="btn btn-icon btn-light-warning btn-sm me-2" title="Edit">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <form action="{{ route('admin.kategori-asset.destroy', $kategori) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm btn-delete" title="Hapus">
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
                                <td colspan="6" class="text-center text-muted">Belum ada kategori asset.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
                <span class="text-muted">Menampilkan {{ $kategoriAssets->firstItem() ?? 0 }} - {{ $kategoriAssets->lastItem() ?? 0 }} dari {{ $kategoriAssets->total() }} data</span>
                {{ $kategoriAssets->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
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

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus kategori ini?',
                        text: 'Kategori yang dihapus tidak dapat dikembalikan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary',
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
