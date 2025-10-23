@extends('admin.layouts.app')

@section('title', 'Dokumen Asset - BPKAD')
@section('page-title', 'Dokumen Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Asset</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Dokumen</li>
@endsection

@push('styles')
<style>
    .dokumen-container { padding: 24px; }

    /* Header Section */
    .page-header { margin-bottom: 24px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .page-header h1 { font-family:'Inter',sans-serif; font-size:28px; font-weight:700; color:#1f2937; margin:0 0 8px 0; }
    .page-header p { font-family:'Inter',sans-serif; font-size:14px; color:#6b7280; margin:0; }
    .actions-right { display:flex; gap:8px; flex-wrap:wrap; }

    /* Stats */
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:16px; }
    @media (max-width:1024px){ .stats-grid{ grid-template-columns:repeat(2,1fr);} }
    @media (max-width:640px){ .stats-grid{ grid-template-columns:1fr;} }
    .stat-card { background:white; border-radius:12px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,.1); border:1px solid #e5e7eb; }
    .stat-card-header{ display:flex; align-items:center; gap:12px; margin-bottom:12px; }
    .stat-icon{ width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; }
    .stat-icon.blue{ background:#dbeafe; color:#1e40af; }
    .stat-icon.green{ background:#d1fae5; color:#065f46; }
    .stat-icon.cyan{ background:#cffafe; color:#155e75; }
    .stat-icon.red{ background:#fee2e2; color:#991b1b; }
    .stat-card-title{ font-family:'Inter',sans-serif; font-size:12px; font-weight:500; color:#6b7280; text-transform:uppercase; letter-spacing:.5px; }
    .stat-card-value{ font-family:'Inter',sans-serif; font-size:32px; font-weight:700; color:#1f2937; line-height:1; }

    /* Tabs */
    .tabs { display:flex; gap:8px; margin:8px 0 16px; }
    .tab {
        padding:10px 16px; border:1px solid #e5e7eb; border-radius:8px;
        font-family:'Inter',sans-serif; font-size:14px; font-weight:600; color:#374151;
        background:white; text-decoration:none; transition:.2s;
    }
    .tab:hover { background:#f9fafb; }
    .tab.active { border-color:#3b82f6; color:#1d4ed8; box-shadow:0 0 0 3px rgba(59,130,246,.12); }

    .tab-pane { display:none; }
    .tab-pane.active { display:block; }

    /* Filter Section */
    .filter-section{ display:flex; gap:16px; margin-bottom:16px; flex-wrap:wrap; background:white; padding:20px; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.1); border:1px solid #e5e7eb; }
    .search-box{ flex:1; min-width:250px; position:relative; }
    .search-box input{ width:100%; padding:10px 16px 10px 44px; border:1px solid #e5e7eb; border-radius:8px; font-family:'Inter',sans-serif; font-size:14px; color:#374151; transition:.2s; }
    .search-box input:focus{ outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    .search-box i{ position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:16px; }

    /* ICON VIEW (grid) */
    .icon-groups{ display:flex; flex-direction:column; gap:20px; }
    .icon-group-title{ font-family:'Inter',sans-serif; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.5px; padding:6px 2px; }
    .icon-grid{ display:grid; grid-template-columns: repeat(auto-fill, minmax(140px,1fr)); gap:14px; }
    @media (min-width:1536px){ .icon-grid{ grid-template-columns: repeat(auto-fill, minmax(160px,1fr)); } }

    .icon-item{ display:flex; flex-direction:column; align-items:center; gap:10px; background:white; border:1px solid #e5e7eb; border-radius:12px; padding:12px; text-decoration:none; transition:.2s ease; box-shadow:0 1px 3px rgba(0,0,0,.06); }
    .icon-item:hover{ transform:translateY(-2px); border-color:#3b82f6; box-shadow:0 8px 16px rgba(0,0,0,.08); }

    /* Gambar ikon file (PNG) */
    .icon-preview{
        width:100%;
        aspect-ratio:1/1;
        border-radius:10px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:#ffffff;
        border:1px solid #e5e7eb;
    }
    .icon-img{
        width:72px;
        height:72px;
        object-fit:contain;
        image-rendering:auto;
    }

    .icon-label{ width:100%; font-family:'Inter',sans-serif; font-size:12px; color:#1f2937; text-align:center; line-height:1.35; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .muted{ color:#6b7280; font-size:11px; }

    /* Pagination */
    .pagination-wrapper{ display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding:16px; background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.1); border:1px solid #e5e7eb; }
    .pagination-info{ font-family:'Inter',sans-serif; font-size:14px; color:#6b7280; }
    .pagination-controls{ display:flex; align-items:center; gap:8px; }
    .page-item{ min-width:36px; height:36px; display:flex; align-items:center; justify-content:center; border:1px solid #e5e7eb; border-radius:6px; font-family:'Inter',sans-serif; font-size:14px; font-weight:500; color:#6b7280; cursor:pointer; transition:.2s; background:white; text-decoration:none; }
    .page-item:hover{ background:#f9fafb; border-color:#d1d5db; }
    .page-item.active{ background:#3b82f6; color:white; border-color:#3b82f6; }
    .page-item.disabled{ opacity:.5; cursor:not-allowed; pointer-events:none; }
    .show-per-page{ display:flex; align-items:center; gap:8px; font-family:'Inter',sans-serif; font-size:14px; color:#6b7280; }
    .show-per-page select{ padding:6px 32px 6px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:14px; color:#374151; cursor:pointer; appearance:none;
        background:white; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; }

    /* Empty State */
    .empty-state{ text-align:center; padding:80px 20px; background:white; border-radius:12px; border:2px dashed #e5e7eb; }
    .empty-state i{ font-size:80px; color:#d1d5db; margin-bottom:16px; }
    .empty-state h3{ font-family:'Inter',sans-serif; font-size:18px; font-weight:600; color:#4b5563; margin-bottom:8px; }
    .empty-state p{ font-family:'Inter',sans-serif; font-size:14px; color:#9ca3af; margin:0; }
</style>
@endpush

@section('content')
@php
    $activeTab = request('tab', 'files'); // files | links
    $qs = request()->query();
@endphp

<div class="dokumen-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>📄 Dokumen Asset</h1>
            <p>Kelola dan pantau dokumen sertifikat aset pemerintah</p>
        </div>
        <div class="actions-right">
            <a href="{{ route('admin.dokumen-asset.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.dokumen-asset.print', request()->query()) }}" target="_blank" class="btn btn-light">
                <i class="fas fa-print"></i> Print Summary
            </a>
            
        </div>
    </div>

    <!-- Flash -->
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    

    <!-- Tabs -->
    <div class="tabs">
        <a href="{{ route('admin.dokumen-asset.index', array_merge($qs, ['tab' => 'files'])) }}"
           class="tab {{ $activeTab === 'files' ? 'active' : '' }}">📁 File</a>
        <a href="{{ route('admin.dokumen-asset.index', array_merge($qs, ['tab' => 'links'])) }}"
           class="tab {{ $activeTab === 'links' ? 'active' : '' }}">🔗 Link</a>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari nama file / aset..." id="searchInput">
        </div>
        <div class="filter-dropdown">
            <select id="perPageSelect" onchange="changePerPage(this.value)">
                <option value="10" {{ (int)request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ (int)request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                <option value="40" {{ (int)request('per_page', 10) == 40 ? 'selected' : '' }}>40</option>
                <option value="60" {{ (int)request('per_page', 10) == 60 ? 'selected' : '' }}>60</option>
            </select>
        </div>
    </div>

    {{-- ===================== TAB: FILES ===================== --}}
    <div id="tab-files" class="tab-pane {{ $activeTab === 'files' ? 'active' : '' }}">
        @php
            use Carbon\Carbon;

            $fileItems = collect($files->items());
            $now = Carbon::now();
            $groupedFiles = collect([
                'Minggu Ini'     => collect(),
                'Bulan Ini'      => collect(),
                'Lebih Lama'     => collect(),
                'Tanpa Tanggal'  => collect(),
            ]);

            foreach ($fileItems as $d) {
                $dt = $d->updated_at ?? $d->created_at ?? $d->tgl_sertif ?? null;
                $dt = $dt ? Carbon::parse($dt) : null;

                if (!$dt) { $groupedFiles['Tanpa Tanggal']->push($d); continue; }
                if ($dt->isSameWeek($now)) { $groupedFiles['Minggu Ini']->push($d); }
                elseif ($dt->isSameMonth($now)) { $groupedFiles['Bulan Ini']->push($d); }
                else { $groupedFiles['Lebih Lama']->push($d); }
            }

            $orderFiles = ['Minggu Ini','Bulan Ini','Lebih Lama','Tanpa Tanggal'];
        @endphp

        @if($fileItems->count() > 0)
            <div class="icon-groups" id="iconGroupsFiles">
                @foreach($orderFiles as $label)
                    @php $items = $groupedFiles[$label] ?? collect(); @endphp
                    @if($items->isEmpty()) @continue @endif

                    <div class="icon-group" data-group="{{ $label }}">
                        <div class="icon-group-title">{{ $label }}</div>
                        <div class="icon-grid">
                            @foreach($items as $d)
                                @php
                                    $filename = basename($d->file_sertif);
                                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                    $searchText = strtolower(($d->asset->nama_asset ?? '').' '.($d->asset->kode_asset ?? '').' '.$filename);
                                @endphp

                                <a class="icon-item"
                                   href="{{ route('admin.dokumen-asset.view', $d) }}"
                                   title="{{ $filename }}"
                                   data-title="{{ $searchText }}">
                                    <div class="icon-preview">
                                        <img class="icon-img"
                                             src="{{ asset('assets/media/pdf.png') }}"
                                             alt="{{ strtoupper($ext) }} file"
                                             loading="lazy">
                                    </div>
                                    <div class="icon-label">{{ $filename }}</div>
                                    <div class="muted">{{ $d->asset->nama_asset ?? '-' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>Tidak ada file</h3>
                <p>Belum ada dokumen dengan file. Silakan tambah/unggah file sertifikat.</p>
            </div>
        @endif

        @if($files->hasPages() || $files->count() > 0)
            <div class="pagination-wrapper">
                <div class="show-per-page">
                    <span>Show</span>
                    <select onchange="changePerPage(this.value)">
                        <option value="10" {{ (int)request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ (int)request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                        <option value="40" {{ (int)request('per_page', 10) == 40 ? 'selected' : '' }}>40</option>
                        <option value="60" {{ (int)request('per_page', 10) == 60 ? 'selected' : '' }}>60</option>
                    </select>
                    <span>per page</span>
                </div>

                <div class="pagination-info">
                    <strong>{{ $files->firstItem() ?? 0 }}</strong> - <strong>{{ $files->lastItem() ?? 0 }}</strong> of <strong>{{ $files->total() }}</strong>
                </div>

                <div class="pagination-controls">
                    @if ($files->onFirstPage())
                        <span class="page-item disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $files->previousPageUrl() }}" class="page-item"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach ($files->getUrlRange(max(1, $files->currentPage() - 2), min($files->lastPage(), $files->currentPage() + 2)) as $page => $url)
                        @if ($page == $files->currentPage())
                            <span class="page-item active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($files->hasMorePages())
                        <a href="{{ $files->nextPageUrl() }}" class="page-item"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-item disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ===================== TAB: LINKS ===================== --}}
    <div id="tab-links" class="tab-pane {{ $activeTab === 'links' ? 'active' : '' }}">
        @php
            $linkItems = collect($links->items());
            $now = Carbon::now();
            $groupedLinks = collect([
                'Minggu Ini'     => collect(),
                'Bulan Ini'      => collect(),
                'Lebih Lama'     => collect(),
                'Tanpa Tanggal'  => collect(),
            ]);

            foreach ($linkItems as $d) {
                $dt = $d->updated_at ?? $d->created_at ?? $d->tgl_sertif ?? null;
                $dt = $dt ? Carbon::parse($dt) : null;

                if (!$dt) { $groupedLinks['Tanpa Tanggal']->push($d); continue; }
                if ($dt->isSameWeek($now)) { $groupedLinks['Minggu Ini']->push($d); }
                elseif ($dt->isSameMonth($now)) { $groupedLinks['Bulan Ini']->push($d); }
                else { $groupedLinks['Lebih Lama']->push($d); }
            }

            $orderLinks = ['Minggu Ini','Bulan Ini','Lebih Lama','Tanpa Tanggal'];
        @endphp

        @if($linkItems->count() > 0)
            <div class="icon-groups" id="iconGroupsLinks">
                @foreach($orderLinks as $label)
                    @php $items = $groupedLinks[$label] ?? collect(); @endphp
                    @if($items->isEmpty()) @continue @endif

                    <div class="icon-group" data-group="{{ $label }}">
                        <div class="icon-group-title">{{ $label }}</div>
                        <div class="icon-grid">
                            @foreach($items as $d)
                                @php
                                    $url = $d->link_sertif;
                                    $host = $url ? parse_url($url, PHP_URL_HOST) : null;
                                    $title = ($d->asset->nama_asset ?? '-') . ' • ' . ($host ?? 'Link');
                                    $searchText = strtolower(($d->asset->nama_asset ?? '').' '.($d->asset->kode_asset ?? '').' '.$url);
                                @endphp

                                <a class="icon-item"
                                   href="{{ $url }}"
                                   title="{{ $title }}"
                                   target="_blank" rel="noopener"
                                   data-title="{{ $searchText }}">
                                    <div class="icon-preview">
                                        <img class="icon-img"
                                             src="{{ asset('assets/media/link.png') }}"
                                             alt="LINK"
                                             loading="lazy">
                                    </div>
                                    <div class="icon-label">{{ $host ?? 'Link' }}</div>
                                    <div class="muted">{{ $d->asset->nama_asset ?? '-' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-link"></i>
                <h3>Tidak ada link</h3>
                <p>Belum ada dokumen dengan tautan sertifikat.</p>
            </div>
        @endif

        @if($links->hasPages() || $links->count() > 0)
            <div class="pagination-wrapper">
                <div class="show-per-page">
                    <span>Show</span>
                    <select onchange="changePerPage(this.value)">
                        <option value="10" {{ (int)request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ (int)request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                        <option value="40" {{ (int)request('per_page', 10) == 40 ? 'selected' : '' }}>40</option>
                        <option value="60" {{ (int)request('per_page', 10) == 60 ? 'selected' : '' }}>60</option>
                    </select>
                    <span>per page</span>
                </div>

                <div class="pagination-info">
                    <strong>{{ $links->firstItem() ?? 0 }}</strong> - <strong>{{ $links->lastItem() ?? 0 }}</strong> of <strong>{{ $links->total() }}</strong>
                </div>

                <div class="pagination-controls">
                    @if ($links->onFirstPage())
                        <span class="page-item disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $links->previousPageUrl() }}" class="page-item"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach ($links->getUrlRange(max(1, $links->currentPage() - 2), min($links->lastPage(), $links->currentPage() + 2)) as $page => $url)
                        @if ($page == $links->currentPage())
                            <span class="page-item active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($links->hasMorePages())
                        <a href="{{ $links->nextPageUrl() }}" class="page-item"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-item disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const searchEl = document.getElementById('searchInput');

// filter hanya pada TAB AKTIF
function filterIcons(){
    const q = (searchEl?.value || '').toLowerCase();
    const activePane = document.querySelector('.tab-pane.active');
    if(!activePane) return;

    const items = activePane.querySelectorAll('.icon-item');

    items.forEach(it => {
        const t = (it.getAttribute('data-title') || '').toLowerCase();
        const match = !q || t.includes(q);
        it.style.display = match ? '' : 'none';
    });

    // sembunyikan group yang kosong (di pane aktif saja)
    activePane.querySelectorAll('.icon-group').forEach(g => {
        const anyVisible = Array.from(g.querySelectorAll('.icon-item')).some(el => el.style.display !== 'none');
        g.style.display = anyVisible ? '' : 'none';
    });

    // empty-state dinamis
    const container = activePane.querySelector('.icon-groups');
    let emptyState = document.getElementById('iv-empty');
    const anyGroupVisible = activePane.querySelectorAll('.icon-group')
        ? Array.from(activePane.querySelectorAll('.icon-group')).some(g => g.style.display !== 'none')
        : false;

    if(!anyGroupVisible){
        if(!emptyState){
            emptyState = document.createElement('div');
            emptyState.id = 'iv-empty';
            emptyState.className = 'empty-state';
            emptyState.style.marginTop = '12px';
            emptyState.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>Tidak ada hasil</h3>
                <p>Coba ubah kata kunci pencarian</p>
            `;
            container?.after(emptyState);
        }
    }else if(emptyState){ emptyState.remove(); }
}

searchEl?.addEventListener('input', filterIcons);

// ubah per page (tetap mempertahankan tab aktif)
function changePerPage(value){
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}
</script>
@endpush
