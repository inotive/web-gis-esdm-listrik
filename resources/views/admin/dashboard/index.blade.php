@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Keterlayanan Listrik Kalimantan Timur')

@push('styles')
    <style>
        /* ===== SUMMARY CARDS ===== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #E5E7EB;
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 10px;
            background: rgba(23, 198, 83, 0.1);
            color: #059669;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }

        .summary-label {
            font-size: 13px;
            color: #6B7280;
        }

        /* ===== CARDS ===== */
        .dash-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .dash-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #F1F5F9;
        }

        .dash-card-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .dash-card-body {
            padding: 20px;
        }

        /* ===== CHART CONTAINER ===== */
        .chart-container {
            position: relative;
            height: 280px;
        }

        /* ===== GRID LAYOUT ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .infra-chart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        /* ===== KABUPATEN LIST ===== */
        .kabupaten-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .kabupaten-item {
            margin-bottom: 14px;
        }

        .kabupaten-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .kabupaten-name {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
        }

        .kabupaten-value {
            font-size: 12px;
            font-weight: 600;
            color: #059669;
        }

        .kabupaten-bar {
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
        }

        .kabupaten-fill {
            height: 100%;
            border-radius: 4px;
            background: #059669;
            transition: width 0.5s ease;
        }

        /* ===== INFRA STATS ===== */
        .infra-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .infra-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #E5E7EB;
            text-align: center;
        }

        .infra-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 auto 10px;
            background: rgba(23, 198, 83, 0.1);
            color: #059669;
        }

        .infra-value {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .infra-label {
            font-size: 12px;
            color: #6B7280;
            margin-top: 2px;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #059669;
        }

        /* ===== LEGEND ===== */
        .chart-legend {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6B7280;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .legend-dot.green {
            background: #059669;
        }

        .legend-dot.gray {
            background: #9CA3AF;
        }

        /* ===== INSIGHTS ===== */
        .insight-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .insight-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #E5E7EB;
        }

        .insight-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .insight-title.green {
            color: #059669;
        }

        .insight-title.orange {
            color: #D97706;
        }

        .insight-title.red {
            color: #DC2626;
        }

        .insight-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .insight-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #F1F5F9;
            font-size: 13px;
        }

        .insight-list li:last-child {
            border-bottom: none;
        }

        .insight-name {
            color: #374151;
        }

        .insight-value {
            font-weight: 600;
        }

        .insight-value.green {
            color: #059669;
        }

        .insight-value.orange {
            color: #D97706;
        }

        .insight-value.red {
            color: #DC2626;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {

            .summary-grid,
            .infra-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .infra-chart-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .insight-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {

            .summary-grid,
            .infra-grid {
                grid-template-columns: 1fr;
            }

            .infra-chart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Dashboard Keterlayanan Listrik</div>
        </div>
        <div class="page-actions">
            <div class="date-pill">
                <i class="ri-calendar-line"></i>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Permohonan Summary Cards -->
    <div class="section-title"><i class="ri-file-list-3-line"></i> Statistik Permohonan</div>
    <div class="summary-grid">
        <div class="summary-card" style="border-left: 4px solid #3B82F6;">
            <div class="summary-icon" style="background: #EFF6FF; color: #3B82F6;"><i class="ri-folder-open-line"></i></div>
            <div class="summary-value">{{ number_format($totalPermohonan) }}</div>
            <div class="summary-label">Total Permohonan</div>
        </div>
        <div class="summary-card" style="border-left: 4px solid #F59E0B;">
            <div class="summary-icon" style="background: #FEF3C7; color: #F59E0B;"><i class="ri-time-line"></i></div>
            <div class="summary-value">{{ number_format($permohonanPending) }}</div>
            <div class="summary-label">Menunggu Approval</div>
        </div>
        <div class="summary-card" style="border-left: 4px solid #10B981;">
            <div class="summary-icon" style="background: #D1FAE5; color: #10B981;"><i class="ri-checkbox-circle-line"></i></div>
            <div class="summary-value">{{ number_format($permohonanApproved) }}</div>
            <div class="summary-label">Disetujui</div>
        </div>
        <div class="summary-card" style="border-left: 4px solid #EF4444;">
            <div class="summary-icon" style="background: #FEE2E2; color: #EF4444;"><i class="ri-close-circle-line"></i></div>
            <div class="summary-value">{{ number_format($permohonanRejected) }}</div>
            <div class="summary-label">Ditolak</div>
        </div>
    </div>

    <!-- Summary Cards (Existing) -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-icon"><i class="ri-government-line"></i></div>
            <div class="summary-value">{{ number_format($totalKabKota) }}</div>
            <div class="summary-label">Kabupaten/Kota</div>
        </div>
        <div class="summary-card">
            <div class="summary-icon"><i class="ri-home-4-line"></i></div>
            <div class="summary-value">{{ number_format($totalDesaBerlistrik) }}</div>
            <div class="summary-label">Desa Berlistrik</div>
        </div>
        <div class="summary-card">
            <div class="summary-icon"><i class="ri-group-line"></i></div>
            <div class="summary-value">{{ number_format($totalKK) }}</div>
            <div class="summary-label">Total Kepala Keluarga</div>
        </div>
        <div class="summary-card">
            <div class="summary-icon"><i class="ri-percent-line"></i></div>
            <div class="summary-value">{{ $rasioElektrifikasi }}%</div>
            <div class="summary-label">Rasio Elektrifikasi</div>
        </div>
    </div>

    <!-- Status Desa Berlistrik Breakdown -->
    <div class="section-title"><i class="ri-lightbulb-flash-line"></i> Status Keterlayanan Listrik Desa</div>

    <div class="status-section-grid">
        <!-- Breakdown Cards -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Jumlah Desa per Kategori</div>
            </div>
            <div class="dash-card-body">
                <div class="status-cards-grid">
                    <!-- Card PLN -->
                    <div class="status-card status-card-pln">
                        <div class="status-number">{{ number_format($totalDesaBerlistrikPln) }}</div>
                        <div class="status-label">Berlistrik PLN</div>
                        <div class="status-percentage">
                            {{ $totalDesa > 0 ? number_format(($totalDesaBerlistrikPln / $totalDesa) * 100, 1) : 0 }}%
                        </div>
                    </div>

                    <!-- Card Non-PLN -->
                    <div class="status-card status-card-non-pln">
                        <div class="status-number">{{ number_format($totalDesaBerlistrikNonPln) }}</div>
                        <div class="status-label">Berlistrik Non-PLN</div>
                        <div class="status-percentage">
                            {{ $totalDesa > 0 ? number_format(($totalDesaBerlistrikNonPln / $totalDesa) * 100, 1) : 0 }}%
                        </div>
                    </div>

                    <!-- Card Tidak Berlistrik -->
                    <div class="status-card status-card-belum">
                        <div class="status-number">{{ number_format($totalDesaBelum) }}</div>
                        <div class="status-label">Tidak Berlistrik</div>
                        <div class="status-percentage">
                            {{ $totalDesa > 0 ? number_format(($totalDesaBelum / $totalDesa) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Proporsi Status Keterlayanan</div>
            </div>
            <div class="dash-card-body">
                <div class="chart-container" style="height: 200px;">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Main grid: 2 columns on desktop */
        .status-section-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        /* Cards grid inside left column */
        .status-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .status-card {
            text-align: center;
            padding: 24px 20px;
            border-radius: 12px;
            border: 2px solid;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .status-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .status-card-pln {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-color: #22c55e;
        }

        .status-card-non-pln {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: #f59e0b;
        }

        .status-card-belum {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-color: #ef4444;
        }

        .status-number {
            font-size: 36px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 8px;
        }

        .status-card-pln .status-number,
        .status-card-pln .status-percentage {
            color: #166534;
        }

        .status-card-non-pln .status-number,
        .status-card-non-pln .status-percentage {
            color: #92400e;
        }

        .status-card-belum .status-number,
        .status-card-belum .status-percentage {
            color: #991b1b;
        }

        .status-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .status-card-pln .status-label {
            color: #15803d;
        }

        .status-card-non-pln .status-label {
            color: #b45309;
        }

        .status-card-belum .status-label {
            color: #dc2626;
        }

        .status-percentage {
            font-size: 16px;
            font-weight: 700;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            /* Stack everything vertically on mobile */
            .status-section-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .status-cards-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .status-card {
                min-height: 120px;
                padding: 20px 16px;
            }

            .status-number {
                font-size: 32px;
            }

            .status-percentage {
                font-size: 14px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {

            /* Tablet: keep 2 columns but adjust cards */
            .status-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .status-cards-grid> :last-child {
                grid-column: 1 / -1;
            }
        }
    </style>


    <!-- Infrastruktur Stats -->
    <div class="section-title"><i class="ri-plug-line"></i> Data Infrastruktur</div>
    <div class="infra-grid">
        <div class="infra-card">
            <div class="infra-icon"><i class="ri-base-station-line"></i></div>
            <div class="infra-value">{{ number_format($totalGardu) }}</div>
            <div class="infra-label">Total Gardu</div>
        </div>
        <div class="infra-card">
            <div class="infra-icon"><i class="ri-git-branch-line"></i></div>
            <div class="infra-value">{{ number_format($totalJaringanKm, 2) }} km</div>
            <div class="infra-label">Panjang Jaringan</div>
        </div>
        <div class="infra-card">
            <div class="infra-icon"><i class="ri-flashlight-line"></i></div>
            <div class="infra-value">{{ number_format($totalPembangkit) }}</div>
            <div class="infra-label">Pembangkit Lokal</div>
        </div>
        <div class="infra-card">
            <div class="infra-icon"><i class="ri-building-line"></i></div>
            <div class="infra-value">{{ number_format($totalPerusahaan) }}</div>
            <div class="infra-label">Perusahaan</div>
        </div>
    </div>

    <div class="infra-chart-grid">
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Gardu per Jenis</div>
            </div>
            <div class="dash-card-body">
                @if ($garduPerJenis->isEmpty())
                    <div style="color: #6B7280; font-size: 13px;">Belum ada data gardu.</div>
                @else
                    <div class="chart-container">
                        <canvas id="garduChart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Panjang Jaringan per Tipe</div>
            </div>
            <div class="dash-card-body">
                @if ($jaringanPerTipe->isEmpty())
                    <div style="color: #6B7280; font-size: 13px;">Belum ada data jaringan.</div>
                @else
                    <div class="chart-container">
                        <canvas id="jaringanChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Company-Infrastructure Section -->
    <div class="section-title"><i class="ri-building-4-line"></i> Infrastruktur per Perusahaan</div>

    <div class="infra-chart-grid">
        <!-- Pie Chart: Gardu per Perusahaan -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Distribusi Gardu per Perusahaan</div>
            </div>
            <div class="dash-card-body">
                @if ($garduPerPerusahaan->isEmpty())
                    <div style="color: #6B7280; font-size: 13px;">Belum ada data gardu perusahaan.</div>
                @else
                    <div class="chart-container">
                        <canvas id="garduPerusahaanChart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bar Chart: Jaringan per Perusahaan -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Panjang Jaringan per Perusahaan</div>
            </div>
            <div class="dash-card-body">
                @if ($jaringanPerPerusahaan->isEmpty())
                    <div style="color: #6B7280; font-size: 13px;">Belum ada data jaringan perusahaan.</div>
                @else
                    <div class="chart-container">
                        <canvas id="jaringanPerusahaanChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Table: Detail Infrastruktur per Perusahaan -->
    <div class="dash-card" style="margin-top: 24px;">
        <div class="dash-card-header">
            <div class="dash-card-title">Top 5 Perusahaan - Detail Infrastruktur</div>
        </div>
        <div class="dash-card-body">
            @if ($topPerusahaan->isEmpty())
                <div style="color: #6B7280; font-size: 13px;">Belum ada data perusahaan.</div>
            @else
                <div style="overflow-x: auto;">
                    <table class="company-table">
                        <thead>
                            <tr>
                                <th>Nama Perusahaan</th>
                                <th>Jenis Usaha</th>
                                <th>Kabupaten/Kota</th>
                                <th class="text-center">Gardu</th>
                                <th class="text-center">Jaringan (km)</th>
                                <th class="text-center">Pembangkit</th>
                                <th class="text-center">Total Infrastruktur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topPerusahaan as $perusahaan)
                                <tr>
                                    <td><strong>{{ $perusahaan['nama'] }}</strong></td>
                                    <td>{{ $perusahaan['jenis_usaha'] ?? '-' }}</td>
                                    <td>{{ $perusahaan['kabupaten_kota'] ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="stats-badge badge-blue">{{ $perusahaan['total_gardu'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="stats-badge badge-green">{{ number_format($perusahaan['total_jaringan_km'], 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="stats-badge badge-orange">{{ $perusahaan['total_pembangkit'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong style="color: #1F2937;">{{ $perusahaan['total_infrastruktur'] }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <style>
        .company-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .company-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .company-table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .company-table th.text-center {
            text-align: center;
        }

        .company-table tbody tr {
            border-bottom: 1px solid #E5E7EB;
            transition: background-color 0.2s;
        }

        .company-table tbody tr:hover {
            background-color: #F9FAFB;
        }

        .company-table td {
            padding: 14px 16px;
            color: #374151;
        }

        .company-table td.text-center {
            text-align: center;
        }

        .stats-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-blue {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .badge-green {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-orange {
            background: #FED7AA;
            color: #92400E;
        }
    </style>


    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Chart: Trend Elektrifikasi -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Perkembangan Desa Berlistrik ({{ now()->year }})</div>
            </div>
            <div class="dash-card-body">
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-dot green"></span> Desa Berlistrik</span>
                    <span class="legend-item"><span class="legend-dot gray"></span> Tahun Lalu</span>
                </div>
            </div>
        </div>

        <!-- Rasio Elektrifikasi per Kabupaten -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">Rasio Elektrifikasi Per Kabupaten</div>
            </div>
            <div class="dash-card-body" style="max-height: 360px; overflow-y: auto;">
                <ul class="kabupaten-list">
                    @foreach (collect($elektrifikasiData)->sortByDesc('rasio') as $data)
                        <li class="kabupaten-item">
                            <div class="kabupaten-header">
                                <span class="kabupaten-name">{{ $data['name'] }}</span>
                                <span class="kabupaten-value">{{ number_format($data['rasio'], 2) }}%</span>
                            </div>
                            <div class="kabupaten-bar">
                                <div class="kabupaten-fill" style="width: {{ $data['rasio'] }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Chart: Desa Per Kabupaten (3 Kategori) -->
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="dash-card-title">Jumlah Desa Per Kabupaten/Kota (PLN, Non-PLN, Tidak Berlistrik)</div>
        </div>
        <div class="dash-card-body">
            <div class="chart-container">
                <canvas id="desaChart"></canvas>
            </div>
            <div class="chart-legend">
                <span class="legend-item"><span class="legend-dot" style="background: #22c55e;"></span> Berlistrik
                    PLN</span>
                <span class="legend-item"><span class="legend-dot" style="background: #f59e0b;"></span> Berlistrik
                    Non-PLN</span>
                <span class="legend-item"><span class="legend-dot" style="background: #ef4444;"></span> Tidak
                    Berlistrik</span>
            </div>
        </div>
    </div>

    <!-- Insights -->
    <div class="section-title"><i class="ri-bar-chart-box-line"></i> Ringkasan</div>
    <div class="insight-grid">
        <div class="insight-card">
            <div class="insight-title green"><i class="ri-trophy-line"></i> Rasio Tertinggi</div>
            <ul class="insight-list">
                @foreach ($topRasio as $item)
                    <li>
                        <span class="insight-name">{{ $item['name'] }}</span>
                        <span class="insight-value green">{{ number_format($item['rasio'], 2) }}%</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="insight-card">
            <div class="insight-title orange"><i class="ri-focus-3-line"></i> Butuh Perhatian</div>
            <ul class="insight-list">
                @foreach ($lowRasio as $item)
                    <li>
                        <span class="insight-name">{{ $item['name'] }}</span>
                        <span class="insight-value orange">{{ number_format($item['rasio'], 2) }}%</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="insight-card">
            <div class="insight-title red"><i class="ri-home-2-line"></i> Desa Belum Berlistrik</div>
            <ul class="insight-list">
                @forelse($desaBelumBanyak as $item)
                    <li>
                        <span class="insight-name">{{ $item['name'] }}</span>
                        <span class="insight-value red">{{ number_format($item['desa_belum']) }} desa</span>
                    </li>
                @empty
                    <li>
                        <span class="insight-name">Semua kabupaten sudah terlayani</span>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Top 10 Ranking Table -->
    <div class="section-title"><i class="ri-trophy-line"></i> Top 10 Ranking Prioritas</div>
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="dash-card-title">Ranking Prioritas Berdasarkan Total Skor</div>
        </div>
        <div class="dash-card-body" style="padding: 0; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background: #7B1FA2; color: white;">
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
                            No</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; white-space: nowrap;">
                            Kabupaten/Kota</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; white-space: nowrap;">
                            Kecamatan</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; white-space: nowrap;">
                            Desa</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
                            Kodifikasi</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
                            Jumlah Calon Pelanggan</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
                            Total Skor</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
                            Prioritas</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPrioritas as $index => $item)
                        <tr
                            style="border-bottom: 1px solid #F1F5F9; {{ $index < 3 ? 'background: #FFFBEB;' : 'background: white;' }}">
                            <td
                                style="padding: 14px 16px; text-align: center; color: #111827; font-weight: 600; white-space: nowrap;">
                                @if ($index === 0)
                                    <span
                                        style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #FCD34D; color: #78350F; border-radius: 50%; font-weight: 700;">1</span>
                                @elseif($index === 1)
                                    <span
                                        style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #D1D5DB; color: #374151; border-radius: 50%; font-weight: 700;">2</span>
                                @elseif($index === 2)
                                    <span
                                        style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #FCA5A5; color: #7F1D1D; border-radius: 50%; font-weight: 700;">3</span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td style="padding: 14px 16px; color: #374151; white-space: nowrap;">
                                {{ $item->regency->name ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; color: #374151; white-space: nowrap;">
                                {{ $item->district->name ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; color: #374151; font-weight: 500; white-space: nowrap;">
                                {{ $item->village->name ?? '-' }}
                            </td>
                            <td
                                style="padding: 14px 16px; text-align: center; color: #6B7280; font-family: monospace; font-size: 12px; white-space: nowrap;">
                                {{ $item->kodifikasi ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                                <span
                                    style="display: inline-block; padding: 4px 12px; background: #DBEAFE; color: #1E40AF; border-radius: 12px; font-weight: 600;">
                                    {{ number_format($item->jumlah_calon_pelanggan ?? 0) }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                                <span
                                    style="display: inline-block; padding: 6px 12px; background: #E0E7FF; color: #3730A3; border-radius: 12px; font-weight: 700; font-size: 13px;">
                                    {{ $item->total_skor }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                                <span
                                    style="display: inline-block; padding: 6px 12px; background: {{ str_contains($item->prioritas ?? '', 'Prioritas 1') ? '#DCFCE7' : '#FEF3C7' }}; color: {{ str_contains($item->prioritas ?? '', 'Prioritas 1') ? '#166534' : '#92400E' }}; border-radius: 6px; font-weight: 600; font-size: 12px;">
                                    {{ $item->prioritas ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                                <a href="{{ route('admin.rencana-pengembangan.index') }}"
                                    style="display: inline-block; padding: 6px 12px; background: #7B1FA2; color: white; border-radius: 6px; font-weight: 600; font-size: 12px; text-decoration: none;">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 24px; text-align: center; color: #6B7280;">
                                Belum ada data prioritas. Silakan tambahkan data di menu <a
                                    href="{{ route('admin.rencana-pengembangan.index') }}"
                                    style="color: #7B1FA2; font-weight: 600;">Rencana Pengembangan Bantuan</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const greenColor = '#17C653';
            const grayColor = '#9CA3AF';
            const garduData = @json($garduPerJenis);
            const jaringanData = @json($jaringanPerTipe);

            // ===== TREND CHART =====
            const trendCtx = document.getElementById('trendChart')?.getContext('2d');
            if (trendCtx) {
                const gradient = trendCtx.createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, 'rgba(23, 198, 83, 0.25)');
                gradient.addColorStop(1, 'rgba(23, 198, 83, 0.02)');

                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! $trendLabels !!},
                        datasets: [{
                            label: 'Desa Berlistrik',
                            data: {!! $trendDesaBerlistrik !!},
                            borderColor: greenColor,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            pointHoverBackgroundColor: greenColor,
                        }, {
                            label: 'Tahun Lalu',
                            data: [960, 970, 980, 990, 995, 1000, 1005, 1010, 1015, 1020, 1022,
                                1025
                            ],
                            borderColor: grayColor,
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#94A3B8',
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(148,163,184,0.12)'
                                },
                                ticks: {
                                    color: '#94A3B8',
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // ===== GARDU CHART =====
            const garduCtx = document.getElementById('garduChart')?.getContext('2d');
            if (garduCtx && garduData.length) {
                new Chart(garduCtx, {
                    type: 'bar',
                    data: {
                        labels: garduData.map(d => d.jenis_gardu_distribusi ?? 'Tidak diketahui'),
                        datasets: [{
                            label: 'Jumlah Gardu',
                            data: garduData.map(d => Number(d.total)),
                            backgroundColor: 'rgba(23, 198, 83, 0.15)',
                            borderColor: greenColor,
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748B',
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(148,163,184,0.12)'
                                },
                                ticks: {
                                    color: '#94A3B8'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // ===== JARINGAN CHART =====
            const jaringanCtx = document.getElementById('jaringanChart')?.getContext('2d');
            if (jaringanCtx && jaringanData.length) {
                new Chart(jaringanCtx, {
                    type: 'bar',
                    data: {
                        labels: jaringanData.map(d => d.jaringan ?? 'Tidak diketahui'),
                        datasets: [{
                            label: 'Panjang Jaringan (km)',
                            data: jaringanData.map(d => Number(d.total_km)),
                            backgroundColor: 'rgba(59, 130, 246, 0.12)',
                            borderColor: '#2563EB',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748B',
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(148,163,184,0.12)'
                                },
                                ticks: {
                                    color: '#94A3B8'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // ===== DESA CHART (3 Kategori: PLN, Non-PLN, Tidak Berlistrik) =====
            const desaCtx = document.getElementById('desaChart')?.getContext('2d');
            if (desaCtx) {
                const elektrifikasiData = @json($elektrifikasiData);
                const plnColor = '#22c55e';
                const nonPlnColor = '#f59e0b';
                const tidakBerlistrikColor = '#ef4444';

                new Chart(desaCtx, {
                    type: 'bar',
                    data: {
                        labels: elektrifikasiData.map(d => d.name),
                        datasets: [{
                            label: 'Berlistrik PLN',
                            data: elektrifikasiData.map(d => d.desa_berlistrik_pln ?? 0),
                            backgroundColor: plnColor,
                            borderRadius: 4
                        }, {
                            label: 'Berlistrik Non-PLN',
                            data: elektrifikasiData.map(d => d.desa_berlistrik_non_pln ?? 0),
                            backgroundColor: nonPlnColor,
                            borderRadius: 4
                        }, {
                            label: 'Tidak Berlistrik',
                            data: elektrifikasiData.map(d => d.desa_belum ?? 0),
                            backgroundColor: tidakBerlistrikColor,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y +
                                            ' desa';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748B',
                                    font: {
                                        size: 10
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(148,163,184,0.12)'
                                },
                                ticks: {
                                    color: '#94A3B8'
                                }
                            }
                        }
                    }
                });
            }

            // ===== PIE CHART (Status Keterlayanan) =====
            const pieCtx = document.getElementById('statusPieChart')?.getContext('2d');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Berlistrik PLN', 'Berlistrik Non-PLN', 'Tidak Berlistrik'],
                        datasets: [{
                            data: [
                                {{ $totalDesaBerlistrikPln }},
                                {{ $totalDesaBerlistrikNonPln }},
                                {{ $totalDesaBelum }}
                            ],
                            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 16,
                                    font: {
                                        size: 11
                                    },
                                    color: '#374151'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((context.parsed / total) * 100)
                                            .toFixed(1) : 0;
                                        return context.label + ': ' + context.parsed.toLocaleString(
                                            'id-ID') + ' (' + pct + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // ===== PIE CHART (Gardu per Perusahaan) =====
            const garduPerusahaanCtx = document.getElementById('garduPerusahaanChart')?.getContext('2d');
            if (garduPerusahaanCtx) {
                const garduPerusahaanData = {!! json_encode($garduPerPerusahaan->values()) !!};
                const garduLabels = garduPerusahaanData.map(item => item.nama);
                const garduValues = garduPerusahaanData.map(item => item.total);

                // Generate vibrant colors for each company
                const garduColors = [
                    '#3B82F6', // Blue
                    '#10B981', // Green
                    '#F59E0B', // Orange
                    '#8B5CF6', // Purple
                    '#EF4444', // Red
                    '#06B6D4', // Cyan
                ];

                new Chart(garduPerusahaanCtx, {
                    type: 'doughnut',
                    data: {
                        labels: garduLabels,
                        datasets: [{
                            data: garduValues,
                            backgroundColor: garduColors.slice(0, garduLabels.length),
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 16,
                                    font: {
                                        size: 11
                                    },
                                    color: '#374151'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((context.parsed / total) * 100)
                                            .toFixed(1) : 0;
                                        return context.label + ': ' + context.parsed + ' gardu (' +
                                            pct + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // ===== BAR CHART (Jaringan per Perusahaan) =====
            const jaringanPerusahaanCtx = document.getElementById('jaringanPerusahaanChart')?.getContext('2d');
            if (jaringanPerusahaanCtx) {
                const jaringanPerusahaanData = {!! json_encode($jaringanPerPerusahaan->values()) !!};
                const jaringanLabels = jaringanPerusahaanData.map(item => item.nama);
                const jaringanValues = jaringanPerusahaanData.map(item => item.total_km);

                new Chart(jaringanPerusahaanCtx, {
                    type: 'bar',
                    data: {
                        labels: jaringanLabels,
                        datasets: [{
                            label: 'Panjang Jaringan (km)',
                            data: jaringanValues,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: '#10B981',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.x.toFixed(2) + ' km';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148,163,184,0.12)'
                                },
                                ticks: {
                                    color: '#94A3B8',
                                    callback: function(value) {
                                        return value + ' km';
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748B',
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
