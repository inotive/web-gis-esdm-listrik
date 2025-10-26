@extends('admin.layouts.app')

@section('title', 'Rekapitulasi Asset Tanah - BPKAD')
@section('page-title', 'Rekapitulasi Asset Tanah')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Manajemen Aset</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Rekapitulasi Asset Tanah</li>
@endsection

@push('styles')
<style>
/* =========================================================================
   NAMESPACE: semua style dibatasi pada halaman ini agar tak bentrok tema
   ========================================================================= */
.asset-rekap-page{
  --surface: #ffffff;
  --surface-alt: #f6f8ff;
  --surface-muted: rgba(15,23,42,.04);
  --border: rgba(148,163,184,.32);
  --border-strong: rgba(99,102,241,.25);
  --text: #0f172a;
  --text-soft: #475569;
  --text-muted: #64748b;
  --accent: #3b82f6;
  --accent-strong: #2563eb;
  --green: #10b981;
  --amber: #f59e0b;
  --shadow: 0 18px 40px -20px rgba(15,23,42,.45);
  --shadow-soft: 0 12px 30px rgba(15,23,42,.08);
  position: relative;
  max-width: 1160px;
  margin-inline: auto;
  padding: 0 clamp(16px, 4vw, 32px) 56px;
}
.asset-rekap-page::before{ content:''; position:absolute; inset:0; margin-top:-48px; z-index:-1; }
.asset-rekap-page .card{ border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-soft); background: var(--surface); }

/* tombol & utilities */
.asset-rekap-page .btn{ border-radius: 12px; font-weight: 600; transition: box-shadow .2s ease, transform .2s ease; }
.asset-rekap-page .btn:hover{ transform: translateY(-1px); box-shadow: 0 12px 24px -12px rgba(15,23,42,.35); }
.asset-rekap-page .btn-export{ background: var(--accent-strong) !important; color:#fff !important; border:none !important; padding:12px 18px !important; display:inline-flex; align-items:center; gap:10px; box-shadow:0 20px 32px -18px rgba(37,99,235,.65); }
.asset-rekap-page .btn-print{ background:#0f172a !important; color:#fff !important; border:none !important; padding:12px 18px !important; display:inline-flex; align-items:center; gap:10px; }
.asset-rekap-page .btn-outline{ background:#fff; color: var(--text); border:1px solid rgba(148,163,184,.4); padding:10px 16px; border-radius:12px; font-weight:600; }

/* tabsbar */
.asset-rekap-page .tabsbar{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin:32px 0 10px; }
.asset-rekap-page .tabs{ display:flex; gap:8px; margin:0; background: rgba(255,255,255,.88); padding:6px; border-radius:16px; border:1px solid rgba(148,163,184,.24); box-shadow: var(--shadow-soft); width:fit-content; }
.asset-rekap-page .tab-actions{ display:flex; gap:10px; }
.asset-rekap-page .tab-link{ display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:12px; font-weight:600; color: var(--text-muted); text-decoration:none; transition: all .2s ease; }
.asset-rekap-page .tab-link.active{ background: var(--accent-strong); color:#fff; box-shadow: 0 16px 30px -18px rgba(37,99,235,.65); }
.asset-rekap-page .tab-pane{ display:none; }
.asset-rekap-page .tab-pane.show{ display:block; }

/* toolbar */
.asset-rekap-page .toolbar{ margin-top:24px; background: rgba(255,255,255,.88); border:1px solid rgba(148,163,184,.25); border-radius:18px; box-shadow: var(--shadow-soft); padding: clamp(18px, 4vw, 26px); display:grid; grid-template-columns:1fr auto; gap:18px; align-items:center; }
.asset-rekap-page .toolbar-left{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; }
.asset-rekap-page .toolbar-right{ display:flex; justify-content:flex-end; align-items:center; }
.asset-rekap-page .search{ display:flex; align-items:center; gap:10px; background: rgba(248,250,255,1); border:1px solid rgba(148,163,184,.25); border-radius:14px; padding:10px 14px; min-width: clamp(200px, 32vw, 280px); }
.asset-rekap-page .search input{ border:none; outline:none; background:transparent; font-size:.92rem; color: var(--text); width:100%; }

/* show per page */
.asset-rekap-page .perpage{ display:inline-flex; align-items:center; gap:10px; font-size:.9rem; color: var(--text-muted); }
.asset-rekap-page .perpage select{ border:1px solid rgba(148,163,184,.35); border-radius:12px; padding:10px 12px; font-size:.9rem; background: rgba(248,250,255,.8); color: var(--text); }

/* filter menu */
.asset-rekap-page .filter{ position:relative; }
.asset-rekap-page .filter > summary{ list-style:none; cursor:pointer; display:inline-flex; align-items:center; gap:8px; background: rgba(59,130,246,.1); border:1px solid rgba(59,130,246,.22); border-radius:14px; padding:10px 16px; font-size:.9rem; color: var(--accent-strong); font-weight:600; }
.asset-rekap-page .filter > summary::-webkit-details-marker{ display:none; }
.asset-rekap-page .filter[open] > summary{ background: rgba(37,99,235,.14); border-color: rgba(37,99,235,.35); }
.asset-rekap-page .filter-menu{ position:absolute; top: calc(100% + 8px); left:0; z-index:30; width:420px; max-width:94vw; background: var(--surface); border:1px solid rgba(148,163,184,.25); border-radius:18px; box-shadow:0 24px 48px -24px rgba(15,23,42,.4); padding:14px 14px 0; display:block; }
.asset-rekap-page .filter-menu .menu-head{ font-size:.73rem; font-weight:700; letter-spacing:.08em; color: var(--text-muted); text-transform:uppercase; padding:6px 4px 10px; border-bottom:1px solid rgba(148,163,184,.2); margin-bottom:12px; }
.asset-rekap-page .filter-grid{ display:grid; grid-template-columns:1fr; gap:12px; }
@media (min-width:768px){ .asset-rekap-page .filter-grid{ grid-template-columns:1fr 1fr; } }
.asset-rekap-page .select-modern{ display:flex; flex-direction:column; gap:6px; }
.asset-rekap-page .select-modern span{ font-size:.75rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color: var(--text-muted); }
.asset-rekap-page .select-modern select{ width:100%; border:1px solid rgba(148,163,184,.35); border-radius:12px; padding:10px 12px; font-size:.9rem; color: var(--text); background: rgba(248,250,255,.8); }
.asset-rekap-page .filter-actions{ position:sticky; bottom:0; background: var(--surface); margin-top:12px; padding:12px 0 14px; border-top:1px solid rgba(148,163,184,.2); display:flex; align-items:center; gap:12px; }
.asset-rekap-page .filter-actions .spacer{ flex:1; }

/* table */
.asset-rekap-page .panel{ background: rgba(255,255,255,.92); border:1px solid rgba(148,163,184,.24); border-radius:18px; box-shadow: var(--shadow-soft); overflow:hidden; }
.asset-rekap-page .table-container{ overflow-x:auto; }
.asset-rekap-page table{ width:100%; border-collapse:separate; border-spacing:0; }
.asset-rekap-page thead th{ background: linear-gradient(180deg, rgba(248,250,255,1) 0%, rgba(240,244,255,1) 100%); border-bottom:1px solid rgba(148,163,184,.3); font-size:.72rem; font-weight:700; letter-spacing:.08em; color: var(--text-muted); text-transform: uppercase; padding:14px 18px; white-space:nowrap; position:sticky; top:0; z-index:2; }
.asset-rekap-page tbody td{ border-bottom:1px solid rgba(226,232,240,.7); padding:12px 18px; font-size:.94rem; color: var(--text); vertical-align: middle; }
.asset-rekap-page tbody tr:nth-child(even){ background: rgba(248,250,255,.7); }
.asset-rekap-page tbody tr:hover{ background: rgba(219,234,254,.55); }
.asset-rekap-page .col-num{ width:62px; text-align:center; color: var(--text-muted); font-weight:700; }
.asset-rekap-page .text-right{text-align:right;} .asset-rekap-page .text-center{text-align:center;}
.asset-rekap-page .chip{ display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; background: rgba(59,130,246,.12); border:1px solid rgba(59,130,246,.2); font-size:.82rem; color: var(--accent-strong); }

/* Fix: jika ada SVG di pagination (mis. tailwind) agar tak membesar */
.asset-rekap-page nav[role="navigation"] svg{ width:16px !important; height:16px !important; display:inline-block; vertical-align:middle; }

/* print & responsive */
@media print{
  .asset-rekap-page .no-print, .sidebar, .header, .footer, .breadcrumb, .card-toolbar{ display:none!important; }
  .asset-rekap-page{ max-width:100%; padding:0; background:#fff; }
  .asset-rekap-page .panel, .asset-rekap-page .card{ box-shadow:none!important; border:1px solid #bbb!important; }
  .asset-rekap-page thead th, .asset-rekap-page tbody td{ font-size:11px; padding:8px 12px; }
  .asset-rekap-page .tab-pane{ display:block!important; }
}
@media (max-width: 992px){ .asset-rekap-page .toolbar{ grid-template-columns:1fr; } }
@media (max-width: 640px){
  .asset-rekap-page{ padding-inline:16px; }
  .asset-rekap-page .tabsbar{ flex-direction:column; align-items:stretch; gap:8px; }
  .asset-rekap-page .tabs{ width:100%; overflow-x:auto; }
  .asset-rekap-page .tab-actions{ justify-content:flex-end; }
  .asset-rekap-page .search{ min-width:100%; }
}
</style>
@endpush

@section('content')
<div class="asset-rekap-page">
  {{-- Header --}}
  <div class="mb-5 no-print">
    <div class="d-flex flex-column">
      <span class="fw-bold fs-2 mb-1">Rekapitulasi Aset</span>
      <span class="text-muted mt-1 fw-semibold fs-7">Per {{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
  </div>

  {{-- Tabs + Actions --}}
  <div class="no-print tabsbar" role="toolbar" aria-label="Tab & Aksi">
    <nav class="tabs" aria-label="Tabel">
      <a href="#" class="tab-link {{ ($activeTab ?? 'rekap') === 'rekap' ? 'active' : '' }}" data-tab="rekap">Tabel Rekapitulasi</a>
      <a href="#" class="tab-link {{ ($activeTab ?? 'rekap') === 'wilayah' ? 'active' : '' }}" data-tab="wilayah">Tabel Jumlah Wilayah</a>
    </nav>

    <div class="tab-actions">
      <button type="button" class="btn btn-sm btn-export" onclick="exportExcel()">
          <i class="fa-solid fa-file-excel fa-lg fa-fw text-white"></i>
          Export Excel
        </button>
        <button type="button" class="btn btn-sm btn-print" onclick="printSummary()">
          <i class="fa-solid fa-print fa-lg fa-fw text-white"></i>
          Cetak
        </button>

    </div>

  </div>

  {{-- Toolbar (Filter + Search + Per Page) --}}
  <form method="GET" action="{{ route('admin.asset.rekapitulasi') }}" id="filterForm" class="no-print">
    <input type="hidden" name="tab" id="activeTabInput" value="{{ $activeTab ?? 'rekap' }}" />
    <div class="toolbar">
      <div class="toolbar-left">
        <label class="search" aria-label="Cari Nama Aset">
          <input type="text" name="search" id="searchInput" placeholder="Cari Nama Aset" value="{{ request('search') }}">
        </label>

        {{-- FILTER --}}
        <details class="filter">
          <summary>Filter Berdasarkan</summary>
          <div class="filter-menu" role="group" aria-label="Filter Berdasarkan">
            <div class="menu-head">Filter</div>

            <div class="filter-grid">
              <label class="select-modern">
                <span>Unit Kerja</span>
                <select name="unit_kerja_id" id="unitKerjaFilter">
                  <option value="">Semua Unit Kerja</option>
                  @foreach($unitKerjas ?? [] as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                      {{ $unit->nama_unit }}
                    </option>
                  @endforeach
                </select>
              </label>

              <label class="select-modern">
                <span>Kabupaten</span>
                <select name="regency_id" id="regencyFilter">
                  <option value="">Semua Kabupaten</option>
                  @foreach($regencies ?? [] as $reg)
                    <option value="{{ $reg->id }}" {{ request('regency_id') == $reg->id ? 'selected' : '' }}>
                      {{ $reg->name }}
                    </option>
                  @endforeach
                </select>
              </label>
            </div>

            <div class="filter-actions">
              <a href="{{ route('admin.asset.rekapitulasi') }}" class="btn btn-outline">Reset</a>
              <span class="spacer"></span>
              <button type="submit" class="btn btn-black">Terapkan</button>
            </div>
          </div>
        </details>
      </div>

      <div class="toolbar-right">
        <label class="perpage" aria-label="Tampilkan jumlah baris per halaman">
          <span>Show per page</span>
          <select name="per_page" id="perPageSelect">
            @foreach([10,25,50,100,200] as $n)
              <option value="{{ $n }}" {{ (int)request('per_page', $perPage ?? 25) === $n ? 'selected' : '' }}>{{ $n }}</option>
            @endforeach
          </select>
        </label>
      </div>
    </div>
  </form>

  {{-- PANE: Rekapitulasi --}}
  <section id="pane-rekap" class="tab-pane {{ ($activeTab ?? 'rekap') === 'rekap' ? 'show' : '' }}" aria-labelledby="tab-rekap">
    <div class="panel mt-3">
      <div class="table-container">
        <table id="tbl-rekap">
          <colgroup>
            <col style="width:62px">
            <col style="width:auto">
            <col style="width:140px">
            <col style="width:180px">
            <col style="width:180px">
            <col style="width:160px">
          </colgroup>
          <thead>
            <tr>
              <th class="col-num">No</th>
              <th>Jenis Aset</th>
              <th class="text-center">Jumlah Titik</th>
              <th>Kecamatan</th>
              <th>Kabupaten</th>
              <th class="text-right">Total Luas (m²)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rekap ?? [] as $row)
              <tr>
                <td class="col-num">{{ ($rekap->firstItem() ?? 0) + $loop->index }}</td>
                <td title="{{ $row->nama_asset }}">{{ $row->nama_asset }}</td>
                <td class="text-center"><span class="badge bg-primary">{{ number_format($row->jumlah_titik ?? 0, 0, ',', '.') }}</span></td>
                <td><span class="chip">{{ $row->kecamatan }}</span></td>
                <td><span class="chip">{{ $row->kabupaten }}</span></td>
                <td class="text-right">{{ number_format($row->total_luas ?? 0, 2, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="empty">
                    <div class="box"></div>
                    Tidak ada data tersedia
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="p-3">
        {{-- HANYA SATU pagination --}}
        <div class="mt-2">
          {{ $rekap->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </section>

  {{-- PANE: Jumlah Wilayah --}}
  <section id="pane-wilayah" class="tab-pane {{ ($activeTab ?? 'rekap') === 'wilayah' ? 'show' : '' }}" aria-labelledby="tab-wilayah">
    <div class="panel mt-3">
      <div class="table-container">
        <table id="tbl-wilayah">
          <colgroup>
            <col style="width:62px">
            <col style="width:220px">
            <col style="width:220px">
            <col style="width:140px">
            <col style="width:auto">
          </colgroup>
          <thead>
            <tr>
              <th class="col-num">No</th>
              <th>Kabupaten</th>
              <th>Kecamatan</th>
              <th class="text-center">Total Asset</th>
              <th>Rincian Jenis Asset</th>
            </tr>
          </thead>
          <tbody>
            @forelse($wilayahSummary ?? [] as $row)
              <tr>
                <td class="col-num">{{ ($wilayahSummary->firstItem() ?? 0) + $loop->index }}</td>
                <td><span class="chip">{{ $row->kabupaten }}</span></td>
                <td><span class="chip">{{ $row->kecamatan }}</span></td>
                <td class="text-center"><span class="badge bg-primary">{{ number_format($row->total_asset ?? 0, 0, ',', '.') }}</span></td>
                <td>
                  @php $perJenis = collect($row->per_jenis ?? []); @endphp
                  @if($perJenis->isEmpty())
                    <em class="text-muted">Tidak ada data</em>
                  @else
                    @foreach($perJenis as $katId => $jumlah)
                      @php $kat = ($kategoriList ?? collect())->firstWhere('id', (int)$katId); @endphp
                      <span class="badge bg-light text-dark me-1 mb-1">{{ $kat->name ?? 'Tanpa Kategori' }}: {{ number_format($jumlah, 0, ',', '.') }}</span>
                    @endforeach
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5">
                  <div class="empty">
                    <div class="box"></div>
                    Tidak ada data tersedia
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="p-3">
        {{-- HANYA SATU pagination --}}
        <div class="mt-2">
          {{ $wilayahSummary->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
/* Tabs (toggle + persist ke query) */
function setActiveTab(tab){
  document.querySelectorAll('.tab-link').forEach(a => a.classList.toggle('active', a.dataset.tab === tab));
  document.getElementById('pane-rekap').classList.toggle('show', tab === 'rekap');
  document.getElementById('pane-wilayah').classList.toggle('show', tab === 'wilayah');
  document.getElementById('activeTabInput').value = tab;
  const url = new URL(window.location.href);
  url.searchParams.set('tab', tab);
  window.history.replaceState({}, '', url);
}
document.querySelectorAll('.tab-link').forEach(a => {
  a.addEventListener('click', (e) => { e.preventDefault(); setActiveTab(a.dataset.tab); });
});

/* Filter auto-submit saat select berubah */
['unitKerjaFilter', 'regencyFilter', 'perPageSelect'].forEach(id => {
  const el = document.getElementById(id);
  el && el.addEventListener('change', () => document.getElementById('filterForm').submit());
});

/* Search debounce */
let t;
const searchEl = document.getElementById('searchInput');
if (searchEl){
  searchEl.addEventListener('input', () => {
    clearTimeout(t);
    t = setTimeout(() => document.getElementById('filterForm').submit(), 500);
  });
}

/* Export Excel (ikut filter & per_page) */
function exportExcel(){
  const form = document.getElementById('filterForm');
  const params = new URLSearchParams(new FormData(form));
  const url = '{{ route("admin.asset.rekapitulasi.export") }}?' + params.toString();
  window.location.href = url;
}

function printSummary(){
  const form = document.getElementById('filterForm');
  const params = new URLSearchParams(new FormData(form));
  const url = '{{ route("admin.asset.rekapitulasi.print") }}?' + params.toString();
  window.open(url, '_blank');
}

</script>
@endpush
