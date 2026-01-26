@extends('admin.layouts.app')

@section('title', 'Data Permohonan Masuk')

@push('styles')
    <style>
        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            gap: 4px;
            background: #F1F5F9;
            padding: 6px;
            border-radius: 12px;
            margin-bottom: 20px;
            width: fit-content;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            color: #334155;
            background: rgba(255, 255, 255, 0.5);
        }

        .tab-btn.active {
            background: white;
            color: #0077B6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .tab-btn i {
            font-size: 16px;
        }

        .tab-btn .badge {
            background: #E0F2FE;
            color: #0077B6;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .tab-btn.active .badge {
            background: #0077B6;
            color: white;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

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

        /* Table Styles */
        .table-data {
            width: 100%;
            border-collapse: collapse;
        }

        .table-data thead {
            background: #FCFCFC;
        }

        .table-data thead th {
            background: #FCFCFC;
            color: #4B5675;
            font-weight: 500;
            font-size: 13px;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #F1F1F4;
            white-space: nowrap;
        }

        /* Filter Row Styling - Always Visible */
        .table-data thead tr.filter-row th {
            padding: 8px 16px;
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
        }

        .filter-input-perizinan,
        .filter-input-permohonan {
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

        .filter-input-perizinan::placeholder,
        .filter-input-permohonan::placeholder {
            color: #9CA3AF;
            font-style: italic;
        }

        .filter-input-perizinan:focus,
        .filter-input-permohonan:focus {
            border-color: #17C653;
            box-shadow: 0 0 0 3px rgba(23, 198, 83, 0.1);
        }

        .table-data tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 13px;
            vertical-align: middle;
        }

        .table-data tbody tr:hover {
            background: #FCFCFC;
        }

        .col-no {
            width: 50px;
            text-align: center;
        }

        .col-aksi {
            width: 140px;
            min-width: 140px;
            text-align: center;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-aktif {
            background: #ECFDF5;
            color: #059669;
        }

        .status-menunggu {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-expired {
            background: #FEE2E2;
            color: #DC2626;
        }

        .status-warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-ditolak {
            background: #F1F5F9;
            color: #64748B;
        }

        .status-akan-kadaluarsa {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-menunggu-terbit {
            background: #DBEAFE;
            color: #1E40AF;
        }

        /* Action Buttons */
        .btn-ico {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-ico:hover {
            background: #F1F5F9;
        }

        .btn-ico.view {
            color: #0077B6;
        }

        .btn-ico.edit {
            color: #D97706;
        }

        .btn-ico.danger {
            color: #DC2626;
        }

        .btn-ico i {
            font-size: 16px;
        }

        /* Table Footer */
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

        .summary {
            color: #4B5675;
            font-size: 13px;
        }

        /* Filter Section */
        .filter-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 6px 12px;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            font-size: 12px;
            background: white;
            color: #374151;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #0077B6;
        }

        /* Date display */
        .date-text {
            font-size: 12px;
            color: #6B7280;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6B7280;
        }

        .empty-state i {
            font-size: 48px;
            color: #D1D5DB;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
            margin: 0;
        }

        /* Jenis Badge */
        .jenis-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #DBEAFE;
            color: #1E40AF;
        }

        .jenis-badge.iuptls {
            background: #D1FAE5;
            color: #065F46;
        }

        .jenis-badge.sktp {
            background: #FEF3C7;
            color: #92400E;
        }

        /* Kapasitas column */
        .kapasitas-value {
            font-weight: 600;
            color: #059669;
        }

        /* Reset button */
        .btn-reset {
            padding: 6px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 12px;
            background: white;
            color: #6B7280;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-reset:hover {
            background: #F9FAFB;
            color: #374151;
        }

        /* Sortable column headers */
        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 20px;
            transition: background-color 0.2s;
        }

        .sortable-header:hover {
            background-color: #F3F4F6;
        }

        .sortable-header .sort-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #9CA3AF;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .sortable-header:hover .sort-icon,
        .sortable-header.sorted .sort-icon {
            opacity: 1;
        }

        .sortable-header.sorted-asc .sort-icon::before {
            content: '▲';
            color: #3B82F6;
        }

        .sortable-header.sorted-desc .sort-icon::before {
            content: '▼';
            color: #3B82F6;
        }

        .sortable-header.sorted .sort-icon {
            color: #3B82F6;
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Perizinan dan Permohonan</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.permohonan.index') }}" class="btn btn-primary">
                <i class="ri-add-line"></i>
                Buat Permohonan Baru
            </a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <button class="tab-btn {{ $tab === 'permohonan' ? 'active' : '' }}" data-tab="permohonan">
            <i class="ri-file-list-3-line"></i>
            <span>Manajemen Permohonan</span>
            <span class="badge">{{ $permohonanUsers->total() }}</span>
        </button>
        <button class="tab-btn {{ $tab === 'perizinan' ? 'active' : '' }}" data-tab="perizinan">
            <i class="ri-file-shield-2-line"></i>
            <span>Data Perizinan</span>
            <span class="badge">{{ $perizinanItems->total() }}</span>
        </button>
    </div>

    <!-- Tab 1: Manajemen Permohonan -->
    <div class="tab-content {{ $tab === 'permohonan' ? 'active' : '' }}" id="tab-permohonan">
        <section class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.permohonan.index') }}" id="filterFormPermohonan">
                    <input type="hidden" name="tab" value="permohonan">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                    {{-- Hidden inputs for per-column filters --}}
                    <input type="hidden" name="filter_permohonan_pengguna"
                        value="{{ request('filter_permohonan_pengguna') }}">
                    <input type="hidden" name="filter_permohonan_kategori"
                        value="{{ request('filter_permohonan_kategori') }}">
                    <input type="hidden" name="filter_permohonan_status"
                        value="{{ request('filter_permohonan_status') }}">
                    <input type="hidden" name="filter_permohonan_tanggal"
                        value="{{ request('filter_permohonan_tanggal') }}">

                    <div class="">
                        @if (request('filter_permohonan_pengguna') ||
                                request('filter_permohonan_kategori') ||
                                request('filter_permohonan_status') ||
                                request('filter_permohonan_tanggal'))
                            <a href="{{ route('admin.permohonan.index', ['tab' => 'permohonan']) }}" class="btn-reset">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table-data" id="tablePermohonan">
                        <thead>
                            <tr>
                                <th class="col-no sortable-header" data-sort="no">
                                    No
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="sortable-header" data-sort="pengguna">
                                    Pengguna
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="sortable-header" data-sort="kategori">
                                    Kategori Permohonan
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="sortable-header" data-sort="status">
                                    Status
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="sortable-header" data-sort="tanggal">
                                    Tanggal Pengajuan
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                            {{-- Filter Row --}}
                            <tr class="">
                                <th class="col-no"></th>
                                <th><input type="text" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_pengguna"
                                        value="{{ request('filter_permohonan_pengguna') }}"
                                        placeholder="Filter pengguna..." data-column="pengguna"></th>
                                <th><input type="text" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_kategori"
                                        value="{{ request('filter_permohonan_kategori') }}"
                                        placeholder="Filter kategori..." data-column="kategori"></th>
                                <th><input type="text" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_status" value="{{ request('filter_permohonan_status') }}"
                                        placeholder="Filter status..." data-column="status"></th>
                                <th><input type="date" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_tanggal" value="{{ request('filter_permohonan_tanggal') }}"
                                        placeholder="Filter tanggal..." data-column="tanggal"></th>
                                <th class="col-aksi"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permohonanUsers as $i => $item)
                                @php
                                    $statusClass = 'status-aktif';
                                    $statusText = ucfirst($item->status);

                                    // Mapping status ke format yang lebih user-friendly
                                    $statusMap = [
                                        'pending' => ['text' => 'Menunggu Verifikasi', 'class' => 'status-menunggu'],
                                        'diproses' => ['text' => 'Sedang Diproses', 'class' => 'status-menunggu'],
                                        'selesai' => ['text' => 'Aktif', 'class' => 'status-aktif'],
                                        'ditolak' => ['text' => 'Ditolak', 'class' => 'status-ditolak'],
                                        'expired' => ['text' => 'Expired', 'class' => 'status-expired'],
                                    ];

                                    if (isset($statusMap[$item->status])) {
                                        $statusText = $statusMap[$item->status]['text'];
                                        $statusClass = $statusMap[$item->status]['class'];
                                    }
                                @endphp
                                <tr>
                                    <td class="col-no">{{ $permohonanUsers->firstItem() + $i }}</td>
                                    <td><strong>{{ $item->user->name ?? '-' }}</strong></td>
                                    <td>{{ $item->permohonan->nama ?? '-' }}</td>
                                    <td><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                                    <td class="date-text">
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                                    <td class="col-aksi">
                                        <a href="{{ route('admin.permohonan-user.show', [$item->permohonan_id, $item->id]) }}"
                                            class="btn-ico view" title="Lihat Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.permohonan-user.edit', [$item->permohonan_id, $item->id]) }}"
                                            class="btn-ico edit" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form
                                            action="{{ route('admin.permohonan-user.destroy', [$item->permohonan_id, $item->id]) }}"
                                            method="POST" style="display:inline-block;margin:0;"
                                            class="form-delete-permohonan"
                                            data-name="{{ $item->user->name ?? 'permohonan ini' }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-ico danger btn-delete-permohonan"
                                                title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="ri-file-list-3-line"></i>
                                            <p>Belum ada data permohonan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <div class="summary">Menampilkan
                        <strong>{{ $permohonanUsers->firstItem() ?? 0 }}–{{ $permohonanUsers->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $permohonanUsers->total() }}</strong> data
                    </div>
                    <div>
                        {{ $permohonanUsers->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Tab 2: Data Perizinan -->
    <div class="tab-content {{ $tab === 'perizinan' ? 'active' : '' }}" id="tab-perizinan">
        <!-- Metric Cards -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px;">
            <!-- Total Perizinan -->
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #EEF2FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-file-shield-2-line" style="font-size: 24px; color: #667eea;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Total Perizinan</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">
                            {{ number_format($perizinanStats['total']) }}</div>
                    </div>
                </div>
            </div>

            <!-- IUPTLS -->
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-shield-check-line" style="font-size: 24px; color: #059669;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; font-weight: 500;">IUPTLS</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">
                            {{ number_format($perizinanStats['iuptls']) }}</div>
                    </div>
                </div>
            </div>

            <!-- SKTP -->
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #FCE7F3; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-shield-star-line" style="font-size: 24px; color: #DB2777;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; font-weight: 500;">SKTP</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">
                            {{ number_format($perizinanStats['sktp']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Sedang Aktif -->
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-checkbox-circle-line" style="font-size: 24px; color: #10B981;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Sedang Aktif</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">
                            {{ number_format($perizinanStats['aktif']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Mau Berakhir -->
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-time-line" style="font-size: 24px; color: #F59E0B;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Mau Berakhir</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">
                            {{ number_format($perizinanStats['mau_berakhir']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Berakhir -->
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #FEE2E2; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-close-circle-line" style="font-size: 24px; color: #EF4444;"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Berakhir</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">
                            {{ number_format($perizinanStats['berakhir']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <section class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.permohonan.index') }}" id="filterFormPerizinan">
                    <input type="hidden" name="tab" value="perizinan">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                    {{-- Hidden inputs for per-column filters --}}
                    <input type="hidden" name="filter_perizinan_nama" value="{{ request('filter_perizinan_nama') }}">
                    <input type="hidden" name="filter_perizinan_kabupaten"
                        value="{{ request('filter_perizinan_kabupaten') }}">
                    <input type="hidden" name="filter_perizinan_jenis" value="{{ request('filter_perizinan_jenis') }}">
                    <input type="hidden" name="filter_perizinan_no_izin"
                        value="{{ request('filter_perizinan_no_izin') }}">
                    <input type="hidden" name="filter_perizinan_tgl_terbit"
                        value="{{ request('filter_perizinan_tgl_terbit') }}">
                    <input type="hidden" name="filter_perizinan_tgl_akhir"
                        value="{{ request('filter_perizinan_tgl_akhir') }}">
                    <input type="hidden" name="filter_perizinan_status"
                        value="{{ request('filter_perizinan_status') }}">
                    <input type="hidden" name="filter_perizinan_kapasitas"
                        value="{{ request('filter_perizinan_kapasitas') }}">

                    <div class="filter-row">
                        <div class="input-group w-search">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" name="q" value="{{ $q }}" class="form-control"
                                placeholder="Cari Perusahaan, No. Izin..." autocomplete="off">
                        </div>
                        <select class="filter-select" name="status" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Sedang Aktif</option>
                            <option value="mau_berakhir" {{ $status === 'mau_berakhir' ? 'selected' : '' }}>Mau Berakhir
                            </option>
                            <option value="berakhir" {{ $status === 'berakhir' ? 'selected' : '' }}>Berakhir</option>
                        </select>
                        <select class="filter-select" name="jenis" onchange="this.form.submit()">
                            <option value="">Semua Jenis Izin</option>
                            @foreach ($jenisOptions as $jenisOption)
                                <option value="{{ $jenisOption }}" {{ $jenis === $jenisOption ? 'selected' : '' }}>
                                    {{ $jenisOption }}</option>
                            @endforeach
                        </select>
                        @if (
                            $q ||
                                $status ||
                                $jenis ||
                                request('filter_perizinan_nama') ||
                                request('filter_perizinan_kabupaten') ||
                                request('filter_perizinan_jenis') ||
                                request('filter_perizinan_no_izin') ||
                                request('filter_perizinan_tgl_terbit') ||
                                request('filter_perizinan_tgl_akhir') ||
                                request('filter_perizinan_status') ||
                                request('filter_perizinan_kapasitas'))
                            <a href="{{ route('admin.permohonan.index', ['tab' => 'perizinan']) }}" class="btn-reset">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table-data" id="tablePerizinan">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th>Nama Pemohon</th>
                                <th>Kabupaten/Kota</th>
                                <th>Jenis</th>
                                <th>No. Surat Izin</th>
                                <th>Tanggal Terbit</th>
                                <th>Tanggal Akhir</th>
                                <th>Status</th>
                                <th style="text-align: right;">Kapasitas</th>
                            </tr>
                            {{-- Filter Row --}}
                            <tr class="">
                                <th class="col-no"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_nama" value="{{ request('filter_perizinan_nama') }}"
                                        placeholder="Filter nama..." data-column="nama"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_kabupaten"
                                        value="{{ request('filter_perizinan_kabupaten') }}"
                                        placeholder="Filter kabupaten..." data-column="kabupaten"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_jenis" value="{{ request('filter_perizinan_jenis') }}"
                                        placeholder="Filter jenis..." data-column="jenis"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_no_izin" value="{{ request('filter_perizinan_no_izin') }}"
                                        placeholder="Filter no. izin..." data-column="no_izin"></th>
                                <th><input type="date" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_tgl_terbit"
                                        value="{{ request('filter_perizinan_tgl_terbit') }}"
                                        placeholder="Filter tgl terbit..." data-column="tgl_terbit"></th>
                                <th><input type="date" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_tgl_akhir"
                                        value="{{ request('filter_perizinan_tgl_akhir') }}"
                                        placeholder="Filter tgl akhir..." data-column="tgl_akhir"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_status" value="{{ request('filter_perizinan_status') }}"
                                        placeholder="Filter status..." data-column="status"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend"
                                        name="filter_perizinan_kapasitas"
                                        value="{{ request('filter_perizinan_kapasitas') }}"
                                        placeholder="Filter kapasitas..." data-column="kapasitas"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perizinanItems as $i => $izin)
                                @php
                                    // Determine status based on tanggal_akhir
                                    $statusText = 'Berakhir';
                                    $statusClass = 'status-expired';

                                    if ($izin->tanggal_akhir) {
                                        $today = now();
                                        $tanggalAkhir = \Carbon\Carbon::parse($izin->tanggal_akhir);
                                        $daysRemaining = $today->diffInDays($tanggalAkhir, false);

                                        if ($tanggalAkhir->isPast()) {
                                            // Sudah lewat tanggal akhir
                                            $statusText = 'Berakhir';
                                            $statusClass = 'status-expired';
                                        } elseif ($daysRemaining <= 30) {
                                            // Dalam 30 hari akan berakhir
                                            $statusText = 'Mau Berakhir';
                                            $statusClass = 'status-warning';
                                        } else {
                                            // Lebih dari 30 hari
                                            $statusText = 'Sedang Aktif';
                                            $statusClass = 'status-aktif';
                                        }
                                    }

                                    // Jenis badge class
                                    $jenisClass = '';
                                    if (strtoupper($izin->jenis) === 'IUPTLS') {
                                        $jenisClass = 'iuptls';
                                    } elseif (strtoupper($izin->jenis) === 'SKTP') {
                                        $jenisClass = 'sktp';
                                    }
                                @endphp
                                <tr>
                                    <td class="col-no">{{ $perizinanItems->firstItem() + $i }}</td>
                                    <td><strong>{{ $izin->nama_pemohon ?? '-' }}</strong></td>
                                    <td>{{ $izin->kabupaten_kota ?? '-' }}</td>
                                    <td>
                                        @if ($izin->jenis)
                                            <span class="jenis-badge {{ $jenisClass }}">{{ $izin->jenis }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $izin->no_surat_izin ?? '-' }}</td>
                                    <td class="date-text">
                                        {{ $izin->tanggal_terbit ? $izin->tanggal_terbit->format('d/m/Y') : '-' }}</td>
                                    <td class="date-text">
                                        {{ $izin->tanggal_akhir ? $izin->tanggal_akhir->format('d/m/Y') : '-' }}</td>
                                    <td><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                                    <td style="text-align: right;">
                                        <span class="kapasitas-value">{{ number_format($izin->kapasitas ?? 0, 2) }}</span>
                                        kVA
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="ri-file-shield-line"></i>
                                            <p>Belum ada data perizinan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <div class="summary">
                        Menampilkan
                        <strong>{{ $perizinanItems->firstItem() ?? 0 }}–{{ $perizinanItems->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $perizinanItems->total() }}</strong> data
                    </div>
                    <div>
                        {{ $perizinanItems->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Navigation
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('tab-' + tabId).classList.add('active');
                });
            });

            // Success notification
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

            // Auto submit search on enter
            const searchInput = document.querySelector('#filterFormPerizinan input[name="q"]');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterFormPerizinan').submit();
                    }
                });
            }

            // ========== Backend-Integrated Filter for Perizinan Tab ==========
            let debounceTimerPerizinan;
            const filterInputsPerizinan = document.querySelectorAll('.filter-input-perizinan.filter-backend');
            const filterFormPerizinan = document.getElementById('filterFormPerizinan');

            filterInputsPerizinan.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimerPerizinan);

                    // Update hidden input value
                    const hiddenInput = filterFormPerizinan.querySelector(
                        `input[name="${this.name}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = this.value;
                    }

                    // Debounce submit
                    debounceTimerPerizinan = setTimeout(() => {
                        filterFormPerizinan.submit();
                    }, 500);
                });
            });

            // ========== Backend-Integrated Filter for Permohonan Tab ==========
            let debounceTimerPermohonan;
            const filterInputsPermohonan = document.querySelectorAll('.filter-input-permohonan.filter-backend');
            const filterFormPermohonan = document.getElementById('filterFormPermohonan');

            filterInputsPermohonan.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimerPermohonan);

                    // Update hidden input value
                    const hiddenInput = filterFormPermohonan.querySelector(
                        `input[name="${this.name}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = this.value;
                    }

                    // Debounce submit
                    debounceTimerPermohonan = setTimeout(() => {
                        filterFormPermohonan.submit();
                    }, 500);
                });
            });

            // ========== Delete Confirmation ==========
            document.querySelectorAll('.btn-delete-permohonan').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-permohonan');
                    const name = form.dataset.name;

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus permohonan dari <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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
        });
    </script>
