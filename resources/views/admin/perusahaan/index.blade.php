@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Perusahaan')

@push('styles')
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

        .table-perusahaan {
            width: 100%;
            border-collapse: collapse;
        }

        .table-perusahaan thead {
            background: #FCFCFC;
        }

        .table-perusahaan thead th {
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

        .table-perusahaan thead th:first-child {
            border-top-left-radius: 0;
        }

        .table-perusahaan thead th:last-child {
            border-top-right-radius: 0;
        }

        /* Filter Row Styling */
        .table-perusahaan thead tr.filter-row th {
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

        .table-perusahaan tbody td {
            padding: 23px 20px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-perusahaan tbody tr:last-child td {
            border-bottom: none;
        }

        .table-perusahaan tbody tr.clickable-row {
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .table-perusahaan tbody tr.clickable-row:hover {
            background: #f0fdf4;
        }

        .table-perusahaan tbody tr:hover {
            background: #FCFCFC;
        }

        .col-no {
            width: 48px;
            text-align: center;
            color: #071437;
        }

        .col-aksi {
            width: 160px;
            min-width: 160px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .col-aksi>* {
            vertical-align: middle;
        }

        /* Action Buttons - Sesuai Figma */
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

        .btn-ico svg {
            width: 24px;
            height: 24px;
            display: block;
        }

        /* Edit icon - Orange/Yellow */
        .btn-ico.edit {
            color: #f59e0b;
        }

        /* Detail icon - Blue */
        .btn-ico.detail {
            color: #0077B6;
        }

        /* Delete icon - Red */
        .btn-ico.danger {
            color: #ef4444;
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

        /* Pagination Styles - Sesuai Figma */
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
            .table-perusahaan {
                font-size: 13px;
            }

            .table-perusahaan thead th,
            .table-perusahaan tbody td {
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

        /* Select2 Custom Styling */
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

        /* Badge Belum Ada */
        .badge-empty {
            display: inline-block;
            padding: 4px 10px;
            background: #F1F5F9;
            color: #64748B;
            font-size: 11px;
            font-weight: 500;
            border-radius: 4px;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Data Perusahaan</div>
        </div>
        <div class="page-actions">

            @include('admin.perusahaan.create') {{-- modal create --}}
            @include('admin.perusahaan.edit_modal') {{-- modal edit --}}

            @can('perusahaan.create')
            <button class="btn btn-primary btn-add">
                <i class="ri-add-line"></i>
                Tambah Data Perusahaan
            </button>
            @endcan
        </div>
    </div>

    <section class="card" style="margin-top:18px;">
        <div class="card-header">
            <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.perusahaan.index') }}">
                {{-- Global Search --}}
                <div class="input-group w-search">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari Nama / Kota / Kab..." autocomplete="off" id="globalSearch">
                </div>

                {{-- Filter Berdasarkan --}}
                <div class="input-group w-filter has-select">
                    <select class="form-select auto-submit" name="regency_id" id="filterRegency">
                        <option value="">Semua Kabupaten/Kota</option>
                        @foreach ($regencies as $rg)
                            <option value="{{ $rg->id }}" @selected(request('regency_id') == $rg->id)>{{ $rg->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Kecamatan --}}
                <div class="input-group w-filter has-select" id="filterDistrictWrapper"
                    style="{{ request('regency_id') ? '' : 'display: none;' }}">
                    <select class="form-select auto-submit" name="district_id" id="filterDistrict">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $dc)
                            <option value="{{ $dc->id }}" @selected(request('district_id') == $dc->id)>{{ $dc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Clear Search Button --}}
                @if (request('q') ||
                        request('regency_id') ||
                        request('district_id') ||
                        request('filter_nama') ||
                        request('filter_alamat') ||
                        request('filter_kontak') ||
                        request('filter_desa') ||
                        request('filter_kecamatan') ||
                        request('filter_kabupaten') ||
                        request('filter_tanggal'))
                    <a href="{{ route('admin.perusahaan.index') }}" class="btn-ghost">
                        <i class="ri-close-circle-line"></i>
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive table-shell">
                <table class="table-perusahaan">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama Perusahaan</th>
                            <th>Nama Pimpinan</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Desa/Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten/Kota</th>
                            <th>Tanggal Terdaftar</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                        {{-- Filter Row --}}

                    </thead>
                    <tbody>
                        @forelse ($perusahaans as $i => $perusahaan)
                            <tr class="clickable-row" data-href="{{ route('admin.perusahaan.show', $perusahaan) }}">
                                <td class="col-no">{{ $perusahaans->firstItem() + $i }}</td>
                                <td><strong>{{ $perusahaan->nama }}</strong></td>
                                <td>
                                    @if ($perusahaan->nama_pimpinan)
                                        {{ $perusahaan->nama_pimpinan }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perusahaan->alamat)
                                        {{ $perusahaan->alamat }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perusahaan->kontak)
                                        {{ $perusahaan->kontak }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perusahaan->village)
                                        {{ $perusahaan->village->name }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perusahaan->village && $perusahaan->village->district)
                                        {{ $perusahaan->village->district->name }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perusahaan->kabupaten_kota)
                                        {{ $perusahaan->kabupaten_kota }}
                                    @elseif($perusahaan->village && $perusahaan->village->district && $perusahaan->village->district->regency)
                                        {{ $perusahaan->village->district->regency->name }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perusahaan->created_at)
                                        {{ $perusahaan->created_at->translatedFormat('d F Y') }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td class="col-aksi">
                                    @can('perusahaan.show')
                                    <a href="{{ route('admin.perusahaan.show', $perusahaan) }}" class="btn-ico detail"
                                        title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('perusahaan.edit')
                                    <button type="button" class="btn-ico edit btn-edit-perusahaan"
                                        data-id="{{ $perusahaan->id }}" data-nama="{{ $perusahaan->nama }}"
                                        data-nama-pimpinan="{{ $perusahaan->nama_pimpinan ?? '' }}"
                                        data-alamat="{{ $perusahaan->alamat ?? '' }}"
                                        data-kontak="{{ $perusahaan->kontak ?? '' }}"
                                        data-kabupaten-kota="{{ $perusahaan->kabupaten_kota ?? '' }}"
                                        data-regency-id="{{ $perusahaan->village->district->regency_id ?? '' }}"
                                        data-district-id="{{ $perusahaan->village->district_id ?? '' }}"
                                        data-village-id="{{ $perusahaan->village_id ?? '' }}" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    @endcan
                                    @can('perusahaan.delete')
                                    <form action="{{ route('admin.perusahaan.destroy', $perusahaan) }}" method="POST"
                                        style="display:inline-block;margin:0;" class="form-delete-perusahaan"
                                        data-name="{{ $perusahaan->nama }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-ico danger btn-delete-perusahaan"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center"
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
                            <input type="hidden" name="regency_id" value="{{ request('regency_id') }}">
                            <input type="hidden" name="district_id" value="{{ request('district_id') }}">
                            <input type="hidden" name="filter_nama" value="{{ request('filter_nama') }}">
                            <input type="hidden" name="filter_alamat" value="{{ request('filter_alamat') }}">
                            <input type="hidden" name="filter_kontak" value="{{ request('filter_kontak') }}">
                            <input type="hidden" name="filter_desa" value="{{ request('filter_desa') }}">
                            <input type="hidden" name="filter_kecamatan" value="{{ request('filter_kecamatan') }}">
                            <input type="hidden" name="filter_kabupaten" value="{{ request('filter_kabupaten') }}">
                            <input type="hidden" name="filter_tanggal" value="{{ request('filter_tanggal') }}">
                            <select class="form-select auto-submit" name="per_page"
                                aria-label="Jumlah baris per halaman">
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
                        {{ $perusahaans->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Wait for jQuery and Select2 to be loaded
        function initSelect2() {
            if (typeof jQuery === 'undefined' || !jQuery.fn.select2) {
                setTimeout(initSelect2, 100);
                return;
            }

            // Initialize Select2 for filter dropdowns
            const selReg = document.getElementById('filterRegency');
            const selDis = document.getElementById('filterDistrict');
            const filterDistrictWrapper = document.getElementById('filterDistrictWrapper');

            // Initialize Select2 for regency
            if (selReg) {
                jQuery(selReg).select2({
                    placeholder: 'Semua Kota / Kab',
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

            // Initialize Select2 for district if regency is selected
            if (selDis && filterDistrictWrapper && {{ request('regency_id') ? 'true' : 'false' }}) {
                jQuery(selDis).select2({
                    placeholder: 'Semua Kecamatan',
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
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            initSelect2();

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
            document.querySelectorAll('.btn-delete-perusahaan').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-perusahaan');
                    const name = form.dataset.name;

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus data perusahaan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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

            // ========== Clickable Row Navigation ==========
            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Jangan navigasi jika klik pada tombol aksi, link, atau form
                    if (e.target.closest('.col-aksi') || e.target.closest('button') ||
                        e.target.closest('a') || e.target.closest('form')) {
                        return;
                    }

                    const href = this.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
            });

            // ========== Form & Filter ==========
            const filterForm = document.getElementById('filterForm');
            const perPageForm = document.getElementById('perPageForm');

            // Auto submit on change (works with both native and Select2)
            document.querySelectorAll('.auto-submit').forEach(el => {
                // Select2 change event (if Select2 is initialized)
                if (jQuery && jQuery.fn.select2) {
                    jQuery(el).on('change', function() {
                        if (perPageForm && perPageForm.contains(el)) {
                            perPageForm.submit();
                        } else if (filterForm) {
                            filterForm.submit();
                        }
                    });
                } else {
                    // Native change event fallback
                    el.addEventListener('change', () => {
                        if (perPageForm && perPageForm.contains(el)) {
                            perPageForm.submit();
                        } else if (filterForm) {
                            filterForm.submit();
                        }
                    });
                }
            });

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

            // Cascading filter: load kecamatan after selecting kabupaten
            const selReg = document.getElementById('filterRegency');
            const selDis = document.getElementById('filterDistrict');
            const filterDistrictWrapper = document.getElementById('filterDistrictWrapper');

            if (selReg && selDis && filterDistrictWrapper) {
                // Function to handle regency change
                const regChangeHandler = async () => {
                    const rid = selReg.value;

                    // Reset district selection
                    selDis.innerHTML = '<option value="">Semua Kecamatan</option>';

                    // Destroy and reinitialize Select2 for district
                    if (jQuery && jQuery.fn.select2 && jQuery(selDis).hasClass(
                            'select2-hidden-accessible')) {
                        jQuery(selDis).select2('destroy');
                    }
                    selDis.value = '';

                    // Show/hide district filter based on regency selection
                    if (rid) {
                        filterDistrictWrapper.style.display = '';

                        try {
                            const res = await fetch(
                                '{{ route('admin.perusahaan.options.districts') }}?regency_id=' +
                                encodeURIComponent(rid));
                            const rows = await res.json();
                            rows.forEach(r => {
                                const opt = document.createElement('option');
                                opt.value = r.id;
                                opt.textContent = r.name;
                                selDis.appendChild(opt);
                            });

                            // Reinitialize Select2 for district
                            if (jQuery && jQuery.fn.select2) {
                                jQuery(selDis).select2({
                                    placeholder: 'Semua Kecamatan',
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

                            // Restore selected district if exists in URL
                            const urlParams = new URLSearchParams(window.location.search);
                            const selectedDistrict = urlParams.get('district_id');
                            if (selectedDistrict) {
                                selDis.value = selectedDistrict;
                                if (jQuery && jQuery.fn.select2) {
                                    jQuery(selDis).trigger('change');
                                }
                            }
                        } catch (error) {
                            console.error('Error loading districts:', error);
                        }
                    } else {
                        // Hide district filter if no regency selected
                        filterDistrictWrapper.style.display = 'none';
                        // Clear district from form to prevent stale filter
                        selDis.value = '';
                        // Destroy Select2 if initialized
                        if (jQuery && jQuery.fn.select2 && jQuery(selDis).hasClass(
                                'select2-hidden-accessible')) {
                            jQuery(selDis).select2('destroy');
                        }
                    }
                };

                // Attach event listener (works with both native and Select2)
                if (jQuery && jQuery.fn.select2) {
                    jQuery(selReg).on('change', regChangeHandler);
                } else {
                    selReg.addEventListener('change', regChangeHandler);
                }
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
        // ========== Global Search with Debounce ==========
        document.addEventListener('DOMContentLoaded', function() {
            const globalSearch = document.getElementById('globalSearch');
            const filterForm = document.getElementById('filterForm');
            let searchTimeout;

            if (globalSearch) {
                globalSearch.addEventListener('input', function() {
                    // Clear previous timeout
                    clearTimeout(searchTimeout);

                    // Set new timeout (500ms debounce)
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // 500ms delay after user stops typing
                });

                // Submit on Enter key
                globalSearch.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimeout);
                        filterForm.submit();
                    }
                });
            }

            // ========== Per-Column Filter with Debounce ==========
            const filterInputs = document.querySelectorAll('.filter-backend');
            let filterTimeout;

            filterInputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Clear previous timeout
                    clearTimeout(filterTimeout);

                    // Set new timeout (500ms debounce)
                    filterTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // 500ms delay after user stops typing
                });

                // Submit on Enter key for filter inputs
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(filterTimeout);
                        filterForm.submit();
                    }
                });
            });
        });
    </script>
@endpush
