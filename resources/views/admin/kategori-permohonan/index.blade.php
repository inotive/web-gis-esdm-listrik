@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Manajemen Kategori Permohonan')

@push('styles')
    <style>
        /* Table Header/Filter Section - Sesuai Figma */
        .card-header {
            background: white;
            border-bottom: 1px solid #F1F1F4;
            padding: 8px 20px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Search Box - Sesuai Figma */
        .w-search {
            width: 250px;
        }

        /* Filter Dropdown - Sesuai Figma */
        .w-filter {
            width: 250px;
        }

        .input-group {
            display: flex;
            align-items: center;
            background: #FCFCFC;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            overflow: hidden;
            height: 32px;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 100%;
            color: #99A1B7;
            background: transparent;
            border: none;
            padding: 0;
        }

        .input-group-text i {
            font-size: 16px;
        }

        .form-control {
            height: 100%;
            border: none;
            background: transparent;
            padding: 0 10px;
            font-size: 11px;
            color: #78829D;
            outline: none;
            width: 100%;
        }

        .form-control::placeholder {
            color: #78829D;
        }

        .form-control:focus {
            outline: none;
        }

        /* Table Styles */
        .table-shell {
            background: white;
            overflow: hidden;
        }

        .table-permohonan {
            width: 100%;
            border-collapse: collapse;
        }

        .table-permohonan thead {
            background: #FCFCFC;
        }

        .table-permohonan thead th {
            background: #FCFCFC;
            color: #4B5675;
            font-weight: 400;
            font-size: 13px;
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #F1F1F4;
            white-space: nowrap;
            border-radius: 0;
        }

        .table-permohonan thead th:first-child {
            border-top-left-radius: 0;
        }

        .table-permohonan thead th:last-child {
            border-top-right-radius: 0;
        }

        .table-permohonan tbody td {
            padding: 23px 20px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-permohonan tbody tr:last-child td {
            border-bottom: none;
        }

        .table-permohonan tbody tr:hover {
            background: #FCFCFC;
        }

        .col-aksi {
            width: 120px;
            text-align: center;
            vertical-align: middle;
        }

        .col-aksi>* {
            vertical-align: middle;
        }

        .btn-ico {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: transform 0.2s;
            padding: 0;
            margin: 0 6px;
            vertical-align: middle;
        }

        .btn-ico:hover {
            transform: scale(1.1);
        }

        /* Edit icon - Kuning */
        .btn-ico.edit {
            color: #f59e0b;
        }

        /* Delete icon - Merah */
        .btn-ico.delete,
        .btn-ico.danger {
            color: #ef4444;
        }

        .btn-ico i {
            font-size: 16px;
        }

        .col-aksi .btn-ico:first-child {
            margin-left: 0;
        }

        .col-aksi form {
            display: inline-flex;
            vertical-align: middle;
        }

        /* Table Footer - Sesuai Figma */
        .table-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 20px;
            border-top: 1px solid #F1F1F4;
            background: #fff;
        }

        .show-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #4B5675;
            font-size: 13px;
            white-space: nowrap;
        }

        .show-wrap form {
            display: inline-flex;
            margin: 0;
            padding: 0;
        }

        .show-wrap .form-select {
            width: 70px;
            height: 30px;
            background: #FCFCFC;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 11px;
            color: #252F4A;
            cursor: pointer;
            text-align: center;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 14px;
            padding-right: 30px;
        }

        .show-wrap .form-select:focus {
            outline: none;
            border-color: #17C653;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 14px;
            color: #4B5675;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            background: transparent;
        }

        .pagination .page-link:hover {
            background: #F5F5F5;
        }

        .pagination .page-item.active .page-link {
            background: #F1F1F4;
            color: #252F4A;
            font-weight: 500;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ========== Select2 Custom Styling ========== */
        /* .select2-container {
                                                        width: 100% !important;
                                                    } Remove this as it breaks the small dropdown when no other selects exist */


        .select2-container--default .select2-selection--single {
            height: 32px !important;
            border: 1px solid #DBDFE9 !important;
            border-radius: 6px !important;
            background: #FCFCFC !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            padding-left: 10px !important;
            padding-right: 28px !important;
            font-size: 11px !important;
            color: #7c7c7c !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px !important;
            right: 10px !important;
            top: 1px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #7c7c7c transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
            margin-top: -2px !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #7c7c7c transparent !important;
            border-width: 0 4px 5px 4px !important;
            margin-top: -2px !important;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #17C653 !important;
            outline: none !important;
        }

        /* Select2 Dropdown */
        .select2-dropdown {
            border: 1px solid #DBDFE9 !important;
            border-radius: 6px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            background: #fff !important;
            margin-top: 4px !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            font-size: 11px !important;
            color: #252F4A !important;
            cursor: pointer !important;
        }

        .select2-results__option--highlighted {
            background: #F0FDF4 !important;
            color: #047857 !important;
        }

        .select2-results__option[aria-selected="true"] {
            background: #17C653 !important;
            color: #fff !important;
        }

        /* Per Page Select2 (Smaller Width) */
        .show-wrap .select2-container {
            width: 70px !important;
        }

        .show-wrap .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-right: 30px;
            text-align: center;
            font-size: 11px !important;
        }
    </style>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Manajemen Kategori Permohonan</div>
        </div>
        <div class="page-actions">

            <a href="{{ route('admin.kategori-permohonan.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i>
                Tambah Kategori Permohonan
            </a>
        </div>
    </div>

    <section class="card" style="margin-top:18px;">
        <div class="card-header">
            <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.kategori-permohonan.index') }}">
                <div class="input-group w-search">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari Nama atau Jenis Kategori Permohonan" autocomplete="off">
                </div>

                <div class="input-group w-filter">
                    <select class="form-select" name="kind" id="filterKind">
                        <option value="">Semua Jenis Permohonan</option>
                        @foreach ($kinds as $kind)
                            <option value="{{ $kind }}" {{ request('kind') == $kind ? 'selected' : '' }}>
                                {{ $kind }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive table-shell">
                <table class="table-permohonan">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama Kategori Permohonan</th>
                            <th>Jenis Kategori Permohonan</th>
                            <th>Jumlah Pertanyaan</th>
                            <th>Keterangan</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permohonans as $i => $permohonan)
                            <tr>
                                <td class="col-no">{{ $permohonans->firstItem() + $i }}</td>
                                <td><strong>{{ $permohonan->nama }}</strong></td>
                                <td>{{ $permohonan->jenis_permohonan }}</td>
                                <td>{{ $permohonan->questions_count ?? 0 }}</td>
                                <td>{{ $permohonan->keterangan ?? '-' }}</td>
                                <td class="col-aksi">
                                    <a href="{{ route('admin.kategori-permohonan.show', $permohonan) }}" class="btn-ico"
                                        title="Detail" style="color: #009ef7;">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.kategori-permohonan.edit', $permohonan) }}"
                                        class="btn-ico edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.kategori-permohonan.destroy', $permohonan) }}"
                                        method="POST" style="display:inline-block;margin:0;" class="form-delete-permohonan"
                                        data-name="{{ $permohonan->nama }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-ico danger btn-delete-permohonan" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center"
                                    style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="table-footer">
                    <div class="show-wrap">
                        <span>Show</span>
                        <form id="perPageForm" method="GET" action="#">
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            <input type="hidden" name="by" value="{{ request('by') }}">
                            <input type="hidden" name="val" value="{{ request('val') }}">
                            <select class="form-select" name="per_page" aria-label="Jumlah baris per halaman">
                                @foreach ([10, 25, 50, 100] as $pp)
                                    <option value="{{ $pp }}"
                                        {{ (string) request('per_page', '10') === (string) $pp ? 'selected' : '' }}>
                                        {{ $pp }}</option>
                                @endforeach
                            </select>
                        </form>
                        <span>per page</span>
                    </div>

                    <nav aria-label="Pagination">
                        {{ $permohonans->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });
            @endif

            // Delete confirmation
            document.querySelectorAll('.btn-delete-permohonan').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-permohonan');
                    const name = form.dataset.name;

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus kategori permohonan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Auto submit on search
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput) {
                let timeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 500);
                });
            }

            // Select2 custom initialization
            $(document).ready(function() {
                // Per page dropdown
                $('select[name="per_page"]').select2({
                    minimumResultsForSearch: Infinity,
                    width: '70px',
                    dropdownAutoWidth: false
                }).on('select2:select', function(e) {
                    $(this).closest('form').submit();
                });

                // Kind filter dropdown
                $('#filterKind').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%',
                    placeholder: 'Semua Jenis Permohonan',
                    allowClear: true
                }).on('select2:select select2:unselect', function(e) {
                    document.getElementById('filterForm').submit();
                });
            });
        });
    </script>
@endpush
