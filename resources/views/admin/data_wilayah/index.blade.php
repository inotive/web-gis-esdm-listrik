@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Wilayah')

@push('styles')
<style>
  /* ... (semua CSS Anda tetap, dipotong untuk ringkas) ... */
  .toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px;}
  .w-search{width:clamp(230px,38vw,340px);} .w-filter{width:clamp(180px,26vw,230px);}
  .input-group{display:flex;align-items:center;background:#FCFCFD;border:1px solid var(--line);border-radius:10px;overflow:hidden;height:36px;}
  .input-group-text{display:grid;place-items:center;width:36px;height:100%;color:#94A3B8;background:#F8FAFC;border-right:1px solid var(--line);}
  .form-control,.form-select{height:36px;border:none;background:transparent;padding:0 10px;font:inherit;color:var(--text);outline:none;width:100%;}
  .btn-ghost{height:32px;padding:0 10px;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer;}
  .btn-ghost:hover{background:#F8FAFC;}
  .table-shell{border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow-1);}
  .table-wilayah{width:100%;border-collapse:separate;border-spacing:0;}
  .table-wilayah thead th{background:#FCFCFD;color:#64748B;font-weight:700;padding:12px 18px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap;}
  .table-wilayah tbody td{padding:16px 18px;border-bottom:1px solid var(--line);color:#252F4A;vertical-align:middle;}
  .table-wilayah tbody tr:hover{background:#FAFAFA;}
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
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Data Wilayah</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>{{ now()->translatedFormat('F Y') }}</span>
      </div>

      @include('admin.data_wilayah.create') {{-- modal create --}}

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Data Wilayah
      </button>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.data-wilayah.index') }}">
        {{-- Search Nama Desa --}}
        <div class="input-group w-search">
          <span class="input-group-text" id="search-addon"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari Nama Desa..." aria-label="Cari Nama Desa" aria-describedby="search-addon">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
              <i class="ri-close-line"></i>
            </button>
          @endif
        </div>

        {{-- Filter Kabupaten --}}
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-government-line"></i></span>
          <select class="form-select auto-submit" name="regency_id" id="filterRegency" aria-label="Pilih Kabupaten/Kota">
            <option value="">Semua Kabupaten</option>
            @foreach($regencies as $rg)
              <option value="{{ $rg->id }}" @selected(request('regency_id')===$rg->id)>{{ $rg->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Filter Kecamatan (depend on Regency) --}}
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-community-line"></i></span>
          <select class="form-select auto-submit" name="district_id" id="filterDistrict" aria-label="Pilih Kecamatan">
            <option value="">Semua Kecamatan</option>
            @foreach($districts as $dc)
              <option value="{{ $dc->id }}" @selected(request('district_id')===$dc->id)>{{ $dc->name }}</option>
            @endforeach
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
              <th>Desa/Kelurahan</th>
              <th>Kecamatan</th>
              <th>Kabupaten</th>
              <th>Koordinat</th>
              <th>Polygon</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($wilayah as $i => $w)
              <tr>
                <td class="col-no">{{ $wilayah->firstItem() + $i }}</td>
                <td><strong>{{ $w->village->name ?? '-' }}</strong></td>
                <td>{{ $w->district->name ?? '-' }}</td>
                <td>{{ $w->regency->name ?? '-' }}</td>
                <td>
                  @if(!is_null($w->lat) && !is_null($w->lng))
                    {{ number_format($w->lat,6) }}, {{ number_format($w->lng,6) }}
                  @else
                    -
                  @endif
                </td>
                <td>{{ $w->polygon_geojson ? 'Ada' : '-' }}</td>
                <td class="col-aksi">
                  <a href="{{ route('admin.data-wilayah.edit', $w) }}" class="btn-ico" title="Edit">
                    <i class="ri-edit-2-line"></i>
                  </a>
                  <form action="{{ route('admin.data-wilayah.destroy', $w) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center" style="text-align:center;color:#64748B;padding:18px;">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">
            Menampilkan <strong>{{ $wilayah->firstItem() ?: 0 }}–{{ $wilayah->lastItem() ?: 0 }}</strong>
            dari <strong>{{ $wilayah->total() }}</strong> data
          </div>

          <div class="show-wrap">
            <span>Show</span>
            <form id="perPageForm" method="GET" action="{{ route('admin.data-wilayah.index') }}">
              <input type="hidden" name="q" value="{{ request('q') }}">
              <input type="hidden" name="regency_id" value="{{ request('regency_id') }}">
              <input type="hidden" name="district_id" value="{{ request('district_id') }}">
              <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                @foreach([5,10,25,50,100] as $pp)
                  <option value="{{ $pp }}" {{ (string)request('per_page','10')===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                @endforeach
              </select>
            </form>
            <span>per page</span>
          </div>

          {{ $wilayah->links() }}
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
        ['q','regency_id','district_id'].forEach(n=>{
          const el = filterForm.querySelector(`[name="${n}"]`);
          if (el) el.value = '';
        });
        filterForm.submit();
      });
    }

    // Cascading filter: ambil kecamatan setelah pilih kabupaten
    const selReg = document.getElementById('filterRegency');
    const selDis = document.getElementById('filterDistrict');
    selReg?.addEventListener('change', async () => {
      if (!selDis) return;
      const rid = selReg.value;
      selDis.innerHTML = '<option value="">Semua Kecamatan</option>';
      if (!rid) return;
      const res = await fetch('{{ route('admin.data-wilayah.options.districts') }}?regency_id=' + encodeURIComponent(rid));
      const rows = await res.json();
      rows.forEach(r => {
        const opt = document.createElement('option');
        opt.value = r.id; opt.textContent = r.name;
        selDis.appendChild(opt);
      });
    });
  });
</script>
@endpush
