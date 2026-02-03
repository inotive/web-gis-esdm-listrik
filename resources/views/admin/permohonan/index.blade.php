@extends('admin.layouts.app')

@section('title', 'Data Permohonan dan Perizinan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            gap: 4px;
            background: #F1F5F9;
            padding: 6px;
            border-radius: 12px;
            margin-bottom: 8px;
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
            text-decoration: none; /* For A tags */
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

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #F1F1F4;
            padding: 8px 20px;
        }

        /* Shared Table Styles */
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

        .col-no { width: 50px; text-align: center; }
        .col-aksi { width: 140px; min-width: 140px; text-align: center; }

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

        .status-aktif { background: #ECFDF5; color: #059669; }
        .status-menunggu { background: #FEF3C7; color: #D97706; }
        .status-proses { background: #E0F2FE; color: #0284C7; }
        .status-expired { background: #FEE2E2; color: #DC2626; }
        .status-ditolak { background: #F1F5F9; color: #64748B; }
        
        /* Action Buttons - Sesuai Figma & Perusahaan */
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
        .btn-ico.view {
            color: #0077B6;
        }

        /* Delete icon - Red */
        .btn-ico.danger {
            color: #ef4444;
        }

        .col-aksi {
            width: 160px;
            min-width: 160px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Table Footer */
        .table-footer {
            display: flex; flex-wrap: wrap; align-items: center;
            justify-content: space-between; gap: 16px;
            padding: 14px 20px; border-top: 1px solid #F1F1F4; background: #fff;
        }
        .summary { color: #4B5675; font-size: 13px; }

        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; color: #6B7280; }
        .empty-state i { font-size: 48px; color: #D1D5DB; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; margin: 0; }

        /* Reset button */
        .btn-reset {
            padding: 6px 12px; border: 1px solid #E5E7EB; border-radius: 6px;
            font-size: 12px; background: white; color: #6B7280;
            cursor: pointer; display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-reset:hover { background: #F9FAFB; color: #374151; }

        /* Sortable column */
        .sortable-header { cursor: pointer; user-select: none; position: relative; padding-right: 20px; }
        .sortable-header:hover { background-color: #F3F4F6; }
        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #E5E7EB;
            display: flex;
            flex-direction: column;
        }

        .summary-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 12px;
        }

        /* Card Variations */
        .card-blue .summary-card-icon { background: #E0F2FE; color: #0284C7; }
        .card-green .summary-card-icon { background: #DCFCE7; color: #16A34A; }
        .card-red .summary-card-icon { background: #FEE2E2; color: #DC2626; }
        .card-yellow .summary-card-icon { background: #FEF9C3; color: #CA8A04; }
        .card-zinc .summary-card-icon { background: #F4F4F5; color: #52525B; }
        .card-pink .summary-card-icon { background: #FCE7F3; color: #DB2777; }

        .summary-card-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
            line-height: 1;
        }

        .summary-card-label {
            font-size: 11px;
            font-weight: 500;
            color: #6B7280;
            line-height: 1.2;
        }

        @media (max-width: 1200px) {
            .summary-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
             .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Permohonan & Perizinan</div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <a href="{{ route('admin.permohonan.index', array_merge(request()->query(), ['tab' => 'permohonan'])) }}" 
           class="tab-btn {{ ($tab == 'permohonan' || !$tab) ? 'active' : '' }}">
            <i class="ri-file-list-3-line"></i>
            Data Permohonan
        </a>
        <a href="{{ route('admin.permohonan.index', array_merge(request()->query(), ['tab' => 'perizinan'])) }}" 
           class="tab-btn {{ $tab == 'perizinan' ? 'active' : '' }}">
            <i class="ri-file-shield-2-line"></i>
            Data Perizinan
        </a>
    </div>

    <!-- TAB 1: PERMOHONAN -->
    <div id="tab-permohonan" class="tab-content {{ ($tab == 'permohonan' || !$tab) ? 'active' : '' }}">
        <div class="mb-2 text-end">
             <a href="{{ route('admin.permohonan.import') }}" class="btn btn-secondary me-2">
                <i class="ri-file-excel-2-line"></i> Import
            </a>
            {{-- Tombol Buat Permohonan di-hide karena sekarang menggunakan fitur import saja --}}
            {{-- <a href="{{ route('admin.permohonan.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Buat Permohonan
            </a> --}}
        </div>

        <section class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.permohonan.index') }}" id="filterFormPermohonan">
                    <input type="hidden" name="tab" value="permohonan">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    
                    {{-- Hidden inputs based on backend filters --}}
                    <input type="hidden" name="filter_permohonan_pengguna" value="{{ request('filter_permohonan_pengguna') }}">
                    <input type="hidden" name="filter_permohonan_kategori" value="{{ request('filter_permohonan_kategori') }}">
                    <input type="hidden" name="filter_permohonan_status" value="{{ request('filter_permohonan_status') }}">
                    <input type="hidden" name="filter_permohonan_tanggal" value="{{ request('filter_permohonan_tanggal') }}">

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
                                <th class="col-no">No</th>
                                <th>Nama Pemohon</th>
                                <th>Kategori Permohonan</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                            <tr class="filter-row">
                                <th class="col-no"></th>
                                <th><input type="text" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_pengguna"
                                        value="{{ request('filter_permohonan_pengguna') }}"
                                        placeholder="Filter pemohon..."></th>
                                <th><input type="text" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_kategori"
                                        value="{{ request('filter_permohonan_kategori') }}"
                                        placeholder="Filter kategori..."></th>
                                <th>
                                    <select class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_status">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('filter_permohonan_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="proses" {{ request('filter_permohonan_status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="selesai" {{ request('filter_permohonan_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ request('filter_permohonan_status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </th>
                                <th><input type="date" class="filter-input-permohonan filter-backend"
                                        name="filter_permohonan_tanggal" value="{{ request('filter_permohonan_tanggal') }}"
                                        placeholder="Filter tanggal..."></th>
                                <th class="col-aksi"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permohonanUsers as $i => $item)
                                @php
                                    $statusClass = 'status-aktif';
                                    $statusText = ucfirst($item->status);
                                    $statusMap = [
                                        'pending' => ['text' => 'Menunggu Verifikasi', 'class' => 'status-menunggu'],
                                        'proses' => ['text' => 'Sedang Diproses', 'class' => 'status-proses'],
                                        'diproses' => ['text' => 'Sedang Diproses', 'class' => 'status-proses'],
                                        'selesai' => ['text' => 'Aktif', 'class' => 'status-aktif'],
                                        'ditolak' => ['text' => 'Ditolak', 'class' => 'status-ditolak'],
                                        'expired' => ['text' => 'Kedaluwarsa', 'class' => 'status-expired'],
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
                                    <td class="date-text">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                                    <td class="col-aksi">
                                        <a href="{{ route('admin.permohonan-user.show', [$item->permohonan_id, $item->id]) }}"
                                            class="btn-ico view" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>
                                        @php $isAdmin = in_array(optional(auth()->user()->roles()->first())->name, ['admin', 'superadmin']); @endphp
                                        @if($isAdmin)
                                            <a href="{{ route('admin.permohonan-user.edit', [$item->permohonan_id, $item->id]) }}"
                                                class="btn-ico edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('admin.permohonan-user.destroy', [$item->permohonan_id, $item->id]) }}"
                                                method="POST" style="display:inline-block;margin:0;" class="form-delete-permohonan"
                                                data-name="{{ $item->user->name ?? 'permohonan ini' }}">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn-ico danger btn-delete-permohonan" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
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
                    <div class="summary">Menampilkan <strong>{{ $permohonanUsers->firstItem() ?? 0 }}–{{ $permohonanUsers->lastItem() ?? 0 }}</strong> dari <strong>{{ $permohonanUsers->total() }}</strong> data</div>
                    <div>{{ $permohonanUsers->appends(['tab' => 'permohonan'])->links() }}</div>
                </div>
            </div>
        </section>
    </div>

    <!-- TAB 2: PERIZINAN -->
    <div id="tab-perizinan" class="tab-content {{ $tab == 'perizinan' ? 'active' : '' }}">
        
        <!-- Summary Cards -->
        <div class="summary-cards">
            <!-- Total Perizinan -->
            <div class="summary-card card-blue">
                <div class="summary-card-icon"><i class="ri-file-list-3-line"></i></div>
                <div class="summary-card-value">{{ number_format($statsPerizinan['total_perizinan'] ?? 0) }}</div>
                <div class="summary-card-label">Total Perizinan</div>
            </div>
            
            <!-- Total IUPTLS -->
            <div class="summary-card card-green">
                <div class="summary-card-icon"><i class="ri-shield-check-line"></i></div>
                <div class="summary-card-value">{{ number_format($statsPerizinan['total_iuptls'] ?? 0) }}</div>
                <div class="summary-card-label">IUPTLS</div>
            </div>

            <!-- Total Rekomtek SKTP -->
            <div class="summary-card card-pink">
                <div class="summary-card-icon"><i class="ri-file-shield-2-line"></i></div>
                <div class="summary-card-value">{{ number_format($statsPerizinan['total_rekomtek_sktp'] ?? 0) }}</div>
                <div class="summary-card-label">SKTP</div>
            </div>

             <!-- Sedang Aktif -->
            <div class="summary-card card-green">
                <div class="summary-card-icon"><i class="ri-checkbox-circle-line"></i></div>
                <div class="summary-card-value">{{ number_format($statsPerizinan['sedang_aktif'] ?? 0) }}</div>
                <div class="summary-card-label">Sedang Aktif</div>
            </div>

            <!-- Mau Berakhir -->
            <div class="summary-card card-yellow">
                <div class="summary-card-icon"><i class="ri-time-line"></i></div>
                <div class="summary-card-value">{{ number_format($statsPerizinan['mau_berakhir'] ?? 0) }}</div>
                <div class="summary-card-label">Mau Berakhir</div>
            </div>

            <!-- Berakhir -->
            <div class="summary-card card-red">
                <div class="summary-card-icon"><i class="ri-close-circle-line"></i></div>
                <div class="summary-card-value">{{ number_format($statsPerizinan['berakhir'] ?? 0) }}</div>
                <div class="summary-card-label">Berakhir</div>
            </div>
        </div>

        <div class="mb-2 text-end">
            <a href="{{ route('admin.perizinan.import') }}" class="btn btn-secondary me-2">
                <i class="ri-file-excel-2-line"></i> Import
            </a>
            <a href="{{ route('admin.perizinan.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Tambah Perizinan
            </a>
        </div>

        <section class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.permohonan.index') }}" id="filterFormPerizinan">
                    <input type="hidden" name="tab" value="perizinan">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    {{-- Global Search for Perizinan --}}
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">

                    <input type="hidden" name="filter_perizinan_nama" value="{{ request('filter_perizinan_nama') }}">
                    <input type="hidden" name="filter_perizinan_kabupaten" value="{{ request('filter_perizinan_kabupaten') }}">
                    <input type="hidden" name="filter_perizinan_jenis" value="{{ request('filter_perizinan_jenis') }}">
                    <input type="hidden" name="filter_perizinan_no_izin" value="{{ request('filter_perizinan_no_izin') }}">
                    <input type="hidden" name="filter_perizinan_tgl_terbit" value="{{ request('filter_perizinan_tgl_terbit') }}">
                    <input type="hidden" name="filter_perizinan_tgl_akhir" value="{{ request('filter_perizinan_tgl_akhir') }}">
                    <input type="hidden" name="filter_perizinan_status" value="{{ request('filter_perizinan_status') }}">
                    <input type="hidden" name="filter_perizinan_kapasitas" value="{{ request('filter_perizinan_kapasitas') }}">
                    
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        {{-- Search Bar --}}
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" name="q" class="form-control" placeholder="Cari Perumahan, No. Izin..." value="{{ request('q') }}" onchange="this.form.submit()">
                        </div>
                        
                        {{-- Dropdown Filters --}}
                        <div class="d-flex gap-2">
                            {{-- Filter Semua Status --}}
                            <select name="status" class="form-select" style="width: auto; min-width: 150px;" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="Sedang Aktif" {{ request('status') == 'Sedang Aktif' ? 'selected' : '' }}>Sedang Aktif</option>
                                <option value="Mau Berakhir" {{ request('status') == 'Mau Berakhir' ? 'selected' : '' }}>Mau Berakhir</option>
                                <option value="Berakhir" {{ request('status') == 'Berakhir' ? 'selected' : '' }}>Berakhir</option>
                            </select>
                            
                            {{-- Filter Semua Jenis Izin --}}
                            <select name="jenis" class="form-select" style="width: auto; min-width: 180px;" onchange="this.form.submit()">
                                <option value="">Semua Jenis Izin</option>
                                <option value="IUPTLS" {{ request('jenis') == 'IUPTLS' ? 'selected' : '' }}>IUPTLS</option>
                                <option value="SKTP" {{ request('jenis') == 'SKTP' ? 'selected' : '' }}>SKTP</option>
                            </select>
                        </div>
                        
                        {{-- Reset Button --}}
                        @if (request('q') || request('jenis') || request('status') || request('filter_perizinan_nama') || request('filter_perizinan_jenis') || request('filter_perizinan_kabupaten') || request('filter_perizinan_tgl_terbit') || request('filter_perizinan_tgl_akhir'))
                            <a href="{{ route('admin.permohonan.index', ['tab' => 'perizinan']) }}" class="btn-reset">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table-data">
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
                                <th class="text-end">Kapasitas</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                            <tr class="filter-row">
                                <th class="col-no"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend" name="filter_perizinan_nama" value="{{ request('filter_perizinan_nama') }}" placeholder="Filter nama..."></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend" name="filter_perizinan_kabupaten" value="{{ request('filter_perizinan_kabupaten') }}" placeholder="Filter kabupaten..."></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend" name="filter_perizinan_jenis" value="{{ request('filter_perizinan_jenis') }}" placeholder="Filter jenis..."></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend" name="filter_perizinan_no_izin" value="{{ request('filter_perizinan_no_izin') }}" placeholder="Filter no izin..."></th>
                                <th><input type="date" class="filter-input-perizinan filter-backend" name="filter_perizinan_tgl_terbit" value="{{ request('filter_perizinan_tgl_terbit') }}" placeholder="MM/DD/YYYY"></th>
                                <th><input type="date" class="filter-input-perizinan filter-backend" name="filter_perizinan_tgl_akhir" value="{{ request('filter_perizinan_tgl_akhir') }}" placeholder="MM/DD/YYYY"></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend" name="filter_perizinan_status" value="{{ request('filter_perizinan_status') }}" placeholder="Filter status..."></th>
                                <th><input type="text" class="filter-input-perizinan filter-backend" name="filter_perizinan_kapasitas" value="{{ request('filter_perizinan_kapasitas') }}" placeholder="Filter kapasitas..."></th>
                                <th class="col-aksi"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perizinans as $i => $item)
                                <tr>
                                    <td class="col-no">{{ $perizinans->firstItem() + $i }}</td>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        @if($item->perusahaan) <br><small class="text-muted">{{ $item->perusahaan->nama }}</small> @endif
                                    </td>
                                    <td>{{ $item->perusahaan->kabupaten_kota ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge" style="background:#DCFCE7;color:#16A34A;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">{{ $item->jenis }}</span>
                                    </td>
                                    <td>{{ $item->no_surat_keluar ?? '-' }}</td>
                                    <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $item->tanggal_akhir ? $item->tanggal_akhir->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($item->status_izin == 'Berakhir')
                                            <span class="status-badge status-expired" style="background:#FEE2E2;color:#DC2626;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">Berakhir</span>
                                        @elseif($item->status_izin == 'Mau Berakhir')
                                            <span class="status-badge status-menunggu" style="background:#FEF9C3;color:#CA8A04;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">Mau Berakhir</span>
                                        @else
                                            <span class="status-badge status-aktif" style="background:#DCFCE7;color:#16A34A;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">Sedang Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end" style="color:#059669;font-weight:700;">{{ number_format($item->total_kapasitas_kva, 2) }} kVA</td>
                                    <td class="col-aksi">
                                        <a href="{{ route('admin.perizinan.show', $item->id) }}" class="btn-ico view" title="Detail"><i class="fa-solid fa-eye"></i></a>
                                        <a href="{{ route('admin.perizinan.edit', $item->id) }}" class="btn-ico edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('admin.perizinan.destroy', $item->id) }}" method="POST" style="display:inline-block;margin:0;" class="form-delete-perizinan" data-name="{{ $item->nama }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-ico danger btn-delete-perizinan" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="empty-state">
                                            <i class="ri-file-shield-2-line"></i>
                                            <p>Belum ada data perizinan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <div class="summary">Menampilkan <strong>{{ $perizinans->firstItem() ?? 0 }}–{{ $perizinans->lastItem() ?? 0 }}</strong> dari <strong>{{ $perizinans->total() }}</strong> data</div>
                    <div>{{ $perizinans->appends(array_merge(request()->query(), ['tab' => 'perizinan']))->links() }}</div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Filter Debounce Logic for Permohonan
            let debounceTimerPermohonan;
            const filterInputsPermohonan = document.querySelectorAll('.filter-input-permohonan.filter-backend');
            const filterFormPermohonan = document.getElementById('filterFormPermohonan');

            filterInputsPermohonan.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimerPermohonan);
                    const hiddenInput = filterFormPermohonan.querySelector(`input[name="${this.name}"]`);
                    if (hiddenInput) hiddenInput.value = this.value;
                    debounceTimerPermohonan = setTimeout(() => { filterFormPermohonan.submit(); }, 500);
                });
            });

            // Filter Debounce Logic for Perizinan
            let debounceTimerPerizinan;
            const filterInputsPerizinan = document.querySelectorAll('.filter-input-perizinan.filter-backend');
            const filterFormPerizinan = document.getElementById('filterFormPerizinan');

            filterInputsPerizinan.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimerPerizinan);
                    const hiddenInput = filterFormPerizinan.querySelector(`input[name="${this.name}"]`);
                    if (hiddenInput) hiddenInput.value = this.value;
                    debounceTimerPerizinan = setTimeout(() => { filterFormPerizinan.submit(); }, 500);
                });
            });

            // Delete Confirmation Permohonan
            document.querySelectorAll('.btn-delete-permohonan').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-permohonan');
                    const name = form.dataset.name;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus permohonan <strong>${name}</strong>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });

            // Delete Confirmation Perizinan
             document.querySelectorAll('.btn-delete-perizinan').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-perizinan');
                    const name = form.dataset.name;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus perizinan <strong>${name}</strong>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });
        });
    </script>
@endpush
