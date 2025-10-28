@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Gardu')

@push('styles')
<style>
  .toolbar { display:flex; flex-wrap:wrap; align-items:center; gap:8px 10px; }
  .toolbar .w-search { width: clamp(230px, 38vw, 340px); }
  .toolbar .w-filter { width: clamp(180px, 26vw, 230px); }
  .input-group { display:flex; align-items:center; background:#FCFCFD; border:1px solid var(--line); border-radius:10px; overflow:hidden; height:36px; }
  .input-group:focus-within { border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12); }
  .input-group-text { display:grid; place-items:center; width:36px; height:100%; color:#94A3B8; background:#F8FAFC; border-right:1px solid var(--line); }
  .form-control, .form-select { height:36px; border:none; background:transparent; padding:0 10px; font: inherit; color: var(--text); outline:none; width:100%; }
  .btn-ghost { height:32px; padding:0 10px; border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ghost:hover { background:#F8FAFC; }

  .table-shell { border: 1px solid var(--line); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-1); }
  table.data { width:100%; border-collapse:separate; border-spacing:0; }
  table.data thead th { background:#FCFCFD; color:#64748B; font-weight:700; padding:12px 18px; text-align:left; border-bottom:1px solid var(--line); white-space:nowrap; }
  table.data tbody td { padding:16px 18px; border-bottom:1px solid var(--line); color:#252F4A; vertical-align:middle; }
  table.data tbody tr:hover { background:#FAFAFA; }

  .col-no{ width:70px; text-align:center; }
  .col-aksi{ width:140px; text-align:center; }
  .btn-ico { --size:32px; width:var(--size); height:var(--size); display:inline-grid; place-items:center; border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ico:hover{ background:#F8FAFC; }
  .btn-ico.danger { border-color:#FEE2E2; color:#DC2626; }
  .btn-ico.danger:hover { background:#FFF5F5; }

  .table-footer{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px; padding:14px 18px; border-top:1px solid var(--line); background:#fff; border-bottom-left-radius:16px; border-bottom-right-radius:16px; }
  .summary{ color:var(--text-dim); }
  .show-wrap{ display:inline-flex; align-items:center; gap:8px; color:var(--text-dim); }
  .show-wrap .form-select { width:92px; }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Data Gardu</div>
    </div>
    <div class="page-actions">
      <a href="{{ route('admin.gardu.create') }}" class="btn btn-primary"><i class="ri-add-line"></i> Tambah Gardu</a>
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
                <a href="{{ route('admin.gardu.edit', $g) }}" class="btn-ico" title="Ubah"><i class="ri-pencil-line"></i></a>
                <form action="{{ route('admin.gardu.destroy', $g) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center; padding:30px;">Belum ada data</td></tr>
          @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">
            Menampilkan <strong>{{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}</strong> dari <strong>{{ $items->total() }}</strong> data
          </div>

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
    if (btnClear) {
      btnClear.addEventListener('click', () => {
        const input = filterForm.querySelector('input[name="q"]');
        if (input) input.value = '';
        filterForm.submit();
      });
    }

    const btnReset = document.getElementById('btnReset');
    if (btnReset) {
      btnReset.addEventListener('click', () => {
        filterForm.reset();
        const inputQ = filterForm.querySelector('input[name="q"]');
        if (inputQ) inputQ.value = '';
        filterForm.submit();
      });
    }
  });
</script>
@endpush
