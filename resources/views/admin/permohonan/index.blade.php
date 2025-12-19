@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Perizinan dan Permohonan')

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
      width: 100px;
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
      <a href="#" class="btn btn-primary" id="btnTambahPermohonan" style="display: none;">
        <i class="ri-add-line"></i>
        Tambah Permohonan
      </a>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="tab-navigation">
    <button class="tab-btn {{ $tab === 'perizinan' ? 'active' : '' }}" data-tab="perizinan">
      <i class="ri-file-shield-2-line"></i>
      <span>Data Perizinan</span>
      <span class="badge">{{ $perizinanItems->total() }}</span>
    </button>
    <button class="tab-btn {{ $tab === 'permohonan' ? 'active' : '' }}" data-tab="permohonan">
      <i class="ri-file-list-3-line"></i>
      <span>Manajemen Permohonan</span>
      <span class="badge">{{ $permohonanUsers->total() }}</span>
    </button>
  </div>

  <!-- Tab 1: Data Perizinan -->
  <div class="tab-content {{ $tab === 'perizinan' ? 'active' : '' }}" id="tab-perizinan">
    <!-- Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px;">
      <!-- Total Perizinan -->
      <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 48px; height: 48px; background: #EEF2FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="ri-file-shield-2-line" style="font-size: 24px; color: #667eea;"></i>
          </div>
          <div>
            <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Total Perizinan</div>
            <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">{{ number_format($perizinanStats['total']) }}</div>
          </div>
        </div>
      </div>

      <!-- IUPTLS -->
      <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="ri-shield-check-line" style="font-size: 24px; color: #059669;"></i>
          </div>
          <div>
            <div style="font-size: 12px; color: #6B7280; font-weight: 500;">IUPTLS</div>
            <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">{{ number_format($perizinanStats['iuptls']) }}</div>
          </div>
        </div>
      </div>

      <!-- SKTP -->
      <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 48px; height: 48px; background: #FCE7F3; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="ri-shield-star-line" style="font-size: 24px; color: #DB2777;"></i>
          </div>
          <div>
            <div style="font-size: 12px; color: #6B7280; font-weight: 500;">SKTP</div>
            <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">{{ number_format($perizinanStats['sktp']) }}</div>
          </div>
        </div>
      </div>

      <!-- Sedang Aktif -->
      <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="ri-checkbox-circle-line" style="font-size: 24px; color: #10B981;"></i>
          </div>
          <div>
            <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Sedang Aktif</div>
            <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">{{ number_format($perizinanStats['aktif']) }}</div>
          </div>
        </div>
      </div>

      <!-- Mau Berakhir -->
      <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="ri-time-line" style="font-size: 24px; color: #F59E0B;"></i>
          </div>
          <div>
            <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Mau Berakhir</div>
            <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">{{ number_format($perizinanStats['mau_berakhir']) }}</div>
          </div>
        </div>
      </div>

      <!-- Berakhir -->
      <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 48px; height: 48px; background: #FEE2E2; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="ri-close-circle-line" style="font-size: 24px; color: #EF4444;"></i>
          </div>
          <div>
            <div style="font-size: 12px; color: #6B7280; font-weight: 500;">Berakhir</div>
            <div style="font-size: 24px; font-weight: 700; color: #111827; line-height: 1;">{{ number_format($perizinanStats['berakhir']) }}</div>
          </div>
        </div>
      </div>
    </div>

    <section class="card">
      <div class="card-header">
        <form method="GET" action="{{ route('admin.permohonan.index') }}" id="filterFormPerizinan">
          <input type="hidden" name="tab" value="perizinan">
          <div class="filter-row">
            <div class="input-group w-search">
              <span class="input-group-text"><i class="ri-search-line"></i></span>
              <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari Perusahaan, No. Izin..."
                autocomplete="off">
            </div>
            <select class="filter-select" name="status" onchange="this.form.submit()">
              <option value="">Semua Status</option>
              <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
              <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
              <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <select class="filter-select" name="jenis" onchange="this.form.submit()">
              <option value="">Semua Jenis Izin</option>
              @foreach($jenisOptions as $jenisOption)
                <option value="{{ $jenisOption }}" {{ $jenis === $jenisOption ? 'selected' : '' }}>{{ $jenisOption }}</option>
              @endforeach
            </select>
            @if($q || $status || $jenis)
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
            </thead>
            <tbody>
              @forelse($perizinanItems as $i => $izin)
                @php
                  // Determine status based on tanggal_terbit and tanggal_akhir
                  $statusText = 'Menunggu Verifikasi';
                  $statusClass = 'status-menunggu';

                  if ($izin->tanggal_terbit && $izin->tanggal_akhir) {
                    $today = now();
                    $tanggalAkhir = \Carbon\Carbon::parse($izin->tanggal_akhir);

                    if ($tanggalAkhir->isPast()) {
                      $statusText = 'Expired';
                      $statusClass = 'status-expired';
                    } else {
                      $statusText = 'Aktif';
                      $statusClass = 'status-aktif';
                    }
                  } elseif ($izin->tanggal_terbit) {
                    $statusText = 'Aktif';
                    $statusClass = 'status-aktif';
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
                    @if($izin->jenis)
                      <span class="jenis-badge {{ $jenisClass }}">{{ $izin->jenis }}</span>
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ $izin->no_surat_izin ?? '-' }}</td>
                  <td class="date-text">{{ $izin->tanggal_terbit ? $izin->tanggal_terbit->format('d/m/Y') : '-' }}</td>
                  <td class="date-text">{{ $izin->tanggal_akhir ? $izin->tanggal_akhir->format('d/m/Y') : '-' }}</td>
                  <td><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                  <td style="text-align: right;">
                    <span class="kapasitas-value">{{ number_format($izin->kapasitas ?? 0, 2) }}</span> kVA
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
            Menampilkan <strong>{{ $perizinanItems->firstItem() ?? 0 }}–{{ $perizinanItems->lastItem() ?? 0 }}</strong>
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

  <!-- Tab 2: Manajemen Permohonan -->
  <div class="tab-content {{ $tab === 'permohonan' ? 'active' : '' }}" id="tab-permohonan">
    <section class="card">
      <div class="card-header">
        <div class="filter-row">
          <div class="input-group w-search">
            <span class="input-group-text"><i class="ri-search-line"></i></span>
            <input type="text" id="searchPermohonan" class="form-control"
              placeholder="Cari Pengguna atau Kategori Permohonan" autocomplete="off">
          </div>
          <select class="filter-select" id="filterRegencyPermohonan">
            <option value="">Semua Kota/Kabupaten</option>
            @foreach($regencies as $regency)
              <option value="{{ $regency->id }}">{{ $regency->name }}</option>
            @endforeach
          </select>
          <select class="filter-select" id="filterDistrictPermohonan" disabled>
            <option value="">Semua Kecamatan</option>
          </select>
          <select class="filter-select" id="filterVillagePermohonan" disabled>
            <option value="">Semua Kelurahan/Desa</option>
          </select>
          <select class="filter-select" id="filterStatusPermohonan">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Verifikasi</option>
            <option value="diproses">Sedang Diproses</option>
            <option value="selesai">Aktif</option>
            <option value="ditolak">Ditolak</option>
            <option value="expired">Expired</option>
          </select>
        </div>
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
                  <td class="date-text">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                  <td class="col-aksi">
                    <a href="{{ route('admin.permohonan-user.show', [$item->permohonan_id, $item->id]) }}"
                      class="btn-ico view" title="Lihat Detail">
                      <i class="ri-eye-line"></i>
                    </a>
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
            <strong>{{ $permohonanUsers->firstItem() ?? 0 }}–{{ $permohonanUsers->lastItem() ?? 0 }}</strong> dari
            <strong>{{ $permohonanUsers->total() }}</strong> data
          </div>
          <div>
            {{ $permohonanUsers->links() }}
          </div>
        </div>
      </div>
    </section>
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

      // Success notification
      @if(session('success'))
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
        searchInput.addEventListener('keypress', function (e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterFormPerizinan').submit();
          }
        });
      }

      // Filter for permohonan table (client-side)
      const searchPermohonan = document.getElementById('searchPermohonan');
      const filterStatusPermohonan = document.getElementById('filterStatusPermohonan');
      const tablePermohonan = document.getElementById('tablePermohonan');

      function filterTablePermohonan() {
        const searchTerm = searchPermohonan?.value.toLowerCase() || '';
        const statusFilter = filterStatusPermohonan?.value.toLowerCase() || '';

        const rows = tablePermohonan?.querySelectorAll('tbody tr') || [];
        rows.forEach(row => {
          if (row.querySelector('.empty-state')) return;

          const pengguna = row.cells[1]?.textContent.toLowerCase() || '';
          const kategori = row.cells[2]?.textContent.toLowerCase() || '';
          const status = row.cells[3]?.textContent.toLowerCase() || '';

          const matchSearch = pengguna.includes(searchTerm) || kategori.includes(searchTerm);
          const matchStatus = !statusFilter ||
            (statusFilter === 'pending' && status.includes('menunggu')) ||
            (statusFilter === 'diproses' && status.includes('diproses')) ||
            (statusFilter === 'selesai' && (status.includes('aktif') || status.includes('selesai'))) ||
            (statusFilter === 'ditolak' && status.includes('ditolak')) ||
            (statusFilter === 'expired' && status.includes('expired'));

          row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });

        // Re-apply sorting after filtering if there's an active sort
        if (currentSortColumn) {
          sortTablePermohonan(currentSortColumn, currentSortDirection);
        }
      }

      searchPermohonan?.addEventListener('input', filterTablePermohonan);
      filterStatusPermohonan?.addEventListener('change', filterTablePermohonan);

      // Cascading dropdown for regency, district, and village (Permohonan tab)
      const filterRegencyPermohonan = document.getElementById('filterRegencyPermohonan');
      const filterDistrictPermohonan = document.getElementById('filterDistrictPermohonan');
      const filterVillagePermohonan = document.getElementById('filterVillagePermohonan');

      // Load districts when regency is selected
      filterRegencyPermohonan?.addEventListener('change', async function() {
        const regencyId = this.value;

        // Reset district and village dropdowns
        if (filterDistrictPermohonan) {
          filterDistrictPermohonan.innerHTML = '<option value="">Semua Kecamatan</option>';
          filterDistrictPermohonan.disabled = !regencyId;
        }
        if (filterVillagePermohonan) {
          filterVillagePermohonan.innerHTML = '<option value="">Semua Kelurahan/Desa</option>';
          filterVillagePermohonan.disabled = true;
        }

        if (regencyId) {
          try {
            const response = await fetch('{{ route("admin.permohonan.options.districts") }}?regency_id=' + encodeURIComponent(regencyId));
            const districts = await response.json();

            if (filterDistrictPermohonan) {
              districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                filterDistrictPermohonan.appendChild(option);
              });
            }
          } catch (error) {
            console.error('Error loading districts:', error);
          }
        }
      });

      // Load villages when district is selected
      filterDistrictPermohonan?.addEventListener('change', async function() {
        const districtId = this.value;

        // Reset village dropdown
        if (filterVillagePermohonan) {
          filterVillagePermohonan.innerHTML = '<option value="">Semua Kelurahan/Desa</option>';
          filterVillagePermohonan.disabled = !districtId;
        }

        if (districtId) {
          try {
            const response = await fetch('{{ route("admin.permohonan.options.villages") }}?district_id=' + encodeURIComponent(districtId));
            const villages = await response.json();

            if (filterVillagePermohonan) {
              villages.forEach(village => {
                const option = document.createElement('option');
                option.value = village.id;
                option.textContent = village.name;
                filterVillagePermohonan.appendChild(option);
              });
            }
          } catch (error) {
            console.error('Error loading villages:', error);
          }
        }
      });

      // Sorting functionality
      let currentSortColumn = null;
      let currentSortDirection = 'asc'; // 'asc' or 'desc'

      const sortableHeaders = tablePermohonan?.querySelectorAll('.sortable-header');
      sortableHeaders?.forEach(header => {
        header.addEventListener('click', function() {
          const sortType = this.getAttribute('data-sort');

          // Toggle sort direction if clicking the same column
          if (currentSortColumn === sortType) {
            currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
          } else {
            currentSortColumn = sortType;
            currentSortDirection = 'asc';
          }

          // Update header classes
          sortableHeaders.forEach(h => {
            h.classList.remove('sorted', 'sorted-asc', 'sorted-desc');
          });
          this.classList.add('sorted', `sorted-${currentSortDirection}`);

          // Sort table
          sortTablePermohonan(sortType, currentSortDirection);
        });
      });

      function sortTablePermohonan(sortType, direction) {
        const tbody = tablePermohonan?.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
          // Skip empty state row and hidden rows (filtered out)
          return !row.querySelector('.empty-state') && row.style.display !== 'none';
        });

        rows.sort((a, b) => {
          let aValue, bValue;

          switch (sortType) {
            case 'no':
              aValue = parseInt(a.cells[0]?.textContent.trim()) || 0;
              bValue = parseInt(b.cells[0]?.textContent.trim()) || 0;
              break;
            case 'pengguna':
              aValue = (a.cells[1]?.textContent.trim() || '').toLowerCase();
              bValue = (b.cells[1]?.textContent.trim() || '').toLowerCase();
              break;
            case 'kategori':
              aValue = (a.cells[2]?.textContent.trim() || '').toLowerCase();
              bValue = (b.cells[2]?.textContent.trim() || '').toLowerCase();
              break;
            case 'status':
              aValue = (a.cells[3]?.textContent.trim() || '').toLowerCase();
              bValue = (b.cells[3]?.textContent.trim() || '').toLowerCase();
              break;
            case 'tanggal':
              // Parse tanggal format d/m/Y
              const aDateStr = a.cells[4]?.textContent.trim() || '';
              const bDateStr = b.cells[4]?.textContent.trim() || '';

              if (aDateStr === '-' && bDateStr === '-') {
                aValue = 0;
                bValue = 0;
              } else if (aDateStr === '-') {
                aValue = 0;
                bValue = parseDate(bDateStr);
              } else if (bDateStr === '-') {
                aValue = parseDate(aDateStr);
                bValue = 0;
              } else {
                aValue = parseDate(aDateStr);
                bValue = parseDate(bDateStr);
              }
              break;
            default:
              return 0;
          }

          // Compare values
          if (typeof aValue === 'number' && typeof bValue === 'number') {
            return direction === 'asc' ? aValue - bValue : bValue - aValue;
          } else {
            if (aValue < bValue) return direction === 'asc' ? -1 : 1;
            if (aValue > bValue) return direction === 'asc' ? 1 : -1;
            return 0;
          }
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
      }

      function parseDate(dateStr) {
        // Parse format d/m/Y to timestamp
        if (!dateStr || dateStr === '-') return 0;
        const parts = dateStr.split('/');
        if (parts.length !== 3) return 0;
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1; // Month is 0-indexed
        const year = parseInt(parts[2], 10);
        const date = new Date(year, month, day);
        return date.getTime();
      }
    });
  </script>
@endpush
