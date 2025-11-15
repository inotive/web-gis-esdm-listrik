@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Infrastruktur Jaringan')

@push('styles')
<style>
  /* ========= MODAL STYLING (konsisten dengan data-wilayah) ========= */
  .modal-overlay{
    position:fixed; inset:0; background:rgba(15,23,42,.45); display:none; z-index:1000; 
    padding:18px; overflow:auto;
  }
  .modal-overlay.show{ display:block; }
  
  .modal{
    max-width:620px; margin:20px auto; background:#fff; border:1px solid var(--line); 
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
  .modal-footer{ padding:14px 20px 18px; }
  
  .btn-save{
    width:100%; height:44px; border:none; border-radius:10px; 
    font-weight:700; color:#fff; background:var(--accent-2); 
    box-shadow:0 10px 22px rgba(34,197,94,.22); cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:filter .18s ease;
  }
  .btn-save:hover{ filter:brightness(.95); }
  
  /* Form Grid - 2 kolom untuk modal infrastruktur */
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
  
  .select, .input{
    width:100%; height:44px; padding:0 12px; border:1px solid #E2E8F0; 
    border-radius:10px; background:#FCFCFD; outline:none; font:inherit; 
    color:#111827; transition:border-color .18s ease, box-shadow .18s ease;
  }
  .select::placeholder, .input::placeholder{ color:#94A3B8; }
  .select:focus, .input:focus{
    border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12);
  }
  
  .text-danger{ color:#F8285A; font-size:11px; margin-top:4px; display:block; }
  
  /* Header / Filter */
  .card-header{ background:white; border-bottom:1px solid #F1F1F4; padding:8px 20px; }
  .toolbar{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
  .w-search{ width:250px; }
  .w-filter{ width:139px; }
  .input-group{ display:flex; align-items:center; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; overflow:hidden; height:32px; }
  .input-group-text{ display:flex; align-items:center; justify-content:center; width:32px; height:100%; color:#99A1B7; background:transparent; border:none; padding:0; }
  .form-control{ height:100%; border:none; background:transparent; padding:0 10px; font-size:11px; color:#78829D; outline:none; width:100%; }
  .input-group.has-select{ position:relative; }
  .form-select{
    height:100%; border:none; background:transparent; padding:0 28px 0 10px; font-size:11px; color:#7c7c7c; outline:none; width:100%;
    appearance:none; -webkit-appearance:none; -moz-appearance:none;
  }
  .input-group.has-select::after{
    content:''; position:absolute; right:10px; top:50%; transform:translateY(-50%); width:14px; height:14px;
    background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:center; background-size:contain; pointer-events:none;
  }
  .btn-ghost{ height:32px; padding:0 10px; border:1px solid #F1F1F4; background:#fff; border-radius:6px; cursor:pointer; display:inline-flex; align-items:center; gap:4px; font-size:13px; color:#4B5675; transition:all .2s; }
  .btn-ghost:hover{ background:#F8FAFC; }

  /* Table */
  .table-shell{ background:white; overflow:hidden; }
  .table-wilayah{ width:100%; border-collapse:collapse; }
  .table-wilayah thead{ background:#FCFCFC; }
  .table-wilayah thead th{ background:#FCFCFC; color:#4B5675; font-weight:400; font-size:13px; padding:12px 20px; text-align:left; border-bottom:1px solid #F1F1F4; white-space:nowrap; }
  .table-wilayah tbody td{ padding:23px 20px; border-bottom:1px solid #F1F1F4; color:#252F4A; font-size:14px; vertical-align:middle; }
  .table-wilayah tbody tr:last-child td{ border-bottom:none; }
  .table-wilayah tbody tr:hover{ background:#FCFCFC; }

  .col-no{ width:48px; text-align:center; color:#071437; }
  .col-aksi{ width:120px; text-align:center; vertical-align:middle; }

  .btn-ico{ width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; border:none; background:transparent; cursor:pointer; transition:transform .2s; padding:0; margin:0 6px; vertical-align:middle; }
  .btn-ico:hover{ transform:scale(1.1); }
  .btn-ico svg{ width:24px; height:24px; display:block; }
  .btn-ico.edit svg path{ stroke:#DFA000; }
  .btn-ico.edit svg circle{ fill:#DFA000; }
  .btn-ico.danger svg path{ stroke:#F8285A; }

  /* Footer */
  .table-footer{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; padding:14px 20px; border-top:1px solid #F1F1F4; background:#fff; }
  .summary{ color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap{ display:inline-flex; align-items:center; gap:10px; color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap form{ display:inline-flex; margin:0; padding:0; }
  .show-wrap .form-select{ width:70px; height:30px; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; padding:4px 8px; font-size:11px; color:#252F4A; cursor:pointer; text-align:center; appearance:none; background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 8px center; background-size:14px; padding-right:30px; }
  .show-wrap .form-select:focus{ outline:none; border-color:#17C653; }

  @media (max-width:768px){
    .toolbar{ flex-direction:column; align-items:stretch; gap:12px; }
    .w-search,.w-filter{ width:100%; }
    .table-wilayah{ font-size:13px; }
    .table-wilayah thead th, .table-wilayah tbody td{ padding:12px 10px; }
    .col-no{ width:40px; }
    .table-footer{ flex-direction:column; align-items:flex-start; }
    .form-grid{ grid-template-columns:1fr; }
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Infrastruktur Jaringan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>{{ now()->translatedFormat('F Y') }}</span></div>

      {{-- include modal create & edit --}}
      @include('admin.infrastruktur.create')
      @include('admin.infrastruktur.edit')

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Infrastruktur
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.infrastruktur.index') }}">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari Jenis/Panjang..." autocomplete="off">
          @if(!empty($q))
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan"><i class="ri-close-line"></i><span class="d-none d-sm-inline"> Clear</span></button>
          @endif
        </div>

        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="jaringan" aria-label="Filter Jaringan">
            <option value="">Semua Jaringan</option>
            @foreach($jaringanOptions as $val => $label)
              <option value="{{ $val }}" {{ ($jaringan ?? '')===$val ? 'selected':'' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <button type="button" class="btn-ghost" id="btnReset" title="Reset">
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
              <th>Jaringan</th>
              <th>Jenis</th>
              <th>Panjang (km)</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($items as $i => $it)
            <tr>
              <td class="col-no">{{ ($items->currentPage()-1)*$items->perPage() + $i + 1 }}</td>
              <td>{{ ucfirst($it->jaringan) }}</td>
              <td><strong>{{ $it->jenis }}</strong></td>
              <td>{{ number_format($it->panjang_jaringan, 2) }}</td>
              <td class="col-aksi">
                <button
                  type="button"
                  class="btn-ico edit btn-edit"
                  title="Ubah"
                  data-action="{{ route('admin.infrastruktur.update', $it) }}"
                  data-jaringan="{{ $it->jaringan }}"
                  data-jenis="{{ $it->jenis }}"
                  data-panjang="{{ $it->panjang_jaringan }}"
                >
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="2" fill="#DFA000"/><path d="M12 5L9 8M12 5L15 8M12 5V3M12 19L9 16M12 19L15 16M12 19V21M19 12L16 9M19 12L16 15M19 12H21M5 12L8 9M5 12L8 15M5 12H3" stroke="#DFA000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <form action="{{ route('admin.infrastruktur.destroy', $it) }}" method="POST" style="display:inline-block;margin:0" onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-ico danger" title="Hapus">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 20H15M10 4H14M7 7H17L16 20H8L7 7Z" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td></tr>
          @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">
            Menampilkan <strong>{{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}</strong> dari <strong>{{ $items->total() }}</strong> data
          </div>

          <div class="show-wrap">
            <span>Show</span>
            <form id="perPageForm" method="GET" action="{{ route('admin.infrastruktur.index') }}">
              <input type="hidden" name="q" value="{{ $q ?? '' }}">
              <input type="hidden" name="jaringan" value="{{ $jaringan ?? '' }}">
              <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                @foreach([5,10,25,50,100] as $pp)
                  <option value="{{ $pp }}" {{ (string)($perPage ?? 10)===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                @endforeach
              </select>
            </form>
            <span>per page</span>
          </div>

          {{ $items->links() }}
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

    document.querySelectorAll('.auto-submit').forEach(el=>{
      el.addEventListener('change', ()=>{
        if (perPageForm && perPageForm.contains(el)) perPageForm.submit(); else if (filterForm) filterForm.submit();
      });
    });

    document.getElementById('btnClearSearch')?.addEventListener('click', ()=>{
      const i = filterForm.querySelector('input[name="q"]'); if (i) i.value=''; filterForm.submit();
    });

    document.getElementById('btnReset')?.addEventListener('click', ()=>{
      filterForm.reset();
      ['q','jaringan'].forEach(n=>{ const el = filterForm.querySelector(`[name="${n}"]`); if (el) el.value=''; });
      filterForm.submit();
    });

    document.querySelector('.btn-add')?.addEventListener('click', (e)=>{ e.preventDefault(); __openModal('modalCreateInfra'); });

    document.querySelectorAll('.btn-edit').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const form = document.getElementById('formEditInfra');
        form.action = btn.dataset.action;
        document.getElementById('jaringan_edit').value = btn.dataset.jaringan || '';
        document.getElementById('jenis_edit').value    = btn.dataset.jenis || '';
        document.getElementById('panjang_edit').value  = btn.dataset.panjang || '';
        __openModal('modalEditInfra');
      });
    });

    document.getElementById('modalCreateInfra')?.addEventListener('click', e=>{ if(e.target.id==='modalCreateInfra') __closeModal('modalCreateInfra'); });
    document.getElementById('modalEditInfra')?.addEventListener('click', e=>{ if(e.target.id==='modalEditInfra') __closeModal('modalEditInfra'); });
  });
</script>
@endpush