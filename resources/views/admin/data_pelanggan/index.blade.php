@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Pelanggan')

@push('styles')
<style>
  /* ===== Toolbar ===== */
  .toolbar { display:flex; flex-wrap:wrap; align-items:center; gap:8px 10px; }
  .toolbar .w-search { width: clamp(230px, 38vw, 340px); }
  .toolbar .w-filter { width: clamp(180px, 26vw, 230px); }

  /* Mini form kit */
  .input-group{
    display:flex; align-items:center; background:#FCFCFD; border:1px solid var(--line);
    border-radius:10px; overflow:hidden; height:36px;
  }
  .input-group:focus-within{ border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12); }
  .input-group-text{
    display:grid; place-items:center; width:36px; height:100%; color:#94A3B8; background:#F8FAFC; border-right:1px solid var(--line);
  }
  .form-control,.form-select{
    height:36px; border:none; background:transparent; padding:0 10px; font:inherit; color:var(--text); outline:none; width:100%;
  }
  .btn-ghost{ height:32px; padding:0 10px; border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ghost:hover{ background:#F8FAFC; }

  /* ===== Table shell ===== */
  .table-shell{ border:1px solid var(--line); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-1); }
  .table-pelanggan{ width:100%; border-collapse:separate; border-spacing:0; }
  .table-pelanggan thead th{
    background:#FCFCFD; color:#64748B; font-weight:700; padding:12px 18px; text-align:left; border-bottom:1px solid var(--line); white-space:nowrap;
  }
  .table-pelanggan tbody td{
    padding:16px 18px; border-bottom:1px solid var(--line); color:#252F4A; vertical-align:middle;
  }
  .table-pelanggan tbody tr:hover{ background:#FAFAFA; }

  /* Kolom */
  .col-no{ width:70px; text-align:center; }
  .col-tipe{ min-width:220px; }
  .col-jumlah{ width:160px; }
  .col-daya{ width:200px; }
  .col-aksi{ width:130px; text-align:center; }

  .btn-ico{ --size:32px; width:var(--size); height:var(--size); display:inline-grid; place-items:center;
    border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ico:hover{ background:#F8FAFC; }
  .btn-ico.danger{ border-color:#FEE2E2; color:#DC2626; }
  .btn-ico.danger:hover{ background:#FFF5F5; }

  /* ===== Footer tabel ===== */
  .table-footer{
    display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px;
    padding:14px 18px; border-top:1px solid var(--line); background:#fff; border-bottom-left-radius:16px; border-bottom-right-radius:16px;
  }
  .summary{ color:var(--text-dim); }

  .show-wrap{ display:inline-flex; align-items:center; gap:8px; color:var(--text-dim); }
  .show-wrap .form-select{ width:92px; }

  /* Pagination pills */
  .pagination{ display:flex; gap:6px; list-style:none; padding:0; margin:0; }
  .page-link{
    min-width:34px; height:34px; padding:0 10px; display:flex; align-items:center; justify-content:center;
    border:1px solid var(--line); background:#fff; border-radius:8px; text-decoration:none; color:var(--text);
  }
  .page-link:hover{ background:#F8FAFC; }
  .page-item.active .page-link{ background:var(--active-soft); color:#0F5132; border-color:#B7F7CF; font-weight:700; }
  .page-item.disabled .page-link{ opacity:.5; pointer-events:none; }

  .page-title{ letter-spacing:-.3px; }

  @media (max-width:720px){ .summary{ width:100%; order:-1; } }
</style>
@endpush

@section('content')
  <!-- Header halaman -->
  <div class="page-head">
    <div>
      <div class="page-meta">Selasa, 22 September 2025</div>
      <div class="page-title">Data Pelanggan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>September 2025</span>
      </div>

      {{-- Modal Create --}}
      @include('admin.data_pelanggan.create')

      <button class="btn btn-primary btn-add" type="button">
        <i class="ri-add-line"></i>
        Tambah Data Pelanggan
      </button>
    </div>
  </div>

  <!-- Kartu: Tabel Data Pelanggan -->
  <section class="card" style="margin-top:18px;">
    <div class="card-header">

      <!-- Toolbar -->
      <form id="filterForm" class="toolbar" method="GET" action="#">
        <!-- Search -->
        <div class="input-group w-search">
          <span class="input-group-text" id="search-addon"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari Tipe Pelanggan..." aria-label="Cari Tipe Pelanggan" aria-describedby="search-addon">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
              <i class="ri-close-line"></i>
            </button>
          @endif
        </div>

        <!-- Filter field -->
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-filter-3-line"></i></span>
          <select class="form-select auto-submit" name="by" aria-label="Filter berdasarkan">
            <option value="" {{ request('by')==='' ? 'selected':'' }}>Filter Berdasarkan</option>
            <option value="tipe" {{ request('by')==='tipe' ? 'selected':'' }}>Tipe</option>
            <option value="daya" {{ request('by')==='daya' ? 'selected':'' }}>Rentang Daya</option>
          </select>
        </div>

        <!-- Filter value (dummy) -->
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-list-unordered"></i></span>
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
        <table class="table-pelanggan">
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
            @php $rows = [
              ['Rumah Tangga', 1245, '450 VA'],
              ['Rumah Tangga', 980, '900 VA'],
              ['Bisnis Kecil', 210, '2.200 VA'],
              ['Industri', 32, '1.000 kVA'],
              ['Pemerintah', 58, '82 kVA'],
              ['Sosial', 143, '6.600 VA'],
              ['Bisnis Menengah', 76, '197 kVA'],
              ['Industri Besar', 12, '5 MVA'],
              ['Pertanian', 64, '23 kVA'],
              ['Lainnya', 25, '3.500 VA'],
            ]; @endphp
            @foreach ($rows as $i => $r)
              <tr>
                <td class="col-no">{{ $i+1 }}</td>
                <td class="col-tipe"><strong>{{ $r[0] }}</strong></td>
                <td class="col-jumlah">{{ number_format($r[1],0,',','.') }}</td>
                <td class="col-daya">{{ $r[2] }}</td>
                <td class="col-aksi">
                  <a href="#" class="btn-ico" title="Pengaturan"><i class="ri-settings-3-line"></i></a>
                  <button type="button" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Footer table -->
        <div class="table-footer">
          <div class="summary">Menampilkan <strong>1–10</strong> dari <strong>52</strong> data</div>

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
            <ul class="pagination">
              <li class="page-item disabled"><span class="page-link" aria-label="Sebelumnya"><i class="ri-arrow-left-s-line"></i></span></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item active" aria-current="page"><span class="page-link">2</span></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item d-none d-sm-block"><a class="page-link" href="#">4</a></li>
              <li class="page-item d-none d-sm-block"><a class="page-link" href="#">5</a></li>
              <li class="page-item"><a class="page-link" href="#" aria-label="Berikutnya"><i class="ri-arrow-right-s-line"></i></a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const perPageForm = document.getElementById('perPageForm');

    // Auto submit untuk semua select bertanda .auto-submit
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (perPageForm && perPageForm.contains(el)) perPageForm.submit();
        else if (filterForm) filterForm.submit();
      });
    });

    // clear search
    const btnClear = document.getElementById('btnClearSearch');
    if (btnClear) {
      btnClear.addEventListener('click', () => {
        const input = filterForm.querySelector('input[name="q"]');
        if (input) input.value = '';
        filterForm.submit();
      });
    }

    // reset filter
    const btnReset = document.getElementById('btnReset');
    if (btnReset) {
      btnReset.addEventListener('click', () => {
        filterForm.reset();
        const inputQ = filterForm.querySelector('input[name="q"]');
        if (inputQ) inputQ.value = '';
        filterForm.submit();
      });
    }

    // Modal open/close
    const openBtn = document.querySelector('.btn-add');
    const modal = document.getElementById('modalPelanggan');
    const closeBtns = modal?.querySelectorAll('[data-close]');

    function openModal(){ modal?.classList.add('show'); }
    function closeModal(){ modal?.classList.remove('show'); }

    openBtn?.addEventListener('click', openModal);
    closeBtns?.forEach(b => b.addEventListener('click', closeModal));
    modal?.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });
    window.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeModal(); });
  });
</script>
@endpush
