@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Pembangkit Lokal')

@push('styles')
<style>
  .toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px;}
  .w-search{width:clamp(230px,38vw,340px);} .w-filter{width:clamp(180px,26vw,230px);}
  .input-group{display:flex;align-items:center;background:#FCFCFD;border:1px solid var(--line);border-radius:10px;overflow:hidden;height:36px;}
  .input-group-text{display:grid;place-items:center;width:36px;height:100%;color:#94A3B8;background:#F8FAFC;border-right:1px solid var(--line);}
  .form-control,.form-select{height:36px;border:none;background:transparent;padding:0 10px;font:inherit;color:var(--text);outline:none;width:100%;}
  .btn-ghost{height:32px;padding:0 10px;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer;}
  .btn-ghost:hover{background:#F8FAFC;}

  .table-shell{border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow-1);}
  table.data{width:100%;border-collapse:separate;border-spacing:0;}
  table.data thead th{background:#FCFCFD;color:#64748B;font-weight:700;padding:12px 18px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap;}
  table.data tbody td{padding:16px 18px;border-bottom:1px solid var(--line);color:#252F4A;vertical-align:middle;}
  table.data tbody tr:hover{background:#FAFAFA;}
  .col-no{width:70px;text-align:center;}
  .col-aksi{width:160px;text-align:center;}
  .btn-ico{--size:32px;width:var(--size);height:var(--size);display:inline-grid;place-items:center;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer;}
  .btn-ico:hover{background:#F8FAFC;}
  .btn-ico.danger{border-color:#FEE2E2;background:#FFF;color:#DC2626;}
  .btn-ico.danger:hover{background:#FFF5F5;}

  .table-footer{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:14px 18px;border-top:1px solid var(--line);background:#fff;border-bottom-left-radius:16px;border-bottom-right-radius:16px;}
  .summary{color:var(--text-dim);}
  .show-wrap{display:inline-flex;align-items:center;gap:8px;color:var(--text-dim);}
  .show-wrap .form-select{width:92px;}

  /* Modal base (dipakai create & edit) */
  .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
  .modal-overlay.show{display:block;}
  .modal{max-width:680px;margin:20px auto;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-2);overflow:hidden;}
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
          <span class="input-group-text" id="search-addon"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ $q }}" class="form-control"
                 placeholder="Cari lokasi/kapasitas..." aria-label="Cari" aria-describedby="search-addon">
          @if($q)
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
              <i class="ri-close-line"></i>
            </button>
          @endif
        </div>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="data">
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
              @php
                $lok = $it->lokasiLabel();
                $w  = $it->wilayah;
                $pid = optional(optional($w)->regency)->province->id ?? '';
              @endphp
              <tr>
                <td class="col-no">{{ $items->firstItem() + $i }}</td>
                <td><strong>{{ $lok ?: '—' }}</strong></td>
                <td>{{ $it->kapasitas_gardu }}</td>
                <td class="col-aksi">
                  <button
                    class="btn-ico btn-edit"
                    title="Edit"
                    data-action="{{ route('admin.pembangkit.update', $it) }}"
                    data-id="{{ $it->id }}"
                    data-kapasitas="{{ $it->kapasitas_gardu }}"
                    data-lokasi="{{ $lok }}"
                    data-wilayah_id="{{ $w->id ?? '' }}"
                    data-province_id="{{ $pid }}"
                    data-regency_id="{{ $w->regency_id ?? '' }}"
                    data-district_id="{{ $w->district_id ?? '' }}"
                    data-village_id="{{ $w->village_id ?? '' }}"
                  ><i class="ri-edit-2-line"></i></button>

                  <form action="{{ route('admin.pembangkit.destroy', $it) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center" style="text-align:center;color:#64748B;padding:18px;">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">
            Menampilkan <strong>{{ $items->firstItem() ?: 0 }}–{{ $items->lastItem() ?: 0 }}</strong>
            dari <strong>{{ $items->total() }}</strong> data
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
      const input = filterForm.querySelector('input[name="q"]'); if (input) input.value = '';
      filterForm.submit();
    });

    // OPEN CREATE MODAL
    document.querySelector('.btn-add')?.addEventListener('click', (e) => {
      e.preventDefault();
      window.__openModal && __openModal('modalCreatePembangkit');
    });

    // OPEN EDIT MODAL + PREFILL
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const m = document.getElementById('modalEditPembangkit');
        const form = document.getElementById('formEditPembangkit');
        if (!m || !form) return;

        // set action PUT
        form.action = btn.dataset.action;

        // isi field
        document.getElementById('kapasitas_edit').value = btn.dataset.kapasitas || '';
        document.getElementById('lokasiInputEdit').value = btn.dataset.lokasi || '';

        // hidden ids
        document.getElementById('wilayah_id_edit').value  = btn.dataset.wilayah_id || '';
        document.getElementById('province_id_edit').value = btn.dataset.province_id || '';
        document.getElementById('regency_id_edit').value  = btn.dataset.regency_id || '';
        document.getElementById('district_id_edit').value = btn.dataset.district_id || '';
        document.getElementById('village_id_edit').value  = btn.dataset.village_id || '';

        window.__openModal && __openModal('modalEditPembangkit');
        // perbaiki size list saran ketika baru dibuka
        setTimeout(()=>window.__invalidateSuggestEdit && window.__invalidateSuggestEdit(), 80);
      });
    });
  });
</script>
@endpush
