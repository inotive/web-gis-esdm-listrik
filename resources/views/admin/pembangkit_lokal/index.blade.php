{{-- resources/views/admin/pembangkit_lokal/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Pembangkit Lokal')

@push('styles')
<style>
  .card-header{ background:white; border-bottom:1px solid #F1F1F4; padding:8px 20px; }
  .toolbar{ display:flex; align-items:center; gap:16px; }
  .w-search{ width:250px; }
  .input-group{ display:flex; align-items:center; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; overflow:hidden; height:32px; }
  .input-group-text{ display:flex; align-items:center; justify-content:center; width:32px; height:100%; color:#99A1B7; background:transparent; border:none; padding:0; }
  .form-control{ height:100%; border:none; background:transparent; padding:0 10px; font-size:11px; color:#78829D; outline:none; width:100%; }
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
  .col-aksi{ width:120px; text-align:center; vertical-align:middle; }

  .btn-ico{ width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; border:none; background:transparent; cursor:pointer; transition:transform .2s; padding:0; margin:0 6px; vertical-align:middle; }
  .btn-ico:hover{ transform:scale(1.1); }
  .btn-ico svg{ width:24px; height:24px; display:block; }
  .btn-ico.edit svg path{ stroke:#DFA000; }
  .btn-ico.edit svg circle{ fill:#DFA000; }
  .btn-ico.danger svg path{ stroke:#F8285A; }

  .table-footer{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; padding:14px 20px; border-top:1px solid #F1F1F4; background:#fff; }
  .summary{ color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap{ display:inline-flex; align-items:center; gap:10px; color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap form{ display:inline-flex; margin:0; padding:0; }
  .show-wrap .form-select{
    width:70px; height:30px; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; padding:4px 8px; font-size:11px; color:#252F4A; cursor:pointer; text-align:center;
    appearance:none; background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 8px center; background-size:14px; padding-right:30px;
  }

  /* Modal styles */
  .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
  .modal-overlay.show{display:block;}
  .modal{max-width:680px;margin:20px auto;background:#fff;border:1px solid #F1F1F4;border-radius:16px;box-shadow:0 12px 32px rgba(2,6,23,.12);overflow:hidden;}
  .modal-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid #F1F1F4;}
  .modal-header h3{margin:0;font-weight:800;font-size:20px;}
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

  @media (max-width:768px){
    .toolbar{ flex-direction:column; align-items:stretch; gap:12px; }
    .w-search{ width:100%; }
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
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Pembangkit Lokal</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>{{ now()->translatedFormat('F Y') }}</span>
      </div>

      {{-- MODALS --}}
      @include('admin.pembangkit_lokal.create')
      @include('admin.pembangkit_lokal.edit')

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Pembangkit
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.pembangkit.index') }}">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari lokasi/kapasitas..." autocomplete="off">
        </div>
        @if($q)
          <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
            <i class="ri-close-line"></i><span class="d-none d-sm-inline"> Clear</span>
          </button>
        @endif
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-wilayah">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Lokasi</th>
              <th>Kapasitas Gardu</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($items as $i => $it)
              <tr>
                <td class="col-no">{{ $items->firstItem() + $i }}</td>
                <td><strong>{{ $it->lokasi_lengkap }}</strong></td>
                <td>{{ $it->kapasitas_gardu }}</td>
                <td class="col-aksi">
                  <button
                    class="btn-ico edit btn-edit"
                    title="Edit"
                    data-action="{{ route('admin.pembangkit.update', $it) }}"
                    data-kapasitas="{{ $it->kapasitas_gardu }}"
                    data-wilayah_id="{{ $it->wilayah_id ?? '' }}"
                  >
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="12" cy="12" r="2" fill="#DFA000"/>
                      <path d="M12 5L9 8M12 5L15 8M12 5V3M12 19L9 16M12 19L15 16M12 19V21M19 12L16 9M19 12L16 15M19 12H21M5 12L8 9M5 12L8 15M5 12H3" stroke="#DFA000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>

                  <form action="{{ route('admin.pembangkit.destroy', $it) }}" method="POST" style="display:inline-block;margin:0" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ico danger" title="Hapus">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 20H15M10 4H14M7 7H17L16 20H8L7 7Z" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center" style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">
            Menampilkan <strong>{{ $items->firstItem() ?: 0 }}–{{ $items->lastItem() ?: 0 }}</strong> dari <strong>{{ $items->total() }}</strong> data
          </div>

          <div class="show-wrap">
            <span>Show</span>
            <form id="perPageForm" method="GET" action="{{ route('admin.pembangkit.index') }}">
              <input type="hidden" name="q" value="{{ $q }}">
              <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                @foreach([5,10,25,50,100] as $pp)
                  <option value="{{ $pp }}" {{ (string)$perPage===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
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

    document.querySelector('.btn-add')?.addEventListener('click', (e) => {
      e.preventDefault();
      __openModal('modalCreatePembangkit');
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const form = document.getElementById('formEditPembangkit');
        if (!form) return;

        form.action = btn.dataset.action;
        document.getElementById('kapasitas_edit').value = btn.dataset.kapasitas || '';
        document.getElementById('wilayah_id_edit').value = btn.dataset.wilayah_id || '';

        __openModal('modalEditPembangkit');
      });
    });
  });
</script>
@endpush