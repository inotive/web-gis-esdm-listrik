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
  </style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Perizinan dan Permohonan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>{{ now()->translatedFormat('F Y') }}</span>
      </div>
      <a href="{{ route('admin.permohonan.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i>
        Tambah Permohonan
      </a>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="tab-navigation">
    <button class="tab-btn active" data-tab="perizinan">
      <i class="ri-file-shield-2-line"></i>
      <span>Data Perizinan</span>
      <span class="badge">{{ count($perizinanData) }}</span>
    </button>
    <button class="tab-btn" data-tab="permohonan">
      <i class="ri-file-list-3-line"></i>
      <span>Manajemen Permohonan</span>
      <span class="badge">{{ count($permohonanData) }}</span>
    </button>
  </div>

  <!-- Tab 1: Data Perizinan -->
  <div class="tab-content active" id="tab-perizinan">
    <section class="card">
      <div class="card-header">
        <div class="filter-row">
          <div class="input-group w-search">
            <span class="input-group-text"><i class="ri-search-line"></i></span>
            <input type="text" id="searchPerizinan" class="form-control" placeholder="Cari Perusahaan atau Jenis Izin"
              autocomplete="off">
          </div>
          <select class="filter-select" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="menunggu">Menunggu Verifikasi</option>
            <option value="expired">Expired</option>
          </select>
          <select class="filter-select" id="filterJenisIzin">
            <option value="">Semua Jenis Izin</option>
            <option value="IUJPTL">IUJPTL</option>
            <option value="IUPTLS">IUPTLS</option>
            <option value="SLO">SLO</option>
            <option value="SKTP">SKTP</option>
          </select>
        </div>
      </div>

      <div class="card-body" style="padding:0;">
        <div class="table-responsive">
          <table class="table-data" id="tablePerizinan">
            <thead>
              <tr>
                <th class="col-no">No</th>
                <th>Perusahaan</th>
                <th>Jenis Izin</th>
                <th>Tanggal Berlaku</th>
                <th>Sumber Pengajuan</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Terbit</th>
                <th>Status</th>
                <th class="col-aksi">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($perizinanData as $i => $izin)
                <tr>
                  <td class="col-no">{{ $i + 1 }}</td>
                  <td><strong>{{ $izin['perusahaan'] }}</strong></td>
                  <td>{{ $izin['jenis_izin'] }}</td>
                  <td class="date-text">{{ $izin['tanggal_berlaku'] }}</td>
                  <td>{{ $izin['sumber_pengajuan'] }}</td>
                  <td class="date-text">{{ $izin['tanggal_pengajuan'] }}</td>
                  <td class="date-text">{{ $izin['tanggal_terbit'] ?? '-' }}</td>
                  <td>
                    @php
                      $statusClass = 'status-aktif';
                      $statusText = $izin['status'];
                      if (str_contains(strtolower($izin['status']), 'menunggu')) {
                        $statusClass = 'status-menunggu';
                      } elseif (str_contains(strtolower($izin['status']), 'expired') || str_contains(strtolower($izin['status']), 'tidak')) {
                        $statusClass = 'status-expired';
                      } elseif (str_contains(strtolower($izin['status']), 'ditolak')) {
                        $statusClass = 'status-ditolak';
                      }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                  </td>
                  <td class="col-aksi">
                    <button class="btn-ico view" title="Lihat Detail"><i class="ri-eye-line"></i></button>
                    <button class="btn-ico edit" title="Edit"><i class="ri-edit-line"></i></button>
                    <button class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-line"></i></button>
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
          <div class="summary">Menampilkan <strong>1–{{ count($perizinanData) }}</strong> dari
            <strong>{{ count($perizinanData) }}</strong> data
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Tab 2: Manajemen Permohonan -->
  <div class="tab-content" id="tab-permohonan">
    <section class="card">
      <div class="card-header">
        <div class="filter-row">
          <div class="input-group w-search">
            <span class="input-group-text"><i class="ri-search-line"></i></span>
            <input type="text" id="searchPermohonan" class="form-control" placeholder="Cari Nama atau Jenis Permohonan"
              autocomplete="off">
          </div>
          <select class="filter-select" id="filterStatusPermohonan">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="menunggu">Menunggu Verifikasi</option>
            <option value="expired">Expired</option>
          </select>
          <select class="filter-select" id="filterJenisPermohonan">
            <option value="">Semua Jenis Izin</option>
            <option value="IUJPTL">IUJPTL</option>
            <option value="IUPTLS">IUPTLS</option>
            <option value="SLO">SLO</option>
            <option value="SKTP">SKTP</option>
          </select>
        </div>
      </div>

      <div class="card-body" style="padding:0;">
        <div class="table-responsive">
          <table class="table-data" id="tablePermohonan">
            <thead>
              <tr>
                <th class="col-no">No</th>
                <th>Perusahaan</th>
                <th>Jenis Izin</th>
                <th>Tanggal Berlaku</th>
                <th>Sumber Pengajuan</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Terbit</th>
                <th>Status</th>
                <th class="col-aksi">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($permohonanData as $i => $item)
                <tr>
                  <td class="col-no">{{ $i + 1 }}</td>
                  <td><strong>{{ $item['perusahaan'] }}</strong></td>
                  <td>{{ $item['jenis_izin'] }}</td>
                  <td class="date-text">{{ $item['tanggal_berlaku'] }}</td>
                  <td>{{ $item['sumber_pengajuan'] }}</td>
                  <td class="date-text">{{ $item['tanggal_pengajuan'] }}</td>
                  <td class="date-text">{{ $item['tanggal_terbit'] ?? '-' }}</td>
                  <td>
                    @php
                      $statusClass = 'status-aktif';
                      $statusText = $item['status'];
                      if (str_contains(strtolower($item['status']), 'menunggu')) {
                        $statusClass = 'status-menunggu';
                      } elseif (str_contains(strtolower($item['status']), 'expired') || str_contains(strtolower($item['status']), 'tidak')) {
                        $statusClass = 'status-expired';
                      } elseif (str_contains(strtolower($item['status']), 'ditolak')) {
                        $statusClass = 'status-ditolak';
                      }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                  </td>
                  <td class="col-aksi">
                    <button class="btn-ico view" title="Lihat Detail"><i class="ri-eye-line"></i></button>
                    <button class="btn-ico edit" title="Edit"><i class="ri-edit-line"></i></button>
                    <button class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-line"></i></button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9">
                    <div class="empty-state">
                      <i class="ri-file-list-3-line"></i>
                      <p>Belum ada data</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div class="summary">Menampilkan
            <strong>1–{{ count($permohonanData) }}</strong> dari
            <strong>{{ count($permohonanData) }}</strong> data
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

      // Delete confirmation
      document.querySelectorAll('.btn-delete-permohonan').forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          const form = this.closest('.form-delete-permohonan');
          const name = form.dataset.name;

          Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus permohonan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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

      // Auto submit on search for permohonan
      const searchInput = document.querySelector('input[name="q"]');
      if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function () {
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
          }, 500);
        });
      }

      // Simple filter for perizinan table (client-side)
      const searchPerizinan = document.getElementById('searchPerizinan');
      const filterStatus = document.getElementById('filterStatus');
      const filterJenisIzin = document.getElementById('filterJenisIzin');
      const tablePerizinan = document.getElementById('tablePerizinan');

      function filterTable() {
        const searchTerm = searchPerizinan?.value.toLowerCase() || '';
        const statusFilter = filterStatus?.value.toLowerCase() || '';
        const jenisFilter = filterJenisIzin?.value || '';

        const rows = tablePerizinan?.querySelectorAll('tbody tr') || [];
        rows.forEach(row => {
          const perusahaan = row.cells[1]?.textContent.toLowerCase() || '';
          const jenisIzin = row.cells[2]?.textContent || '';
          const status = row.cells[7]?.textContent.toLowerCase() || '';

          const matchSearch = perusahaan.includes(searchTerm) || jenisIzin.toLowerCase().includes(searchTerm);
          const matchStatus = !statusFilter || status.includes(statusFilter);
          const matchJenis = !jenisFilter || jenisIzin === jenisFilter;

          row.style.display = (matchSearch && matchStatus && matchJenis) ? '' : 'none';
        });
      }

      searchPerizinan?.addEventListener('input', filterTable);
      filterStatus?.addEventListener('change', filterTable);
      filterJenisIzin?.addEventListener('change', filterTable);
    });
  </script>
@endpush