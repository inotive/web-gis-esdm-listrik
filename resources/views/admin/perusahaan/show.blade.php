@extends('admin.layouts.app')

@section('title', 'Detail Perusahaan - ' . $perusahaan->nama)

@push('styles')
    <style>
        /* Back Button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 16px;
        }

        .btn-back:hover {
            background: #E2E8F0;
            color: #1E293B;
        }

        /* Company Info Card */
        .company-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            padding: 24px;
            margin-bottom: 24px;
        }

        .company-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 20px;
        }

        .company-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #0077B6 0%, #00B4D8 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            flex-shrink: 0;
        }

        .company-info h2 {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px 0;
        }

        .company-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #ECFDF5;
            color: #059669;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .detail-item {
            padding: 16px;
            background: #F8FAFC;
            border-radius: 10px;
        }

        .detail-item-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .detail-item-value {
            font-size: 14px;
            color: #1F2937;
            font-weight: 600;
        }

        /* Tabs Navigation */
        .detail-tabs {
            display: flex;
            gap: 4px;
            background: #F1F5F9;
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #1E293B;
            background: rgba(255, 255, 255, 0.5);
        }

        .tab-btn.active {
            background: white;
            color: #0077B6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tab-btn .badge {
            background: #E2E8F0;
            color: #475569;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .tab-btn.active .badge {
            background: #0077B6;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Section Title */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        .section-title i {
            color: #0077B6;
        }

        .section-title .badge {
            background: #0077B6;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }

        /* Common Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .data-table thead th {
            background: #F8FAFC;
            color: #4B5675;
            font-weight: 500;
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }

        .data-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F5F9;
            color: #374151;
            vertical-align: top;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr:hover {
            background: #F8FAFC;
        }

        .col-no {
            width: 50px;
            text-align: center;
            color: #6B7280;
        }

        .no-izin {
            font-weight: 600;
            color: #0077B6;
        }

        .jenis-izin,
        .nama-bold {
            font-weight: 500;
            color: #111827;
        }

        .tanggal,
        .text-muted {
            font-size: 12px;
            color: #6B7280;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-aktif,
        .status-approved {
            background: #ECFDF5;
            color: #059669;
        }

        .status-pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-rejected,
        .status-expired {
            background: #FEE2E2;
            color: #DC2626;
        }

        .status-warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-database {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .keterangan {
            font-size: 12px;
            color: #6B7280;
        }

        /* Document Link */
        .doc-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: #EFF6FF;
            color: #2563EB;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .doc-link:hover {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
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

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-action {
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
            text-decoration: none;
        }

        .btn-action.primary {
            background: #0077B6;
            color: white;
        }

        .btn-action.secondary {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #E2E8F0;
        }

        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 22px;
        }

        .stat-card-icon.perizinan {
            background: linear-gradient(135deg, #0077B6 0%, #00B4D8 100%);
            color: white;
        }

        .stat-card-icon.permohonan {
            background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%);
            color: white;
        }

        .stat-card-icon.dokumen {
            background: linear-gradient(135deg, #059669 0%, #34D399 100%);
            color: white;
        }

        .stat-card-icon.infrastruktur {
            background: linear-gradient(135deg, #F59E0B 0%, #FCD34D 100%);
            color: white;
        }

        .stat-card-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .stat-card-label {
            font-size: 13px;
            color: #6B7280;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .summary-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .company-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .detail-tabs {
                flex-direction: column;
            }

            .summary-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.perusahaan.index') }}" class="btn-back">
        <i class="ri-arrow-left-line"></i>
        Kembali ke Daftar Perusahaan
    </a>

    <div class="page-head">
        <div>
            <div class="page-meta">Detail Perusahaan</div>
            <div class="page-title">{{ $perusahaan->nama }}</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.perusahaan.edit', $perusahaan) }}" class="btn btn-primary">
                <i class="ri-edit-line"></i>
                Edit Perusahaan
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="summary-stats">
        <div class="stat-card">
            <div class="stat-card-icon perizinan">
                <i class="ri-file-shield-2-line"></i>
            </div>
            <div class="stat-card-value">{{ count($perizinanData) }}</div>
            <div class="stat-card-label">Total Perizinan</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon permohonan">
                <i class="ri-file-list-3-line"></i>
            </div>
            <div class="stat-card-value">{{ count($permohonanData) }}</div>
            <div class="stat-card-label">Total Permohonan</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon dokumen">
                <i class="ri-folder-2-line"></i>
            </div>
            <div class="stat-card-value">{{ count($dokumenData) }}</div>
            <div class="stat-card-label">Total Dokumen</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon infrastruktur">
                <i class="ri-building-4-line"></i>
            </div>
            <div class="stat-card-value">
                {{ $perusahaan->pembangkitListriks->count() + $perusahaan->gardus->count() + $perusahaan->infrastrukturJaringans->count() }}
            </div>
            <div class="stat-card-label">Infrastruktur</div>
        </div>
    </div>

    <!-- Company Info Card -->
    <div class="company-card">
        <div class="company-header">
            <div class="company-icon">
                <i class="ri-building-line"></i>
            </div>
            <div class="company-info">
                <h2>{{ $perusahaan->nama }}</h2>
                <span class="company-badge">
                    <i class="ri-checkbox-circle-fill"></i>
                    Perusahaan Aktif
                </span>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-item-label">Nama Pimpinan</div>
                <div class="detail-item-value">{{ $perusahaan->nama_pimpinan ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Alamat</div>
                <div class="detail-item-value">{{ $perusahaan->alamat ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Kontak</div>
                <div class="detail-item-value">{{ $perusahaan->kontak ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Desa/Kelurahan</div>
                <div class="detail-item-value">{{ $perusahaan->village->name ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Kecamatan</div>
                <div class="detail-item-value">{{ $perusahaan->village->district->name ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Kabupaten/Kota</div>
                <div class="detail-item-value">
                    {{ $perusahaan->kabupaten_kota ?? ($perusahaan->village->district->regency->name ?? '-') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Tanggal Terdaftar</div>
                <div class="detail-item-value">{{ $perusahaan->created_at->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="detail-tabs">
        <button class="tab-btn active" data-tab="perizinan">
            <i class="ri-file-shield-2-line"></i>
            History Perizinan
            <span class="badge">{{ count($perizinanData) }}</span>
        </button>
        <button class="tab-btn" data-tab="permohonan">
            <i class="ri-file-list-3-line"></i>
            Permohonan
            <span class="badge">{{ count($permohonanData) }}</span>
        </button>
        <button class="tab-btn" data-tab="dokumen">
            <i class="ri-folder-2-line"></i>
            Dokumen
            <span class="badge">{{ count($dokumenData) }}</span>
        </button>
    </div>

    <!-- Tab Content: Perizinan -->
    <div class="tab-content active" id="tab-perizinan">
        <div class="section-title">
            <i class="ri-file-shield-2-line"></i>
            <span>History Perizinan</span>
            <span class="badge">{{ count($perizinanData) }}</span>
        </div>

        <div class="table-container">
            @if (count($perizinanData) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>No. Surat Izin</th>
                            <th>Jenis Izin</th>
                            <th>Tanggal Terbit</th>
                            <th>Tanggal Akhir</th>
                            <th>Lokasi</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perizinanData as $index => $izin)
                            <tr>
                                <td class="col-no">{{ $index + 1 }}</td>
                                <td class="no-izin">{{ $izin['no_izin'] }}</td>
                                <td>
                                    <span class="status-badge status-aktif">{{ $izin['jenis_izin'] }}</span>
                                </td>
                                <td class="tanggal">
                                    {{ $izin['tanggal_terbit'] ? \Carbon\Carbon::parse($izin['tanggal_terbit'])->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="tanggal">
                                    {{ $izin['tanggal_akhir'] ? \Carbon\Carbon::parse($izin['tanggal_akhir'])->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td>{{ $izin['lokasi'] ?? '-' }}</td>
                                <td>{{ $izin['kapasitas'] ? number_format($izin['kapasitas'], 2, ',', '.') . ' kVA' : '-' }}
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'status-expired';
                                        if ($izin['status'] === 'Aktif') {
                                            $statusClass = 'status-aktif';
                                        } elseif ($izin['status'] === 'Mau Berakhir') {
                                            $statusClass = 'status-warning';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $izin['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="ri-file-shield-line"></i>
                    <p>Belum ada data perizinan untuk perusahaan ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tab Content: Permohonan -->
    <div class="tab-content" id="tab-permohonan">
        <div class="section-title">
            <i class="ri-file-list-3-line"></i>
            <span>Data Permohonan</span>
            <span class="badge">{{ count($permohonanData) }}</span>
        </div>

        <div class="table-container">
            @if (count($permohonanData) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Jenis Permohonan</th>
                            <th>Pengaju</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permohonanData as $index => $permohonan)
                            <tr>
                                <td class="col-no">{{ $index + 1 }}</td>
                                <td class="nama-bold">{{ $permohonan['jenis_permohonan'] }}</td>
                                <td>{{ $permohonan['user'] }}</td>
                                <td class="tanggal">
                                    {{ $permohonan['tanggal_pengajuan'] ? \Carbon\Carbon::parse($permohonan['tanggal_pengajuan'])->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'status-pending';
                                        $statusText = ucfirst($permohonan['status']);
                                        if (
                                            in_array(strtolower($permohonan['status']), [
                                                'approved',
                                                'disetujui',
                                                'selesai',
                                            ])
                                        ) {
                                            $statusClass = 'status-approved';
                                        } elseif (
                                            in_array(strtolower($permohonan['status']), ['rejected', 'ditolak'])
                                        ) {
                                            $statusClass = 'status-rejected';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="keterangan">{{ $permohonan['keterangan'] ?? '-' }}</td>
                                <td>
                                    @if (count($permohonan['documents']) > 0)
                                        @foreach ($permohonan['documents'] as $doc)
                                            @if ($doc->dokumen)
                                                <a href="{{ asset('storage/' . $doc->dokumen->path) }}" target="_blank"
                                                    class="doc-link">
                                                    <i class="ri-file-line"></i>
                                                    {{ Str::limit($doc->nama ?? $doc->dokumen->nama, 20) }}
                                                </a>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="ri-file-list-line"></i>
                    <p>Belum ada data permohonan untuk perusahaan ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tab Content: Dokumen -->
    <div class="tab-content" id="tab-dokumen">
        <div class="section-title">
            <i class="ri-folder-2-line"></i>
            <span>Dokumen Perusahaan</span>
            <span class="badge">{{ count($dokumenData) }}</span>
        </div>

        <div class="table-container">
            @if (count($dokumenData) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama Dokumen</th>
                            <th>Tipe</th>
                            <th>Sumber</th>
                            <th>Ukuran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokumenData as $index => $doc)
                            <tr>
                                <td class="col-no">{{ $index + 1 }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if (isset($doc['tipe']) && $doc['tipe'] === 'folder')
                                            <i class="ri-folder-fill" style="color: #F59E0B; font-size: 18px;"></i>
                                        @else
                                            <i class="ri-file-text-fill" style="color: #22C55E; font-size: 18px;"></i>
                                        @endif
                                        <span class="nama-bold">{{ $doc['nama'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if (isset($doc['tipe']))
                                        <span
                                            class="status-badge {{ $doc['tipe'] === 'folder' ? 'status-warning' : 'status-aktif' }}">
                                            {{ ucfirst($doc['tipe']) }}
                                        </span>
                                    @else
                                        <span class="status-badge status-aktif">File</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $source = $doc['source'] ?? 'unknown';
                                        $sourceBadgeClass = 'status-aktif';
                                        $sourceLabel = $doc['perizinan_nama'] ?? 'Dokumen';

                                        if ($source === 'dokumen_db') {
                                            $sourceBadgeClass = 'status-database';
                                            $sourceLabel = 'Database Dokumen';
                                        } elseif ($source === 'perizinan') {
                                            $sourceBadgeClass = 'status-aktif';
                                        } elseif ($source === 'permohonan') {
                                            $sourceBadgeClass = 'status-warning';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $sourceBadgeClass }}">{{ $sourceLabel }}</span>
                                </td>
                                <td class="text-muted">
                                    {{ $doc['size'] ?? '-' }}
                                </td>
                                <td class="tanggal">
                                    {{ $doc['tanggal_terbit'] ? \Carbon\Carbon::parse($doc['tanggal_terbit'])->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td>
                                    @if (isset($doc['source']) && $doc['source'] === 'dokumen_db')
                                        @if (isset($doc['tipe']) && $doc['tipe'] === 'folder')
                                            <a href="{{ route('admin.dokumen.index', ['folder' => $doc['id']]) }}"
                                                class="doc-link">
                                                <i class="ri-folder-open-line"></i>
                                                Buka
                                            </a>
                                        @elseif(isset($doc['path']) && $doc['path'])
                                            <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank"
                                                class="doc-link">
                                                <i class="ri-eye-line"></i>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @elseif($doc['dokumen'] && $doc['dokumen']->path)
                                        <a href="{{ asset('storage/' . $doc['dokumen']->path) }}" target="_blank"
                                            class="doc-link">
                                            <i class="ri-eye-line"></i>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="ri-folder-line"></i>
                    <p>Belum ada dokumen untuk perusahaan ini</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.dataset.tab;

                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    this.classList.add('active');
                    document.getElementById('tab-' + tabId).classList.add('active');
                });
            });
        });
    </script>
@endpush
