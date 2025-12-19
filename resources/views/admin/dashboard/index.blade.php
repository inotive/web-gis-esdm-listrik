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

  <!-- Summary Cards -->
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
        @if($garduPerJenis->isEmpty())
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
        @if($jaringanPerTipe->isEmpty())
          <div style="color: #6B7280; font-size: 13px;">Belum ada data jaringan.</div>
        @else
          <div class="chart-container">
            <canvas id="jaringanChart"></canvas>
          </div>
        @endif
      </div>
    </div>
  </div>

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
          @foreach(collect($elektrifikasiData)->sortByDesc('rasio') as $data)
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

  <!-- Chart: Desa Per Kabupaten -->
  <div class="dash-card">
    <div class="dash-card-header">
      <div class="dash-card-title">Jumlah Desa Berlistrik Per Kabupaten/Kota</div>
    </div>
    <div class="dash-card-body">
      <div class="chart-container">
        <canvas id="desaChart"></canvas>
      </div>
      <div class="chart-legend">
        <span class="legend-item"><span class="legend-dot green"></span> Desa Berlistrik</span>
        <span class="legend-item"><span class="legend-dot gray"></span> Desa Belum Berlistrik</span>
      </div>
    </div>
  </div>

  <!-- Insights -->
  <div class="section-title"><i class="ri-bar-chart-box-line"></i> Ringkasan</div>
  <div class="insight-grid">
    <div class="insight-card">
      <div class="insight-title green"><i class="ri-trophy-line"></i> Rasio Tertinggi</div>
      <ul class="insight-list">
        @foreach($topRasio as $item)
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
        @foreach($lowRasio as $item)
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

  <!-- Top 5 Ranking Table -->
  <div class="section-title"><i class="ri-trophy-line"></i> Top 5 Ranking Prioritas</div>
  <div class="dash-card">
    <div class="dash-card-header">
      <div class="dash-card-title">Ranking Prioritas Berdasarkan Total Skor</div>
    </div>
    <div class="dash-card-body" style="padding: 0; overflow-x: auto;">
      <table style="width: 100%; min-width: 1400px; border-collapse: collapse; font-size: 13px;">
        <thead>
          <tr style="background: #7B1FA2; color: white;">
            <th rowspan="2"
              style="padding: 12px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              No</th>
            <th rowspan="2"
              style="padding: 12px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              Kecamatan</th>
            <th rowspan="2"
              style="padding: 12px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              Desa</th>
            <th rowspan="2"
              style="padding: 12px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              Klasifikasi</th>
            <th rowspan="2"
              style="padding: 12px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              Kelengkapan Persyaratan SKTP (%)</th>
            <th colspan="2"
              style="padding: 8px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
              Aksesibilitas<br />(Bobot: 20%)</th>
            <th colspan="2"
              style="padding: 8px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
              Radius ke Jaringan Eksisting<br />(Bobot: 20%)</th>
            <th colspan="2"
              style="padding: 8px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
              Arah & Kejelasan Tata Ruang<br />(Bobot: 20%)</th>
            <th colspan="2"
              style="padding: 8px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
              Kegiatan<br />(Bobot: 20%)</th>
            <th colspan="2"
              style="padding: 8px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
              Jumlah Pengguna<br />(Bobot: 20%)</th>
            <th rowspan="2"
              style="padding: 12px 16px; text-align: center; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              Total Skor<br />(E-Skor + Bobot)</th>
            <th rowspan="2" style="padding: 12px 16px; text-align: center; font-weight: 600; white-space: nowrap;">
              Prioritas</th>
          </tr>
          <tr style="background: #7B1FA2; color: white;">
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              kondisi</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              skor</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              kondisi</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              skor</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              kondisi</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              skor</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              kondisi</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              skor</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              kondisi</th>
            <th
              style="padding: 8px 12px; text-align: center; font-weight: 500; font-size: 12px; border-right: 1px solid rgba(255,255,255,0.2); white-space: nowrap;">
              skor</th>
          </tr>
        </thead>
        <tbody>
          @php
            $rankingData = [
              [
                'kecamatan' => 'Bintuni',
                'desa' => 'Bintuni Ulu',
                'klasifikasi' => 'BRU-BUA-1',
                'perizinan' => '6,67',
                'aksesibilitas_kondisi' => 'Terdapat Jaringan Jalan dengan Lebar Lahan dan 4 Meter dan Terasfaltasi dengan Jarak Pengukuran Lainnya',
                'aksesibilitas_skor' => '4',
                'radius_kondisi' => '> 15 Km',
                'radius_skor' => '1',
                'tata_ruang_kondisi' => 'Sesuai Dengan RTR dan Terdapat Perumahan Bersebelah Lainnya/Tidak Terdapat Perizinan Bersebelah Lainnya',
                'tata_ruang_skor' => '4',
                'kegiatan' => 'Terdapat Pusat Kegiatan Eksisting',
                'kegiatan_skor' => '4',
                'pengguna_kondisi' => '158',
                'pengguna_skor' => '4',
                'total_skor' => '17',
                'prioritas' => 'Prioritas 2 SUTM'
              ],
              [
                'kecamatan' => 'Bintuni',
                'desa' => 'Karanggau',
                'klasifikasi' => 'BRU-KRG-1',
                'perizinan' => '4,17',
                'aksesibilitas_kondisi' => 'Tidak Terdapat Jaringan Jalan dan Tidak dengan Jalan Penghubung Lainnya',
                'aksesibilitas_skor' => '2',
                'radius_kondisi' => '2,5 Km - 7,5 Km',
                'radius_skor' => '3',
                'tata_ruang_kondisi' => 'Tidak Sesuai Dengan RTR dan Tidak Terdapat Perizinan Bersebelah Lainnya',
                'tata_ruang_skor' => '3',
                'kegiatan' => 'Terdapat Pusat Kegiatan Eksisting dan Mengatasi Pertumbuhan',
                'kegiatan_skor' => '5',
                'pengguna_kondisi' => '52',
                'pengguna_skor' => '2',
                'total_skor' => '15',
                'prioritas' => 'Prioritas 2 SUTM'
              ],
              [
                'kecamatan' => 'Biduk-Biduk',
                'desa' => 'Teluk Sumbang',
                'klasifikasi' => 'BRU-BDK-1',
                'perizinan' => '3,46',
                'aksesibilitas_kondisi' => 'Terdapat Jaringan Jalan dengan Lebar Lahan dan 4 Meter dan Terasfaltasi dengan Jarak Pengukuran Lainnya',
                'aksesibilitas_skor' => '5',
                'radius_kondisi' => '> 15 Km',
                'radius_skor' => '1',
                'tata_ruang_kondisi' => 'Sesuai Dengan RTR dan Tidak Terdapat Perizinan Kawasan Hutan Lainnya',
                'tata_ruang_skor' => '5',
                'kegiatan' => 'Terdapat Pusat Kegiatan Eksisting',
                'kegiatan_skor' => '4',
                'pengguna_kondisi' => '138',
                'pengguna_skor' => '4',
                'total_skor' => '19',
                'prioritas' => 'Prioritas 1 SUTM'
              ],
              [
                'kecamatan' => 'Kelay',
                'desa' => 'Lemar Dayak',
                'klasifikasi' => 'BRU-LDK-1',
                'perizinan' => '2,67',
                'aksesibilitas_kondisi' => 'Terdapat Jaringan Jalan dengan Lebar Lahan dan 4 Meter dan Terasfaltasi dengan Jarak Pengukuran Lainnya',
                'aksesibilitas_skor' => '4',
                'radius_kondisi' => '7,5 Km - 15 Km',
                'radius_skor' => '2',
                'tata_ruang_kondisi' => 'Sesuai Dengan RTR dan Terdapat Perumahan Bersebelah Lainnya/Tidak Terdapat Perizinan Bersebelah Lainnya',
                'tata_ruang_skor' => '4',
                'kegiatan' => 'Terdapat Pusat Kegiatan Eksisting dan Mengatasi Pertumbuhan',
                'kegiatan_skor' => '5',
                'pengguna_kondisi' => '64',
                'pengguna_skor' => '3',
                'total_skor' => '18',
                'prioritas' => 'Prioritas 1 SUTM'
              ],
              [
                'kecamatan' => 'Kelay',
                'desa' => 'Long Beliu',
                'klasifikasi' => 'BRU-LGB-1',
                'perizinan' => '3,96',
                'aksesibilitas_kondisi' => 'Terdapat Jaringan Jalan dengan Lebar Lahan dan 4 Meter dan Terasfaltasi dengan Jarak Pengukuran Lainnya',
                'aksesibilitas_skor' => '4',
                'radius_kondisi' => '2,5 Km - 7,5 Km',
                'radius_skor' => '3',
                'tata_ruang_kondisi' => 'Sesuai Dengan RTR dan Tidak Terdapat Perizinan Kawasan Hutan Lainnya',
                'tata_ruang_skor' => '5',
                'kegiatan' => 'Terdapat Potensi Perkembangan Kegiatan',
                'kegiatan_skor' => '3',
                'pengguna_kondisi' => '275',
                'pengguna_skor' => '5',
                'total_skor' => '20',
                'prioritas' => 'Prioritas 1 SUTM'
              ],
            ];
          @endphp

          @foreach($rankingData as $index => $item)
            <tr style="border-bottom: 1px solid #F1F5F9; {{ $index < 3 ? 'background: #FFFBEB;' : 'background: white;' }}">
              <td
                style="padding: 12px 16px; text-align: center; color: #111827; font-weight: 600; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                @if($index === 0)
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
              <td style="padding: 12px 16px; color: #374151; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['kecamatan'] }}</td>
              <td
                style="padding: 12px 16px; color: #374151; font-weight: 500; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['desa'] }}</td>
              <td
                style="padding: 12px 16px; color: #6B7280; font-family: monospace; font-size: 12px; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['klasifikasi'] }}</td>
              <td
                style="padding: 12px 16px; text-align: center; color: #374151; font-weight: 600; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['perizinan'] }}%</td>
              <td
                style="padding: 12px 16px; color: #374151; font-size: 12px; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['aksesibilitas_kondisi'] }}</td>
              <td style="padding: 12px 16px; text-align: center; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                <span
                  style="display: inline-block; padding: 4px 10px; background: #DBEAFE; color: #1E40AF; border-radius: 12px; font-weight: 600;">{{ $item['aksesibilitas_skor'] }}</span>
              </td>
              <td
                style="padding: 12px 16px; color: #374151; font-size: 12px; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['radius_kondisi'] }}</td>
              <td style="padding: 12px 16px; text-align: center; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                <span
                  style="display: inline-block; padding: 4px 10px; background: #DBEAFE; color: #1E40AF; border-radius: 12px; font-weight: 600;">{{ $item['radius_skor'] }}</span>
              </td>
              <td
                style="padding: 12px 16px; color: #374151; font-size: 12px; border-right: 1px solid #F1F5F9; max-width: 250px;">
                {{ $item['tata_ruang_kondisi'] }}</td>
              <td style="padding: 12px 16px; text-align: center; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                <span
                  style="display: inline-block; padding: 4px 10px; background: #DBEAFE; color: #1E40AF; border-radius: 12px; font-weight: 600;">{{ $item['tata_ruang_skor'] }}</span>
              </td>
              <td
                style="padding: 12px 16px; color: #374151; font-size: 12px; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['kegiatan'] }}</td>
              <td style="padding: 12px 16px; text-align: center; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                <span
                  style="display: inline-block; padding: 4px 10px; background: #DBEAFE; color: #1E40AF; border-radius: 12px; font-weight: 600;">{{ $item['kegiatan_skor'] }}</span>
              </td>
              <td
                style="padding: 12px 16px; text-align: center; color: #374151; font-weight: 600; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                {{ $item['pengguna_kondisi'] }}</td>
              <td style="padding: 12px 16px; text-align: center; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                <span
                  style="display: inline-block; padding: 4px 10px; background: #DBEAFE; color: #1E40AF; border-radius: 12px; font-weight: 600;">{{ $item['pengguna_skor'] }}</span>
              </td>
              <td style="padding: 12px 16px; text-align: center; border-right: 1px solid #F1F5F9; white-space: nowrap;">
                <div style="font-weight: 700; color: #059669; font-size: 18px;">{{ $item['total_skor'] }}</div>
              </td>
              <td style="padding: 12px 16px; text-align: center; white-space: nowrap;">
                <span
                  style="display: inline-block; padding: 6px 12px; background: {{ str_contains($item['prioritas'], 'Prioritas 1') ? '#DCFCE7' : '#FEF3C7' }}; color: {{ str_contains($item['prioritas'], 'Prioritas 1') ? '#166534' : '#92400E' }}; border-radius: 6px; font-weight: 600; font-size: 12px;">
                  {{ $item['prioritas'] }}
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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
              data: [960, 970, 980, 990, 995, 1000, 1005, 1010, 1015, 1020, 1022, 1025],
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
            plugins: { legend: { display: false } },
            scales: {
              x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 11 } } },
              y: {
                grid: { color: 'rgba(148,163,184,0.12)' },
                ticks: { color: '#94A3B8', font: { size: 11 } }
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
            plugins: { legend: { display: false } },
            scales: {
              x: { grid: { display: false }, ticks: { color: '#64748B', font: { size: 10 } } },
              y: { grid: { color: 'rgba(148,163,184,0.12)' }, ticks: { color: '#94A3B8' }, beginAtZero: true }
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
            plugins: { legend: { display: false } },
            scales: {
              x: { grid: { display: false }, ticks: { color: '#64748B', font: { size: 10 } } },
              y: { grid: { color: 'rgba(148,163,184,0.12)' }, ticks: { color: '#94A3B8' }, beginAtZero: true }
            }
          }
        });
      }

      // ===== DESA CHART =====
      const desaCtx = document.getElementById('desaChart')?.getContext('2d');
      if (desaCtx) {
        const elektrifikasiData = @json($elektrifikasiData);
        new Chart(desaCtx, {
          type: 'bar',
          data: {
            labels: elektrifikasiData.map(d => d.name),
            datasets: [{
              label: 'Desa Berlistrik',
              data: elektrifikasiData.map(d => d.desa_berlistrik),
              backgroundColor: greenColor,
              borderRadius: 4
            }, {
              label: 'Desa Belum Berlistrik',
              data: elektrifikasiData.map(d => d.desa_belum),
              backgroundColor: grayColor,
              borderRadius: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: {
                grid: { display: false },
                ticks: { color: '#64748B', font: { size: 10 }, maxRotation: 45, minRotation: 45 }
              },
              y: { grid: { color: 'rgba(148,163,184,0.12)' }, ticks: { color: '#94A3B8' } }
            }
          }
        });
      }
    });
  </script>
@endpush