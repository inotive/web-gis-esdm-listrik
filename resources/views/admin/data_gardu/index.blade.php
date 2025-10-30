@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Gardu')

@push('styles')
<style>
  .toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px;}
  .w-search{width:clamp(230px,38vw,340px);} .w-filter{width:clamp(180px,26vw,230px);}
  .input-group{display:flex;align-items:center;background:#FCFCFD;border:1px solid var(--line);border-radius:10px;overflow:hidden;height:36px;}
  .input-group:focus-within{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .input-group-text{display:grid;place-items:center;width:36px;height:100%;color:#94A3B8;background:#F8FAFC;border-right:1px solid var(--line)}
  .form-control,.form-select{height:36px;border:none;background:transparent;padding:0 10px;font:inherit;color:var(--text);outline:none;width:100%}
  .btn-ghost{height:32px;padding:0 10px;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ghost:hover{background:#F8FAFC}

  .table-shell{border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow-1)}
  table.data{width:100%;border-collapse:separate;border-spacing:0}
  table.data thead th{background:#FCFCFD;color:#64748B;font-weight:700;padding:12px 18px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap}
  table.data tbody td{padding:16px 18px;border-bottom:1px solid var(--line);color:#252F4A;vertical-align:middle}
  table.data tbody tr:hover{background:#FAFAFA}

  .col-no{width:70px;text-align:center}
  .col-aksi{width:160px;text-align:center}
  .btn-ico{--size:32px;width:var(--size);height:var(--size);display:inline-grid;place-items:center;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ico:hover{background:#F8FAFC}
  .btn-ico.danger{border-color:#FEE2E2;background:#FFF;color:#DC2626}
  .btn-ico.danger:hover{background:#FFF5F5}

  .table-footer{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:14px 18px;border-top:1px solid var(--line);background:#fff;border-bottom-left-radius:16px;border-bottom-right-radius:16px}
  .summary{color:var(--text-dim)}
  .show-wrap{display:inline-flex;align-items:center;gap:8px;color:#var(--text-dim)}
  .show-wrap .form-select{width:92px}

  /* modal base (dipakai create & edit) */
  .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
  .modal-overlay.show{display:block;}
  .modal{max-width:720px;margin:20px auto;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-2);overflow:hidden;}
  .modal-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid var(--line);}
  .modal-header h3{margin:0;font-weight:800;font-size:20px;letter-spacing:-.2px;}
  .btn-x{width:36px;height:36px;display:grid;place-items:center;border:1px solid #E2E8F0;background:#fff;border-radius:10px;cursor:pointer;}
  .btn-x:hover{background:#F8FAFC;}
  .modal-body{padding:18px 20px 6px;}
  .modal-footer{padding:14px 20px 18px;}
  .btn-save{width:100%;height:44px;border:none;border-radius:10px;font-weight:700;color:#fff;background:var(--accent-2);box-shadow:0 10px 22px rgba(34,197,94,.22);cursor:pointer;}
  .btn-save:hover{filter:brightness(.95);}

  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .form-grid .full{grid-column:1/-1}
  .f{display:flex;flex-direction:column;gap:8px}
  .f label{font-size:13px;color:#475569}
  .input,.select{height:42px;border:1px solid var(--line,#E5E7EB);border-radius:10px;background:#FCFCFD;padding:0 12px;font:inherit;color:#111827}
  .input:focus,.select:focus{outline:none;border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .muted{color:#64748B;font-size:12px}

  .suggest-wrap{position:relative}
  .suggest-box{position:absolute;left:0;right:0;top:100%;margin-top:4px;background:#fff;border:1px solid #E5E7EB;border-radius:10px;box-shadow:0 6px 20px rgba(2,6,23,.08);max-height:280px;overflow:auto;z-index:50}
  .suggest-item{padding:10px 12px;cursor:pointer}
  .suggest-item:hover,.suggest-item.active{background:#F0FDF4}
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
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari Nama/Lokasi...">
          @if($q)
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan"><i class="ri-close-line"></i></button>
          @endif
        </div>

        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-filter-3-line"></i></span>
          <select class="form-select auto-submit" name="jenis" aria-label="Jenis Gardu">
            <option value="">Semua Jenis</option>
            @foreach($jenisOptions as $opt)
              <option value="{{ $opt }}" {{ $jenis===$opt ? 'selected' : '' }}>{{ $opt }}</option>
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
        <table class="data">
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
              <td>{{ $g->lokasi ?: '—' }}</td>
              <td class="col-aksi">
                <button
                  class="btn-ico btn-edit"
                  title="Ubah"
                  data-action="{{ route('admin.gardu.update', $g) }}"
                  data-nama="{{ $g->nama }}"
                  data-jenis="{{ $g->jenis_gardu_distribusi }}"
                  data-lokasi="{{ $g->lokasi }}"
                  data-wilayah_id="{{ $g->wilayah_id ?? '' }}"
                  data-province_id="{{ optional(optional($g->wilayah)->regency)->province->id ?? '' }}"
                  data-regency_id="{{ $g->wilayah->regency_id ?? '' }}"
                  data-district_id="{{ $g->wilayah->district_id ?? '' }}"
                  data-village_id="{{ $g->wilayah->village_id ?? '' }}"
                ><i class="ri-edit-2-line"></i></button>

                <form action="{{ route('admin.gardu.destroy', $g) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;padding:28px;">Belum ada data</td></tr>
          @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan <strong>{{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}</strong> dari <strong>{{ $items->total() }}</strong> data</div>
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
          {{ $items->links() }}
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  // helpers modal sekali saja
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

    document.getElementById('btnClearSearch')?.addEventListener('click', () => {
      const input = filterForm.querySelector('input[name="q"]'); if (input) input.value = ''; filterForm.submit();
    });

    document.getElementById('btnReset')?.addEventListener('click', () => {
      filterForm.reset();
      const inputQ = filterForm.querySelector('input[name="q"]');
      if (inputQ) inputQ.value = '';
      filterForm.submit();
    });

    // open create
    document.querySelector('.btn-add')?.addEventListener('click', (e)=>{ e.preventDefault(); __openModal('modalCreateGardu'); });

    // open edit + prefill
    document.querySelectorAll('.btn-edit').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const form = document.getElementById('formEditGardu');
        form.action = btn.dataset.action;
        document.getElementById('nama_edit').value = btn.dataset.nama || '';
        document.getElementById('jenis_edit').value = btn.dataset.jenis || '';
        document.getElementById('lokasiInputEdit').value = btn.dataset.lokasi || '';

        document.getElementById('wilayah_id_edit').value  = btn.dataset.wilayah_id || '';
        document.getElementById('province_id_edit').value = btn.dataset.province_id || '';
        document.getElementById('regency_id_edit').value  = btn.dataset.regency_id || '';
        document.getElementById('district_id_edit').value = btn.dataset.district_id || '';
        document.getElementById('village_id_edit').value  = btn.dataset.village_id || '';

        __openModal('modalEditGardu');
        setTimeout(()=>window.__invalidateSuggestGarduEdit && window.__invalidateSuggestGarduEdit(), 80);
      });
    });
  });
</script>
@endpush
