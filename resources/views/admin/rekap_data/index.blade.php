@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Rekap Data')

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
            color: #059669;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .tab-btn i {
            font-size: 16px;
        }

        .tab-btn .badge {
            background: rgba(23, 198, 83, 0.15);
            color: #059669;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .tab-btn.active .badge {
            background: #059669;
            color: white;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Report Header */
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            border-radius: 12px;
            color: white;
        }

        .report-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .report-subtitle {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
        }

        .report-year {
            font-size: 16px;
            font-weight: 700;
            margin-top: 8px;
            padding: 4px 16px;
            background: rgba(255, 255, 255, 0.2);
            display: inline-block;
            border-radius: 20px;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        /* Rekap Data Table */
        .table-rekap {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-rekap thead th {
            background: #059669;
            color: white;
            font-weight: 600;
            padding: 12px 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            vertical-align: middle;
            font-size: 12px;
        }

        .table-rekap thead th.sub-header {
            background: #10B981;
            font-size: 11px;
        }

        .table-rekap tbody td {
            padding: 10px;
            border: 1px solid #E5E7EB;
            text-align: center;
            color: #1F2937;
        }

        .table-rekap tbody tr:hover td {
            background: #ECFDF5;
        }

        .table-rekap tbody tr:nth-child(even) td {
            background: #FAFAFA;
        }

        .col-no {
            width: 50px;
            font-weight: 600;
            color: #059669;
        }

        .col-kabkota {
            text-align: left !important;
            padding-left: 14px !important;
            font-weight: 500;
        }

        .col-number {
            font-variant-numeric: tabular-nums;
        }

        .percentage-high {
            color: #059669;
            font-weight: 600;
        }

        .percentage-medium {
            color: #D97706;
            font-weight: 600;
        }

        .percentage-low {
            color: #DC2626;
            font-weight: 600;
        }

        /* Total Row */
        .table-rekap tfoot td {
            background: #059669 !important;
            color: white !important;
            font-weight: 700;
            padding: 12px 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-rekap tfoot td.total-label {
            text-align: left !important;
            padding-left: 14px !important;
        }

        /* Notes Section */
        .notes-section {
            margin-top: 16px;
            padding: 12px 16px;
            background: #ECFDF5;
            border-radius: 8px;
            border-left: 3px solid #059669;
        }

        .notes-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .notes-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .notes-list li {
            font-size: 12px;
            color: #6B7280;
        }

        .notes-list li strong {
            color: #374151;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .year-select {
            padding: 8px 14px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 13px;
            background: white;
            color: #374151;
            cursor: pointer;
        }

        .year-select:focus {
            outline: none;
            border-color: #059669;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-export.excel {
            background: #059669;
            color: white;
        }

        .btn-export.pdf {
            background: #DC2626;
            color: white;
        }

        .btn-export.print {
            background: #6B7280;
            color: white;
        }

        .btn-export:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-cards.three-cols {
            grid-template-columns: repeat(3, 1fr);
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #E5E7EB;
        }

        .summary-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 10px;
            background: rgba(23, 198, 83, 0.1);
            color: #17C653;
        }

        .summary-card-value {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }

        .summary-card-label {
            font-size: 13px;
            color: #6B7280;
        }

        /* Table Wrapper */
        .table-wrapper {
            overflow-x: auto;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .summary-cards.three-cols {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {

            .summary-cards,
            .summary-cards.three-cols {
                grid-template-columns: 1fr;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .tab-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        @media print {

            .action-bar,
            .page-head,
            .sidebar,
            .topbar,
            .tab-navigation,
            .summary-cards {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Rekap Data</div>
        </div>
        <div class="page-actions">
            <div class="date-pill">
                <i class="ri-calendar-line"></i>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <button class="tab-btn active" data-tab="desa-berlistrik">
            <i class="ri-flashlight-line"></i>
            <span>Ringkasan Desa Berlistrik</span>
            <span class="badge">{{ $total['jumlah_desa'] }}</span>
        </button>
        <button class="tab-btn" data-tab="infrastruktur">
            <i class="ri-building-2-line"></i>
            <span>Infrastruktur</span>
            <span class="badge">{{ count($infrastrukturData) }}</span>
        </button>
    </div>

    <!-- Tab 1: Ringkasan Desa Berlistrik -->
    <div class="tab-content active" id="tab-desa-berlistrik">
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-home-4-line"></i></div>
                <div class="summary-card-value">{{ number_format($total['jumlah_desa']) }}</div>
                <div class="summary-card-label">Total Desa</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-flashlight-line"></i></div>
                <div class="summary-card-value">{{ number_format($total['desa_berlistrik_jumlah']) }}</div>
                <div class="summary-card-label">Desa Berlistrik</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-group-line"></i></div>
                <div class="summary-card-value">{{ number_format($total['jumlah_kk']) }}</div>
                <div class="summary-card-label">Total Kepala Keluarga</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-percent-line"></i></div>
                <div class="summary-card-value">{{ number_format($total['rasio_elektrifikasi'], 2) }}%</div>
                <div class="summary-card-label">Rasio Elektrifikasi</div>
            </div>
        </div>

        <div class="action-bar">
            <select class="year-select" id="tahunSelect">
                @php
                    $years = isset($availableYears) && $availableYears->isNotEmpty()
                        ? $availableYears->merge(collect(range(2018, 2025)))->unique()->sortDesc()
                        : collect(range(2018, 2025))->sortDesc();
                @endphp
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endforeach
            </select>
            <div class="action-buttons">
                <a href="{{ route('admin.rekap-data.template') }}" class="btn-export print" style="background: #6366f1; text-decoration: none;">
                    <i class="ri-download-line"></i> Download Template
                </a>
                <button class="btn-export excel" onclick="openImportModal()">
                    <i class="ri-upload-line"></i> Import Data
                </button>
                <button class="btn-export excel"><i class="ri-file-excel-2-line"></i> Export Excel</button>
                <button class="btn-export pdf"><i class="ri-file-pdf-2-line"></i> Export PDF</button>
                <button class="btn-export print" onclick="window.print()"><i class="ri-printer-line"></i> Cetak</button>
            </div>
        </div>

        <!-- Import Modal -->
        <div id="importModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
            <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 400px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <span class="close" onclick="closeImportModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
                <h3 style="margin-top: 0; color: #111827; font-size: 18px; font-weight: 600;">Import Data Rekap</h3>
                
                <form action="{{ route('admin.rekap-data.import') }}" method="POST" enctype="multipart/form-data" style="margin-top: 16px;">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label for="importTahun" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Pilih Tahun</label>
                        <select name="tahun" id="importTahun" class="year-select" style="width: 100%;" required>
                             @php
                                    $years = isset($availableYears) && $availableYears->isNotEmpty()
                                    ? $availableYears->merge(collect(range(2018, 2027)))->unique()->sortDesc()
                                    : collect(range(2018, 2027))->sortDesc();
                            @endphp
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="importFile" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">File Excel/CSV</label>
                        <input type="file" name="file" id="importFile" accept=".xlsx, .xls, .csv" required style="width: 100%; font-size: 14px; color: #6b7280; file-selector-button: border: 0; file-selector-button: background: #f3f4f6; file-selector-button: padding: 8px 12px; file-selector-button: border-radius: 6px; file-selector-button: margin-right: 12px;">
                    </div>

                    <button type="submit" class="btn-export excel" style="width: 100%; justify-content: center;">
                        <i class="ri-upload-cloud-line"></i> Upload & Import
                    </button>
                </form>
            </div>
        </div>

        <div class="report-header">
            <div class="report-title">DATA RASIO DESA BERLISTRIK DAN RASIO ELEKTRIFIKASI</div>
            <div class="report-subtitle">PER KABUPATEN/KOTA KALIMANTAN TIMUR</div>
            <div class="report-year">Tahun {{ $tahun }}</div>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="table-rekap">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Kabupaten/Kota</th>
                            <th rowspan="2">Jumlah Desa</th>
                            <th rowspan="2">Jumlah KK</th>
                            <th rowspan="2">Jumlah Penduduk</th>
                            <th colspan="3" class="sub-header">Desa Berlistrik</th>
                            <th rowspan="2">Desa Belum Berlistrik</th>
                            <th colspan="3" class="sub-header">KK Berlistrik</th>
                            <th rowspan="2">Rasio Desa Berlistrik (%)</th>
                            <th rowspan="2">Jumlah KK Belum Berlistrik</th>
                            <th rowspan="2">Rasio Elektrifikasi (%)</th>
                        </tr>
                        <tr>
                            <th class="sub-header">PLN</th>
                            <th class="sub-header">Non PLN</th>
                            <th class="sub-header">Jumlah</th>
                            <th class="sub-header">PLN</th>
                            <th class="sub-header">Non PLN</th>
                            <th class="sub-header">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapData as $data)
                            <tr>
                                <td class="col-no">{{ $data['no'] }}</td>
                                <td class="col-kabkota">{{ $data['kabupaten_kota'] }}</td>
                                <td class="col-number">{{ number_format($data['jumlah_desa']) }}</td>
                                <td class="col-number">{{ number_format($data['jumlah_kk']) }}</td>
                                <td class="col-number">{{ number_format($data['jumlah_penduduk']) }}</td>
                                <td class="col-number">{{ number_format($data['desa_berlistrik_pln']) }}</td>
                                <td class="col-number">{{ number_format($data['desa_berlistrik_non_pln']) }}</td>
                                <td class="col-number">{{ number_format($data['desa_berlistrik_jumlah']) }}</td>
                                <td class="col-number">{{ number_format($data['desa_belum_berlistrik']) }}</td>
                                <td class="col-number">{{ number_format($data['kk_berlistrik_pln']) }}</td>
                                <td class="col-number">{{ number_format($data['kk_berlistrik_non_pln']) }}</td>
                                <td class="col-number">{{ number_format($data['kk_berlistrik_jumlah']) }}</td>
                                <td
                                    class="{{ $data['rasio_desa_berlistrik'] >= 95 ? 'percentage-high' : ($data['rasio_desa_berlistrik'] >= 80 ? 'percentage-medium' : 'percentage-low') }}">
                                    {{ number_format($data['rasio_desa_berlistrik'], 2) }}%
                                </td>
                                <td class="col-number">{{ number_format($data['jumlah_kk_belum_berlistrik']) }}</td>
                                <td
                                    class="{{ $data['rasio_elektrifikasi'] >= 85 ? 'percentage-high' : ($data['rasio_elektrifikasi'] >= 70 ? 'percentage-medium' : 'percentage-low') }}">
                                    {{ number_format($data['rasio_elektrifikasi'], 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="total-label">TOTAL KALTIM</td>
                            <td>{{ number_format($total['jumlah_desa']) }}</td>
                            <td>{{ number_format($total['jumlah_kk']) }}</td>
                            <td>{{ number_format($total['jumlah_penduduk']) }}</td>
                            <td>{{ number_format($total['desa_berlistrik_pln']) }}</td>
                            <td>{{ number_format($total['desa_berlistrik_non_pln']) }}</td>
                            <td>{{ number_format($total['desa_berlistrik_jumlah']) }}</td>
                            <td>{{ number_format($total['desa_belum_berlistrik']) }}</td>
                            <td>{{ number_format($total['kk_berlistrik_pln']) }}</td>
                            <td>{{ number_format($total['kk_berlistrik_non_pln']) }}</td>
                            <td>{{ number_format($total['kk_berlistrik_jumlah']) }}</td>
                            <td>{{ number_format($total['rasio_desa_berlistrik'], 2) }}%</td>
                            <td>{{ number_format($total['jumlah_kk_belum_berlistrik']) }}</td>
                            <td>{{ number_format($total['rasio_elektrifikasi'], 2) }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="notes-section">
            <div class="notes-title">NB :</div>
            <ul class="notes-list">
                <li><strong>RT</strong> = Konsumen Rumah Tangga</li>
                <li><strong>KK</strong> = Kepala Keluarga</li>
            </ul>
        </div>
    </div>

    <!-- Tab 2: Infrastruktur -->
    <div class="tab-content" id="tab-infrastruktur">
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-file-list-3-line"></i></div>
                <div class="summary-card-value">{{ number_format($totalInfra['jumlah_perizinan'] ?? 0) }}</div>
                <div class="summary-card-label">Total Perizinan</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-shield-check-line"></i></div>
                <div class="summary-card-value">{{ number_format($totalInfra['jumlah_iuptls'] ?? 0) }}</div>
                <div class="summary-card-label">Total IUPTLS</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-file-shield-2-line"></i></div>
                <div class="summary-card-value">{{ number_format($totalInfra['rekomtek_sktp'] ?? 0) }}</div>
                <div class="summary-card-label">Total Rekomtek SKTP</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon"><i class="ri-flashlight-fill"></i></div>
                <div class="summary-card-value">{{ number_format($totalInfra['jumlah_kapasitas'] ?? 0, 2) }}</div>
                <div class="summary-card-label">Total Kapasitas (kVA)</div>
            </div>
        </div>

        <div class="action-bar">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="color:#6B7280;font-size:13px;"><i class="ri-database-2-line"></i> Data dari Database
                    Perizinan</span>
            </div>
            <div class="action-buttons">
                <button class="btn-export excel"><i class="ri-file-excel-2-line"></i> Export Excel</button>
                <button class="btn-export pdf"><i class="ri-file-pdf-2-line"></i> Export PDF</button>
                <button class="btn-export print" onclick="window.print()"><i class="ri-printer-line"></i> Cetak</button>
            </div>
        </div>

        <div class="report-header">
            <div class="report-title">DATA INFRASTRUKTUR KETENAGALISTRIKAN</div>
            <div class="report-subtitle">PER KABUPATEN/KOTA KALIMANTAN TIMUR</div>
            <div class="report-year">Rekap Perizinan</div>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="table-rekap">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kota/Kabupaten</th>
                            <th>Jumlah Perizinan</th>
                            <th>IUPTLS</th>
                            <th>Rekomtek SKTP</th>
                            <th>Kapasitas (kVA)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($infrastrukturData as $data)
                            <tr>
                                <td class="col-no">{{ $data['no'] }}</td>
                                <td class="col-kabkota">{{ $data['kabupaten_kota'] }}</td>
                                <td class="col-number">
                                    <span
                                        style="background:#E0F2FE;color:#0369A1;padding:4px 12px;border-radius:12px;font-weight:600;">
                                        {{ number_format($data['jumlah_perizinan']) }}
                                    </span>
                                </td>
                                <td class="col-number">{{ number_format($data['jumlah_iuptls']) }}</td>
                                <td class="col-number">{{ number_format($data['rekomtek_sktp']) }}</td>
                                <td class="col-number">{{ number_format($data['jumlah_kapasitas'], 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.rekap-data.detail', ['kabupaten' => urlencode($data['kabupaten_kota'])]) }}"
                                        class="btn-ico" title="Lihat Detail" style="color:#059669;">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;padding:40px;color:#6B7280;">
                                    <i class="ri-file-list-3-line" style="font-size:48px;color:#D1D5DB;"></i>
                                    <p style="margin-top:8px;">Belum ada data perizinan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($infrastrukturData) > 0)
                        <tfoot>
                            <tr>
                                <td colspan="2" class="total-label">TOTAL KALTIM</td>
                                <td>{{ number_format($totalInfra['jumlah_perizinan'] ?? 0) }}</td>
                                <td>{{ number_format($totalInfra['jumlah_iuptls'] ?? 0) }}</td>
                                <td>{{ number_format($totalInfra['rekomtek_sktp'] ?? 0) }}</td>
                                <td>{{ number_format($totalInfra['jumlah_kapasitas'] ?? 0, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="notes-section">
            <div class="notes-title">Keterangan :</div>
            <ul class="notes-list">
                <li><strong>IUPTLS</strong> = Izin Usaha Penyediaan Tenaga Listrik untuk Kepentingan Sendiri</li>
                <li><strong>SKTP</strong> = Surat Keterangan Terdaftar Pembangkit</li>
                <li><strong>kVA</strong> = Kilo Volt Ampere</li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab Navigation
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('tab-' + tabId).classList.add('active');
                });
            });

            // Year filter
            document.getElementById('tahunSelect')?.addEventListener('change', function () {
                window.location.href = '{{ route("admin.rekap-data.index") }}?tahun=' + this.value;
            });

            document.getElementById('tahunSelectInfra')?.addEventListener('change', function () {
                window.location.href = '{{ route("admin.rekap-data.index") }}?tahun=' + this.value;
            });
        });

        // Modal Functions
        function openImportModal() {
            document.getElementById('importModal').style.display = 'block';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            var modal = document.getElementById('importModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endpush