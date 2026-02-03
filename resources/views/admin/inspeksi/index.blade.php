@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Inspeksi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Table Header/Filter Section */
        .card-header {
            background: white;
            border-bottom: 1px solid #F1F1F4;
            padding: 8px 20px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Search Box */
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

        /* Filter Dropdown */
        .w-filter {
            width: 160px;
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
        }

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

        /* Date Input */
        .w-date {
            width: 140px;
        }

        .form-control[type="date"] {
            padding-right: 8px;
        }

        /* Select2 Custom Styling for Filter */
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

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
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
            z-index: 9999 !important;
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

        /* Hide default arrow when Select2 is active */
        .input-group.has-select.select2-active::after {
            display: none;
        }

        /* Clear Button */
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

        /* Table Styles */
        .table-shell {
            background: white;
            overflow-x: auto;
        }

        .table-inspeksi {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .table-inspeksi thead {
            background: #FCFCFC;
        }

        .table-inspeksi thead th {
            background: #FCFCFC;
            color: #4B5675;
            font-weight: 400;
            font-size: 13px;
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #F1F1F4;
            white-space: nowrap;
        }

        .table-inspeksi tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-inspeksi tbody tr:hover {
            background: #FCFCFC;
        }

        .col-no {
            width: 48px;
            text-align: center;
        }

        .col-aksi {
            width: 120px;
            text-align: center;
            white-space: nowrap;
        }

        /* Action Buttons */
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
        }

        .btn-ico:hover {
            transform: scale(1.1);
        }

        .btn-ico.edit {
            color: #f59e0b;
        }

        .btn-ico.danger {
            color: #ef4444;
        }

        .btn-ico.download {
            color: #0077B6;
        }

        /* File Link */
        .file-link {
            color: #0077B6;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        /* Badge Empty */
        .badge-empty {
            display: inline-block;
            padding: 4px 10px;
            background: #F1F5F9;
            color: #64748B;
            font-size: 11px;
            font-weight: 500;
            border-radius: 4px;
        }

        /* Table Footer */
        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid #F1F1F4;
            background: #fff;
        }

        .summary {
            color: #4B5675;
            font-size: 13px;
        }

        /* Pagination */
        .pagination {
            display: flex;
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

        @media (max-width: 768px) {
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .w-search,
            .w-filter,
            .w-date {
                width: 100%;
            }

            .table-footer {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Data Inspeksi</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.inspeksi.create') }}" class="btn btn-primary btn-add">
                <i class="ri-add-line"></i>
                Tambah Inspeksi
            </a>
        </div>
    </div>

    <section class="card" style="margin-top:18px;">
        <div class="card-header">
            <form class="toolbar" method="GET" action="{{ route('admin.inspeksi.index') }}">
                {{-- Global Search --}}
                <div class="input-group w-search">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari Referensi / Perusahaan..." autocomplete="off">
                </div>

                {{-- Filter Perusahaan --}}
                <div class="w-filter">
                    <select class="form-select select2-perusahaan" name="perusahaan_id" id="filterPerusahaan">
                        <option value="">Semua Perusahaan</option>
                        @foreach ($perusahaans as $p)
                            <option value="{{ $p->id }}" @selected(request('perusahaan_id') == $p->id)>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tanggal Dari --}}
                <div class="input-group w-date">
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-control"
                        placeholder="Dari Tanggal">
                </div>

                {{-- Filter Tanggal Sampai --}}
                <div class="input-group w-date">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-control"
                        placeholder="Sampai Tanggal">
                </div>

                <button type="submit" class="btn-ghost">
                    <i class="ri-filter-line"></i>
                    Filter
                </button>

                {{-- Clear Button --}}
                @if (request('q') || request('perusahaan_id') || request('tanggal_dari') || request('tanggal_sampai'))
                    <a href="{{ route('admin.inspeksi.index') }}" class="btn-ghost">
                        <i class="ri-close-circle-line"></i>
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive table-shell">
                <table class="table-inspeksi">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Tanggal</th>
                            <th>Referensi Izin</th>
                            <th>Perusahaan</th>
                            <th>Ditambahkan Oleh</th>
                            <th>Berita Acara</th>
                            <th>Lampiran</th>
                            <th>Catatan</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inspeksis as $i => $inspeksi)
                            <tr>
                                <td class="col-no">{{ $inspeksis->firstItem() + $i }}</td>
                                <td>{{ $inspeksi->tanggal->translatedFormat('d F Y') }}</td>
                                <td><strong>{{ $inspeksi->referensi_izin }}</strong></td>
                                <td>{{ $inspeksi->perusahaan->nama ?? '-' }}</td>
                                <td>{{ $inspeksi->pengguna->name ?? '-' }}</td>
                                <td>
                                    @if ($inspeksi->berita_acara)
                                        {{ Str::limit($inspeksi->berita_acara, 50) }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($inspeksi->lampiran)
                                        <a href="{{ Storage::url($inspeksi->lampiran) }}" target="_blank"
                                            class="file-link">
                                            <i class="ri-attachment-line"></i>
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($inspeksi->catatan)
                                        {{ Str::limit($inspeksi->catatan, 50) }}
                                    @else
                                        <span class="badge-empty">Belum ada</span>
                                    @endif
                                </td>
                                <td class="col-aksi">
                                    <a href="{{ route('admin.inspeksi.edit', $inspeksi) }}" class="btn-ico edit"
                                        title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.inspeksi.destroy', $inspeksi) }}" method="POST"
                                        style="display:inline-block;margin:0;" class="form-delete"
                                        onsubmit="return confirm('Yakin ingin menghapus data inspeksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ico danger" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;color:#64748B;padding:40px;">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="table-footer">
                    <div class="summary">
                        Menampilkan {{ $inspeksis->firstItem() ?? 0 }} - {{ $inspeksis->lastItem() ?? 0 }} dari
                        {{ $inspeksis->total() }} data
                    </div>
                    {{ $inspeksis->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for company filter
            $('.select2-perusahaan').select2({
                placeholder: 'Semua Perusahaan',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0 // Always show search box
            });

            // Add class to parent when Select2 is active
            $('.select2-perusahaan').on('select2:open', function() {
                $(this).closest('.w-filter').addClass('select2-active');
            });

            $('.select2-perusahaan').on('select2:close', function() {
                $(this).closest('.w-filter').removeClass('select2-active');
            });

            // Auto submit form when selection changes
            $('.select2-perusahaan').on('change', function() {
                $(this).closest('form').submit();
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endpush
