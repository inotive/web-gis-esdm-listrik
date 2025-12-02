@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Pelanggan')

@push('styles')
<style>
  /* Table Header/Filter Section - Sesuai Figma */
  .card-header { background:white; border-bottom:1px solid #F1F1F4; padding:8px 20px; }
  .toolbar { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }

  /* Search Box */
  .w-search { width:250px; }
  .input-group { display:flex; align-items:center; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; overflow:hidden; height:32px; }
  .input-group-text { display:flex; align-items:center; justify-content:center; width:32px; height:100%; color:#99A1B7; background:transparent; border:none; padding:0; }
  .input-group-text i { font-size:16px; }
  .form-control { height:100%; border:none; background:transparent; padding:0 10px; font-size:11px; color:#78829D; outline:none; width:100%; }
  .form-control::placeholder { color:#78829D; }

  /* Filter Dropdown */
  .w-filter { width:139px; }
  .input-group.has-select { position:relative; }
  .input-group .form-select{
    height:100%; border:none; background:transparent; padding:0 28px 0 10px; font-size:11px; color:#7c7c7c; outline:none; width:100%;
    cursor:pointer; appearance:none; -webkit-appearance:none; -moz-appearance:none;
  }
  .input-group .form-select option:first-child{ color:#7c7c7c; }
  .input-group .form-select option:not(:first-child){ color:#252F4A; }
  .input-group.has-select::after{
    content:''; position:absolute; right:10px; top:50%; transform:translateY(-50%); width:14px; height:14px;
    background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:center; background-size:contain; pointer-events:none;
  }

  /* Clear & Reset Buttons */
  .btn-ghost{
    height:32px; padding:0 10px; border:1px solid #F1F1F4; background:#fff; border-radius:6px; cursor:pointer;
    display:inline-flex; align-items:center; gap:4px; font-size:13px; color:#4B5675; transition:all .2s;
  }
  .btn-ghost:hover{ background:#F8FAFC; }
  .btn-ghost i{ font-size:14px; }

  /* Table Styles */
  .table-shell{ background:white; overflow:hidden; }
  .table-wilayah{ width:100%; border-collapse:collapse; }
  .table-wilayah thead{ background:#FCFCFC; }
  .table-wilayah thead th{
    background:#FCFCFC; color:#4B5675; font-weight:400; font-size:13px; padding:12px 20px; text-align:left; border-bottom:1px solid #F1F1F4; white-space:nowrap;
  }
  .table-wilayah tbody td{ padding:23px 20px; border-bottom:1px solid #F1F1F4; color:#252F4A; font-size:14px; vertical-align:middle; }
  .table-wilayah tbody tr:last-child td{ border-bottom:none; }
  .table-wilayah tbody tr:hover{ background:#FCFCFC; }

  /* Kolom */
  .col-no{ width:48px; text-align:center; color:#071437; }
  .col-tipe{ min-width:220px; }
  .col-jumlah{ width:160px; }
  .col-daya{ width:200px; }
  .col-aksi{ width:120px; text-align:center; vertical-align:middle; }

  /* Action Buttons */
  .btn-ico{ width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; border:none; background:transparent; cursor:pointer; transition:transform .2s; padding:0; margin:0 6px; vertical-align:middle; }
  .btn-ico:hover{ transform:scale(1.1); }
  .btn-ico svg{ width:24px; height:24px; display:block; }
  .btn-ico.danger svg path{ stroke:#F8285A; }

  /* Footer */
  .table-footer{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; padding:14px 20px; border-top:1px solid #F1F1F4; background:#fff; }
  .summary{ color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap{ display:inline-flex; align-items:center; gap:10px; color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap form{ display:inline-flex; margin:0; padding:0; }
  .show-wrap .form-select{
    width:70px; height:30px; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; padding:4px 8px; font-size:11px; color:#252F4A; cursor:pointer; text-align:center;
    appearance:none; -webkit-appearance:none; -moz-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 8px center; background-size:14px; padding-right:30px;
  }
  .show-wrap .form-select:focus{ outline:none; border-color:#17C653; }

  /* Pagination */
  .pagination{ display:flex; align-items:center; gap:2px; }
  .pagination .page-item{ list-style:none; }
  .pagination .page-link{
    width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:14px; color:#4B5675; text-decoration:none; transition:all .2s;
    border:none; background:transparent;
  }
  .pagination .page-link:hover{ background:#F5F5F5; }
  .pagination .page-item.active .page-link{ background:#F1F1F4; color:#252F4A; font-weight:500; }
  .pagination .page-item.disabled .page-link{ opacity:.5; cursor:not-allowed; }

  /* Responsive */
  @media (max-width:768px){
    .toolbar{ flex-direction:column; align-items:stretch; gap:12px; }
    .w-search,.w-filter{ width:100%; }
    .table-wilayah{ font-size:13px; }
    .table-wilayah thead th, .table-wilayah tbody td{ padding:12px 10px; }
    .col-no{ width:40px; }
    .table-footer{ flex-direction:column; align-items:flex-start; }
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">Selasa, 22 September 2025</div>
      <div class="page-title">Data Pelanggan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>September 2025</span></div>

      {{-- Modal Create --}}
      @include('admin.data_pelanggan.create')

      {{-- Modal Edit --}}
      @include('admin.data_pelanggan.edit_modal')

      <button class="btn btn-primary btn-add" type="button">
        <i class="ri-add-line"></i>
        Tambah Data Pelanggan
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:18px; display:none;" id="successAlert">
      <strong>Berhasil!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:18px; display:none;" id="errorAlert">
      <strong>Error!</strong> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:18px; display:none;" id="validationAlert">
      <strong>Terjadi kesalahan:</strong>
      <ul style="margin:0; padding-left:20px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="#">
        <div class="input-group w-search">
          <span class="input-group-text" id="search-addon"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari Tipe Pelanggan..." aria-label="Cari Tipe Pelanggan" aria-describedby="search-addon" autocomplete="off">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
              <i class="ri-close-line"></i><span class="d-none d-sm-inline"> Clear</span>
            </button>
          @endif
        </div>

        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="by" aria-label="Filter berdasarkan">
            <option value="" {{ request('by')==='' ? 'selected':'' }}>Filter Berdasarkan</option>
            <option value="tipe" {{ request('by')==='tipe' ? 'selected':'' }}>Tipe</option>
            <option value="daya" {{ request('by')==='daya' ? 'selected':'' }}>Rentang Daya</option>
          </select>
        </div>

        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="val" aria-label="Nilai filter">
            <option value="" {{ request('val')==='' ? 'selected':'' }}>Semua</option>
            <option value="rt" {{ request('val')==='rt' ? 'selected':'' }}>Rumah Tangga</option>
            <option value="bisnis" {{ request('val')==='bisnis' ? 'selected':'' }}>Bisnis</option>
            <option value="industri" {{ request('val')==='industri' ? 'selected':'' }}>Industri</option>
            <option value="pemerintah" {{ request('val')==='pemerintah' ? 'selected':'' }}>Pemerintah</option>
          </select>
        </div>

        <button type="button" class="btn-ghost" id="btnReset" title="Reset filter">
          <i class="ri-refresh-line"></i><span class="d-none d-sm-inline"> Reset</span>
        </button>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-wilayah">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th class="col-tipe">Tipe Pelanggan</th>
              <th class="col-jumlah">Jumlah</th>
              <th class="col-daya">Daya Tersambung</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pelanggans as $i => $pelanggan)
              <tr>
                <td class="col-no">{{ $pelanggans->firstItem() + $i }}</td>
                <td class="col-tipe"><strong>{{ $pelanggan->tipe_pelanggan }}</strong></td>
                <td class="col-jumlah">{{ number_format($pelanggan->jumlah, 0, ',', '.') }}</td>
                <td class="col-daya">{{ $pelanggan->daya_tersambung }}</td>
                <td class="col-aksi">
                  <button type="button" class="btn-ico btn-edit" title="Edit" 
                    data-id="{{ $pelanggan->id }}"
                    data-tipe="{{ $pelanggan->tipe_pelanggan }}"
                    data-jumlah="{{ $pelanggan->jumlah }}"
                    data-daya="{{ $pelanggan->daya_tersambung }}"
                    data-ket="{{ $pelanggan->keterangan }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M11 2H9C4 2 2 4 2 9v6c0 5 2 7 7 7h6c5 0 7-2 7-7v-2" stroke="#17C653" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M16.04 3.02 8.16 10.9c-.3.3-.6.89-.66 1.32l-.43 3.01c-.16 1.09.61 1.85 1.7 1.7l3.01-.43c.42-.06 1.01-.36 1.32-.66l7.88-7.88c1.36-1.36 2-2.94 0-4.94-2-2-3.58-1.36-4.94 0Z" stroke="#17C653" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M14.91 4.15a7.144 7.144 0 0 0 4.94 4.94" stroke="#17C653" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                  <form action="{{ route('admin.pelanggan.destroy', $pelanggan) }}" method="POST" style="display:inline;" class="form-delete" data-tipe="{{ $pelanggan->tipe_pelanggan }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-ico danger btn-delete" title="Hapus">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 5.98c-3.33-.33-6.68-.5-10.02-.5-1.98 0-3.96.1-5.94.3L3 5.98M8.5 4.97l.22-1.31C8.88 2.71 9 2 10.69 2h2.62c1.69 0 1.82.75 1.97 1.67l.22 1.3M18.85 9.14l-.65 10.07C18.09 20.78 18 22 15.21 22H8.79C6 22 5.91 20.78 5.8 19.21L5.15 9.14M10.33 16.5h3.33M9.5 12.5h5" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align:center; padding:40px 20px; color:#94A3B8;">Belum ada data pelanggan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan <strong>{{ $pelanggans->firstItem() ?? 0 }}–{{ $pelanggans->lastItem() ?? 0 }}</strong> dari <strong>{{ $pelanggans->total() }}</strong> data</div>

          <div class="show-wrap">
            <span>Show</span>
            <form id="perPageForm" method="GET" action="#">
              <input type="hidden" name="q" value="{{ request('q') }}">
              <input type="hidden" name="by" value="{{ request('by') }}">
              <input type="hidden" name="val" value="{{ request('val') }}">
              <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                @foreach([5,10,25,50,100] as $pp)
                  <option value="{{ $pp }}" {{ (string)request('per_page','10')===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                @endforeach
              </select>
            </form>
            <span>per page</span>
          </div>

          <nav aria-label="Pagination">
            {{ $pelanggans->links('pagination::bootstrap-4') }}
          </nav>
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
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.form-delete');
        const tipe = form.dataset.tipe;

        Swal.fire({
          title: 'Konfirmasi Hapus',
          html: `Apakah Anda yakin ingin menghapus data pelanggan <strong>${tipe}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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

    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (perPageForm && perPageForm.contains(el)) perPageForm.submit();
        else if (filterForm) filterForm.submit();
      });
    });

    const btnClear = document.getElementById('btnClearSearch');
    btnClear?.addEventListener('click', () => {
      const input = filterForm.querySelector('input[name="q"]');
      if (input) input.value = '';
      filterForm.submit();
    });

    const btnReset = document.getElementById('btnReset');
    btnReset?.addEventListener('click', () => {
      filterForm.reset();
      const inputQ = filterForm.querySelector('input[name="q"]');
      if (inputQ) inputQ.value = '';
      filterForm.submit();
    });

    // ========== Modal Create ==========
    const openBtn = document.querySelector('.btn-add');
    const modal = document.getElementById('modalPelanggan');
    const closeBtns = modal?.querySelectorAll('[data-close]');
    function openModal(){ modal?.classList.add('show'); }
    function closeModal(){ modal?.classList.remove('show'); }
    openBtn?.addEventListener('click', openModal);
    closeBtns?.forEach(b => b.addEventListener('click', closeModal));
    modal?.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });

    // ========== Modal Edit ==========
    const modalEdit = document.getElementById('modalEditPelanggan');
    const closeBtnsEdit = modalEdit?.querySelectorAll('[data-close-edit]');
    function openModalEdit(){ modalEdit?.classList.add('show'); }
    function closeModalEdit(){ modalEdit?.classList.remove('show'); }
    closeBtnsEdit?.forEach(b => b.addEventListener('click', closeModalEdit));
    modalEdit?.addEventListener('click', (e)=>{ if(e.target === modalEdit) closeModalEdit(); });

    // Handle Edit Button Click
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const tipe = this.dataset.tipe;
        const jumlah = this.dataset.jumlah;
        const daya = this.dataset.daya;
        const ket = this.dataset.ket || '';

        // Parse daya (contoh: "450 VA" atau "1.5 kVA")
        const dayaParts = daya.trim().split(' ');
        const dayaVal = dayaParts[0];
        const dayaUnit = dayaParts[1] || 'kVA';

        // Update form action
        const form = document.getElementById('formEditPelanggan');
        form.action = `/admin/pelanggan/${id}`;

        // Fill form fields
        document.getElementById('edit_tipe').value = tipe;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_daya_val').value = dayaVal;
        document.getElementById('edit_daya_unit').value = dayaUnit;
        document.getElementById('edit_ket').value = ket;

        openModalEdit();
      });
    });

    // Close modals on Escape
    window.addEventListener('keydown', (e)=>{ 
      if(e.key==='Escape') {
        closeModal(); 
        closeModalEdit();
      }
    });
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
