@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Detail Perizinan ' . $kabupaten)

@push('styles')
    <style>
        /* Page Header */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6B7280;
            font-size: 13px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #059669;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.primary {
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
        }

        .stat-icon.blue {
            background: rgba(3, 105, 161, 0.1);
            color: #0369A1;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            margin-top: 2px;
        }

        /* Table Card */
        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            display: flex;
            align-items: center;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 0 12px;
        }

        .search-input i {
            color: #9CA3AF;
        }

        .search-input input {
            border: none;
            background: transparent;
            padding: 8px 10px;
            font-size: 13px;
            outline: none;
            min-width: 200px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 13px;
            background: white;
            cursor: pointer;
            min-width: 150px;
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border: 1px solid #E5E7EB;
            background: white;
            border-radius: 8px;
            font-size: 13px;
            color: #6B7280;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: #F3F4F6;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
            font-size: 13px;
        }

        .data-table th {
            background: #F9FAFB;
            font-weight: 600;
            color: #374151;
        }

        .data-table tr:hover td {
            background: #F9FAFB;
        }

        .data-table td.col-no {
            text-align: center;
            width: 50px;
            color: #6B7280;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-blue {
            background: #E0F2FE;
            color: #0369A1;
        }

        .badge-green {
            background: #DCFCE7;
            color: #166534;
        }

        .badge-yellow {
            background: #FEF3C7;
            color: #92400E;
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

        /* Table Footer */
        .table-footer {
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #E5E7EB;
            background: #F9FAFB;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-footer .summary {
            font-size: 13px;
            color: #6B7280;
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <a href="{{ route('admin.rekap-data.index') }}?tab=infrastruktur" class="back-link">
                <i class="ri-arrow-left-line"></i> Kembali ke Rekap Data
            </a>
            <div class="page-title">Detail Perizinan - {{ $kabupaten }}</div>
        </div>
        <div class="page-actions">
            <div class="date-pill">
                <i class="ri-calendar-line"></i>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="ri-file-list-3-line"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_perizinan']) }}</div>
                <div class="stat-label">Total Perizinan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="ri-flashlight-line"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_kapasitas'], 2) }}</div>
                <div class="stat-label">Total Kapasitas (kVA)</div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Perizinan</div>
            <form class="filter-bar" method="GET" action="{{ route('admin.rekap-data.detail', $kabupaten) }}">
                <div class="search-input">
                    <i class="ri-search-line"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari pemohon, no. izin..." autocomplete="off">
                </div>
                @if($jenisOptions->count() > 0)
                <select name="jenis" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions as $opt)
                        <option value="{{ $opt }}" {{ $jenis === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                @endif
                <button type="button" class="btn-reset" onclick="window.location.href='{{ route('admin.rekap-data.detail', $kabupaten) }}'">
                    <i class="ri-refresh-line"></i> Reset
                </button>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis</th>
                        <th>No. Surat Izin</th>
                        <th>Tanggal Terbit</th>
                        <th>Tanggal Akhir</th>
                        <th>Lokasi</th>
                        <th style="text-align:right;">Kapasitas (kVA)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perizinanItems as $i => $item)
                        <tr>
                            <td class="col-no">{{ ($perizinanItems->currentPage() - 1) * $perizinanItems->perPage() + $i + 1 }}</td>
                            <td><strong>{{ $item->nama_pemohon ?: '-' }}</strong></td>
                            <td>
                                @if($item->jenis)
                                    <span class="badge badge-blue">{{ $item->jenis }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->no_surat_izin ?: '-' }}</td>
                            <td>
                                @if($item->tanggal_terbit && $item->tanggal_terbit->year > 1900)
                                    {{ $item->tanggal_terbit->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($item->tanggal_akhir && $item->tanggal_akhir->year > 1900)
                                    @if($item->tanggal_akhir->isPast())
                                        <span class="badge badge-yellow">{{ $item->tanggal_akhir->format('d/m/Y') }}</span>
                                    @else
                                        <span class="badge badge-green">{{ $item->tanggal_akhir->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->lokasi ?: '-' }}</td>
                            <td style="text-align:right;"><strong>{{ number_format($item->total_kapasitas, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="ri-file-list-3-line"></i>
                                    <p>Belum ada data perizinan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($perizinanItems->count() > 0)
        <div class="table-footer">
            <div class="summary">
                Menampilkan <strong>{{ $perizinanItems->firstItem() }}–{{ $perizinanItems->lastItem() }}</strong> 
                dari <strong>{{ $perizinanItems->total() }}</strong> perizinan
            </div>
            {{ $perizinanItems->links() }}
        </div>
        @endif
    </div>
@endsection
