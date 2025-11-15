{{-- resources/views/admin/data_gardu/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Gardu')

@push('styles')
<style>
  /* ====== Header / Filter (match figma) ====== */
  .card-header {
    background: #fff;
    border-bottom: 1px solid #F1F1F4;
    padding: 8px 20px;
  }
  .toolbar { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }

  /* Search */
  .w-search { width:250px; }
  .input-group {
    display:flex; align-items:center;
    background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px;
    overflow:hidden; height:32px;
  }
  .input-group-text {
    display:flex; align-items:center; justify-content:center;
    width:32px; height:100%; color:#99A1B7; background:transparent; border:none; padding:0;
  }
  .input-group-text i { font-size:16px; }
  .form-control {
    height:100%; border:none; background:transparent; padding:0 10px;
    font-size:11px; color:#78829D; outline:none; width:100%;
  }
  .form-control::placeholder { color:#78829D; }

  /* Select (Filter) */
  .w-filter { width:139px; }
  .input-group.has-select { position:relative; }
  .input-group .form-select{
    height:100%; border:none; background:transparent; padding:0 28px 0 10px;
    font-size:11px; color:#7c7c7c; outline:none; width:100%; cursor:pointer;
    appearance:none; -webkit-appearance:none; -moz-appearance:none;
  }
  .input-group .form-select option:first-child { color:#7c7c7c; }
  .input-group .form-select option:not(:first-child) { color:#252F4A; }
  .input-group.has-select::after{
    content:''; position:absolute; right:10px; top:50%; transform:translateY(-50%);
    width:14px; height:14px;
    background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:center; background-size:contain; pointer-events:none;
  }

  /* Buttons */
  .btn-ghost{
    height:32px; padding:0 10px; border:1px solid #F1F1F4; background:#fff; border-radius:6px;
    cursor:pointer; display:inline-flex; align-items:center; gap:4px; font-size:13px; color:#4B5675;
    transition:all .2s;
  }
  .btn-ghost:hover{ background:#F8FAFC; }
  .btn-ghost i{ font-size:14px; }

  /* ====== Table (match figma) ====== */
  .table-shell{ background:#fff;  overflow:hidden; }
  .table-wilayah{ width:100%; border-collapse:collapse; }
  .table-wilayah thead{ background:#FCFCFC; }
  .table-wilayah thead th{
    background:#FCFCFC; color:#4B5675; font-weight:400; font-size:13px;
    padding:12px 20px; text-align:left; border-bottom:1px solid #F1F1F4; white-space:nowrap; border-radius:0;
  }
  .table-wilayah tbody td{
    padding:23px 20px; border-bottom:1px solid #F1F1F4; color:#252F4A; font-size:14px; vertical-align:middle;
  }
  .table-wilayah tbody tr:last-child td{ border-bottom:none; }
  .table-wilayah tbody tr:hover{ background:#FCFCFC; }
  .col-no{ width:48px; text-align:center; color:#071437; }
  .col-aksi{ width:120px; text-align:center; vertical-align:middle; }
  .col-aksi > * { vertical-align:middle; }

  /* Action buttons */
  .btn-ico{
    width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;
    border:none; background:transparent; cursor:pointer; transition:transform .2s; padding:0; margin:0 6px; vertical-align:middle;
  }
  .btn-ico:hover{ transform:scale(1.1); }
  .btn-ico svg{ width:24px; height:24px; display:block; }
  .btn-ico.edit svg path{ stroke:#DFA000; }
  .btn-ico.edit svg circle{ fill:#DFA000; }
  .btn-ico.danger svg path{ stroke:#F8285A; }
  .col-aksi .btn-ico:first-child{ margin-left:0; }
  .col-aksi form{ display:inline-flex; vertical-align:middle; }

  /* ====== Footer ====== */
  .table-footer{
    display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between;
    gap:16px; padding:14px 20px; border-top:1px solid #F1F1F4; background:#fff;
  }
  .table-footer-left{ display:flex; align-items:center; gap:16px; }
  .table-footer-right{ display:flex; align-items:center; gap:16px; }
  .summary{ color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap{ display:inline-flex; align-items:center; gap:10px; color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap form{ display:inline-flex; margin:0; padding:0; }
  .show-wrap .form-select{
    width:70px; height:30px; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px;
    font-size:11px; color:#252F4A; cursor:pointer; text-align:center;
    appearance:none; -webkit-appearance:none; -moz-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 8px center; background-size:14px; padding:4px 30px 4px 8px;
  }
  .show-wrap .form-select:focus{ outline:none; border-color:#17C653; }

  /* Pagination */
  .pagination{ display:flex; align-items:center; gap:2px; }
  .pagination .page-item{ list-style:none; }
  .pagination .page-link{
    width:30px; height:30px; display:flex; align-items:center; justify-content:center;
    border-radius:6px; font-size:14px; color:#4B5675; text-decoration:none; transition:all .2s;
    border:none; background:transparent;
  }
  .pagination .page-link:hover{ background:#F5F5F5; }
  .pagination .page-item.active .page-link{ background:#F1F1F4; color:#252F4A; font-weight:500; }
  .pagination .page-item.disabled .page-link{ opacity:.5; cursor:not-allowed; }

  /* ====== Modal base ====== */
  .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
  .modal-overlay.show{display:block;}
  .modal{max-width:720px;margin:20px auto;background:#fff;border:1px solid #F1F1F4;border-radius:16px;box-shadow:0 12px 32px rgba(2,6,23,.12);overflow:hidden;}
  .modal-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid #F1F1F4;}
  .modal-header h3{margin:0;font-weight:800;font-size:20px;letter-spacing:-.2px;}
  .btn-x{width:36px;height:36px;display:grid;place-items:center;border:1px solid #E2E8F0;background:#fff;border-radius:10px;cursor:pointer;}
  .btn-x:hover{background:#F8FAFC;}
  .modal-body{padding:18px 20px 6px;}
  .modal-footer{padding:14px 20px 18px;}
  .btn-save{width:100%;height:44px;border:none;border-radius:10px;font-weight:700;color:#fff;background:var(--accent-2,#17C653);box-shadow:0 10px 22px rgba(34,197,94,.22);cursor:pointer;}
  .btn-save:hover{filter:brightness(.95);}
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .form-grid .full{grid-column:1/-1}
  .f{display:flex;flex-direction:column;gap:8px}
  .f label{font-size:13px;color:#475569}
  .input,.select{height:42px;border:1px solid #E5E7EB;border-radius:10px;background:#FCFCFD;padding:0 12px;font:inherit;color:#111827}
  .input:focus,.select:focus{outline:none;border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .muted{color:#64748B;font-size:12px}
  .select-search{width:100%;max-height:250px;overflow-y:auto;}

  /* Responsive */
  @media (max-width:768px){
    .toolbar{ flex-direction:column; align-items:stretch; gap:12px; }
    .w-search,.w-filter{ width:100%; }
    .table-wilayah{ font-size:13px; }
    .table-wilayah thead th, .table-wilayah tbody td{ padding:12px 10px; }
    .col-no{ width:40px; }
    .table-footer{ flex-direction:column; align-items:flex-start; }
    .table-footer-left, .table-footer-right{ width:100%; justify-content:space-between; }
    .table-footer-right{ flex-direction:column; align-items:flex-start; gap:12px; }
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Data Gardu</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>{{ now()->translatedFormat('F Y') }}</span></div>

      {{-- Modal Create & Edit --}}
      @include('admin.data_gardu.create')
      @include('admin.data_gardu.edit')

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Gardu
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.gardu.index') }}">
        {{-- Search Nama/Lokasi --}}
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari Nama/Lokasi..." autocomplete="off">
        </div>

        {{-- Filter Jenis --}}
        <div class="input-group w-filter has-select">
          <select class="form-select auto-submit" name="jenis" aria-label="Jenis Gardu">
            <option value="">Semua Jenis</option>
            @foreach($jenisOptions as $opt)
              <option value="{{ $opt }}" {{ $jenis===$opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>

        {{-- Tombol Clear & Reset --}}
        @if($q)
          <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
            <i class="ri-close-line"></i><span class="d-none d-sm-inline"> Clear</span>
          </button>
        @endif
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
              <th>Nama Gardu</th>
              <th>Jenis</th>
              <th>Lokasi</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($items as $i => $g)
            <tr>
              <td class="col-no">{{ ($items->currentPage()-1)*$items->perPage() + $i + 1 }}</td>
              <td><strong>{{ $g->nama }}</strong></td>
              <td>{{ $g->jenis_gardu_distribusi }}</td>
              <td>{{ $g->lokasi_lengkap }}</td>
              <td class="col-aksi">
                {{-- Edit --}}
                <button
                  class="btn-ico edit btn-edit"
                  title="Ubah"
                  data-action="{{ route('admin.gardu.update', $g) }}"
                  data-nama="{{ $g->nama }}"
                  data-jenis="{{ $g->jenis_gardu_distribusi }}"
                  data-wilayah_id="{{ $g->wilayah_id ?? '' }}"
                >
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="2" fill="#DFA000"/>
                    <path d="M12 5L9 8M12 5L15 8M12 5V3M12 19L9 16M12 19L15 16M12 19V21M19 12L16 9M19 12L16 15M19 12H21M5 12L8 9M5 12L8 15M5 12H3" stroke="#DFA000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>

                {{-- Delete --}}
                <form action="{{ route('admin.gardu.destroy', $g) }}" method="POST" style="display:inline-block;margin:0;" onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-ico danger" title="Hapus">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M9 20H15M10 4H14M7 7H17L16 20H8L7 7Z" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center" style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td></tr>
          @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="table-footer-left">
            <div class="show-wrap">
              <span>Show</span>
              <form id="perPageForm" method="GET" action="{{ route('admin.gardu.index') }}">
                <input type="hidden" name="q" value="{{ $q }}">
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                  @foreach([5,10,25,50,100] as $pp)
                    <option value="{{ $pp }}" {{ (string)$perPage===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                  @endforeach
                </select>
              </form>
              <span>per page</span>
            </div>
          </div>

          <div class="table-footer-right">
            <div class="summary">
              {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
            </div>

            {{ $items->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  // helpers modal
  window.__openModal  = id => { const o=document.getElementById(id); if(o){o.classList.add('show'); document.body.style.overflow='hidden';}};
  window.__closeModal = id => { const o=document.getElementById(id); if(o){o.classList.remove('show'); document.body.style.overflow='';}};

  document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const perPageForm = document.getElementById('perPageForm');

    // Auto submit select
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (perPageForm && perPageForm.contains(el)) perPageForm.submit(); 
        else if (filterForm) filterForm.submit();
      });
    });

    // Clear search
    document.getElementById('btnClearSearch')?.addEventListener('click', () => {
      const input = filterForm.querySelector('input[name="q"]'); 
      if (input) input.value = ''; 
      filterForm.submit();
    });

    // Reset
    document.getElementById('btnReset')?.addEventListener('click', () => {
      filterForm.reset();
      const inputQ = filterForm.querySelector('input[name="q"]');
      if (inputQ) inputQ.value = '';
      filterForm.submit();
    });

    // open create
    document.querySelector('.btn-add')?.addEventListener('click', (e)=>{ 
      e.preventDefault(); 
      __openModal('modalCreateGardu'); 
    });

    // open edit + prefill
    document.querySelectorAll('.btn-edit').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const form = document.getElementById('formEditGardu');
        form.action = btn.dataset.action;
        document.getElementById('nama_edit').value = btn.dataset.nama || '';
        document.getElementById('jenis_edit').value = btn.dataset.jenis || '';
        document.getElementById('wilayah_id_edit').value = btn.dataset.wilayah_id || '';

        __openModal('modalEditGardu');
      });
    });
  });
</script>
@endpush