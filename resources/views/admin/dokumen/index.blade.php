@extends('admin.layouts.app')

@push('styles')
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.9);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(15, 23, 42, .45);
            display: none;
            z-index: 9999;
            padding: 18px;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex !important;
        }

        .modal-overlay .modal {
            max-width: 500px;
            width: calc(100% - 36px);
            margin: auto;
            background: #ffffff !important;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
            z-index: 10000;
            flex-shrink: 0;
            min-height: 100px;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
            pointer-events: auto;
            height: auto;
        }

        .modal-overlay.show .modal {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
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
        @if ($currentFolder || count($breadcrumbs) > 0)
            <div class="breadcrumbs">
                <a href="{{ route('admin.dokumen.index', ['sort' => $sortBy]) }}">
                    <i class="ri-home-line"></i> Root
                </a>
                @foreach ($breadcrumbs as $crumb)
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
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari dokumen atau folder..."
                        value="{{ $search ?? '' }}" autocomplete="off">
                    <i class="ri-search-line search-icon"></i>
                </div>
                <span id="itemCount" style="font-size: 14px; color: #6b7280; font-weight: 600;">{{ $dokumens->count() }}
                    item</span>
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
                    <button type="button" class="view-toggle-btn active" id="btnGridView" onclick="switchView('grid')"
                        title="Grid View">
                        <i class="ri-grid-line"></i>
                    </button>
                    <button type="button" class="view-toggle-btn" id="btnListView" onclick="switchView('list')"
                        title="List View">
                        <i class="ri-list-check"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content wrapper for AJAX updates -->
        <div id="dokumenContent">
            <!-- Grid View -->
            <div class="dokumen-grid {{ $dokumens->count() > 0 ? '' : 'hidden' }}" id="gridView">
                @if ($dokumens->count() > 0)
                    @foreach ($dokumens as $dokumen)
                        <div class="dokumen-item {{ $dokumen->isFolder() ? 'folder' : '' }}"
                            onclick="{{ $dokumen->isFolder() ? "window.location.href='" . route('admin.dokumen.index', ['folder' => $dokumen->id, 'sort' => $sortBy]) . "'" : "openPreviewModal('" . $dokumen->url . "', '" . addslashes($dokumen->nama) . "', '" . ($dokumen->mime_type ?? '') . "')" }}">
                            <div class="dokumen-actions">
                                <button type="button" class="btn-icon edit"
                                    onclick="event.stopPropagation(); openRenameModal({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')"
                                    title="Rename">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                @if ($dokumen->isFile())
                                    <a href="{{ route('admin.dokumen.download', $dokumen) }}" class="btn-icon download"
                                        onclick="event.stopPropagation()" title="Download">
                                        <i class="ri-download-line"></i>
                                    </a>
                                @endif
                                <button type="button" class="btn-icon delete"
                                    onclick="event.stopPropagation(); deleteDokumen({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')"
                                    title="Hapus">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>

                            <div class="dokumen-icon">
                                @if ($dokumen->isFolder())
                                    <i class="ri-folder-fill"></i>
                                @else
                                    <i class="ri-file-line"></i>
                                @endif
                            </div>

                            <div class="dokumen-name">{{ $dokumen->nama }}</div>
                            <div class="dokumen-meta">
                                @if ($dokumen->isFile())
                                    {{ $dokumen->formatted_size }}
                                @else
                                    {{ $dokumen->children->count() }} item
                                @endif
                                @if (!empty($search) && $dokumen->parent)
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
                @if ($dokumens->count() > 0)
                    @foreach ($dokumens as $dokumen)
                        <div class="dokumen-list-item {{ $dokumen->isFolder() ? 'folder' : '' }}"
                            onclick="{{ $dokumen->isFolder() ? "window.location.href='" . route('admin.dokumen.index', ['folder' => $dokumen->id, 'sort' => $sortBy, 'q' => $search ?? '']) . "'" : "openPreviewModal('" . $dokumen->url . "', '" . addslashes($dokumen->nama) . "', '" . ($dokumen->mime_type ?? '') . "')" }}">
                            <div class="dokumen-list-icon">
                                @if ($dokumen->isFolder())
                                    <i class="ri-folder-fill"></i>
                                @else
                                    <i class="ri-file-line"></i>
                                @endif
                            </div>

                            <div class="dokumen-list-info">
                                <div class="dokumen-list-name">{{ $dokumen->nama }}</div>
                                <div class="dokumen-list-meta">
                                    @if ($dokumen->isFile())
                                        <span>{{ $dokumen->formatted_size }}</span>
                                        <span>{{ $dokumen->mime_type ?? '-' }}</span>
                                    @else
                                        <span>{{ $dokumen->children->count() }} item</span>
                                    @endif
                                    @if (!empty($search) && $dokumen->parent)
                                        <span style="color: #3B82F6; font-weight: 500;"><i class="ri-folder-line"></i>
                                            {{ $dokumen->parent->nama }}</span>
                                    @endif
                                    <span>Oleh: {{ $dokumen->user->name ?? 'System' }}</span>
                                    <span>{{ $dokumen->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>

                            <div class="dokumen-list-actions">
                                <button type="button" class="btn-icon edit"
                                    onclick="event.stopPropagation(); openRenameModal({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')"
                                    title="Rename">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                @if ($dokumen->isFile())
                                    <a href="{{ route('admin.dokumen.download', $dokumen) }}" class="btn-icon download"
                                        onclick="event.stopPropagation()" title="Download">
                                        <i class="ri-download-line"></i>
                                    </a>
                                @endif
                                <button type="button" class="btn-icon delete"
                                    onclick="event.stopPropagation(); deleteDokumen({{ $dokumen->id }}, '{{ addslashes($dokumen->nama) }}')"
                                    title="Hapus">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if ($dokumens->count() == 0)
                <div class="empty-state">
                    <i class="ri-folder-open-line"></i>
                    @if (!empty($search))
                        <p>Data tidak ditemukan</p>
                        <p style="font-size: 12px; margin-top: 8px;">Tidak ada dokumen atau folder yang cocok dengan kata
                            kunci "{{ $search }}"</p>
                    @else
                        <p>Folder ini kosong</p>
                        <p style="font-size: 12px; margin-top: 8px;">Upload file atau buat folder baru untuk memulai</p>
                    @endif
                </div>
            @endif

            <!-- Pagination -->
            @if ($dokumens->hasPages())
                <div style="display: flex; justify-content: center; padding: 24px 20px; border-top: 1px solid #F1F1F4;">
                    {{ $dokumens->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

        <!-- Loading indicator -->
        <div id="dokumenLoading" style="display: none; text-align: center; padding: 40px;">
            <div style="display: inline-block;">
                <div
                    style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--accent-2); border-radius: 50%; animation: spin 1s linear infinite;">
                </div>
                <p style="margin-top: 16px; color: #6b7280;">Memuat...</p>
            </div>
        </div>

        <style>
            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>

        <!-- FilePond Custom Styling -->
        <style>
            /* Minimal FilePond customization - let FilePond use its default styles */
            .filepond--root {
                font-family: inherit;
                margin-bottom: 0;
            }

            /* Customize colors to match design */
            .filepond--drop-label {
                min-height: 150px;
            }

            .filepond--panel-root {
                background-color: #f8f9fa;
            }

            /* Green accent color */
            .filepond--file-action-button {
                cursor: pointer;
            }

            .filepond--file-status-main {
                color: #22C55E;
            }

            /* Make items full width for better visibility */
            .filepond--item {
                width: 100%;
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
                @if ($currentFolder)
                    <input type="hidden" name="folder" value="{{ $currentFolder->id }}">
                @endif
                <div class="modal-body">
                    <div class="form-group">
                        <label class="label">Nama Folder <span style="color:#DC2626">*</span></label>
                        <input type="text" name="nama" class="input" placeholder="Masukkan nama folder" required
                            autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modalCreateFolder')">Batal</button>
                    <button type="submit" class="btn-save">Buat Folder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload File with FilePond -->
    <div id="modalUpload" class="modal-overlay">
        <div class="modal" style="max-width: 700px;">
            <div class="modal-header">
                <h3>Upload File</h3>
                <button type="button" class="btn-x" onclick="closeUploadModal()">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="label">Pilih File atau Drag & Drop <span style="color:#DC2626">*</span></label>
                    <input type="file" id="filepond" name="files[]" multiple data-max-file-size="10MB"
                        data-max-files="50">
                    <small style="color: #6b7280; font-size: 12px; margin-top: 8px; display: block;">
                        <i class="ri-information-line"></i>
                        Maksimal 10MB per file. Tipe file: doc, docx, xlsx, xls, ppt, pptx, jpg, jpeg, png, pdf.
                        <br>
                        <i class="ri-folder-upload-line"></i>
                        Anda bisa drag & drop multiple files atau folder sekaligus.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeUploadModal()">Batal</button>
                <button type="button" class="btn-save" id="btnProcessUpload" onclick="processUpload()">
                    <i class="ri-upload-cloud-line"></i> Upload Files
                </button>
            </div>
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

    <!-- Modal Preview -->
    <div id="modalPreview" class="modal-overlay" style="background: rgba(0, 0, 0, 0.9);">
        <div class="modal" style="max-width: 90vw; width: 90vw; height: 90vh; max-height: 90vh; margin: auto;">
            <div class="modal-header" style="background: #1e293b; color: #fff; border-bottom: 1px solid #334155;">
                <h3 id="previewTitle" style="color: #fff; font-size: 16px; font-weight: 600;">Preview Dokumen</h3>
                <button type="button" class="btn-x" onclick="closePreviewModal()"
                    style="background: #334155; border-color: #475569; color: #fff;">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body" id="previewBody"
                style="padding: 0; height: calc(90vh - 70px); overflow: hidden; background: #0f172a;">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <!-- Mammoth.js for Word preview -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>

    <!-- SheetJS for Excel preview -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

    <script>
        // ========== FilePond Configuration ==========
        // FilePond instance
        let pond = null;

        // Initialize FilePond when modal opens
        function initFilePond() {
            console.log('Initializing FilePond...');

            // Check if FilePond is loaded
            if (typeof FilePond === 'undefined') {
                console.error('FilePond library not loaded!');
                alert('FilePond library gagal dimuat. Silakan refresh halaman.');
                return;
            }

            // Register FilePond plugins
            try {
                FilePond.registerPlugin(
                    FilePondPluginFileValidateType,
                    FilePondPluginFileValidateSize,
                    FilePondPluginImagePreview
                );
                console.log('FilePond plugins registered');
            } catch (error) {
                console.error('Error registering FilePond plugins:', error);
            }

            const inputElement = document.getElementById('filepond');

            if (!inputElement) {
                console.error('FilePond input element not found!');
                return;
            }

            console.log('Input element found:', inputElement);

            // Destroy existing instance if any
            if (pond) {
                console.log('Destroying existing FilePond instance');
                pond.destroy();
                pond = null;
            }

            // Create FilePond instance
            try {
                pond = FilePond.create(inputElement, {
                    allowMultiple: true,
                    maxFiles: 50,
                    maxFileSize: '10MB',
                    instantUpload: false, // IMPORTANT: Don't auto-upload, wait for button click
                    acceptedFileTypes: [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                    labelIdle: `
                    <div style="width: 100%; text-align: center; padding: 20px;">
                        <p style="font-size: 14px; color: #4B5675; margin: 0;">
                            Drag & Drop your files or <span style="color: #22C55E; text-decoration: underline; cursor: pointer;">Browse</span>
                        </p>
                    </div>
                `,
                    labelFileLoading: 'Loading',
                    labelFileLoadError: 'Error during load',
                    labelFileProcessing: 'Uploading',
                    labelFileProcessingComplete: 'Upload complete',
                    labelFileProcessingAborted: 'Upload cancelled',
                    labelFileProcessingError: 'Error during upload',
                    labelTapToCancel: 'tap to cancel',
                    labelTapToRetry: 'tap to retry',
                    labelTapToUndo: 'tap to undo',
                    labelButtonRemoveItem: 'Remove',
                    labelButtonAbortItemLoad: 'Abort',
                    labelButtonRetryItemLoad: 'Retry',
                    labelButtonAbortItemProcessing: 'Cancel',
                    labelButtonUndoItemProcessing: 'Undo',
                    labelButtonRetryItemProcessing: 'Retry',
                    labelButtonProcessItem: 'Upload',
                    labelMaxFileSizeExceeded: 'File terlalu besar',
                    labelMaxFileSize: 'Maksimal ukuran file: {filesize}',
                    labelMaxTotalFileSizeExceeded: 'Total ukuran file terlalu besar',
                    labelMaxTotalFileSize: 'Maksimal total ukuran: {filesize}',
                    labelFileTypeNotAllowed: 'Tipe file tidak diperbolehkan',
                    fileValidateTypeLabelExpectedTypes: 'Tipe file yang diperbolehkan',
                    credits: false,
                    stylePanelLayout: 'compact',
                    styleButtonRemoveItemPosition: 'right',
                    styleLoadIndicatorPosition: 'right',
                    styleProgressIndicatorPosition: 'right',
                });

                console.log('FilePond initialized successfully:', pond);
            } catch (error) {
                console.error('Error creating FilePond instance:', error);
                alert('Gagal menginisialisasi FilePond. Error: ' + error.message);
            }
        }

        // Process upload
        function processUpload() {
            if (!pond) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'FilePond belum diinisialisasi',
                });
                return;
            }

            const files = pond.getFiles();

            if (files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada file',
                    text: 'Silakan pilih file yang akan diupload',
                });
                return;
            }

            // Get button and disable it
            const uploadBtn = document.getElementById('btnProcessUpload');
            const modalOverlay = document.getElementById('modalUpload');

            if (uploadBtn) {
                uploadBtn.disabled = true;
                uploadBtn.style.opacity = '0.6';
                uploadBtn.style.cursor = 'not-allowed';
                uploadBtn.innerHTML =
                    '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> Uploading...';
            }

            // Change cursor to wait
            if (modalOverlay) {
                modalOverlay.style.cursor = 'wait';
            }
            document.body.style.cursor = 'wait';

            // Show loading
            Swal.fire({
                title: 'Uploading...',
                html: `Mengupload <strong>0</strong> dari <strong>${files.length}</strong> file`,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Prepare FormData
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('parent_id', '{{ $currentFolder->id ?? '' }}');
            formData.append('sort', '{{ $sortBy }}');
            @if ($currentFolder)
                formData.append('folder', '{{ $currentFolder->id }}');
            @endif

            // Add all files
            files.forEach((fileItem, index) => {
                formData.append('files[]', fileItem.file);
            });

            // Upload via AJAX
            fetch('{{ route('admin.dokumen.file.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset cursor and button
                    document.body.style.cursor = 'default';
                    if (modalOverlay) modalOverlay.style.cursor = 'default';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || `${data.uploaded} file berhasil diupload`,
                            timer: 3000,
                            timerProgressBar: true,
                        }).then(() => {
                            // Reload page
                            window.location.reload();
                        });
                    } else {
                        // Re-enable button on error
                        if (uploadBtn) {
                            uploadBtn.disabled = false;
                            uploadBtn.style.opacity = '1';
                            uploadBtn.style.cursor = 'pointer';
                            uploadBtn.innerHTML = '<i class="ri-upload-cloud-line"></i> Upload Files';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Terjadi kesalahan saat upload',
                        });
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);

                    // Reset cursor and button
                    document.body.style.cursor = 'default';
                    if (modalOverlay) modalOverlay.style.cursor = 'default';

                    if (uploadBtn) {
                        uploadBtn.disabled = false;
                        uploadBtn.style.opacity = '1';
                        uploadBtn.style.cursor = 'pointer';
                        uploadBtn.innerHTML = '<i class="ri-upload-cloud-line"></i> Upload Files';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat upload. Silakan coba lagi.',
                    });
                });
        }

        // Open upload modal
        function openUploadModal() {
            console.log('Opening upload modal...');
            openModal('modalUpload');
            // Initialize FilePond after modal is shown
            setTimeout(() => {
                console.log('Calling initFilePond...');
                initFilePond();
            }, 100);
        }

        // Close upload modal
        function closeUploadModal() {
            // Destroy FilePond instance
            if (pond) {
                pond.destroy();
                pond = null;
            }
            closeModal('modalUpload');
        }

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
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                // Force browser to apply the display change
                void modal.offsetHeight;
                // Ensure modal box is visible
                const modalBox = modal.querySelector('.modal');
                if (modalBox) {
                    modalBox.style.display = 'block';
                    modalBox.style.visibility = 'visible';
                    modalBox.style.opacity = '1';
                }
            } else {
                console.error('Modal not found:', id);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        function openCreateFolderModal() {
            openModal('modalCreateFolder');
        }

        function openRenameModal(id, nama) {
            const renameInput = document.getElementById('renameNama');
            const renameForm = document.getElementById('formRename');
            if (renameInput && renameForm) {
                renameInput.value = nama;
                renameForm.action = '{{ route('admin.dokumen.update', ':id') }}'.replace(':id', id);
                openModal('modalRename');
            }
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
                    const url = new URL('{{ route('admin.dokumen.destroy', ':id') }}'.replace(':id', id),
                        window
                        .location.origin);
                    url.searchParams.set('sort', '{{ $sortBy }}');
                    form.action = url.toString();
                    form.innerHTML = '@csrf @method('DELETE')';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Initialize modal event listeners
        (function() {
            // Close modal on backdrop click
            function initModalListeners() {
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
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initModalListeners);
            } else {
                initModalListeners();
            }
        })();

        @if (session('success'))
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

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '<ul style="text-align:left; padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#22C55E',
                confirmButtonText: 'OK'
            });
        @endif

        // Preview Modal Functions
        function openPreviewModal(url, nama, mimeType) {
            const modal = document.getElementById('modalPreview');
            const previewTitle = document.getElementById('previewTitle');
            const previewBody = document.getElementById('previewBody');

            if (!modal || !previewTitle || !previewBody) {
                console.error('Preview modal elements not found');
                return;
            }

            // Set title
            previewTitle.textContent = nama;

            // Clear previous content
            previewBody.innerHTML = '';

            // Get file extension
            const extension = nama.split('.').pop().toLowerCase();

            // Determine file type and render accordingly
            const isPDF = mimeType && mimeType.includes('pdf');
            const isImage = mimeType && (mimeType.includes('image') || /\.(jpg|jpeg|png|gif|bmp|webp|svg)$/i.test(
                nama));
            const isVideo = mimeType && (mimeType.includes('video') || /\.(mp4|webm|ogg|mov)$/i.test(nama));
            const isAudio = mimeType && (mimeType.includes('audio') || /\.(mp3|wav|ogg|m4a)$/i.test(nama));
            const isOfficeDoc = isOfficeDocument(mimeType, extension);

            if (isPDF) {
                // PDF Preview
                previewBody.innerHTML = `
                    <iframe
                        src="${url}"
                        style="width: 100%; height: 100%; border: none; background: #fff;"
                        title="${nama}">
                    </iframe>
                `;
            } else if (isImage) {
                // Image Preview
                previewBody.innerHTML = `
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #0f172a;">
                        <img
                            src="${url}"
                            alt="${nama}"
                            style="max-width: 100%; max-height: 100%; object-fit: contain;"
                        />
                    </div>
                `;
            } else if (isVideo) {
                // Video Preview
                previewBody.innerHTML = `
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #0f172a;">
                        <video
                            controls
                            style="max-width: 100%; max-height: 100%;"
                            src="${url}">
                            Browser Anda tidak mendukung video player.
                        </video>
                    </div>
                `;
            } else if (isAudio) {
                // Audio Preview
                previewBody.innerHTML = `
                    <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0f172a; padding: 40px;">
                        <i class="ri-music-2-line" style="font-size: 80px; color: #22C55E; margin-bottom: 24px;"></i>
                        <h4 style="color: #fff; margin-bottom: 24px; text-align: center;">${nama}</h4>
                        <audio
                            controls
                            style="width: 100%; max-width: 500px;"
                            src="${url}">
                            Browser Anda tidak mendukung audio player.
                        </audio>
                    </div>
                `;
            } else if (isOfficeDoc) {
                // Office Documents (Word, Excel, PowerPoint)
                previewOfficeDocument(url, nama, extension, previewBody);
            } else {
                // Unsupported file type - show download option
                previewBody.innerHTML = `
                    <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0f172a; padding: 40px; text-align: center;">
                        <i class="ri-file-line" style="font-size: 80px; color: #64748b; margin-bottom: 24px;"></i>
                        <h4 style="color: #fff; margin-bottom: 12px;">Preview tidak tersedia</h4>
                        <p style="color: #94a3b8; margin-bottom: 24px;">File ini tidak dapat ditampilkan di browser.</p>
                        <a href="${url}" download class="btn btn-primary" style="text-decoration: none;">
                            <i class="ri-download-line"></i> Download File
                        </a>
                    </div>
                `;
            }

            // Show modal
            openModal('modalPreview');
        }

        /**
         * Check if file is Office document
         */
        function isOfficeDocument(mimeType, extension) {
            const officeMimeTypes = [
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'application/vnd.ms-excel', // .xls
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                'application/vnd.ms-powerpoint', // .ppt
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
            ];

            const officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

            return (mimeType && officeMimeTypes.includes(mimeType)) || officeExtensions.includes(extension);
        }

        /**
         * Preview Office Documents with Mammoth.js (Word) and SheetJS (Excel)
         */
        function previewOfficeDocument(url, nama, extension, previewBody) {
            // Show loading
            previewBody.innerHTML = `
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #fff;">
                    <div style="text-align: center;">
                        <div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #22C55E; margin-bottom: 16px;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p style="color: #64748b;">Memuat preview dokumen...</p>
                    </div>
                </div>
            `;

            // Fetch file as blob
            fetch(url)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => {
                    if (['doc', 'docx'].includes(extension)) {
                        // Preview Word document with Mammoth.js
                        previewWordDocument(arrayBuffer, nama, previewBody, url);
                    } else if (['xls', 'xlsx'].includes(extension)) {
                        // Preview Excel document with SheetJS
                        previewExcelDocument(arrayBuffer, nama, previewBody, url);
                    } else if (['ppt', 'pptx'].includes(extension)) {
                        // PowerPoint - show download (no preview library)
                        showDownloadOnly(url, nama, extension, previewBody);
                    }
                })
                .catch(error => {
                    console.error('Error loading document:', error);
                    previewBody.innerHTML = `
                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #fff; padding: 40px; text-align: center;">
                            <i class="ri-file-warning-line" style="font-size: 64px; color: #f59e0b; margin-bottom: 20px;"></i>
                            <h4 style="color: #1e293b; margin-bottom: 12px;">Gagal memuat preview</h4>
                            <p style="color: #64748b; margin-bottom: 24px; font-size: 14px;">
                                Terjadi kesalahan saat memuat dokumen.
                            </p>
                            <a href="${url}" download="${nama}" class="btn btn-primary" style="text-decoration: none;">
                                <i class="ri-download-line"></i> Download File
                            </a>
                        </div>
                    `;
                });
        }

        /**
         * Preview Word document with Mammoth.js
         */
        function previewWordDocument(arrayBuffer, nama, previewBody, url) {
            mammoth.convertToHtml({
                    arrayBuffer: arrayBuffer
                })
                .then(result => {
                    const html = result.value;
                    previewBody.innerHTML = `
                        <div style="width: 100%; height: 100%; overflow: auto; background: #fff;">
                            <div style="max-width: 800px; margin: 0 auto; padding: 40px; background: #fff;">
                                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                    <h4 style="margin: 0 0 8px 0; color: #1e293b;">${nama}</h4>
                                    <a href="${url}" download="${nama}" style="color: #22C55E; text-decoration: none; font-size: 14px;">
                                        <i class="ri-download-line"></i> Download File
                                    </a>
                                </div>
                                <div style="font-family: 'Times New Roman', serif; font-size: 14px; line-height: 1.6; color: #1e293b;">
                                    ${html}
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Mammoth error:', error);
                    showDownloadOnly(url, nama, 'docx', previewBody);
                });
        }

        /**
         * Preview Excel document with SheetJS
         */
        function previewExcelDocument(arrayBuffer, nama, previewBody, url) {
            try {
                const workbook = XLSX.read(arrayBuffer, {
                    type: 'array'
                });
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const html = XLSX.utils.sheet_to_html(worksheet);

                // Build sheet tabs
                let sheetTabs = '';
                workbook.SheetNames.forEach((sheetName, index) => {
                    const isActive = index === 0 ? 'active' : '';
                    sheetTabs += `
                        <button
                            class="sheet-tab ${isActive}"
                            onclick="switchSheet('${sheetName}')"
                            data-sheet="${sheetName}"
                            style="padding: 8px 16px; border: none; background: ${index === 0 ? '#22C55E' : '#f3f4f6'}; color: ${index === 0 ? '#fff' : '#64748b'}; cursor: pointer; border-radius: 4px 4px 0 0; margin-right: 4px; font-size: 13px;">
                            ${sheetName}
                        </button>
                    `;
                });

                previewBody.innerHTML = `
                    <div style="width: 100%; height: 100%; display: flex; flex-direction: column; background: #fff;">
                        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                            <h4 style="margin: 0 0 8px 0; color: #1e293b;">${nama}</h4>
                            <a href="${url}" download="${nama}" style="color: #22C55E; text-decoration: none; font-size: 14px;">
                                <i class="ri-download-line"></i> Download File
                            </a>
                        </div>
                        <div style="padding: 12px 20px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            ${sheetTabs}
                        </div>
                        <div id="sheetContent" style="flex: 1; overflow: auto; padding: 20px;">
                            ${html}
                        </div>
                    </div>
                `;

                // Store workbook for sheet switching
                window.currentWorkbook = workbook;

                // Style the table
                const table = previewBody.querySelector('table');
                if (table) {
                    table.style.borderCollapse = 'collapse';
                    table.style.width = '100%';
                    table.style.fontSize = '13px';

                    const cells = table.querySelectorAll('td, th');
                    cells.forEach(cell => {
                        cell.style.border = '1px solid #e5e7eb';
                        cell.style.padding = '8px 12px';
                    });

                    const headers = table.querySelectorAll('th');
                    headers.forEach(th => {
                        th.style.background = '#f3f4f6';
                        th.style.fontWeight = '600';
                        th.style.color = '#1e293b';
                    });
                }
            } catch (error) {
                console.error('SheetJS error:', error);
                showDownloadOnly(url, nama, 'xlsx', previewBody);
            }
        }

        /**
         * Switch Excel sheet
         */
        function switchSheet(sheetName) {
            if (!window.currentWorkbook) return;

            const worksheet = window.currentWorkbook.Sheets[sheetName];
            const html = XLSX.utils.sheet_to_html(worksheet);

            const sheetContent = document.getElementById('sheetContent');
            if (sheetContent) {
                sheetContent.innerHTML = html;

                // Style the table
                const table = sheetContent.querySelector('table');
                if (table) {
                    table.style.borderCollapse = 'collapse';
                    table.style.width = '100%';
                    table.style.fontSize = '13px';

                    const cells = table.querySelectorAll('td, th');
                    cells.forEach(cell => {
                        cell.style.border = '1px solid #e5e7eb';
                        cell.style.padding = '8px 12px';
                    });

                    const headers = table.querySelectorAll('th');
                    headers.forEach(th => {
                        th.style.background = '#f3f4f6';
                        th.style.fontWeight = '600';
                        th.style.color = '#1e293b';
                    });
                }
            }

            // Update active tab
            document.querySelectorAll('.sheet-tab').forEach(tab => {
                if (tab.dataset.sheet === sheetName) {
                    tab.style.background = '#22C55E';
                    tab.style.color = '#fff';
                } else {
                    tab.style.background = '#f3f4f6';
                    tab.style.color = '#64748b';
                }
            });
        }

        /**
         * Show download only (for unsupported formats)
         */
        function showDownloadOnly(url, nama, extension, previewBody) {
            let icon = 'ri-file-word-line';
            let color = '#2B579A';

            if (['xls', 'xlsx'].includes(extension)) {
                icon = 'ri-file-excel-line';
                color = '#217346';
            } else if (['ppt', 'pptx'].includes(extension)) {
                icon = 'ri-file-ppt-line';
                color = '#D24726';
            }

            previewBody.innerHTML = `
                <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0f172a; padding: 40px; text-align: center;">
                    <i class="${icon}" style="font-size: 80px; color: ${color}; margin-bottom: 24px;"></i>
                    <h4 style="color: #fff; margin-bottom: 12px;">Dokumen Office</h4>
                    <p style="color: #94a3b8; margin-bottom: 8px; font-size: 14px;">${nama}</p>
                    <p style="color: #64748b; margin-bottom: 24px; font-size: 12px;">
                        Klik tombol di bawah untuk download dan buka dokumen
                    </p>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;">
                        <a href="${url}" download="${nama}" class="btn btn-primary" style="text-decoration: none;">
                            <i class="ri-download-line"></i> Download File
                        </a>
                        <a href="${url}" target="_blank" class="btn btn-secondary" style="text-decoration: none;">
                            <i class="ri-external-link-line"></i> Buka di Tab Baru
                        </a>
                    </div>
                    <small style="color: #64748b; margin-top: 24px; font-size: 11px;">
                        <i class="ri-information-line"></i>
                        Tipe file: ${extension.toUpperCase()}
                    </small>
                </div>
            `;
        }

        /**
         * Try Google Docs Viewer as fallback
         */
        function tryGoogleViewer(googleViewerUrl, nama) {
            const iframe = document.getElementById('officeViewerFrame');
            const error = document.getElementById('officeViewerError');
            const loading = document.getElementById('officeViewerLoading');

            if (error) error.style.display = 'none';
            if (loading) {
                loading.style.display = 'block';
                loading.querySelector('p').textContent = 'Mencoba viewer alternatif...';
            }

            if (iframe) {
                iframe.src = googleViewerUrl;
            }

            // Auto-hide loading after 10 seconds
            setTimeout(() => {
                if (loading) loading.style.display = 'none';
            }, 10000);
        }

        function closePreviewModal() {
            closeModal('modalPreview');
            // Clear content to stop any media playback
            const previewBody = document.getElementById('previewBody');
            if (previewBody) {
                setTimeout(() => {
                    previewBody.innerHTML = '';
                }, 300);
            }
        }

        // Add ESC key handler specifically for preview modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const previewModal = document.getElementById('modalPreview');
                if (previewModal && previewModal.classList.contains('show')) {
                    closePreviewModal();
                }
            }
        });
    </script>
@endpush
