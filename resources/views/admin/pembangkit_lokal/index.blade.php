@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Pembangkit Lokal')

@push('styles')
<style>
  .toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px}
  .toolbar .w-search{width:clamp(230px,38vw,340px)}
  .toolbar .w-filter{width:clamp(180px,26vw,230px)}
  .input-group{display:flex;align-items:center;background:#FCFCFD;border:1px solid var(--line);border-radius:10px;overflow:hidden;height:36px}
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
  .col-tipe{width:140px}
  .col-daya{width:170px}
  .col-sta{width:150px}
  .col-aksi{width:130px;text-align:center}
  .btn-ico{--size:32px;width:var(--size);height:var(--size);display:inline-grid;place-items:center;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ico:hover{background:#F8FAFC}
  .btn-ico.danger{border-color:#FEE2E2;color:#DC2626}
  .btn-ico.danger:hover{background:#FFF5F5}

  .table-footer{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:14px 18px;border-top:1px solid var(--line);background:#fff;border-bottom-left-radius:16px;border-bottom-right-radius:16px}
  .summary{color:var(--text-dim)}
  .show-wrap{display:inline-flex;align-items:center;gap:8px;color:var(--text-dim)}
  .show-wrap .form-select{width:92px}
  .pagination{display:flex;gap:6px;list-style:none;padding:0;margin:0}
  .page-link{min-width:34px;height:34px;padding:0 10px;display:flex;align-items:center;justify-content:center;border:1px solid var(--line);background:#fff;border-radius:8px;text-decoration:none;color:var(--text)}
  .page-link:hover{background:#F8FAFC}
  .page-item.active .page-link{background:var(--active-soft);color:#0F5132;border-color:#B7F7CF;font-weight:700}
  .page-item.disabled .page-link{opacity:.5;pointer-events:none}
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">Selasa, 22 September 2025</div>
      <div class="page-title">Pembangkit Lokal</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>September 2025</span></div>

      {{-- Modal Create Pembangkit --}}
      @include('admin.pembangkit_lokal.create')

      <button class="btn btn-primary btn-add"><i class="ri-add-line"></i> Tambah Pembangkit</button>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
  

      <form id="filterForm" class="toolbar" method="GET" action="#">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari Nama Pembangkit...">
          @if(request('q')) <button type="button" class="btn-ghost" id="btnClearSearch"><i class="ri-close-line"></i></button> @endif
        </div>

        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-flashlight-line"></i></span>
          <select class="form-select auto-submit" name="tipe">
            <option value="">Semua Tipe</option>
            @foreach(['PLTD','PLTS','PLTMH'] as $t)
              <option {{ request('tipe')===$t ? 'selected':'' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-checkbox-circle-line"></i></span>
          <select class="form-select auto-submit" name="status">
            <option value="">Semua Status</option>
            @foreach(['Beroperasi','Uji Coba','Perawatan'] as $s)
              <option {{ request('status')===$s ? 'selected':'' }}>{{ $s }}</option>
            @endforeach
          </select>
        </div>

        <button type="button" class="btn-ghost" id="btnReset"><i class="ri-refresh-line"></i><span class="d-none d-sm-inline"> Reset</span></button>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="data">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Pembangkit</th>
              <th class="col-tipe">Tipe</th>
              <th class="col-daya">Daya Terpasang</th>
              <th class="col-sta">Status</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $rows = [
              ['PLTD Sungai K','PLTD','2 MW','Beroperasi'],
              ['PLTS Desa M','PLTS','250 kWp','Uji Coba'],
              ['PLTMH Lembah N','PLTMH','1.2 MW','Beroperasi'],
              ['PLTS Pulau O','PLTS','500 kWp','Perawatan'],
              ['PLTD Hulu P','PLTD','5 MW','Beroperasi'],
            ]; @endphp
            @foreach ($rows as $i => $r)
              <tr>
                <td class="col-no">{{ $i+1 }}</td>
                <td><strong>{{ $r[0] }}</strong></td>
                <td class="col-tipe">{{ $r[1] }}</td>
                <td class="col-daya">{{ $r[2] }}</td>
                <td class="col-sta">{{ $r[3] }}</td>
                <td class="col-aksi">
                  <a href="#" class="btn-ico" title="Pengaturan"><i class="ri-settings-3-line"></i></a>
                  <button type="button" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan <strong>1–5</strong> dari <strong>37</strong> data</div>
          <div class="show-wrap">
            <span>Show</span>
            <form id="perPageForm" method="GET" action="#">
              <input type="hidden" name="q" value="{{ request('q') }}">
              <input type="hidden" name="tipe" value="{{ request('tipe') }}">
              <input type="hidden" name="status" value="{{ request('status') }}">
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
  document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const perPageForm = document.getElementById('perPageForm');
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (perPageForm && perPageForm.contains(el)) perPageForm.submit(); else if (filterForm) filterForm.submit();
      });
    });
    const btnClear = document.getElementById('btnClearSearch');
    btnClear?.addEventListener('click', () => { const i=filterForm.querySelector('input[name="q"]'); if(i) i.value=''; filterForm.submit(); });
    const btnReset = document.getElementById('btnReset');
    btnReset?.addEventListener('click', () => { filterForm.reset(); const i=filterForm.querySelector('input[name="q"]'); if(i) i.value=''; filterForm.submit(); });
  });
</script>
@endpush
