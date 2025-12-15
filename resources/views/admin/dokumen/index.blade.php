@extends('admin.layouts.app')

@section('title', 'Manajemen Dokumen')

@push('styles')
<style>
  .page-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
  }

  .page-actions {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid var(--line);
    cursor: pointer;
    background: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
  }

  .btn-primary {
    background: var(--accent-2);
    color: #fff;
    border: none;
  }

  .btn-secondary {
    background: #6b7280;
    color: #fff;
    border: none;
  }

  .card {
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: var(--shadow-1);
    padding: 24px;
    background: #fff;
  }

  .breadcrumbs {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
  }

  .breadcrumbs a {
    color: var(--accent-2);
    text-decoration: none;
    font-size: 14px;
  }

  .breadcrumbs span {
    color: #6b7280;
    font-size: 14px;
  }

  .dokumen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .dokumen-item {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
  }

  .dokumen-item:hover {
    border-color: var(--accent-2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
  }

  .dokumen-icon {
    font-size: 48px;
    margin-bottom: 12px;
    color: var(--accent-2);
  }

  .dokumen-item.folder .dokumen-icon {
    color: #F59E0B;
  }

  .dokumen-name {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    line-height: 1.4;
  }

  .dokumen-meta {
    font-size: 12px;
    color: #6b7280;
  }

  .dokumen-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s;
  }

  .dokumen-item:hover .dokumen-actions {
    opacity: 1;
  }

  .btn-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: rgba(255,255,255,0.9);
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
  }

  .btn-icon:hover {
    background: #fff;
    transform: scale(1.1);
  }

  .btn-icon.edit {
    color: #DFA000;
  }

  .btn-icon.delete {
    color: #ef4444;
  }

  .btn-icon.download {
    color: var(--accent-2);
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
  }

  .empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  /* Toolbar */
  .toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
  }

  .toolbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
  }

  .search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
  }

  .search-input {
    width: 100%;
    height: 36px;
    padding: 0 36px 0 12px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    background: #fff;
    font-size: 14px;
    color: #111827;
    outline: none;
    transition: all 0.2s;
  }

  .search-input:focus {
    border-color: var(--accent-2);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
  }

  .search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    pointer-events: none;
  }

  .toolbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .view-toggle {
    display: flex;
    gap: 4px;
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 4px;
  }

  .view-toggle-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    border-radius: 6px;
    cursor: pointer;
    color: #6b7280;
    transition: all 0.2s;
  }

  .view-toggle-btn.active {
    background: var(--accent-2);
    color: #fff;
  }

  .view-toggle-btn:hover {
    background: #F3F4F6;
  }

  .view-toggle-btn.active:hover {
    background: var(--accent-2);
  }

  .sort-select {
    height: 36px;
    padding: 0 12px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    background: #fff;
    font-size: 14px;
    color: #111827;
    cursor: pointer;
    outline: none;
  }

  .sort-select:focus {
    border-color: var(--accent-2);
  }

  /* List View */
  .dokumen-list {
    display: none;
  }

  .dokumen-list.active {
    display: block;
  }

  .dokumen-list-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    margin-bottom: 8px;
    background: #fff;
    transition: all 0.2s;
    cursor: pointer;
  }

  .dokumen-list-item:hover {
    border-color: var(--accent-2);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }

  .dokumen-list-item.folder {
    background: #FEF3C7;
  }

  .dokumen-list-icon {
    font-size: 32px;
    color: var(--accent-2);
    width: 40px;
    text-align: center;
  }

  .dokumen-list-item.folder .dokumen-list-icon {
    color: #F59E0B;
  }

  .dokumen-list-info {
    flex: 1;
    min-width: 0;
  }

  .dokumen-list-name {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
    word-break: break-word;
  }

  .dokumen-list-meta {
    font-size: 12px;
    color: #6b7280;
    display: flex;
    gap: 12px;
  }

  .dokumen-list-actions {
    display: flex;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.2s;
  }

  .dokumen-list-item:hover .dokumen-list-actions {
    opacity: 1;
  }

  .dokumen-grid.hidden {
    display: none;
  }

  /* Modal Styles */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.45);
    display: none;
    z-index: 1000;
    padding: 18px;
    overflow: auto;
  }

  .modal-overlay.show {
    display: block;
  }

  .modal {
    max-width: 500px;
    margin: 20px auto;
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: var(--shadow-2);
    overflow: hidden;
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    border-bottom: 1px solid var(--line);
  }

  .modal-header h3 {
    margin: 0;
    font-weight: 800;
    font-size: 20px;
  }

  .btn-x {
    width: 36px;
    height: 36px;
    display: grid;
    place-items: center;
    border: 1px solid #E2E8F0;
    background: #fff;
    border-radius: 10px;
    cursor: pointer;
  }

  .modal-body {
    padding: 20px;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .label {
    display: block;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
    font-weight: 600;
  }

  .input {
    width: 100%;
    height: 44px;
    padding: 0 12px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #FCFCFD;
    outline: none;
    font: inherit;
    color: #111827;
  }

  .modal-footer {
    padding: 14px 20px 18px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    border-top: 1px solid var(--line);
  }

  .btn-cancel {
    height: 44px;
    padding: 0 20px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    font-weight: 600;
    color: #64748B;
    background: #fff;
    cursor: pointer;
  }

  .btn-save {
    height: 44px;
    padding: 0 20px;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    background: var(--accent-2);
    cursor: pointer;
  }
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">Manajemen Dokumen</div>
  </div>
  <div class="page-actions">
    <button type="button" class="btn btn-secondary" onclick="openCreateFolderModal()">
      <i class="ri-folder-add-line"></i> Buat Folder
    </button>
    <button type="button" class="btn btn-primary" onclick="openUploadModal()">
      <i class="ri-upload-cloud-2-line"></i> Upload File
    </button>
  </div>
</div>

<section class="card">
  @if($currentFolder || count($breadcrumbs) > 0)
    <div class="breadcrumbs">
      <a href="{{ route('admin.dokumen.index', ['sort' => $sortBy]) }}">
        <i class="ri-home-line"></i> Root
      </a>
      @foreach($breadcrumbs as $crumb)
        <span>/</span>
        <a href="{{ route('admin.dokumen.index', ['folder' => $crumb->id, 'sort' => $sortBy]) }}">
          {{ $crumb->nama }}
        </a>
      @endforeach
    </div>
  @endif

  <!-- Toolbar -->
  <div class="toolbar">
    <div class="toolbar-left">
      <div class="search-box">
        <input type="text"
               id="searchInput"
               class="search-input"
               placeholder="Cari dokumen atau folder..."
               value="{{ $search ?? '' }}"
               autocomplete="off">
        <i class="ri-search-line search-icon"></i>
      </div>
      <span id="itemCount" style="font-size: 14px; color: #6b7280; font-weight: 600;">{{ $dokumens->count() }} item</span>
    </div>
    <div class="toolbar-right">
      <select class="sort-select" id="sortSelect" onchange="applySort()">
        <option value="name_asc" {{ $sortBy == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
        <option value="name_desc" {{ $sortBy == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
        <option value="date_desc" {{ $sortBy == 'date_desc' ? 'selected' : '' }}>Terbaru</option>
        <option value="date_asc" {{ $sortBy == 'date_asc' ? 'selected' : '' }}>Terlama</option>
        <option value="size_desc" {{ $sortBy == 'size_desc' ? 'selected' : '' }}>Ukuran Terbesar</option>
        <option value="size_asc" {{ $sortBy == 'size_asc' ? 'selected' : '' }}>Ukuran Terkecil</option>
      </select>
      <div class="view-toggle">
        <button type="button" class="view-toggle-btn active" id="btnGridView" onclick="switchView('grid')" title="Grid View">
          <i class="ri-grid-line"></i>
        </button>
        <button type="button" class="view-toggle-btn" id="btnListView" onclick="switchView('list')" title="List View">
          <i class="ri-list-check"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Content wrapper for AJAX updates -->
  <div id="dokumenContent">
    <!-- Grid View -->
    <div class="dokumen-grid {{ $dokumens->count() > 0 ? '' : 'hidden' }}" id="gridView">
    @if($dokumens->count() > 0)
      @foreach($dokumens as $dokumen)
        <div class="dokumen-item {{ $dokumen->isFolder() ? 'folder' : '' }}"
             onclick="{{ $dokumen->isFolder() ? "window.location.href='" . route('admin.dokumen.index', ['folder' => $dokumen->id, 'sort' => $sortBy]) . "'" : "window.open('" . $dokumen->url . "', '_blank')" }}">
          <div class="dokumen-actions">
            <button type="button" class="btn-icon edit" onclick="event.stopPropagation(); openRenameModal({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')" title="Rename">
              <i class="ri-pencil-line"></i>
            </button>
            @if($dokumen->isFile())
              <a href="{{ route('admin.dokumen.download', $dokumen) }}" class="btn-icon download" onclick="event.stopPropagation()" title="Download">
                <i class="ri-download-line"></i>
              </a>
            @endif
            <button type="button" class="btn-icon delete" onclick="event.stopPropagation(); deleteDokumen({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')" title="Hapus">
              <i class="ri-delete-bin-line"></i>
            </button>
          </div>

          <div class="dokumen-icon">
            @if($dokumen->isFolder())
              <i class="ri-folder-fill"></i>
            @else
              <i class="ri-file-line"></i>
            @endif
          </div>

          <div class="dokumen-name">{{ $dokumen->nama }}</div>
          <div class="dokumen-meta">
            @if($dokumen->isFile())
              {{ $dokumen->formatted_size }}
            @else
              {{ $dokumen->children->count() }} item
            @endif
            @if(!empty($search) && $dokumen->parent)
              <div style="font-size: 11px; color: #3B82F6; margin-top: 4px; font-weight: 500;">
                <i class="ri-folder-line"></i> {{ $dokumen->parent->nama }}
              </div>
            @endif
          </div>
        </div>
      @endforeach
    @endif
    </div>

    <!-- List View -->
    <div class="dokumen-list {{ $dokumens->count() > 0 ? '' : 'hidden' }}" id="listView">
    @if($dokumens->count() > 0)
      @foreach($dokumens as $dokumen)
        <div class="dokumen-list-item {{ $dokumen->isFolder() ? 'folder' : '' }}"
             onclick="{{ $dokumen->isFolder() ? "window.location.href='" . route('admin.dokumen.index', ['folder' => $dokumen->id, 'sort' => $sortBy, 'q' => $search ?? '']) . "'" : "window.open('" . $dokumen->url . "', '_blank')" }}">
          <div class="dokumen-list-icon">
            @if($dokumen->isFolder())
              <i class="ri-folder-fill"></i>
            @else
              <i class="ri-file-line"></i>
            @endif
          </div>

          <div class="dokumen-list-info">
            <div class="dokumen-list-name">{{ $dokumen->nama }}</div>
            <div class="dokumen-list-meta">
              @if($dokumen->isFile())
                <span>{{ $dokumen->formatted_size }}</span>
                <span>{{ $dokumen->mime_type ?? '-' }}</span>
              @else
                <span>{{ $dokumen->children->count() }} item</span>
              @endif
              @if(!empty($search) && $dokumen->parent)
                <span style="color: #3B82F6; font-weight: 500;"><i class="ri-folder-line"></i> {{ $dokumen->parent->nama }}</span>
              @endif
              <span>Oleh: {{ $dokumen->user->name ?? 'System' }}</span>
              <span>{{ $dokumen->created_at->format('d M Y H:i') }}</span>
            </div>
          </div>

          <div class="dokumen-list-actions">
            <button type="button" class="btn-icon edit" onclick="event.stopPropagation(); openRenameModal({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')" title="Rename">
              <i class="ri-pencil-line"></i>
            </button>
            @if($dokumen->isFile())
              <a href="{{ route('admin.dokumen.download', $dokumen) }}" class="btn-icon download" onclick="event.stopPropagation()" title="Download">
                <i class="ri-download-line"></i>
              </a>
            @endif
            <button type="button" class="btn-icon delete" onclick="event.stopPropagation(); deleteDokumen({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')" title="Hapus">
              <i class="ri-delete-bin-line"></i>
            </button>
          </div>
        </div>
      @endforeach
    @endif
    </div>

    @if($dokumens->count() == 0)
    <div class="empty-state">
      <i class="ri-folder-open-line"></i>
      @if(!empty($search))
        <p>Data tidak ditemukan</p>
        <p style="font-size: 12px; margin-top: 8px;">Tidak ada dokumen atau folder yang cocok dengan kata kunci "{{ $search }}"</p>
      @else
        <p>Folder ini kosong</p>
        <p style="font-size: 12px; margin-top: 8px;">Upload file atau buat folder baru untuk memulai</p>
      @endif
    </div>
  @endif
  </div>

  <!-- Loading indicator -->
  <div id="dokumenLoading" style="display: none; text-align: center; padding: 40px;">
    <div style="display: inline-block;">
      <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--accent-2); border-radius: 50%; animation: spin 1s linear infinite;"></div>
      <p style="margin-top: 16px; color: #6b7280;">Memuat...</p>
    </div>
  </div>

  <style>
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</section>

<!-- Modal Create Folder -->
<div id="modalCreateFolder" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Buat Folder Baru</h3>
      <button type="button" class="btn-x" onclick="closeModal('modalCreateFolder')">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.dokumen.folder.store') }}">
      @csrf
      <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
      <input type="hidden" name="sort" value="{{ $sortBy }}">
      @if($currentFolder)
        <input type="hidden" name="folder" value="{{ $currentFolder->id }}">
      @endif
      <div class="modal-body">
        <div class="form-group">
          <label class="label">Nama Folder <span style="color:#DC2626">*</span></label>
          <input type="text" name="nama" class="input" placeholder="Masukkan nama folder" required autofocus>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal('modalCreateFolder')">Batal</button>
        <button type="submit" class="btn-save">Buat Folder</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Upload File -->
<div id="modalUpload" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Upload File</h3>
      <button type="button" class="btn-x" onclick="closeModal('modalUpload')">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.dokumen.file.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
      <input type="hidden" name="sort" value="{{ $sortBy }}">
      @if($currentFolder)
        <input type="hidden" name="folder" value="{{ $currentFolder->id }}">
      @endif
      <div class="modal-body">
        <div class="form-group">
          <label class="label">Pilih File <span style="color:#DC2626">*</span></label>
          <input type="file" name="files[]" class="input" multiple required>
          <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">
            Maksimal 10MB per file. Bisa upload beberapa file sekaligus.
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal('modalUpload')">Batal</button>
        <button type="submit" class="btn-save">Upload</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Rename -->
<div id="modalRename" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Rename</h3>
      <button type="button" class="btn-x" onclick="closeModal('modalRename')">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <form id="formRename" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="sort" value="{{ $sortBy }}">
      <div class="modal-body">
        <div class="form-group">
          <label class="label">Nama <span style="color:#DC2626">*</span></label>
          <input type="text" name="nama" id="renameNama" class="input" required autofocus>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal('modalRename')">Batal</button>
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
// View management
let currentView = localStorage.getItem('dokumenView') || 'grid';

function switchView(view) {
  currentView = view;
  localStorage.setItem('dokumenView', view);

  const gridView = document.getElementById('gridView');
  const listView = document.getElementById('listView');
  const btnGrid = document.getElementById('btnGridView');
  const btnList = document.getElementById('btnListView');

  // Check if all elements exist before accessing classList
  if (!gridView || !listView || !btnGrid || !btnList) {
    return; // Exit early if elements don't exist
  }

  if (view === 'grid') {
    gridView.classList.remove('hidden');
    listView.classList.remove('active');
    btnGrid.classList.add('active');
    btnList.classList.remove('active');
  } else {
    gridView.classList.add('hidden');
    listView.classList.add('active');
    btnGrid.classList.remove('active');
    btnList.classList.add('active');
  }
}

function applySort() {
  const sortSelect = document.getElementById('sortSelect');
  const sortValue = sortSelect.value;
  const url = new URL(window.location.href);
  url.searchParams.set('sort', sortValue);
  window.location.href = url.toString();
}

// Initialize view on page load
document.addEventListener('DOMContentLoaded', function() {
  switchView(currentView);

  // Search functionality with AJAX (only update content, not search box)
  let searchTimeout;
  let isSearching = false; // Flag to prevent multiple simultaneous requests
  let pendingSearchValue = null; // Store pending search value
  const searchInput = document.getElementById('searchInput');
  const dokumenContent = document.getElementById('dokumenContent');
  const dokumenLoading = document.getElementById('dokumenLoading');

  // Function to perform search
  function performSearch(searchValue) {
    const url = new URL(window.location.href);

    // Preserve folder and sort parameters
    const folder = url.searchParams.get('folder');
    const sort = url.searchParams.get('sort') || 'name_asc';

    // Build request URL
    const requestUrl = new URL(url.pathname, window.location.origin);
    if (folder) requestUrl.searchParams.set('folder', folder);
    if (sort) requestUrl.searchParams.set('sort', sort);
    if (searchValue) {
      requestUrl.searchParams.set('q', searchValue);
    } else {
      requestUrl.searchParams.delete('q');
    }

    // Update URL without reload (for bookmarking)
    const newUrl = requestUrl.toString();
    window.history.pushState({}, '', newUrl);

    // Set searching flag
    isSearching = true;

    // Show loading indicator
    if (dokumenContent) dokumenContent.style.display = 'none';
    if (dokumenLoading) dokumenLoading.style.display = 'block';

    // Make AJAX request
    fetch(requestUrl.toString(), {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html',
      }
    })
    .then(response => response.text())
    .then(html => {
      // Parse the response HTML
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const newContent = doc.getElementById('dokumenContent');
      const newItemCount = doc.getElementById('itemCount');

      if (newContent && dokumenContent) {
        // Update content
        dokumenContent.innerHTML = newContent.innerHTML;
        dokumenContent.style.display = 'block';

        // Update item count
        if (newItemCount) {
          const itemCountEl = document.getElementById('itemCount');
          if (itemCountEl) {
            itemCountEl.textContent = newItemCount.textContent;
          }
        }

        // Reinitialize view after DOM update
        // Use setTimeout to ensure DOM is fully updated
        setTimeout(() => {
          switchView(currentView);
        }, 0);
      }
    })
    .catch(error => {
      console.error('Search error:', error);
      // Fallback to full page reload on error
      window.location.href = newUrl;
    })
    .finally(() => {
      // Reset searching flag and hide loading indicator
      isSearching = false;
      if (dokumenLoading) dokumenLoading.style.display = 'none';

      // Check if there's a pending search
      if (pendingSearchValue !== null) {
        const pending = pendingSearchValue;
        pendingSearchValue = null;
        performSearch(pending);
      }
    });
  }

  if (searchInput) {
    let lastSearchValue = searchInput.value.trim();

    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      const searchValue = this.value.trim();

      // Only trigger search if value changed
      if (searchValue === lastSearchValue) {
        return;
      }

      searchTimeout = setTimeout(() => {
        // If currently searching, queue this search
        if (isSearching) {
          pendingSearchValue = searchValue;
          return;
        }

        // Update last search value immediately to prevent duplicate requests
        lastSearchValue = searchValue;

        // Perform search
        performSearch(searchValue);
      }, 500); // 500ms delay for auto-search
    });
  }
});

function openModal(id) {
  document.getElementById(id).classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeModal(id) {
  document.getElementById(id).classList.remove('show');
  document.body.style.overflow = '';
}

function openCreateFolderModal() {
  openModal('modalCreateFolder');
}

function openUploadModal() {
  openModal('modalUpload');
}

function openRenameModal(id, nama) {
  document.getElementById('renameNama').value = nama;
  document.getElementById('formRename').action = '{{ route("admin.dokumen.update", ":id") }}'.replace(':id', id);
  openModal('modalRename');
}

function deleteDokumen(id, nama) {
  Swal.fire({
    title: 'Konfirmasi Hapus',
    html: `Apakah Anda yakin ingin menghapus <strong>${nama}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#94a3b8',
    confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
    cancelButtonText: 'Batal',
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      const url = new URL('{{ route("admin.dokumen.destroy", ":id") }}'.replace(':id', id), window.location.origin);
      url.searchParams.set('sort', '{{ $sortBy }}');
      form.action = url.toString();
      form.innerHTML = '@csrf @method("DELETE")';
      document.body.appendChild(form);
      form.submit();
    }
  });
}

// Close modal on backdrop click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) {
      closeModal(this.id);
    }
  });
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.show').forEach(modal => {
      closeModal(modal.id);
    });
  }
});

@if(session('success'))
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false,
    toast: true,
    position: 'top-end',
  });
@endif

@if($errors->any())
  Swal.fire({
    icon: 'error',
    title: 'Terjadi Kesalahan!',
    html: '<ul style="text-align:left; padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
    confirmButtonColor: '#22C55E',
    confirmButtonText: 'OK'
  });
@endif
</script>
@endpush
