@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Desa')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        /* Filter Dropdown - Sesuai Figma */
        .w-filter {
            width: 139px;
        }

        .input-group.has-select {
            position: relative;
        }

        .input-group .form-select {
            height: 100%;
            border: none;
            background: transparent;
            padding: 0 28px 0 10px;
            font-size: 11px;
            color: #7c7c7c;
            outline: none;
            width: 100%;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .input-group .form-select option:first-child {
            color: #7c7c7c;
        }

        .input-group .form-select option:not(:first-child) {
            color: #252F4A;
        }

        .input-group .form-select:focus {
            outline: none;
        }

        /* Custom dropdown arrow inside input-group */
        .input-group.has-select::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            pointer-events: none;
        }

        /* Clear & Reset Buttons */
        .btn-ghost {
            height: 32px;
            padding: 0 10px;
            border: 1px solid #F1F1F4;
            background: #fff;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #4B5675;
            transition: all 0.2s;
        }

        .btn-ghost:hover {
            background: #F8FAFC;
        }

        .btn-ghost i {
            font-size: 14px;
        }

        /* Updated Table Styles - Sesuai Figma */
        .table-shell {
            background: white;
            overflow: hidden;
        }

        .table-desa {
            width: 100%;
            border-collapse: collapse;
        }

        .table-desa thead {
            background: #FCFCFC;
        }

        .table-desa thead th {
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

        .table-desa thead th:first-child {
            border-top-left-radius: 0;
        }

        .table-desa thead th:last-child {
            border-top-right-radius: 0;
        }

        /* Filter Row Styling */
        .table-desa thead tr.filter-row th {
            padding: 8px 20px;
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
        }

        .filter-input {
            width: 100%;
            height: 32px;
            padding: 0 10px;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            background: #fff;
            font-size: 11px;
            color: #252F4A;
            outline: none;
            transition: all 0.2s;
        }

        .filter-input::placeholder {
            color: #9CA3AF;
            font-style: italic;
        }

        .filter-input:focus {
            border-color: #17C653;
            box-shadow: 0 0 0 3px rgba(23, 198, 83, 0.1);
        }

        .table-desa tbody td {
            padding: 23px 20px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-desa tbody tr:last-child td {
            border-bottom: none;
        }

        .table-desa tbody tr:hover {
            background: #FCFCFC;
        }

        .col-no {
            width: 48px;
            text-align: center;
            color: #071437;
        }

        .col-aksi {
            width: 120px;
            text-align: center;
            vertical-align: middle;
        }

        .col-aksi>* {
            vertical-align: middle;
        }

        /* Action Buttons - Sesuai Manajemen Pengguna */
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

        /* Badge Styles for Status Berlistrik */
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.4;
            border-radius: 6px;
            text-align: center;
            white-space: nowrap;
        }

        .badge-status-success {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .badge-status-info {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .badge-status-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .badge-status-danger {
            background-color: #FEE2E2;
            color: #EF4444;
        }

        .badge-status-secondary {
            background-color: #F3F4F6;
            color: #6B7280;
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

        .table-footer-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .table-footer-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .summary {
            color: #4B5675;
            font-size: 13px;
            white-space: nowrap;
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

        /* Pagination Styles - Sesuai Manajemen Pengguna */
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

        /* ========== Select2 Custom Styling (Mengikuti Perusahaan) ========== */
        .select2-container {
            width: 100% !important;
        }

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

        .select2-search--dropdown {
            padding: 8px !important;
            border-bottom: 1px solid #F1F1F4 !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #DBDFE9 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            font-size: 11px !important;
            color: #252F4A !important;
            outline: none !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #17C653 !important;
            box-shadow: 0 0 0 3px rgba(23, 198, 83, 0.1) !important;
        }

        .select2-results {
            padding: 4px 0 !important;
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

        .select2-results__option[aria-selected="true"]:hover {
            background: #22C55E !important;
        }

        .select2-results__option--loading {
            padding: 8px 12px !important;
            color: #6b7280 !important;
            font-size: 11px !important;
        }

        .select2-results__message {
            padding: 8px 12px !important;
            color: #6b7280 !important;
            font-size: 11px !important;
        }

        /* Remove default Select2 arrow from input-group */
        .input-group.has-select::after {
            display: none !important;
        }

        /* Ensure Select2 dropdown appears above other elements */
        .select2-container--open .select2-dropdown {
            z-index: 9999 !important;
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

        /* Responsive */
        @media (max-width: 768px) {

            /* Toolbar / Filter Section */
            .toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .w-search,
            .w-filter {
                width: 100%;
            }

            /* Table */
            .table-desa {
                font-size: 13px;
            }

            .table-desa thead th,
            .table-desa tbody td {
                padding: 12px 10px;
            }

            .col-no {
                width: 40px;
            }

            /* Footer */
            .table-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-footer-left,
            .table-footer-right {
                width: 100%;
                justify-content: space-between;
            }

            .table-footer-right {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Data Desa</div>
        </div>
        <div class="page-actions">

            @include('admin.desa.create') {{-- modal create --}}
            @include('admin.desa.edit_modal') {{-- modal edit --}}

            <button class="btn btn-primary btn-add">
                <i class="ri-add-line"></i>
                Tambah Data Desa
            </button>
        </div>
    </div>

    <section class="card" style="margin-top:18px;">
        <div class="card-header">
            <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.desa.index') }}">
                {{-- Search Nama Desa --}}
                <div class="input-group w-search">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari Nama Desa" autocomplete="off">
                </div>

                {{-- Filter Berdasarkan --}}
                <div class="input-group w-filter has-select">
                    <select class="form-select" name="regency_id" id="filterRegency">
                        <option value="">Semua Kabupaten/Kota</option>
                        @foreach ($regencies as $rg)
                            <option value="{{ $rg->id }}" @selected(request('regency_id') == $rg->id)>{{ $rg->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Kecamatan --}}
                @if (request('regency_id'))
                    <div class="input-group w-filter has-select">
                        <select class="form-select" name="district_id" id="filterDistrict">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($districts as $dc)
                                <option value="{{ $dc->id }}" @selected(request('district_id') == $dc->id)>{{ $dc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="input-group w-filter has-select" style="display: none;">
                        <select class="form-select" name="district_id" id="filterDistrict">
                            <option value="">Semua Kecamatan</option>
                        </select>
                    </div>
                @endif

                {{-- Filter Status Listrik --}}
                <div class="input-group w-filter has-select">
                    <select class="form-select" name="status" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="Terlayani Listrik" @selected(request('status') == 'Terlayani Listrik')>Terlayani Listrik</option>
                        <option value="Belum Terlayani Listrik" @selected(request('status') == 'Belum Terlayani Listrik')>Belum Terlayani Listrik
                        </option>
                    </select>
                </div>

                {{-- Clear Search Button --}}
                @if (request('q') ||
                        request('regency_id') ||
                        request('district_id') ||
                        request('status') ||
                        request('filter_desa_nama') ||
                        request('filter_desa_kecamatan') ||
                        request('filter_desa_kabupaten') ||
                        request('filter_desa_status'))
                    <a href="{{ route('admin.desa.index') }}" class="btn-ghost">
                        <i class="ri-close-circle-line"></i>
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive table-shell">
                <table class="table-desa">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama Desa</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten/Kota</th>
                            <th>Status Listrik</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($desas as $i => $desa)
                            <tr>
                                <td class="col-no">{{ $desas->firstItem() + $i }}</td>
                                <td><strong>{{ $desa->name }}</strong></td>
                                <td>{{ $desa->district->name ?? '-' }}</td>
                                <td>{{ $desa->district->regency->name ?? '-' }}</td>
                                <td>
                                    @php
                                        // Priority 1: From ImportedJsonFeature map
                                        // Priority 2: From local table (status_berlistrik)
                                        // dd($desa->status_berlistrik);
                                        $statusProps = $statusMap[$desa->name] ?? null;
                                        $status = $statusProps['StatusDesa'] ?? ($desa->status_berlistrik ?? '');
                                        $statusUpper = strtoupper($status);
                                    @endphp

                                    @if ($status)
                                        @if (str_contains($statusUpper, 'BELUM') || str_contains($statusUpper, 'TIDAK'))
                                            <span class="badge-status badge-status-danger">{{ $status }}</span>
                                        @elseif(str_contains($statusUpper, 'TERLAYANI') || str_contains($statusUpper, 'BERLISTRIK'))
                                            <span class="badge-status badge-status-success">{{ $status }}</span>
                                        @else
                                            <span class="badge-status badge-status-info">{{ $status }}</span>
                                        @endif
                                    @else
                                        <span class="badge-status badge-status-secondary">Tidak Ada Data</span>
                                    @endif
                                </td>
                                <td class="col-aksi">
                                    <button type="button" class="btn-ico edit btn-edit-desa" data-id="{{ $desa->id }}"
                                        data-name="{{ $desa->name }}"
                                        data-regency-id="{{ $desa->district->regency_id ?? '' }}"
                                        data-district-id="{{ $desa->district_id }}" data-status="{{ $status }}"
                                        title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.desa.destroy', $desa) }}" method="POST"
                                        style="display:inline-block;margin:0;" class="form-delete-desa"
                                        data-name="{{ $desa->name }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-ico delete btn-delete-desa" title="Hapus">
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
                    <div class="table-footer-left">
                        <div class="summary">Menampilkan {{ $desas->count() }} data</div>
                    </div>
                    <div class="table-footer-right">
                        <div class="show-wrap">
                            <span>Show</span>
                            <form id="perPageForm" method="GET" action="#">
                                <input type="hidden" name="q" value="{{ request('q') }}">
                                <input type="hidden" name="regency_id" value="{{ request('regency_id') }}">
                                <input type="hidden" name="district_id" value="{{ request('district_id') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
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
                            {{ $desas->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
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
            // ========== SweetAlert Notifications ==========
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
                    customClass: {
                        popup: 'swal-custom-toast'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#22C55E',
                    confirmButtonText: 'OK'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: '<ul style="text-align:left; padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#22C55E',
                    confirmButtonText: 'OK'
                });
            @endif

            // ========== Delete Confirmation ==========
            document.querySelectorAll('.btn-delete-desa').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-desa');
                    const name = form.dataset.name;

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus data desa <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn-swal-confirm',
                            cancelButton: 'btn-swal-cancel'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // ========== Form & Filter ==========
            const filterForm = document.getElementById('filterForm');
            const perPageForm = document.getElementById('perPageForm');

            // Note: Auto submit is now handled by Select2 change events above

            // Auto submit on search (with debounce)
            const searchInput = filterForm?.querySelector('input[name="q"]');
            if (searchInput) {
                let timeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // Submit after 500ms of no typing
                });
            }

            // ========== Initialize Select2 ==========
            // Wait for jQuery to be ready
            $(document).ready(function() {
                // Initialize Select2 for all select elements with search enabled
                function initSelect2() {
                    // Filter Regency (Kabupaten)
                    if ($('#filterRegency').length && !$('#filterRegency').hasClass(
                            'select2-hidden-accessible')) {
                        $('#filterRegency').select2({
                            placeholder: 'Semua Kabupaten/Kota',
                            allowClear: true,
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "Tidak ada hasil";
                                },
                                searching: function() {
                                    return "Mencari...";
                                }
                            }
                        });
                    }

                    // Filter District (Kecamatan)
                    if ($('#filterDistrict').length && !$('#filterDistrict').hasClass(
                            'select2-hidden-accessible')) {
                        $('#filterDistrict').select2({
                            placeholder: 'Semua Kecamatan',
                            allowClear: true,
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "Tidak ada hasil";
                                },
                                searching: function() {
                                    return "Mencari...";
                                }
                            }
                        });
                    }

                    // Filter Status
                    if ($('#filterStatus').length && !$('#filterStatus').hasClass(
                            'select2-hidden-accessible')) {
                        $('#filterStatus').select2({
                            placeholder: 'Semua Status',
                            allowClear: true,
                            width: '100%',
                            minimumResultsForSearch: Infinity,
                            language: {
                                noResults: function() {
                                    return "Tidak ada hasil";
                                }
                            }
                        });
                    }

                    // Per Page Selector
                    if ($('select[name="per_page"]').length && !$('select[name="per_page"]').hasClass(
                            'select2-hidden-accessible')) {
                        $('select[name="per_page"]').select2({
                            width: '70px',
                            minimumResultsForSearch: Infinity, // Disable search for per page (small list)
                            language: {
                                noResults: function() {
                                    return "Tidak ada hasil";
                                }
                            }
                        });
                    }
                }

                // Initialize Select2
                initSelect2();
            });

            // ========== Cascading filter with Select2 ==========
            $(document).ready(function() {
                const selReg = $('#filterRegency');
                const selDis = $('#filterDistrict');

                if (selReg.length && selDis.length) {
                    selReg.on('change', async function() {
                        const rid = $(this).val();

                        // Destroy existing Select2 instance
                        if (selDis.hasClass('select2-hidden-accessible')) {
                            selDis.select2('destroy');
                        }

                        // Clear and reset district select
                        selDis.empty().append('<option value="">Semua Kecamatan</option>');
                        selDis.val('').trigger('change');

                        if (!rid) {
                            // Hide district filter if no regency selected
                            selDis.closest('.input-group').hide();
                            return;
                        }

                        // Show district filter
                        selDis.closest('.input-group').show();

                        try {
                            const res = await fetch(
                                '{{ route('admin.desa.options.districts') }}?regency_id=' +
                                encodeURIComponent(rid));
                            const rows = await res.json();

                            rows.forEach(r => {
                                const opt = new Option(r.name, r.id, false, false);
                                selDis.append(opt);
                            });

                            // Re-initialize Select2 for district after options are added
                            selDis.select2({
                                placeholder: 'Semua Kecamatan',
                                allowClear: true,
                                width: '100%',
                                language: {
                                    noResults: function() {
                                        return "Tidak ada hasil";
                                    },
                                    searching: function() {
                                        return "Mencari...";
                                    }
                                }
                            });

                            // Set selected value if exists in request
                            @if (request('district_id'))
                                selDis.val('{{ request('district_id') }}').trigger('change');
                            @endif
                        } catch (error) {
                            console.error('Error loading districts:', error);
                        }
                    });
                }

                // ========== Auto submit on Select2 change ==========
                // Handle auto-submit for filter form
                $('#filterRegency, #filterDistrict, #filterStatus').on('change', function() {
                    // Small delay to ensure Select2 value is set
                    setTimeout(() => {
                        const form = document.getElementById('filterForm');
                        if (form) {
                            form.submit();
                        }
                    }, 100);
                });

                // Handle auto-submit for per page form
                $('select[name="per_page"]').on('change', function() {
                    setTimeout(() => {
                        const form = document.getElementById('perPageForm');
                        if (form) {
                            form.submit();
                        }
                    }, 100);
                });
            });

            // ========== Update Pagination Icons (Like Manajemen Pengguna) ==========
            function updatePaginationIcons() {
                const pagination = document.querySelector('.pagination');
                if (!pagination) return;

                pagination.querySelectorAll('.page-link').forEach(link => {
                    const text = link.textContent.trim();
                    const rel = link.getAttribute('rel');
                    const href = link.getAttribute('href');

                    // Detect previous link: rel="prev" or text contains "‹" or "«"
                    const isPrevious = rel === 'prev' || text === '‹' || text === '«' ||
                        (href && href.includes('page=') && text.match(/previous|sebelum/i));

                    // Detect next link: rel="next" or text contains "›" or "»"
                    const isNext = rel === 'next' || text === '›' || text === '»' ||
                        (href && href.includes('page=') && text.match(/next|berikut/i));

                    if (isPrevious && !link.querySelector('i')) {
                        link.innerHTML = '<i class="ri-arrow-left-s-line"></i>';
                    } else if (isNext && !link.querySelector('i')) {
                        link.innerHTML = '<i class="ri-arrow-right-s-line"></i>';
                    }
                });
            }

            // Run on page load
            setTimeout(updatePaginationIcons, 100);

            // Also run after any DOM changes (if using dynamic content)
            const observer = new MutationObserver(() => {
                setTimeout(updatePaginationIcons, 50);
            });
            const paginationContainer = document.querySelector('nav[aria-label="Pagination"]');
            if (paginationContainer) {
                observer.observe(paginationContainer, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>

    <style>
        /* Custom SweetAlert Styling */
        .swal-custom-toast {
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 16px !important;
        }

        .swal2-title {
            font-weight: 700 !important;
            font-size: 20px !important;
        }

        .swal2-html-container {
            font-size: 14px !important;
        }

        .btn-swal-confirm,
        .btn-swal-cancel {
            padding: 10px 20px !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
        }

        .swal2-icon {
            margin: 1.5rem auto 1rem !important;
        }
    </style>

    <script>
        // ========== Column Filter Functionality ==========
        document.addEventListener('DOMContentLoaded', function() {
            const filterInputs = document.querySelectorAll('.filter-input');
            const tableRows = document.querySelectorAll('.table-desa tbody tr');

            filterInputs.forEach(input => {
                input.addEventListener('input', function() {
                    tableRows.forEach(row => {
                        // Skip empty state row
                        if (row.querySelector('td[colspan]')) return;

                        // Check all filters to determine if row should be visible
                        let allFiltersMatch = true;
                        filterInputs.forEach((filterInput) => {
                            const filterVal = filterInput.value.toLowerCase()
                                .trim();
                            if (filterVal) {
                                const columnIndex = Array.from(filterInput.closest(
                                    'tr').children).indexOf(filterInput.closest(
                                    'th'));
                                const targetCell = row.children[columnIndex];
                                if (targetCell) {
                                    const targetText = targetCell.textContent
                                        .toLowerCase().trim();
                                    if (!targetText.includes(filterVal)) {
                                        allFiltersMatch = false;
                                    }
                                }
                            }
                        });

                        // Show/hide row based on all filters
                        if (allFiltersMatch) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Update row count
                    updateVisibleRowCount();
                });
            });

            // Function to update visible row count
            function updateVisibleRowCount() {
                const visibleRows = Array.from(tableRows).filter(row => {
                    return row.style.display !== 'none' && !row.querySelector('td[colspan]');
                });

                const summary = document.querySelector('.summary');
                if (summary) {
                    const total = tableRows.length - (document.querySelector('.table-desa tbody tr td[colspan]') ?
                        1 : 0);
                    const visible = visibleRows.length;

                    if (visible < total) {
                        summary.textContent = `Menampilkan ${visible} dari ${total} data (filtered)`;
                    } else {
                        summary.textContent = `Menampilkan ${visible} data`;
                    }
                }
            }
        });
    </script>
@endpush
