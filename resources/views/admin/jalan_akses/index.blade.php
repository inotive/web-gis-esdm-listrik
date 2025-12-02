@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Jalan & Aksesbilitas')

@push('styles')
<style>
  /* ========= MODAL STYLING (konsisten dengan data-wilayah) ========= */
  .modal-overlay{
    position:fixed; inset:0; background:rgba(15,23,42,.45); display:none; z-index:1000; 
    padding:18px; overflow:auto;
  }
  .modal-overlay.show{ display:block; }
  
  .modal{
    max-width:720px; margin:20px auto; background:#fff; border:1px solid var(--line); 
    border-radius:16px; box-shadow:var(--shadow-2); overflow:hidden;
  }
  
  .modal-header{
    display:flex; justify-content:space-between; align-items:center; 
    padding:18px 20px; border-bottom:1px solid var(--line);
  }
  .modal-header h3{
    margin:0; font-weight:800; font-size:20px; letter-spacing:-.2px; color:#111827;
  }
  
  .btn-x{
    width:36px; height:36px; display:grid; place-items:center; 
    border:1px solid #E2E8F0; background:#fff; border-radius:10px; cursor:pointer;
    transition:background .18s ease;
  }
  .btn-x:hover{ background:#F8FAFC; }
  .btn-x i{ font-size:18px; color:#64748B; }
  
  .modal-body{ padding:18px 20px 6px; }
  .modal-footer{ 
    padding:14px 20px 18px; 
    display:flex; align-items:center; gap:10px;
  }
  
  .btn-save{
    flex:1; height:44px; border:none; border-radius:10px; 
    font-weight:700; color:#fff; background:var(--accent-2); 
    box-shadow:0 10px 22px rgba(34,197,94,.22); cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:filter .18s ease;
  }
  .btn-save:hover{ filter:brightness(.95); }
  
  .btn-cancel{
    flex:1; height:44px; border:1px solid #E2E8F0; border-radius:10px;
    font-weight:600; color:#64748B; background:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:background .18s ease;
  }
  .btn-cancel:hover{ background:#F8FAFC; }
  
  /* Form Grid - untuk layout 2 kolom */
  .form-grid{
    display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;
  }
  .form-grid .f{ display:flex; flex-direction:column; }
  .form-grid .f.full{ grid-column:1 / -1; }
  
  /* Form elements */
  .form-group{ margin-bottom:16px; }
  
  .form-grid label, .form-group label{
    display:block; font-size:14px; color:#374151; margin:0 0 8px; font-weight:600;
  }
  
  .select, .input, .textarea{
    width:100%; padding:0 12px; border:1px solid #E2E8F0; 
    border-radius:10px; background:#FCFCFD; outline:none; font:inherit; 
    color:#111827; transition:border-color .18s ease, box-shadow .18s ease;
  }
  .input, .select{ height:44px; }
  .textarea{ 
    min-height:88px; padding:12px; resize:vertical;
    font-family:inherit;
  }
  .select::placeholder, .input::placeholder, .textarea::placeholder{ color:#94A3B8; }
  .select:focus, .input:focus, .textarea:focus{
    border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12);
  }
  
  .text-danger{ color:#F8285A; font-size:11px; margin-top:4px; display:block; }

  /* ========= TABLE & FILTER STYLING ========= */
  .card-header{ background:white; border-bottom:1px solid #F1F1F4; padding:8px 20px; }
  .toolbar{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
  .w-search{ width:250px; }
  .w-filter{ width:139px; }
  .input-group{ display:flex; align-items:center; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; overflow:hidden; height:32px; }
  .input-group-text{ display:flex; align-items:center; justify-content:center; width:32px; height:100%; color:#99A1B7; background:transparent; border:none; padding:0; }
  .form-control{ height:100%; border:none; background:transparent; padding:0 10px; font-size:11px; color:#78829D; outline:none; width:100%; }
  .form-select{
    height:100%; border:none; background:transparent; padding:0 28px 0 10px; font-size:11px; color:#7c7c7c; outline:none; width:100%;
    appearance:none; -webkit-appearance:none; -moz-appearance:none;
  }
  .input-group.has-select{ position:relative; }
  .input-group.has-select::after{
    content:''; position:absolute; right:10px; top:50%; transform:translateY(-50%); width:14px; height:14px;
    background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:center; background-size:contain; pointer-events:none;
  }
  .btn-ghost{ height:32px; padding:0 10px; border:1px solid #F1F1F4; background:#fff; border-radius:6px; cursor:pointer; display:inline-flex; align-items:center; gap:4px; font-size:13px; color:#4B5675; transition:.2s; }
  .btn-ghost:hover{ background:#F8FAFC; }

  .table-shell{ background:white; overflow:hidden; }
  .table-wilayah{ width:100%; border-collapse:collapse; }
  .table-wilayah thead{ background:#FCFCFC; }
  .table-wilayah thead th{ background:#FCFCFC; color:#4B5675; font-weight:400; font-size:13px; padding:12px 20px; text-align:left; border-bottom:1px solid #F1F1F4; white-space:nowrap; }
  .table-wilayah tbody td{ padding:23px 20px; border-bottom:1px solid #F1F1F4; color:#252F4A; font-size:14px; vertical-align:middle; }
  .table-wilayah tbody tr:last-child td{ border-bottom:none; }
  .table-wilayah tbody tr:hover{ background:#FCFCFC; }

  .col-no{ width:48px; text-align:center; color:#071437; }
  .col-kon{ width:140px; }
  .col-pan{ width:140px; }
  .col-jen{ width:140px; }
  .col-aksi{ width:120px; text-align:center; vertical-align:middle; }

  .btn-ico{ width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; border:none; background:transparent; cursor:pointer; transition:transform .2s; padding:0; margin:0 6px; vertical-align:middle; }
  .btn-ico:hover{ transform:scale(1.1); }
  .btn-ico svg{ width:24px; height:24px; display:block; }
  .btn-ico.danger svg path{ stroke:#F8285A; }

  .table-footer{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; padding:14px 20px; border-top:1px solid #F1F1F4; background:#fff; }
  .summary{ color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap{ display:inline-flex; align-items:center; gap:10px; color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap form{ display:inline-flex; margin:0; padding:0; }
  .show-wrap .form-select{ width:70px; height:30px; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; padding:4px 8px; font-size:11px; color:#252F4A; text-align:center; appearance:none; background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 8px center; background-size:14px; padding-right:30px; }

  .pagination{ display:flex; align-items:center; gap:2px; }
  .pagination .page-item{ list-style:none; }
  .pagination .page-link{ width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:14px; color:#4B5675; text-decoration:none; transition:.2s; border:none; background:transparent; }
  .pagination .page-link:hover{ background:#F5F5F5; }
  .pagination .page-item.active .page-link{ background:#F1F1F4; color:#252F4A; font-weight:500; }
  .pagination .page-item.disabled .page-link{ opacity:.5; cursor:not-allowed; }

  @media (max-width:768px){
    .toolbar{ flex-direction:column; align-items:stretch; gap:12px; }
    .w-search,.w-filter{ width:100%; }
    .table-wilayah{ font-size:13px; }
    .table-wilayah thead th, .table-wilayah tbody td{ padding:12px 10px; }
    .col-no{ width:40px; }
    .table-footer{ flex-direction:column; align-items:flex-start; }
    .form-grid{ grid-template-columns:1fr; }
    .modal-footer{ flex-direction:column; }
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Data Jalan & Aksesbilitas</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>{{ now()->translatedFormat('F Y') }}</span></div>

      {{-- Modal Create Jalan & Akses --}}
      @include('admin.jalan_akses.create')

      <button class="btn btn-primary btn-add"><i class="ri-add-line"></i> Tambah Jalan/Akses</button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="#">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari Nama Jalan/Akses..." autocomplete="off">
          @if(request('q')) <button type="button" class="btn-ghost" id="btnClearSearch"><i class="ri-close-line"></i><span class="d-none d-sm-inline"> Clear</span></button> @endif
        </div>

        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="kondisi">
            <option value="">Semua Kondisi</option>
            @foreach(['Baik','Sedang','Rusak'] as $k)
              <option {{ request('kondisi')===$k ? 'selected':'' }}>{{ $k }}</option>
            @endforeach
          </select>
        </div>

        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="jenis">
            <option value="">Semua Jenis Akses</option>
            @foreach(['Darat','Air','Udara'] as $j)
              <option {{ request('jenis')===$j ? 'selected':'' }}>{{ $j }}</option>
            @endforeach
          </select>
        </div>

        <button type="button" class="btn-ghost" id="btnReset"><i class="ri-refresh-line"></i><span class="d-none d-sm-inline"> Reset</span></button>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-wilayah">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Jalan/Akses</th>
              <th class="col-kon">Kondisi</th>
              <th class="col-pan">Panjang (km)</th>
              <th class="col-jen">Jenis</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $rows = [
              ['Jalan Poros Utama','Baik','12.5','Darat'],
              ['Sungai Hulu','Sedang','23.0','Air'],
              ['Landasan Perintis','Rusak','1.2','Udara'],
              ['Jalan Desa Timur','Sedang','5.8','Darat'],
              ['Rawa Selatan','Baik','7.0','Air'],
            ]; @endphp
            @foreach ($rows as $i => $r)
              <tr>
                <td class="col-no">{{ $i+1 }}</td>
                <td><strong>{{ $r[0] }}</strong></td>
                <td class="col-kon">{{ $r[1] }}</td>
                <td class="col-pan">{{ $r[2] }}</td>
                <td class="col-jen">{{ $r[3] }}</td>
                <td class="col-aksi">
                  <a href="#" class="btn-ico" title="Pengaturan">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="#4B5675" stroke-width="1.5"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.33 1.82l.02.05a2 2 0 1 1-3.38 0l.02-.05a1.65 1.65 0 0 0-.33-1.82 1.65 1.65 0 0 0-1-.6 1.65 1.65 0 0 0-1.82.33l-.06.06A2 2 0 1 1 3.3 17l.06-.06A1.65 1.65 0 0 0 4 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1.82-.33l-.05.02a2 2 0 1 1 0-3.38l.05.02A1.65 1.65 0 0 0 4 9a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1.82-.33l-.06.02A2 2 0 1 1 3.3 2.6l.06.06A1.65 1.65 0 0 0 5 4.6c.27 0 .53-.05.77-.16.29-.12.55-.3.73-.56l.02-.03a2 2 0 1 1 3.38 0l.02.03c.18.26.44.44.73.56.24.11.5.16.77.16.5 0 .98-.2 1.34-.56l.06-.06A2 2 0 1 1 20.7 4.6l-.06.06c-.36.36-.56.84-.56 1.34 0 .27.05.53.16.77.12.29.3.55.56.73l.03.02a2 2 0 1 1 0 3.38l-.03.02c-.26.18-.44.44-.56.73-.11.24-.16.5-.16.77Z" stroke="#4B5675" stroke-width="1.5" stroke-linecap="round"/></svg>
                  </a>
                  <button type="button" class="btn-ico danger" title="Hapus">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 20H15M10 4H14M7 7H17L16 20H8L7 7Z" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan <strong>1–5</strong> dari <strong>19</strong> data</div>
          <div class="show-wrap">
            <span>Show</span>
            <form id="perPageForm" method="GET" action="#">
              <input type="hidden" name="q" value="{{ request('q') }}">
              <input type="hidden" name="kondisi" value="{{ request('kondisi') }}">
              <input type="hidden" name="jenis" value="{{ request('jenis') }}">
              <select class="form-select auto-submit" name="per_page">
                @foreach([5,10,25,50,100] as $pp)
                  <option value="{{ $pp }}" {{ (string)request('per_page','10')===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                @endforeach
              </select>
            </form>
            <span>per page</span>
          </div>
          <nav aria-label="Pagination">
            <ul class="pagination">
              <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-left-s-line"></i></span></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item active"><span class="page-link">2</span></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item d-none d-sm-block"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#"><i class="ri-arrow-right-s-line"></i></a></li>
            </ul>
          </nav>
        </div>

      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  window.__openModal  = id => { const o=document.getElementById(id); if(o){o.classList.add('show'); document.body.style.overflow='hidden';}};
  window.__closeModal = id => { const o=document.getElementById(id); if(o){o.classList.remove('show'); document.body.style.overflow='';}};

  document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const perPageForm = document.getElementById('perPageForm');
    
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (perPageForm && perPageForm.contains(el)) perPageForm.submit(); else if (filterForm) filterForm.submit();
      });
    });
    
    document.getElementById('btnClearSearch')?.addEventListener('click', ()=>{ 
      const i=filterForm.querySelector('input[name="q"]'); if(i) i.value=''; filterForm.submit(); 
    });
    
    document.getElementById('btnReset')?.addEventListener('click', ()=>{ 
      filterForm.reset(); const i=filterForm.querySelector('input[name="q"]'); if(i) i.value=''; filterForm.submit(); 
    });

    // Modal overlay click to close
    document.getElementById('modalCreateJalan')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreateJalan') __closeModal('modalCreateJalan');
    });
  });
</script>
@endpush