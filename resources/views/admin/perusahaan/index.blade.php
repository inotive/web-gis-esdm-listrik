@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Perusahaan')

@push('styles')
<style>
  /* Table Header/Filter Section - Sesuai Figma */
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

  /* Search Box - Sesuai Figma */
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

  .input-group-text i {
    font-size: 16px;
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

  .form-control::placeholder {
    color: #78829D;
  }

  .form-control:focus {
    outline: none;
  }

  /* Filter Dropdown - Sesuai Figma */
  .w-filter {
    width: 139px;
  }

  .input-group.has-select {
    position: relative;
  }

  .input-group .form-select {
    height: 100%;
    border: none;
    background: transparent;
    padding: 0 28px 0 10px;
    font-size: 11px;
    color: #7c7c7c;
    outline: none;
    width: 100%;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
  }

  .input-group .form-select option:first-child {
    color: #7c7c7c;
  }

  .input-group .form-select option:not(:first-child) {
    color: #252F4A;
  }

  .input-group .form-select:focus {
    outline: none;
  }

  /* Custom dropdown arrow inside input-group */
  .input-group.has-select::after {
    content: '';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    pointer-events: none;
  }

  /* Clear & Reset Buttons */
  .btn-ghost {
    height: 32px;
    padding: 0 10px;
    border: 1px solid #F1F1F4;
    background: #fff;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    color: #4B5675;
    transition: all 0.2s;
  }

  .btn-ghost:hover {
    background: #F8FAFC;
  }

  .btn-ghost i {
    font-size: 14px;
  }

  /* Updated Table Styles - Sesuai Figma */
  .table-shell{
    background: white;
    overflow: hidden;
  }

  .table-perusahaan{
    width: 100%;
    border-collapse: collapse;
  }

  .table-perusahaan thead {
    background: #FCFCFC;
  }

  .table-perusahaan thead th{
    background: #FCFCFC;
    color: #4B5675;
    font-weight: 400;
    font-size: 13px;
    padding: 12px 20px;
    text-align: left;
    border-bottom: 1px solid #F1F1F4;
    white-space: nowrap;
    border-radius: 0;
  }

  .table-perusahaan thead th:first-child {
    border-top-left-radius: 0;
  }

  .table-perusahaan thead th:last-child {
    border-top-right-radius: 0;
  }

  .table-perusahaan tbody td{
    padding: 23px 20px;
    border-bottom: 1px solid #F1F1F4;
    color: #252F4A;
    font-size: 14px;
    vertical-align: middle;
  }

  .table-perusahaan tbody tr:last-child td {
    border-bottom: none;
  }

  .table-perusahaan tbody tr:hover{
    background: #FCFCFC;
  }

  .col-no{
    width: 48px;
    text-align: center;
    color: #071437;
  }

  .col-aksi{
    width: 120px;
    text-align: center;
    vertical-align: middle;
  }

  .col-aksi > * {
    vertical-align: middle;
  }

  /* Action Buttons - Sesuai Figma */
  .btn-ico{
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

  .btn-ico:hover{
    transform: scale(1.1);
  }

  .btn-ico svg {
    width: 24px;
    height: 24px;
    display: block;
  }

  /* Edit icon - Orange/Yellow */
  .btn-ico.edit svg path {
    stroke: #DFA000;
  }

  .btn-ico.edit svg circle {
    fill: #DFA000;
  }

  /* Delete icon - Red */
  .btn-ico.danger svg path {
    stroke: #F8285A;
  }

  .col-aksi .btn-ico:first-child {
    margin-left: 0;
  }

  .col-aksi form {
    display: inline-flex;
    vertical-align: middle;
  }

  /* Table Footer - Sesuai Figma */
  .table-footer{
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 20px;
    border-top: 1px solid #F1F1F4;
    background: #fff;
  }

  .table-footer-left {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .table-footer-right {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .summary{
    color: #4B5675;
    font-size: 13px;
    white-space: nowrap;
  }

  .show-wrap{
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #4B5675;
    font-size: 13px;
    white-space: nowrap;
  }

  .show-wrap form {
    display: inline-flex;
    margin: 0;
    padding: 0;
  }

  .show-wrap .form-select{
    width: 70px;
    height: 30px;
    background: #FCFCFC;
    border: 1px solid #DBDFE9;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 11px;
    color: #252F4A;
    cursor: pointer;
    text-align: center;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 14px;
    padding-right: 30px;
  }

  .show-wrap .form-select:focus {
    outline: none;
    border-color: #17C653;
  }

  /* Pagination Styles - Sesuai Figma */
  .pagination {
    display: flex;
    align-items: center;
    gap: 2px;
  }

  .pagination .page-item {
    list-style: none;
  }

  .pagination .page-link {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 14px;
    color: #4B5675;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    background: transparent;
  }

  .pagination .page-link:hover {
    background: #F5F5F5;
  }

  .pagination .page-item.active .page-link {
    background: #F1F1F4;
    color: #252F4A;
    font-weight: 500;
  }

  .pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Responsive */
  @media (max-width: 768px) {
    /* Toolbar / Filter Section */
    .toolbar {
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
    }

    .w-search,
    .w-filter {
      width: 100%;
    }

    /* Table */
    .table-perusahaan {
      font-size: 13px;
    }

    .table-perusahaan thead th,
    .table-perusahaan tbody td {
      padding: 12px 10px;
    }

    .col-no {
      width: 40px;
    }

    /* Footer */
    .table-footer {
      flex-direction: column;
      align-items: flex-start;
    }

    .table-footer-left,
    .table-footer-right {
      width: 100%;
      justify-content: space-between;
    }

    .table-footer-right {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Data Perusahaan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>{{ now()->translatedFormat('F Y') }}</span>
      </div>

      @include('admin.perusahaan.create') {{-- modal create --}}
      @include('admin.perusahaan.edit_modal') {{-- modal edit --}}

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Data Perusahaan
      </button>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.perusahaan.index') }}">
        {{-- Search Nama Perusahaan --}}
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari Nama Perusahaan" autocomplete="off">
        </div>

        {{-- Filter Berdasarkan --}}
        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="regency_id" id="filterRegency">
            <option value="">Filter Berdasarkan</option>
            @foreach($regencies as $rg)
              <option value="{{ $rg->id }}" @selected(request('regency_id')==$rg->id)>{{ $rg->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Hidden filter untuk maintain state --}}
        @if(request('regency_id'))
          <div class="input-group w-filter has-select" style="display: none;">
            <select class="form-select auto-submit" name="district_id" id="filterDistrict">
              <option value="">Semua Kecamatan</option>
              @foreach($districts as $dc)
                <option value="{{ $dc->id }}" @selected(request('district_id')==$dc->id)>{{ $dc->name }}</option>
              @endforeach
            </select>
          </div>
        @endif
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-perusahaan">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Perusahaan</th>
              <th>Alamat</th>
              <th>Desa</th>
              <th>Kecamatan</th>
              <th>Kabupaten/Kota</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($perusahaans as $i => $perusahaan)
              <tr>
                <td class="col-no">{{ $perusahaans->firstItem() + $i }}</td>
                <td><strong>{{ $perusahaan->nama }}</strong></td>
                <td>{{ $perusahaan->alamat ?? '-' }}</td>
                <td>{{ $perusahaan->village->name ?? '-' }}</td>
                <td>{{ $perusahaan->village->district->name ?? '-' }}</td>
                <td>{{ $perusahaan->village->district->regency->name ?? '-' }}</td>
                <td class="col-aksi">
                  <button type="button" class="btn-ico edit btn-edit-perusahaan"
                    data-id="{{ $perusahaan->id }}"
                    data-nama="{{ $perusahaan->nama }}"
                    data-alamat="{{ $perusahaan->alamat ?? '' }}"
                    data-regency-id="{{ $perusahaan->village->district->regency_id ?? '' }}"
                    data-district-id="{{ $perusahaan->village->district_id ?? '' }}"
                    data-village-id="{{ $perusahaan->village_id }}"
                    title="Edit">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="12" cy="12" r="2" fill="#DFA000"/>
                      <path d="M12 5L9 8M12 5L15 8M12 5V3M12 19L9 16M12 19L15 16M12 19V21M19 12L16 9M19 12L16 15M19 12H21M5 12L8 9M5 12L8 15M5 12H3" stroke="#DFA000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                  <form action="{{ route('admin.perusahaan.destroy', $perusahaan) }}" method="POST" style="display:inline-block;margin:0;" class="form-delete-perusahaan" data-name="{{ $perusahaan->nama }}">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-ico danger btn-delete-perusahaan" title="Hapus">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 20H15M10 4H14M7 7H17L16 20H8L7 7Z" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center" style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="table-footer-left">
            <div class="show-wrap">
              <span>Show</span>
              <form id="perPageForm" method="GET" action="{{ route('admin.perusahaan.index') }}">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="regency_id" value="{{ request('regency_id') }}">
                <input type="hidden" name="district_id" value="{{ request('district_id') }}">
                <input type="hidden" name="village_id" value="{{ request('village_id') }}">
                <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                  @foreach([5,10,25,50,100] as $pp)
                    <option value="{{ $pp }}" {{ (string)request('per_page','10')===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                  @endforeach
                </select>
              </form>
              <span>per page</span>
            </div>
          </div>

          <div class="table-footer-right">
            <div class="summary">
              {{ $perusahaans->firstItem() ?: 0 }}-{{ $perusahaans->lastItem() ?: 0 }} of {{ $perusahaans->total() }}
            </div>

            {{ $perusahaans->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ========== SweetAlert Notifications ==========
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
        customClass: {
          popup: 'swal-custom-toast'
        }
      });
    @endif

    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#22C55E',
        confirmButtonText: 'OK'
      });
    @endif

    @if($errors->any())
      Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan!',
        html: '<ul style="text-align:left; padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        confirmButtonColor: '#22C55E',
        confirmButtonText: 'OK'
      });
    @endif

    // ========== Delete Confirmation ==========
    document.querySelectorAll('.btn-delete-perusahaan').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.form-delete-perusahaan');
        const name = form.dataset.name;

        Swal.fire({
          title: 'Konfirmasi Hapus',
          html: `Apakah Anda yakin ingin menghapus data perusahaan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#94a3b8',
          confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            confirmButton: 'btn-swal-confirm',
            cancelButton: 'btn-swal-cancel'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // ========== Form & Filter ==========
    const filterForm = document.getElementById('filterForm');
    const perPageForm = document.getElementById('perPageForm');

    // Auto submit on change
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (perPageForm && perPageForm.contains(el)) {
          perPageForm.submit();
        } else if (filterForm) {
          filterForm.submit();
        }
      });
    });

    // Auto submit on search (with debounce)
    const searchInput = filterForm?.querySelector('input[name="q"]');
    if (searchInput) {
      let timeout;
      searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          filterForm.submit();
        }, 500); // Submit after 500ms of no typing
      });
    }

    // Cascading filter: load kecamatan after selecting kabupaten
    const selReg = document.getElementById('filterRegency');
    const selDis = document.getElementById('filterDistrict');

    if (selReg && selDis) {
      selReg.addEventListener('change', async () => {
        const rid = selReg.value;
        selDis.innerHTML = '<option value="">Semua Kecamatan</option>';

        if (!rid) return;

        try {
          const res = await fetch('{{ route('admin.perusahaan.options.districts') }}?regency_id=' + encodeURIComponent(rid));
          const rows = await res.json();
          rows.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.name;
            selDis.appendChild(opt);
          });
        } catch (error) {
          console.error('Error loading districts:', error);
        }
      });
    }
  });
</script>

<style>
  /* Custom SweetAlert Styling */
  .swal-custom-toast {
    font-family: 'Inter', sans-serif !important;
  }
  .swal2-popup {
    font-family: 'Inter', sans-serif !important;
    border-radius: 16px !important;
  }
  .swal2-title {
    font-weight: 700 !important;
    font-size: 20px !important;
  }
  .swal2-html-container {
    font-size: 14px !important;
  }
  .btn-swal-confirm, .btn-swal-cancel {
    padding: 10px 20px !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
  }
  .swal2-icon {
    margin: 1.5rem auto 1rem !important;
  }
</style>
@endpush
