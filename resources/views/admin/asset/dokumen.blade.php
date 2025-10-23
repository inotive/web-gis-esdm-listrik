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
    .dokumen-container {
        padding: 24px;
    }

    /* Header Section */
    .page-header {
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-family: 'Inter', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 8px 0;
    }

    .page-header p {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon.blue { background: #dbeafe; color: #1e40af; }
    .stat-icon.green { background: #d1fae5; color: #065f46; }
    .stat-icon.yellow { background: #fef3c7; color: #92400e; }
    .stat-icon.red { background: #fee2e2; color: #991b1b; }

    .stat-card-title {
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card-value {
        font-family: 'Inter', sans-serif;
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    /* Filter Section */
    .filter-section {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 10px 16px 10px 44px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #374151;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
    }

    .filter-dropdown {
        min-width: 200px;
    }

    .filter-dropdown select {
        width: 100%;
        padding: 10px 40px 10px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #374151;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
    }

    .filter-dropdown select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Asset Grid */
    .asset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    /* Asset Card */
    .asset-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
    }

    .asset-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        border-color: #3b82f6;
    }

    .asset-card-header {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
    }

    .asset-card-title {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 4px 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    .asset-card-code {
        font-family: 'Courier New', monospace;
        font-size: 11px;
        color: #6b7280;
        background: #f9fafb;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    .asset-card-image {
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f3f4f6;
        position: relative;
    }

    .asset-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .asset-card:hover .asset-card-image img {
        transform: scale(1.05);
    }

    .asset-card-body {
        padding: 16px;
        flex: 1;
    }

    .asset-info-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 10px;
    }

    .asset-info-item:last-child {
        margin-bottom: 0;
    }

    .asset-info-icon {
        width: 16px;
        height: 16px;
        color: #6b7280;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .asset-info-text {
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        color: #4b5563;
        flex: 1;
        line-height: 1.5;
    }

    .asset-info-label {
        font-weight: 500;
        color: #6b7280;
    }

    /* Document Status Badge */
    .doc-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
    }

    .doc-status.digitalized {
        background: #d1fae5;
        color: #065f46;
    }

    .doc-status.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .doc-status.no-doc {
        background: #fee2e2;
        color: #991b1b;
    }

    .doc-status i {
        font-size: 10px;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 32px;
        padding: 24px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .pagination-info {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #6b7280;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-item {
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
        text-decoration: none;
    }

    .page-item:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .page-item.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .page-item.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .show-per-page {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #6b7280;
    }

    .show-per-page select {
        padding: 6px 32px 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        color: #374151;
        cursor: pointer;
        appearance: none;
        background: white;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        border: 2px dashed #e5e7eb;
    }

    .empty-state i {
        font-size: 80px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #9ca3af;
        margin: 0;
    }

    /* Image Placeholder */
    .image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 48px;
    }

    /* Truncate text */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="dokumen-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1>📄 Dokumen Asset</h1>
        <p>Kelola dan pantau dokumen sertifikat aset pemerintah</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon blue">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="stat-card-title">Total Dokumen</span>
            </div>
            <div class="stat-card-value">{{ $assets->total() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="stat-card-title">Terdigitalisasi</span>
            </div>
            <div class="stat-card-value">
                {{ $assets->filter(fn($a) => $a->dokumenUtama?->has_file)->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon yellow">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="stat-card-title">Belum Digital</span>
            </div>
            <div class="stat-card-value">
                {{ $assets->filter(fn($a) => $a->dokumenUtama && !$a->dokumenUtama->has_file)->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon red">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <span class="stat-card-title">Tanpa Dokumen</span>
            </div>
            <div class="stat-card-value">
                {{ $assets->filter(fn($a) => !$a->dokumenUtama)->count() }}
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari nama aset atau nomor sertifikat..." id="searchInput">
        </div>
        <div class="filter-dropdown">
            <select id="filterKategori">
                <option value="">Semua Kategori</option>
                <option value="digitalized">Sudah Digital</option>
                <option value="pending">Belum Digital</option>
                <option value="no-doc">Tanpa Dokumen</option>
            </select>
        </div>
        <div class="filter-dropdown">
            <select id="filterUnitKerja">
                <option value="">Semua Unit Kerja</option>
                @foreach($assets->pluck('unitKerja')->unique()->filter() as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Asset Grid -->
    <div class="asset-grid">
        @forelse ($assets as $asset)
            <div class="asset-card" 
                 data-kategori="{{ $asset->kategori?->name }}" 
                 data-unit="{{ $asset->unitKerja?->id }}"
                 data-status="{{ $asset->dokumenUtama ? ($asset->dokumenUtama->has_file ? 'digitalized' : 'pending') : 'no-doc' }}"
                 onclick="viewAssetDetail({{ $asset->id }})">
                
                <div class="asset-card-header">
                    <h3 class="asset-card-title">{{ $asset->nama_asset }}</h3>
                    <span class="asset-card-code">{{ $asset->kode_asset }}</span>
                </div>
                
                <div class="asset-card-image">
                    @if($asset->foto ?? false)
                        <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama_asset }}">
                    @else
                        <div class="image-placeholder">
                            <i class="fas fa-building"></i>
                        </div>
                    @endif
                </div>
                
                <div class="asset-card-body">
                    <div class="asset-info-item">
                        <i class="fas fa-tag asset-info-icon"></i>
                        <span class="asset-info-text">
                            <span class="asset-info-label">Kategori:</span><br>
                            {{ $asset->kategori->name ?? '-' }}
                        </span>
                    </div>
                    
                    <div class="asset-info-item">
                        <i class="fas fa-building asset-info-icon"></i>
                        <span class="asset-info-text">
                            <span class="asset-info-label">Unit Kerja:</span><br>
                            <span class="text-truncate-2">{{ $asset->unitKerja->nama_unit ?? '-' }}</span>
                        </span>
                    </div>
                    
                    @if($asset->dokumenUtama)
                        <div class="asset-info-item">
                            <i class="fas fa-file-alt asset-info-icon"></i>
                            <span class="asset-info-text">
                                <span class="asset-info-label">No. Sertifikat:</span><br>
                                {{ $asset->dokumenUtama->no_sertif ?? 'Belum ada nomor' }}
                            </span>
                        </div>
                        
                        <div class="asset-info-item">
                            <i class="fas fa-calendar asset-info-icon"></i>
                            <span class="asset-info-text">
                                <span class="asset-info-label">Tgl. Sertifikat:</span><br>
                                {{ $asset->dokumenUtama->tgl_sertif ? $asset->dokumenUtama->tgl_sertif->format('d M Y') : '-' }}
                            </span>
                        </div>

                        <div class="asset-info-item">
                            <i class="fas fa-info-circle asset-info-icon"></i>
                            <span class="asset-info-text">
                                <span class="asset-info-label">Status:</span><br>
                                <span class="text-truncate-2">{{ Str::limit($asset->dokumenUtama->sts_sertif ?? '-', 50) }}</span>
                            </span>
                        </div>

                        @if($asset->dokumenUtama->has_file)
                            <span class="doc-status digitalized">
                                <i class="fas fa-check-circle"></i>
                                Sudah Terdigitalisasi
                            </span>
                        @else
                            <span class="doc-status pending">
                                <i class="fas fa-clock"></i>
                                Belum Digital
                            </span>
                        @endif
                    @else
                        <div class="asset-info-item">
                            <i class="fas fa-exclamation-circle asset-info-icon" style="color: #ef4444;"></i>
                            <span class="asset-info-text" style="color: #ef4444;">
                                <strong>Belum ada dokumen</strong>
                            </span>
                        </div>
                        <span class="doc-status no-doc">
                            <i class="fas fa-times-circle"></i>
                            Tanpa Dokumen
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-folder-open"></i>
                <h3>Tidak ada data aset</h3>
                <p>Silakan import data aset terlebih dahulu</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($assets->hasPages() || $assets->count() > 0)
        <div class="pagination-wrapper">
            <div class="show-per-page">
                <span>Show</span>
                <select id="perPageSelect" onchange="changePerPage(this.value)">
                    <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                    <option value="20" {{ request('per_page') == 20 || !request('per_page') ? 'selected' : '' }}>20</option>
                    <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40</option>
                    <option value="60" {{ request('per_page') == 60 ? 'selected' : '' }}>60</option>
                </select>
                <span>per page</span>
            </div>

            <div class="pagination-info">
                <strong>{{ $assets->firstItem() ?? 0 }}</strong> - <strong>{{ $assets->lastItem() ?? 0 }}</strong> of <strong>{{ $assets->total() }}</strong>
            </div>

            <div class="pagination-controls">
                @if ($assets->onFirstPage())
                    <span class="page-item disabled">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $assets->previousPageUrl() }}" class="page-item">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($assets->getUrlRange(max(1, $assets->currentPage() - 2), min($assets->lastPage(), $assets->currentPage() + 2)) as $page => $url)
                    @if ($page == $assets->currentPage())
                        <span class="page-item active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($assets->hasMorePages())
                    <a href="{{ $assets->nextPageUrl() }}" class="page-item">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="page-item disabled">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterCards();
});

// Filter by kategori/status
document.getElementById('filterKategori').addEventListener('change', function(e) {
    filterCards();
});

// Filter by unit kerja
document.getElementById('filterUnitKerja').addEventListener('change', function(e) {
    filterCards();
});

function filterCards() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filterStatus = document.getElementById('filterKategori').value;
    const filterUnit = document.getElementById('filterUnitKerja').value;
    const cards = document.querySelectorAll('.asset-card');
    
    let visibleCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.asset-card-title').textContent.toLowerCase();
        const code = card.querySelector('.asset-card-code').textContent.toLowerCase();
        const status = card.dataset.status;
        const unit = card.dataset.unit;
        
        let matchSearch = title.includes(searchTerm) || code.includes(searchTerm);
        let matchStatus = !filterStatus || status === filterStatus;
        let matchUnit = !filterUnit || unit === filterUnit;
        
        if (matchSearch && matchStatus && matchUnit) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    const grid = document.querySelector('.asset-grid');
    const emptyState = grid.querySelector('.empty-state');
    
    if (visibleCount === 0 && !emptyState) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'empty-state';
        emptyDiv.style.gridColumn = '1 / -1';
        emptyDiv.innerHTML = `
            <i class="fas fa-search"></i>
            <h3>Tidak ada hasil</h3>
            <p>Coba ubah filter atau kata kunci pencarian</p>
        `;
        grid.appendChild(emptyDiv);
    } else if (visibleCount > 0 && emptyState) {
        emptyState.remove();
    }
}

// View asset detail
function viewAssetDetail(assetId) {
    window.location.href = `/admin/asset/${assetId}/edit`;
}

// Change per page
function changePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}
</script>
@endpush