@extends('admin.layouts.app')

@section('title', 'Dashboard - BPKAD')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkfKZ5q6rpTqjCPe5rG1LRU1QMjQ9cx203QF5pDgS63Z8psI5qF1J8Kmg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* ====== LAYOUT GUARD: batasi lebar halaman ====== */
    .dash-wrap {
        max-width: 1200px;        /* << batas maksimal agar tidak melebar */
        margin: 0 auto;
        padding: 0 16px;
    }

    .welcome-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .welcome-text h2 { font-size: 14px; font-weight: 400; color: #78829d; margin-bottom: 8px; }
    .welcome-text h1 { font-size: 32px; font-weight: 700; color: #1e3456; }

    .date-filter { display: flex; flex-direction: column; gap: 8px; }
    .date-filter label { font-size: 13px; font-weight: 500; color: #071324; }
    .date-selector {
        display: flex; align-items: center; gap: 8px; padding: 10px 14px;
        background-color: #fcfcfc; border: 1px solid #dcdcdc; border-radius: 6px; cursor: pointer; transition: all 0.2s;
    }
    .date-selector:hover { border-color: #701229; }
    .date-selector span { font-size: 13px; color: #071324; font-weight: 500; }

    .stats-card {
        background-color: white; border-radius: 24px; padding: 24px; margin-bottom: 20px;
        box-shadow: 0px 1px 3px rgba(16, 24, 40, 0.1), 0px 1px 2px rgba(16, 24, 40, 0.06);
    }
    .stats-header { margin-bottom: 20px; }
    .stats-header h3 { font-size: 13px; font-weight: 400; color: #78829d; margin-bottom: 6px; }
    .stats-header h2 { font-size: 28px; font-weight: 700; color: #1e3456; letter-spacing: -0.72px; }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .stat-item {
        background-color: #ffffff; border: 1px solid #f1f1f4; border-radius: 14px; padding: 14px; transition: all 0.2s;
    }
    .stat-item:hover { box-shadow: 0 4px 12px rgba(16, 24, 40, 0.08); transform: translateY(-2px); }
    .stat-item-content { display: flex; align-items: flex-start; gap: 12px; }
    .stat-icon {
        width: 36px; height: 36px; background-color: #f6f6f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-icon i { font-size: 18px; color: #701229; }
    .stat-info h4 { font-size: 13px; font-weight: 600; color: #071437; margin-bottom: 4px; }
    .stat-info p { font-size: 16px; font-weight: 700; color: #071437; letter-spacing: -0.36px; }

    .demographics-card {
        background-color: white; border-radius: 24px; padding: 24px;
        box-shadow: 0px 1px 3px rgba(16, 24, 40, 0.1), 0px 1px 2px rgba(16, 24, 40, 0.06);
    }
    .demographics-header { margin-bottom: 18px; }
    .demographics-header h2 { font-size: 18px; font-weight: 700; color: #071324; }

    /* ====== CHART: Grid agar tidak melebar ====== */
    .demographics-chart {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); /* wrap otomatis */
        gap: 12px;
        margin-bottom: 16px;
    }
    .region-bar { display: flex; flex-direction: column; align-items: center; min-width: 0; }
    .bars {
        display: flex; align-items: flex-end; justify-content: center;
        gap: 4px; margin-bottom: 8px; height: 200px;          /* skala maksimum */
        width: 100%;
    }
    .bar { width: 12px; border-radius: 4px 4px 0 0; transition: opacity .2s ease; }
    .bar:hover { opacity: .9; }

    .region-name {
        font-size: 11px; font-weight: 600; color: #071437; text-align: center; line-height: 14px;
        word-break: break-word; hyphens: auto; max-width: 100%;
    }

    .chart-legend-bottom { display: flex; gap: 16px; flex-wrap: wrap; }
    .legend-item-bottom { display: flex; align-items: center; gap: 8px; }
    .legend-color-bottom { width: 12px; height: 12px; border-radius: 3px; }
    .legend-text-bottom { font-size: 12px; font-weight: 600; color: #071437; }

    /* ====== Tabel ringkas: tidak melebar & rapi ====== */
    .table-wrap { overflow-x: auto; }
    .table-compact { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 13px; }
    .table-compact th, .table-compact td { padding: 10px 8px; border-bottom: 1px solid #f3f3f3; word-wrap: break-word; }
    .table-compact thead th { border-bottom: 1px solid #eee; text-align: left; color: #071437; }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .welcome-text h1 { font-size: 24px; }
        .stats-header h2 { font-size: 24px; }
        .bars { height: 170px; }
    }
</style>
@endpush

@section('content')
<div class="dash-wrap"><!-- wrapper agar tidak melebar -->

    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-text">
            <h2>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h2>
            <h1>Hallo, {{ Auth::user()->name ?? 'Superadmin' }}</h1>
        </div>
        <div class="date-filter">
            <label>Tanggal</label>
            <div class="date-selector">
                <i class="fa-solid fa-calendar-days fs-4"></i>
                <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Asset (Total) -->
    <div class="stats-card">
        <div class="stats-header">
            <h3>Statistik Aset</h3>
            <h2>{{ number_format($totalAsset, 0, ',', '.') }} aset</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-item-content">
                    <div class="stat-icon"><i class="fa-solid fa-house"></i></div>
                    <div class="stat-info">
                        <h4>Total Asset</h4>
                        <p>{{ number_format($totalAsset, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-item-content">
                    <div class="stat-icon"><i class="fa-solid fa-building-columns"></i></div>
                    <div class="stat-info">
                        <h4>Total Luas (m²)</h4>
                        <p>{{ number_format($totalLuas, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-item-content">
                    <div class="stat-icon"><i class="fa-solid fa-user"></i></div>
                    <div class="stat-info">
                        <h4>Total Unit Kerja</h4>
                        <p>{{ number_format($totalUnitKerja, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Asset per Kabupaten -->
    <div class="stats-card">
        <div class="stats-header">
            <h3>Jumlah Asset per Kabupaten</h3>
            <h2>Ringkasan per Wilayah</h2>
        </div>
        <div class="table-wrap">
            <table class="table-compact">
                <thead>
                    <tr>
                        <th style="width: 45%;">Kabupaten</th>
                        <th style="width: 20%;">Jumlah Aset</th>
                        <th style="width: 35%;">Total Luas (m²)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jumlahAssetPerKab as $row)
                        <tr>
                            <td>{{ $row['kabupaten'] }}</td>
                            <td>{{ number_format($row['total'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['total_luas'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Demografi Wilayah — Asal Tanah per Kabupaten (DINAMIS dari kolom `asal`) -->
    @php
        /* Palet warna untuk kategori asal (putar jika kategori > palet) */
        $palette = ['#e30013', '#4479db', '#cdcbc4', '#22c55e', '#f59e0b', '#8b5cf6', '#14b8a6', '#ec4899', '#0ea5e9', '#f97316'];
        /* Label tampilannya: title-case dari key normalisasi */
        $asalDisplay = collect($asalLabels)->map(fn($k) => ucwords($k));
    @endphp

    <div class="demographics-card">
        <div class="demographics-header">
            <h2>Demografi Wilayah — Asal Tanah per Kabupaten</h2>
        </div>

        <div class="demographics-chart">
            @foreach($asalPerKab as $row)
                <div class="region-bar">
                    <div class="bars">
                        @foreach($asalLabels as $i => $key)
                            @php
                                $val = $row->counts[$key] ?? 0;
                                $h   = (int) floor(($val / $maxAsal) * 200); /* skala 0..200px */
                                $bg  = $palette[$i % count($palette)];
                                $title = ($asalDisplay[$i] ?? ucfirst($key)) . ': ' . number_format($val, 0, ',', '.');
                            @endphp
                            <div class="bar" style="height: {{ $h }}px; background: {{ $bg }};" title="{{ $title }}"></div>
                        @endforeach
                    </div>
                    <div class="region-name">{{ $row->kabupaten }}</div>
                </div>
            @endforeach
        </div>

        <div class="chart-legend-bottom">
            @foreach($asalDisplay as $i => $label)
                <div class="legend-item-bottom">
                    <div class="legend-color-bottom" style="background-color: {{ $palette[$i % count($palette)] }};"></div>
                    <div class="legend-text-bottom">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    </div>

</div><!-- /.dash-wrap -->
@endsection

@push('scripts')
<script>
    // Hover halus untuk bar
    document.querySelectorAll('.bar').forEach(bar => {
        bar.addEventListener('mouseenter', function(){ this.style.opacity = '0.9'; });
        bar.addEventListener('mouseleave', function(){ this.style.opacity = '1'; });
    });
</script>
@endpush
