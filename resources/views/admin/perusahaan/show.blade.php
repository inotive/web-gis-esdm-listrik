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

        /* Perizinan Table */
        .table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }

        .table-perizinan {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-perizinan thead th {
            background: #F8FAFC;
            color: #4B5675;
            font-weight: 500;
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }

        .table-perizinan tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F5F9;
            color: #374151;
            vertical-align: top;
        }

        .table-perizinan tbody tr:last-child td {
            border-bottom: none;
        }

        .table-perizinan tbody tr:hover {
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

        .jenis-izin {
            font-weight: 500;
            color: #111827;
        }

        .tanggal {
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

        .status-aktif {
            background: #ECFDF5;
            color: #059669;
        }

        .status-warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-expired {
            background: #FEE2E2;
            color: #DC2626;
        }

        .keterangan {
            font-size: 12px;
            color: #6B7280;
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

        /* Responsive */
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .company-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
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
                <div class="detail-item-label">Alamat</div>
                <div class="detail-item-value">{{ $perusahaan->alamat ?? '-' }}</div>
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
                <div class="detail-item-value">{{ $perusahaan->village->district->regency->name ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Tanggal Terdaftar</div>
                <div class="detail-item-value">{{ $perusahaan->created_at->translatedFormat('d F Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item-label">Terakhir Diperbarui</div>
                <div class="detail-item-value">{{ $perusahaan->updated_at->translatedFormat('d F Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Perizinan Section -->
    <div class="section-title">
        <i class="ri-file-shield-2-line"></i>
        <span>Detail Perizinan</span>
        <span class="badge">{{ count($perizinanData) }}</span>
    </div>

    <div class="table-container">
        @if(count($perizinanData) > 0)
            <table class="table-perizinan">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Nomor Izin</th>
                        <th>Jenis Izin</th>
                        <th>Tanggal Terbit</th>
                        <th>Tanggal Berlaku</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perizinanData as $index => $izin)
                        <tr>
                            <td class="col-no">{{ $index + 1 }}</td>
                            <td class="no-izin">{{ $izin['no_izin'] }}</td>
                            <td class="jenis-izin">{{ $izin['jenis_izin'] }}</td>
                            <td class="tanggal">
                                {{ $izin['tanggal_terbit'] ? \Carbon\Carbon::parse($izin['tanggal_terbit'])->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="tanggal">
                                {{ $izin['tanggal_berlaku'] ? \Carbon\Carbon::parse($izin['tanggal_berlaku'])->translatedFormat('d M Y') : 'Tidak Terbatas' }}
                            </td>
                            <td>
                                @php
                                    $statusClass = 'status-aktif';
                                    if ($izin['status'] === 'Perlu Diperpanjang') {
                                        $statusClass = 'status-warning';
                                    } elseif ($izin['status'] === 'Expired' || $izin['status'] === 'Tidak Aktif') {
                                        $statusClass = 'status-expired';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $izin['status'] }}
                                </span>
                            </td>
                            <td class="keterangan">{{ $izin['keterangan'] }}</td>
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
@endsection

@push('scripts')
    <script>
        // Any additional scripts can go here
    </script>
@endpush