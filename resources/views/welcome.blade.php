@extends('landing.layout.app')

@section('title', 'ASET PEMPROV KALTIM')

@push('styles')
<style>
  /* Pastikan kanvas peta siap menampung panel sejak awal (hindari "lompat" posisi) */
  #viewDiv{
    position: relative;
    width: 100%;
    min-height: calc(100vh - 100px);
  }

  /* ===== Filter Panel (kiri bawah) ===== */
  .filter-panel{
    position: fixed;
    left: 12px !important;
    right: auto !important;
    bottom: 12px !important;
    z-index: 50;
    width:340px; max-width:88vw;
    background:#fff; border-radius:16px;
    box-shadow:0 8px 24px rgba(0,0,0,.15);
    padding:16px; max-height:65vh; overflow-y:auto;
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial;
  }
  .filter-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #e5e7eb}
  .filter-title{font-weight:700;font-size:16px;color:#111827;display:flex;align-items:center;gap:8px}
  .filter-toggle{background:#f1f5f9;border:none;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:16px;transition:all .2s}
  .filter-toggle:hover{background:#e2e8f0}
  .filter-group{margin-bottom:12px}
  .filter-label{font-size:12px;font-weight:600;color:#64748b;margin-bottom:6px;display:block}
  .filter-select{width:100%;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fff;color:#111827;transition:all .2s;cursor:pointer}
  .filter-select:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
  .filter-select:hover{border-color:#cbd5e1}
  .filter-select:disabled{background:#f1f5f9;cursor:not-allowed;opacity:0.6}
  
  /* Filter Text Input */
  .filter-input{width:100%;padding:10px 32px 10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fff;color:#111827;transition:all .2s}
  .filter-input:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
  .filter-input::placeholder{color:#94a3b8}
  
  /* Search Clear Button */
  #filterSearchClear {
    font-size: 14px;
    color: #94a3b8;
    transition: all 0.2s;
  }
  #filterSearchClear:hover {
    color: #ef4444;
  }
  
  .filter-actions{display:flex;gap:8px;margin-top:16px;padding-top:12px;border-top:2px solid #e5e7eb}
  .btn-filter{flex:1;padding:10px 16px;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s}
  .btn-apply{background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);color:#fff}
  .btn-apply:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,.3)}
  .btn-reset{background:#f8fafc;color:#64748b;border:1.5px solid #e5e7eb}
  .btn-reset:hover{background:#f1f5f9;color:#475569}
  .filter-count{background:#dbeafe;color:#1e40af;padding:8px 12px;border-radius:8px;font-size:12px;font-weight:600;text-align:center;margin-top:12px}
  .filter-panel.collapsed .filter-content{display:none}
  .filter-panel.collapsed{width:auto;padding:12px}
  
  /* Warna untuk Kategori Tanah - dengan color box */
  .kat-option {
    display: flex !important;
    align-items: center;
    gap: 8px;
  }
  .kat-color-box {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    flex-shrink: 0;
  }
  .kat-k1 .kat-color-box { background-color: #22c55e; }
  .kat-k2 .kat-color-box { background-color: #eab308; }
  .kat-k3 .kat-color-box { background-color: #ef4444; }
  .kat-bersertifikat .kat-color-box { background-color: #3b82f6; }
  
  /* Custom Searchable Select Styling */
  .searchable-select-wrapper {
    position: relative;
  }
  .searchable-select-wrapper input[type="text"] {
    width: 100%;
    padding: 10px 32px 10px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    background: #fff;
    color: #111827;
    cursor: pointer;
  }
  .searchable-select-wrapper input[type="text"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
  }
  .searchable-select-wrapper input[type="text"]:disabled {
    background: #f1f5f9;
    cursor: not-allowed;
    opacity: 0.6;
    color: #94a3b8;
  }
  .searchable-select-wrapper .dropdown-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    font-size: 10px;
    color: #64748b;
  }
  .searchable-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    max-height: 250px;
    overflow-y: auto;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
    z-index: 1000;
    display: none;
  }
  .searchable-dropdown.active {
    display: block;
  }
  .searchable-dropdown-item {
    padding: 10px 12px;
    font-size: 13px;
    cursor: pointer;
    transition: background .15s;
  }
  .searchable-dropdown-item:hover {
    background: #f1f5f9;
  }
  .searchable-dropdown-item.selected {
    background: #dbeafe;
    color: #1e40af;
    font-weight: 600;
  }
  .searchable-dropdown-item.kat-option {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .searchable-dropdown-empty {
    padding: 10px 12px;
    font-size: 13px;
    color: #94a3b8;
    text-align: center;
  }
  
  /* Search Suggestion Item Styling */
  .search-suggestion-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .search-suggestion-main {
    font-weight: 600;
    color: #111827;
  }
  .search-suggestion-meta {
    font-size: 11px;
    color: #64748b;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .search-suggestion-tag {
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    color: #475569;
  }
  
  @media (max-width: 768px){ .filter-panel{width:280px} }

  /* ===== Detail Panel (kanan) ===== */
  .detail-panel{
    position: fixed;
    right:12px;
    top: var(--detail-top, 80px);
    bottom:12px;
    width:360px; max-width:88vw; display:none; z-index:20;
    background:#fff; border:1px solid #e5e7eb; border-radius:14px;
    box-shadow:0 12px 28px rgba(0,0,0,.18); padding:16px; overflow:auto;
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
  }
  .detail-panel::-webkit-scrollbar {width: 8px;}
  .detail-panel::-webkit-scrollbar-track {background: #f1f5f9;border-radius: 4px;}
  .detail-panel::-webkit-scrollbar-thumb {background: #cbd5e1;border-radius: 4px;}
  .detail-panel::-webkit-scrollbar-thumb:hover {background: #94a3b8;}
  .detail-panel.show{display:block}
  .dp-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:10px;border-bottom:2px solid #e5e7eb}
  .dp-title{font-weight:800;color:#0f172a;font-size:17px}
  .dp-close{border:none;background:#f1f5f9;width:32px;height:32px;border-radius:10px;cursor:pointer;transition:all .2s;font-size:18px}
  .dp-close:hover{background:#e2e8f0;transform: rotate(90deg)}
  .dp-section{margin-bottom:16px;padding:12px;border-radius:10px}
  .dp-section-title{font-size:11px;font-weight:700;margin-bottom:8px;letter-spacing:0.5px}
  .dp-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
  .dp-item{background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:10px}
  .dp-item.full{grid-column:1 / -1}
  .dp-label{font-size:10px;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.3px;font-weight:600}
  .dp-value{font-size:13px;font-weight:600;color:#111827;word-break:break-word;line-height:1.4}
  .dp-link{color:#2563eb;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:4px;cursor:pointer}
  .dp-link:hover{text-decoration:underline;color:#1e40af}
  @media (max-width:768px){ .detail-panel{width:calc(100% - 24px);right:12px} }

  /* ===== Modal Sertifikat ===== */
  .sertifikat-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 9999;
    animation: fadeIn 0.2s ease-out;
  }
  
  .sertifikat-modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  .sertifikat-modal-content {
    position: relative;
    background: #fff;
    border-radius: 16px;
    max-width: 90vw;
    max-height: 90vh;
    width: 900px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    animation: slideUp 0.3s ease-out;
  }
  
  @keyframes slideUp {
    from {
      transform: translateY(50px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  
  .sertifikat-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 2px solid #e5e7eb;
     background: var(--nav-bg);
    border-radius: 16px 16px 0 0;
  }
  
  .sertifikat-modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .sertifikat-modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 20px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  
  .sertifikat-modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
  }
  
  .sertifikat-modal-body {
    padding: 24px;
    overflow: auto;
    flex: 1;
    background: #f8fafc;
  }
  
  .sertifikat-preview {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .sertifikat-preview iframe {
    width: 100%;
    height: 600px;
    border: none;
    border-radius: 8px;
  }
  
  .sertifikat-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  .sertifikat-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    color: #64748b;
  }
  
  .sertifikat-loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  
  .sertifikat-error {
    text-align: center;
    padding: 40px 20px;
    color: #ef4444;
  }
  
  .sertifikat-error-icon {
    font-size: 48px;
    margin-bottom: 16px;
  }
  
  .sertifikat-error-text {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
  }
  
  .sertifikat-error-detail {
    font-size: 14px;
    color: #94a3b8;
  }
  
  .sertifikat-modal-footer {
    padding: 16px 24px;
    border-top: 2px solid #e5e7eb;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    background: #fff;
    border-radius: 0 0 16px 16px;
  }
  
  .sertifikat-modal-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  
  .sertifikat-modal-btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
  }
  
  .sertifikat-modal-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }
  
  .sertifikat-modal-btn-secondary {
    background: #f1f5f9;
    color: #475569;
  }
  
  .sertifikat-modal-btn-secondary:hover {
    background: #e2e8f0;
  }
  
  @media (max-width: 768px) {
    .sertifikat-modal-content {
      width: 100%;
      max-width: 95vw;
      max-height: 95vh;
    }
    
    .sertifikat-modal-header {
      padding: 16px 20px;
    }
    
    .sertifikat-modal-title {
      font-size: 16px;
    }
    
    .sertifikat-modal-body {
      padding: 16px;
    }
    
    .sertifikat-preview iframe {
      height: 400px;
    }
  }

  /* ===== Live Location Accuracy Widget ===== */
  .live-location-accuracy-widget{
    padding:6px 10px;
    font-size:11px;
    line-height:1.4;
    background:#0f172a;
    color:#e5e7eb;
    border-radius:9999px;
    box-shadow:0 6px 18px rgba(0,0,0,.25);
    border:1px solid #1f2937;
    min-width:160px;
    text-align:left;
  }
  .live-location-accuracy-widget span{
    font-weight:600;
    color:#bfdbfe;
  }
</style>
@endpush

@section('content')
  <div id="viewDiv">
    {{-- Filter Panel --}}
    <aside id="filterPanel" class="filter-panel esri-widget esri-component" aria-label="Filter Peta">
      <div class="filter-header">
        <div class="filter-title">🔍 Filter Peta</div>
        <button id="filterToggle" class="filter-toggle" title="Tutup/Buka">▼</button>
      </div>

      <div class="filter-content">
        {{-- 0. Filter Pencarian Teks dengan Suggest --}}
        <div class="filter-group">
          <label class="filter-label">🔎 Pencarian</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterSearch" class="filter-input" placeholder="Cari nama aset, unit kerja, atau lokasi..." autocomplete="off">
            <span class="dropdown-arrow" id="filterSearchClear" style="cursor: pointer; display: none;">✕</span>
            <div id="filterSearchDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        <div style="border-top:1px dashed #e5e7eb;margin:16px 0"></div>

        {{-- 1. Kabupaten - Searchable --}}
        <div class="filter-group">
          <label class="filter-label">📍 Kabupaten/Kota</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterKabupatenInput" placeholder="Pilih Kabupaten/Kota" readonly>
            <span class="dropdown-arrow">▼</span>
            <div id="filterKabupatenDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        {{-- 2. Kecamatan - Searchable --}}
        <div class="filter-group">
          <label class="filter-label">🏘️ Kecamatan</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterKecamatanInput" placeholder="Pilih Kabupaten terlebih dahulu" readonly disabled>
            <span class="dropdown-arrow">▼</span>
            <div id="filterKecamatanDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        {{-- 3. Kelurahan - Searchable --}}
        <div class="filter-group">
          <label class="filter-label">🏠 Kelurahan/Desa</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterKelurahanInput" placeholder="Pilih Kecamatan terlebih dahulu" readonly disabled>
            <span class="dropdown-arrow">▼</span>
            <div id="filterKelurahanDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        <div style="border-top:1px dashed #e5e7eb;margin:16px 0"></div>

        {{-- 4. Kategori Tanah --}}
        <div class="filter-group">
          <label class="filter-label">🏞️ Kategori Tanah</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterKatTanahInput" placeholder="Semua Kategori" readonly>
            <span class="dropdown-arrow">▼</span>
            <div id="filterKatTanahDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        {{-- 5. Unit Kerja --}}
        <div class="filter-group">
          <label class="filter-label">🏢 Unit Kerja</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterUnitKerjaInput" placeholder="Pilih atau ketik untuk mencari..." readonly>
            <span class="dropdown-arrow">▼</span>
            <div id="filterUnitKerjaDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        {{-- 6. Asal Perolehan --}}
        <div class="filter-group">
          <label class="filter-label">📋 Asal Perolehan</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterAsalInput" placeholder="Pilih atau ketik untuk mencari..." readonly>
            <span class="dropdown-arrow">▼</span>
            <div id="filterAsalDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        {{-- 7. Pemanfaatan --}}
        <div class="filter-group">
          <label class="filter-label">🏗️ Pemanfaatan</label>
          <div class="searchable-select-wrapper">
            <input type="text" id="filterPemanfaatanInput" placeholder="Pilih atau ketik untuk mencari..." readonly>
            <span class="dropdown-arrow">▼</span>
            <div id="filterPemanfaatanDropdown" class="searchable-dropdown"></div>
          </div>
        </div>

        <div class="filter-actions">
          <button id="btnApplyFilter" class="btn-filter btn-apply">Terapkan</button>
          <button id="btnResetFilter" class="btn-filter btn-reset">Reset</button>
        </div>

        <div id="filterCount" class="filter-count" style="display:none;">
          Menampilkan <strong>0</strong> aset
        </div>
      </div>
    </aside>

    {{-- Detail Panel --}}
    <aside id="detailPanel" class="detail-panel" aria-live="polite">
      <div class="dp-head">
        <div class="dp-title">📋 Detail Aset</div>
        <button id="dpClose" class="dp-close" title="Tutup">✕</button>
      </div>

      {{-- Informasi Utama --}}
      <div class="dp-section" style="background:#eff6ff;border-left:4px solid #3b82f6">
        <div class="dp-section-title" style="color:#1e40af">📌 INFORMASI UTAMA</div>
        <div class="dp-grid">
          <div class="dp-item"><div class="dp-label">ID Aset</div><div class="dp-value" id="dpId">-</div></div>
          <div class="dp-item"><div class="dp-label">Kode Aset</div><div class="dp-value" id="dpKodeAsset">-</div></div>
          <div class="dp-item full"><div class="dp-label">Nama Aset</div><div class="dp-value" id="dpNama">-</div></div>
          <div class="dp-item"><div class="dp-label">No. Register</div><div class="dp-value" id="dpNoRegister">-</div></div>
          <div class="dp-item"><div class="dp-label">Kode Lokasi</div><div class="dp-value" id="dpKode">-</div></div>
          <div class="dp-item"><div class="dp-label">Luas (m²)</div><div class="dp-value" id="dpLuas">-</div></div>
          <div class="dp-item"><div class="dp-label">Nilai Perolehan</div><div class="dp-value" id="dpNilaiPerolehan">-</div></div>
        </div>
      </div>

      {{-- Kategori & Unit --}}
      <div class="dp-section" style="background:#f0fdf4;border-left:4px solid #10b981">
        <div class="dp-section-title" style="color:#047857">🏢 KATEGORI & UNIT</div>
        <div class="dp-grid">
          <div class="dp-item full"><div class="dp-label">Unit Kerja</div><div class="dp-value" id="dpUnitKerja">-</div></div>
          <div class="dp-item full"><div class="dp-label">Kategori Tanah</div><div class="dp-value" id="dpKatTanah">-</div></div>
          <div class="dp-item full"><div class="dp-label">Penggunaan (SPMA)</div><div class="dp-value" id="dpPenggunaan">-</div></div>
          <div class="dp-item full"><div class="dp-label">Pemanfaatan</div><div class="dp-value" id="dpPemanfaatan">-</div></div>
          <div class="dp-item full"><div class="dp-label">Asal Perolehan</div><div class="dp-value" id="dpAsal">-</div></div>
        </div>
      </div>

      {{-- Lokasi --}}
      <div class="dp-section" style="background:#fef3c7;border-left:4px solid #f59e0b">
        <div class="dp-section-title" style="color:#b45309">📍 LOKASI</div>
        <div class="dp-grid">
          <div class="dp-item full"><div class="dp-label">Alamat</div><div class="dp-value" id="dpAlamat">-</div></div>
          <div class="dp-item"><div class="dp-label">Kelurahan</div><div class="dp-value" id="dpKelurahan">-</div></div>
          <div class="dp-item"><div class="dp-label">Kecamatan</div><div class="dp-value" id="dpKecamatan">-</div></div>
          <div class="dp-item"><div class="dp-label">Kabupaten</div><div class="dp-value" id="dpKabupaten">-</div></div>
          <div class="dp-item"><div class="dp-label">Provinsi</div><div class="dp-value" id="dpProvinsi">-</div></div>
          <div class="dp-item"><div class="dp-label">Latitude</div><div class="dp-value" id="dpLatitude">-</div></div>
          <div class="dp-item"><div class="dp-label">Longitude</div><div class="dp-value" id="dpLongitude">-</div></div>
        </div>
      </div>

      {{-- Hak Tanah --}}
      <div class="dp-section" style="background:#fce7f3;border-left:4px solid #ec4899">
        <div class="dp-section-title" style="color:#be185d">📜 HAK TANAH</div>
        <div class="dp-grid">
          <div class="dp-item"><div class="dp-label">Nomor Hak</div><div class="dp-value" id="dpNomorHak">-</div></div>
          <div class="dp-item"><div class="dp-label">Jenis Hak</div><div class="dp-value" id="dpJenisHak">-</div></div>
        </div>
      </div>

      {{-- Sertifikat & Dokumen --}}
      <div class="dp-section" style="background:#ede9fe;border-left:4px solid #8b5cf6">
        <div class="dp-section-title" style="color:#6b21a8">📄 SERTIFIKAT & DOKUMEN</div>
        <div class="dp-grid">
          <div class="dp-item"><div class="dp-label">Tanggal Sertifikat</div><div class="dp-value" id="dpTglSertif">-</div></div>
          <div class="dp-item"><div class="dp-label">Tahun</div><div class="dp-value" id="dpTahun">-</div></div>
          <div class="dp-item full"><div class="dp-label">File/Link Sertifikat</div><div class="dp-value" id="dpSertifikat">-</div></div>
        </div>
      </div>
    </aside>

    {{-- Modal Sertifikat --}}
    <div id="sertifikatModal" class="sertifikat-modal">
      <div class="sertifikat-modal-content">
        <div class="sertifikat-modal-header">
          <div class="sertifikat-modal-title">
            <span>📄</span>
            <span id="modalSertifikatTitle">Preview Sertifikat</span>
          </div>
          <button id="modalSertifikatClose" class="sertifikat-modal-close">✕</button>
        </div>
        
        <div class="sertifikat-modal-body">
          <div id="sertifikatPreviewContainer" class="sertifikat-preview">
            <div class="sertifikat-loading">
              <div class="sertifikat-loading-spinner"></div>
              <div>Memuat dokumen...</div>
            </div>
          </div>
        </div>
        
        <div class="sertifikat-modal-footer">
          <button id="modalDownloadBtn" class="sertifikat-modal-btn sertifikat-modal-btn-primary" style="display:none;">
            <span>⬇</span>
            <span>Download</span>
          </button>
          <button id="modalOpenNewTabBtn" class="sertifikat-modal-btn sertifikat-modal-btn-secondary" style="display:none;">
            <span>🔗</span>
            <span>Buka di Tab Baru</span>
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>window.dojoConfig = { async: true };</script>
<script src="https://js.arcgis.com/4.29/"></script>

<script>
(function(){
  const $ = id => document.getElementById(id);
  const fmt = n => (n===null||n===undefined||isNaN(n)) ? "-" : Number(n).toLocaleString('id-ID');

  const formatTanggalIndonesia = (dateStr) => {
    if (!dateStr || dateStr === '-' || dateStr === '') return '-';
    try {
      const date = new Date(dateStr);
      if (isNaN(date.getTime())) return '-';
      const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      return `${hari[date.getDay()]}, ${date.getDate()} ${bulan[date.getMonth()]} ${date.getFullYear()}`;
    } catch { return '-'; }
  };

  // ===== Modal Sertifikat Functions =====
  let currentSertifikatUrl = null;

  function openSertifikatModal(url, namaAset = '') {
    if (!url) return;
    
    currentSertifikatUrl = url;
    const modal = $('sertifikatModal');
    const container = $('sertifikatPreviewContainer');
    const downloadBtn = $('modalDownloadBtn');
    const openTabBtn = $('modalOpenNewTabBtn');
    const titleEl = $('modalSertifikatTitle');
    
    // Set title
    titleEl.textContent = namaAset ? `Sertifikat - ${namaAset}` : 'Preview Sertifikat';
    
    // Show modal
    modal.classList.add('show');
    
    // Reset container
    container.innerHTML = '<div class="sertifikat-loading"><div class="sertifikat-loading-spinner"></div><div>Memuat dokumen...</div></div>';
    
    // Hide buttons initially
    downloadBtn.style.display = 'none';
    openTabBtn.style.display = 'none';
    
    // Determine file type
    const urlLower = url.toLowerCase();
    const isPDF = urlLower.endsWith('.pdf') || urlLower.includes('.pdf?') || urlLower.includes('pdf');
    const isImage = /\.(jpg|jpeg|png|gif|bmp|webp|svg)(\?|$)/i.test(urlLower);
    
    setTimeout(() => {
      if (isPDF) {
        // PDF Preview
        container.innerHTML = `<iframe src="${url}" title="Preview Sertifikat PDF"></iframe>`;
        downloadBtn.style.display = 'inline-flex';
        openTabBtn.style.display = 'inline-flex';
      } else if (isImage) {
        // Image Preview
        const img = document.createElement('img');
        img.src = url;
        img.alt = 'Sertifikat';
        img.onerror = () => {
          container.innerHTML = `
            <div class="sertifikat-error">
              <div class="sertifikat-error-icon">⚠️</div>
              <div class="sertifikat-error-text">Gagal memuat gambar</div>
              <div class="sertifikat-error-detail">Gambar tidak dapat ditampilkan atau format tidak didukung</div>
            </div>
          `;
        };
        container.innerHTML = '';
        container.appendChild(img);
        downloadBtn.style.display = 'inline-flex';
        openTabBtn.style.display = 'inline-flex';
      } else {
        // Unknown type - show link
        container.innerHTML = `
          <div class="sertifikat-error">
            <div class="sertifikat-error-icon">📎</div>
            <div class="sertifikat-error-text">Preview tidak tersedia</div>
            <div class="sertifikat-error-detail">Dokumen tidak dapat ditampilkan langsung. Silakan buka di tab baru atau download.</div>
          </div>
        `;
        downloadBtn.style.display = 'inline-flex';
        openTabBtn.style.display = 'inline-flex';
      }
    }, 100);
  }

  function closeSertifikatModal() {
    const modal = $('sertifikatModal');
    modal.classList.remove('show');
    currentSertifikatUrl = null;
  }

  // Modal event listeners
  $('modalSertifikatClose').addEventListener('click', closeSertifikatModal);
  
  $('sertifikatModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeSertifikatModal();
    }
  });
  
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && $('sertifikatModal').classList.contains('show')) {
      closeSertifikatModal();
    }
  });
  
  $('modalDownloadBtn').addEventListener('click', function() {
    if (currentSertifikatUrl) {
      const a = document.createElement('a');
      a.href = currentSertifikatUrl;
      a.download = 'sertifikat';
      a.target = '_blank';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
  });
  
  $('modalOpenNewTabBtn').addEventListener('click', function() {
    if (currentSertifikatUrl) {
      window.open(currentSertifikatUrl, '_blank');
    }
  });

  function computeHeaderHeight(){
    const selectors = ['.navbar', 'header', '.site-header', '.app-header', '.topbar'];
    for (const sel of selectors){ const el = document.querySelector(sel); if (el) return el.getBoundingClientRect().height; }
    return 64;
  }
  function adjustDetailPanelOffset(){
    const dp = $('detailPanel'); if (!dp) return;
    const h = computeHeaderHeight();
    dp.style.setProperty('--detail-top', (h + 12) + 'px');
    dp.style.top = (h + 12) + 'px';
  }
  window.addEventListener('load', adjustDetailPanelOffset);
  window.addEventListener('resize', adjustDetailPanelOffset);

  // ===== Searchable Select Component =====
  function createSearchableSelect(inputId, dropdownId, options, placeholder = 'Pilih...', withColorBox = false, onChange = null) {
    const input = $(inputId);
    const dropdown = $(dropdownId);
    let selectedValue = '';
    let allOptions = options;
    let isDisabled = input.disabled;
    
    function getColorClass(text) {
      if (!withColorBox) return '';
      const textLower = text.toLowerCase().trim();
      if (textLower.includes('k1') || textLower === '1' || textLower === 'kategori 1') return 'kat-k1';
      if (textLower.includes('k2') || textLower === '2' || textLower === 'kategori 2') return 'kat-k2';
      if (textLower.includes('k3') || textLower === '3' || textLower === 'kategori 3') return 'kat-k3';
      if (textLower.includes('bersertifikat')) return 'kat-bersertifikat';
      return '';
    }
    
    function renderOptions(filter = '') {
      dropdown.innerHTML = '';
      const filtered = allOptions.filter(opt => 
        opt.text.toLowerCase().includes(filter.toLowerCase())
      );
      
      if (filtered.length === 0) {
        dropdown.innerHTML = '<div class="searchable-dropdown-empty">Tidak ada hasil</div>';
        return;
      }
      
      filtered.forEach(opt => {
        const div = document.createElement('div');
        div.className = 'searchable-dropdown-item';
        
        if (withColorBox) {
          const colorClass = getColorClass(opt.text);
          div.className += colorClass ? ' kat-option ' + colorClass : ' kat-option';
          
          if (colorClass && opt.value !== '') {
            div.innerHTML = `<span class="kat-color-box"></span><span>${opt.text}</span>`;
          } else {
            div.textContent = opt.text;
          }
        } else {
          div.textContent = opt.text;
        }
        
        div.dataset.value = opt.value;
        if (opt.value === selectedValue) {
          div.classList.add('selected');
        }
        
        div.addEventListener('click', () => {
          const oldValue = selectedValue;
          selectedValue = opt.value;
          input.value = opt.text;
          dropdown.classList.remove('active');
          input.setAttribute('readonly', 'readonly');
          
          if (onChange && oldValue !== selectedValue) {
            onChange(selectedValue, opt);
          }
        });
        
        dropdown.appendChild(div);
      });
    }
    
    input.addEventListener('click', () => {
      if (isDisabled) return;
      dropdown.classList.toggle('active');
      if (dropdown.classList.contains('active')) {
        input.removeAttribute('readonly');
        input.focus();
        renderOptions(input.value === placeholder || input.value === selectedValue ? '' : input.value);
      }
    });
    
    input.addEventListener('input', () => {
      if (isDisabled) return;
      dropdown.classList.add('active');
      renderOptions(input.value);
    });
    
    input.addEventListener('blur', (e) => {
      setTimeout(() => {
        if (!dropdown.contains(e.relatedTarget)) {
          dropdown.classList.remove('active');
          if (selectedValue) {
            const selected = allOptions.find(o => o.value === selectedValue);
            input.value = selected ? selected.text : '';
          } else {
            input.value = '';
          }
          input.setAttribute('readonly', 'readonly');
        }
      }, 200);
    });
    
    document.addEventListener('click', (e) => {
      if (!input.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
        input.setAttribute('readonly', 'readonly');
      }
    });
    
    return {
      getValue: () => selectedValue,
      setValue: (val, triggerChange = false) => {
        const oldValue = selectedValue;
        selectedValue = val;
        const opt = allOptions.find(o => o.value === val);
        input.value = opt ? opt.text : '';
        
        if (triggerChange && onChange && oldValue !== selectedValue) {
          onChange(selectedValue, opt);
        }
      },
      clear: () => {
        selectedValue = '';
        input.value = '';
        input.placeholder = placeholder;
      },
      updateOptions: (newOptions, newPlaceholder = null) => {
        allOptions = newOptions;
        selectedValue = '';
        input.value = '';
        if (newPlaceholder) {
          input.placeholder = newPlaceholder;
        }
        renderOptions();
      },
      setDisabled: (disabled) => {
        isDisabled = disabled;
        input.disabled = disabled;
        if (disabled) {
          dropdown.classList.remove('active');
        }
      }
    };
  }

  // ===== Filter data & storage =====
  let filterData = {kategori_tanah:[],unit_kerja:[],asal_perolehan:[],pemanfaatan:[],kabupaten:[],kecamatan:[],kelurahan:[]};
  let currentFilters = {regency_id:'',district_id:'',village_id:'',kat_tanah:'',unit_kerja:'',asal:'',pemanfaatan:'',search:''};
  
  let kabupatenSelect, kecamatanSelect, kelurahanSelect, katTanahSelect, unitKerjaSelect, asalSelect, pemanfaatanSelect;
  let cascadeInited = false;

  // ===== Load filter options dari API =====
  fetch("{{ url('/api/aset/filters') }}")
    .then(r => r.json())
    .then(data => { filterData = data; populateFilters(); setupCascadingFilters(); })
    .catch(err => console.error('Error loading filters:', err));

  function populateFilters() {
    kabupatenSelect = createSearchableSelect(
      'filterKabupatenInput',
      'filterKabupatenDropdown',
      [{value: '', text: 'Pilih Kabupaten/Kota'}, ...(filterData.kabupaten||[]).map(it => ({value: it.id, text: it.name}))],
      'Pilih Kabupaten/Kota'
    );

    kecamatanSelect = createSearchableSelect(
      'filterKecamatanInput',
      'filterKecamatanDropdown',
      [{value: '', text: 'Pilih Kabupaten terlebih dahulu'}],
      'Pilih Kabupaten terlebih dahulu'
    );
    kecamatanSelect.setDisabled(true);

    kelurahanSelect = createSearchableSelect(
      'filterKelurahanInput',
      'filterKelurahanDropdown',
      [{value: '', text: 'Pilih Kecamatan terlebih dahulu'}],
      'Pilih Kecamatan terlebih dahulu'
    );
    kelurahanSelect.setDisabled(true);

    const katTanahOptions = [
      {value: '', text: 'Semua Kategori'},
      {value: 'bersertifikat', text: 'Bersertifikat'},
      ...(filterData.kategori_tanah||[]).map(v => ({value: v, text: v}))
    ];

    katTanahSelect = createSearchableSelect(
      'filterKatTanahInput',
      'filterKatTanahDropdown',
      katTanahOptions,
      'Semua Kategori',
      true
    );

    unitKerjaSelect = createSearchableSelect(
      'filterUnitKerjaInput',
      'filterUnitKerjaDropdown',
      [{value: '', text: 'Semua Unit Kerja'}, ...(filterData.unit_kerja||[]).map(v => ({value: v.name, text: v.name}))],
      'Pilih atau ketik untuk mencari...'
    );
    
    asalSelect = createSearchableSelect(
      'filterAsalInput',
      'filterAsalDropdown',
      [{value: '', text: 'Semua Asal'}, ...(filterData.asal_perolehan||[]).map(v => ({value: v, text: v}))],
      'Pilih atau ketik untuk mencari...'
    );

    pemanfaatanSelect = createSearchableSelect(
      'filterPemanfaatanInput',
      'filterPemanfaatanDropdown',
      [{value: '', text: 'Semua Pemanfaatan'}, ...(filterData.pemanfaatan||[]).map(v => ({value: v, text: v}))],
      'Pilih atau ketik untuk mencari...'
    );
  }

  // ===== util WHERE =====
  function escapeSql(s){ return String(s).replace(/'/g,"''"); }
  
  function buildWhere(f){
    const c = [];

    if (f.regency_id)  c.push(`regency_id='${escapeSql(String(f.regency_id))}'`);
    if (f.district_id) c.push(`district_id='${escapeSql(String(f.district_id))}'`);
    if (f.village_id)  c.push(`village_id='${escapeSql(String(f.village_id))}'`);
    
    if (f.kat_tanah) {
      const katValue = f.kat_tanah.toLowerCase().trim();
      
      if (katValue === 'bersertifikat') {
        c.push(`(kat_tanah IS NULL OR kat_tanah = '' OR kat_tanah = '-' OR LOWER(kat_tanah) LIKE '%bersertifikat%' OR (LOWER(kat_tanah) NOT LIKE '%k1%' AND LOWER(kat_tanah) NOT LIKE '%k2%' AND LOWER(kat_tanah) NOT LIKE '%k3%' AND LOWER(kat_tanah) NOT LIKE 'kategori 1' AND LOWER(kat_tanah) NOT LIKE 'kategori 2' AND LOWER(kat_tanah) NOT LIKE 'kategori 3'))`);
      } else {
        c.push(`kat_tanah='${escapeSql(f.kat_tanah)}'`);
      }
    }
    
    if (f.unit_kerja)  c.push(`unit_kerja='${escapeSql(f.unit_kerja)}'`);
    if (f.asal)        c.push(`asal='${escapeSql(f.asal)}'`);
    if (f.pemanfaatan) c.push(`pemanfaatan='${escapeSql(f.pemanfaatan)}'`);

    if (f.search && f.search.trim() !== '') {
      const term = escapeSql(f.search.trim().toLowerCase());
      c.push(`search_index LIKE '%${term}%'`);
    }

    return c.length ? c.join(' AND ') : '1=1';
  }

  function setupCascadingFilters() {
    if (cascadeInited) return;
    cascadeInited = true;
  }

  // ===== Search Autocomplete/Suggest =====
  let searchSuggestionsCache = [];
  let isLoadingSuggestions = false;

  async function loadSearchSuggestions() {
    if (searchSuggestionsCache.length > 0 || isLoadingSuggestions) return;
    
    isLoadingSuggestions = true;
    try {
      const response = await fetch("{{ url('/api/aset') }}");
      const data = await response.json();
      
      if (data && data.features) {
        // Extract unique suggestions from features
        const suggestions = new Set();
        data.features.forEach(feature => {
          const props = feature.properties;
          
          // Add various searchable fields
          if (props.nama_asset) suggestions.add(JSON.stringify({
            text: props.nama_asset,
            type: 'Nama Aset',
            meta: [props.unit_kerja, props.kabupaten].filter(Boolean),
            value: props.nama_asset
          }));
          
          if (props.unit_kerja) suggestions.add(JSON.stringify({
            text: props.unit_kerja,
            type: 'Unit Kerja',
            meta: [],
            value: props.unit_kerja
          }));
          
          if (props.kabupaten) suggestions.add(JSON.stringify({
            text: props.kabupaten,
            type: 'Kabupaten',
            meta: [],
            value: props.kabupaten
          }));
          
          if (props.kecamatan) suggestions.add(JSON.stringify({
            text: props.kecamatan,
            type: 'Kecamatan',
            meta: [props.kabupaten].filter(Boolean),
            value: props.kecamatan
          }));
          
          if (props.kelurahan) suggestions.add(JSON.stringify({
            text: props.kelurahan,
            type: 'Kelurahan',
            meta: [props.kecamatan, props.kabupaten].filter(Boolean),
            value: props.kelurahan
          }));
          
          if (props.alamat) suggestions.add(JSON.stringify({
            text: props.alamat,
            type: 'Alamat',
            meta: [props.kelurahan].filter(Boolean),
            value: props.alamat
          }));
        });
        
        searchSuggestionsCache = Array.from(suggestions).map(s => JSON.parse(s));
      }
    } catch (error) {
      console.error('Error loading search suggestions:', error);
    } finally {
      isLoadingSuggestions = false;
    }
  }

  function filterSearchSuggestions(query) {
    if (!query || query.length < 2) return [];
    
    const lowerQuery = query.toLowerCase();
    return searchSuggestionsCache
      .filter(item => item.text.toLowerCase().includes(lowerQuery))
      .slice(0, 10); // Limit to 10 results
  }

  function renderSearchSuggestions(suggestions) {
    const dropdown = $('filterSearchDropdown');
    dropdown.innerHTML = '';
    
    if (suggestions.length === 0) {
      dropdown.innerHTML = '<div class="searchable-dropdown-empty">Tidak ada hasil ditemukan</div>';
      dropdown.classList.add('active');
      return;
    }
    
    suggestions.forEach(suggestion => {
      const div = document.createElement('div');
      div.className = 'searchable-dropdown-item';
      
      const suggestionHtml = `
        <div class="search-suggestion-item">
          <div class="search-suggestion-main">${suggestion.text}</div>
          <div class="search-suggestion-meta">
            <span class="search-suggestion-tag">${suggestion.type}</span>
            ${suggestion.meta.map(m => `<span style="font-size: 10px;">${m}</span>`).join(' • ')}
          </div>
        </div>
      `;
      
      div.innerHTML = suggestionHtml;
      div.dataset.value = suggestion.value;
      
      div.addEventListener('click', () => {
        const searchInput = $('filterSearch');
        searchInput.value = suggestion.value;
        dropdown.classList.remove('active');
        
        // Show clear button
        $('filterSearchClear').style.display = 'block';
        
        // Trigger filter
        applyFiltersIfReady();
      });
      
      dropdown.appendChild(div);
    });
    
    dropdown.classList.add('active');
  }

  function setupSearchAutocomplete() {
    const searchInput = $('filterSearch');
    const dropdown = $('filterSearchDropdown');
    const clearBtn = $('filterSearchClear');
    
    let searchDebounce;
    
    // Load suggestions on first focus
    searchInput.addEventListener('focus', () => {
      loadSearchSuggestions();
    });
    
    // Handle input
    searchInput.addEventListener('input', function() {
      const value = this.value;
      
      // Show/hide clear button
      if (value.length > 0) {
        clearBtn.style.display = 'block';
      } else {
        clearBtn.style.display = 'none';
        dropdown.classList.remove('active');
      }
      
      // Debounce suggestions
      clearTimeout(searchDebounce);
      
      if (value.length >= 2) {
        searchDebounce = setTimeout(() => {
          const suggestions = filterSearchSuggestions(value);
          renderSearchSuggestions(suggestions);
        }, 200);
      } else {
        dropdown.classList.remove('active');
      }
    });
    
    // Clear button
    clearBtn.addEventListener('click', function() {
      searchInput.value = '';
      this.style.display = 'none';
      dropdown.classList.remove('active');
      
      // Clear filter if already applied
      if (currentFilters.search) {
        currentFilters.search = '';
        applyFiltersIfReady();
      }
    });
    
    // Close dropdown on click outside
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !dropdown.contains(e.target) && !clearBtn.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });
    
    // Close dropdown on Escape
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        dropdown.classList.remove('active');
        searchInput.blur();
      }
    });
  }

  // Setup autocomplete after DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupSearchAutocomplete);
  } else {
    setupSearchAutocomplete();
  }

  // ===== ArcGIS =====
  require([
    "esri/Map",
    "esri/Basemap",
    "esri/views/MapView",
    "esri/layers/GeoJSONLayer",
    "esri/layers/GraphicsLayer",
    "esri/geometry/Circle",
    "esri/Graphic",
    "esri/widgets/Legend",
    "esri/widgets/Expand",
    "esri/widgets/Home",
    "esri/widgets/Search",
    "esri/widgets/ScaleBar",
    "esri/widgets/BasemapGallery",
    "esri/widgets/BasemapToggle",
    "esri/widgets/BasemapGallery/support/LocalBasemapsSource",
    "esri/widgets/Print",
    "esri/widgets/Compass",
    "esri/widgets/Track"
  ], function(
    Map,
    Basemap,
    MapView,
    GeoJSONLayer,
    GraphicsLayer,
    Circle,
    Graphic,
    Legend,
    Expand,
    Home,
    Search,
    ScaleBar,
    BasemapGallery,
    BasemapToggle,
    LocalBasemapsSource,
    Print,
    Compass,
    Track
  ){

    const map = new Map({ basemap: Basemap.fromId("satellite") });
    const view = new MapView({
      container: "viewDiv",
      map, center: [117.15, -0.5], zoom: 10,
      popup: { autoOpenEnabled: false }
    });

    // ===== util cek ketersediaan fitur GeoJSON =====
    async function hasFeatures(url){
      try{
        const res = await fetch(url + (url.includes('?') ? '&' : '?') + '_ts=' + Date.now(), {
          headers: { 'Accept': 'application/json' }
        });
        const fc = await res.json();
        return Array.isArray(fc?.features) && fc.features.length > 0;
      }catch(e){ console.warn('hasFeatures error:', e); return false; }
    }

    // ===== Renderers & labeling =====
    const labelExpr = `
      var a = DefaultValue($feature.nama_asset, '');
      var u = DefaultValue($feature.unit_kerja, '');
      var A = Trim(a);
      var U = Trim(u);
      IIF((A == '' && U == ''),'', IIF(U == '', A, IIF(A == '', U, U + ' (' + A + ')')))
    `;
    const commonTextSymbol = {
      type: "text",
      color: "#111827",
      haloColor: "#ffffff",
      haloSize: 1.5,
      font: { family: "system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Helvetica Neue, Arial", size: 10, weight: "bold" }
    };
    
    const katExpression = `
      var v = Lower(Trim($feature.kat_tanah));
      
      if (IsEmpty(v) || v == '' || v == '-' || v == 'null') {
        return 'bersertifikat';
      }
      
      v = IIF(v == 'k1' || v == '1' || v == 'kategori 1', 'k1',
          IIF(v == 'k2' || v == '2' || v == 'kategori 2', 'k2',
          IIF(v == 'k3' || v == '3' || v == 'kategori 3', 'k3',
          IIF(Find('bersertifikat', v) > -1, 'bersertifikat', 'bersertifikat'))));
      
      return v;
    `;

    function applyPolygonRenderer(layer){
      layer.renderer = {
        type: "unique-value",
        valueExpression: katExpression,
        valueExpressionTitle: "Kategori Tanah",
        uniqueValueInfos: [
          { value:"k1", label:"K1", symbol:{ type:"simple-fill", color:[34,197,94,0.45], outline:{ color:[21,128,61,1], width:1.5 } } },
          { value:"k2", label:"K2", symbol:{ type:"simple-fill", color:[234,179,8,0.45], outline:{ color:[202,138,4,1], width:1.5 } } },
          { value:"k3", label:"K3", symbol:{ type:"simple-fill", color:[239,68,68,0.45], outline:{ color:[220,38,38,1], width:1.5 } } },
          { value:"bersertifikat", label:"Bersertifikat", symbol:{ type:"simple-fill", color:[59,130,246,0.45], outline:{ color:[29,78,216,1], width:1.5 } } }
        ]
      };
      layer.labelsVisible = true;
      layer.labelingInfo = [{ labelExpressionInfo:{ expression: labelExpr }, labelPlacement:"center-center", symbol: commonTextSymbol }];
    }
    
    function applyLineRenderer(layer){
      layer.renderer = {
        type: "unique-value",
        valueExpression: katExpression,
        valueExpressionTitle: "Kategori Tanah",
        uniqueValueInfos: [
          { value:"k1", label:"K1", symbol:{ type:"simple-line", color:[21,128,61,1], width:2.5 } },
          { value:"k2", label:"K2", symbol:{ type:"simple-line", color:[202,138,4,1], width:2.5 } },
          { value:"k3", label:"K3", symbol:{ type:"simple-line", color:[220,38,38,1], width:2.5 } },
          { value:"bersertifikat", label:"Bersertifikat", symbol:{ type:"simple-line", color:[29,78,216,1], width:2.5 } }
        ]
      };
      layer.labelsVisible = true;
      layer.labelingInfo = [{ labelExpressionInfo:{ expression: labelExpr }, labelPlacement:"center-along", symbol: commonTextSymbol }];
    }
    
    function applyPointRenderer(layer){
      layer.renderer = {
        type: "unique-value",
        valueExpression: katExpression,
        valueExpressionTitle: "Kategori Tanah",
        uniqueValueInfos: [
          { value:"k1", label:"K1", symbol:{ type:"simple-marker", size:8, outline:{ color:[21,128,61,1], width:1 }, color:[34,197,94,0.9] } },
          { value:"k2", label:"K2", symbol:{ type:"simple-marker", size:8, outline:{ color:[202,138,4,1], width:1 }, color:[234,179,8,0.9] } },
          { value:"k3", label:"K3", symbol:{ type:"simple-marker", size:8, outline:{ color:[220,38,38,1], width:1 }, color:[239,68,68,0.9] } },
          { value:"bersertifikat", label:"Bersertifikat", symbol:{ type:"simple-marker", size:8, outline:{ color:[29,78,216,1], width:1 }, color:[59,130,246,0.9] } }
        ]
      };
      layer.labelsVisible = true;
      layer.labelingInfo = [{ labelExpressionInfo:{ expression: labelExpr }, labelPlacement:"center-center", symbol: commonTextSymbol }];
    }

    // ====== Aset: buat hanya layer yang ada fiturnya ======
    const baseAsetUrl = "{{ url('/api/aset') }}";
    const urls = {
      polygon: `${baseAsetUrl}?geometry=polygon`,
      line:    `${baseAsetUrl}?geometry=line`,
      point:   `${baseAsetUrl}?geometry=point`
    };

    const asetLayers = [];
    let asetPolyLayer=null, asetLineLayer=null, asetPointLayer=null;

    (async () => {
      if (await hasFeatures(urls.polygon)) {
        asetPolyLayer = new GeoJSONLayer({ url: urls.polygon, title: "Aset Tanah (Polygon)", outFields: ["*"] });
        applyPolygonRenderer(asetPolyLayer);
        map.add(asetPolyLayer);
        asetLayers.push(asetPolyLayer);
      }
      if (await hasFeatures(urls.line)) {
        asetLineLayer = new GeoJSONLayer({ url: urls.line, title: "Aset Tanah (Polyline)", outFields: ["*"] });
        applyLineRenderer(asetLineLayer);
        map.add(asetLineLayer);
        asetLayers.push(asetLineLayer);
      }
      if (await hasFeatures(urls.point)) {
        asetPointLayer = new GeoJSONLayer({ url: urls.point, title: "Aset Tanah (Titik)", outFields: ["*"] });
        applyPointRenderer(asetPointLayer);
        map.add(asetPointLayer);
        asetLayers.push(asetPointLayer);
      }

      // ===== Batas Administrasi =====
      const batasProvLayer = new GeoJSONLayer({
        url: "{{ url('/api/batas/provinsi') }}?ids=64",
        title: "Batas Provinsi",
        renderer: { type:"simple", symbol:{ type:"simple-line", color:[17,24,39,1], width:2.5 } }
      });
      const batasKabLayer = new GeoJSONLayer({
        url: "{{ url('/api/batas/kabupaten') }}?prov=64",
        title: "Batas Kabupaten/Kota",
        renderer: { type:"simple", symbol:{ type:"simple-line", color:[255,255,255,1], width:1.2 } }
      });
      map.addMany([batasKabLayer, batasProvLayer]);

      // ===== Widgets dasar =====
      const home = new Home({ view });
      view.ui.add(home, { position: "top-left", index: 0 });

      const compass = new Compass({ view });
      view.ui.add(compass, { position: "top-left", index: 1 });

      const printWidget = new Print({
        view,
        printServiceUrl: "https://utility.arcgisonline.com/ArcGIS/rest/services/Utilities/PrintingTools/GPServer/Export%20Web%20Map%20Task"
      });
      const printExpand = new Expand({
        view,
        content: printWidget,
        expanded: false,
        expandIconClass: "esri-icon-printer",
        expandTooltip: "Cetak peta"
      });
      view.ui.add(printExpand, { position: "top-left", index: 2 });

      // ===== LIVE LOCATION (seperti Google Maps) =====
      const liveLocationLayer = new GraphicsLayer({
        title: "Lokasi saya",
        listMode: "hide"
      });
      map.add(liveLocationLayer);

      const accuracyWidget = document.createElement("div");
      accuracyWidget.className = "live-location-accuracy-widget esri-widget esri-component";
      accuracyWidget.innerHTML = "📍 Lokasi belum aktif";
      view.ui.add(accuracyWidget, { position: "bottom-right", index: 1 });

      const trackWidget = new Track({
        view,
        scale: 3000,
        geolocationOptions: {
          maximumAge: 0,
          timeout: 15000,
          enableHighAccuracy: true
        },
        graphic: new Graphic({
          symbol: {
            type: "simple-marker",
            size: 12,
            color: [59,130,246,1],
            outline: {
              color: [255,255,255,1],
              width: 2
            }
          }
        })
      });
      trackWidget.label = "Ikuti lokasi saya";

      view.ui.add(trackWidget, { position: "top-left", index: 3 });

      trackWidget.on("track", ({ position }) => {
        const coords = position && position.coords ? position.coords : null;
        if (!coords) return;
        const { longitude, latitude, accuracy } = coords;

        liveLocationLayer.removeAll();

        const point = {
          type: "point",
          longitude,
          latitude
        };

        if (typeof accuracy === "number" && !isNaN(accuracy)) {
          const accuracyCircle = new Circle({
            center: point,
            radius: accuracy,
            radiusUnit: "meters",
            geodesic: true
          });

          const circleGraphic = new Graphic({
            geometry: accuracyCircle,
            symbol: {
              type: "simple-fill",
              color: [59,130,246,0.12],
              outline: {
                color: [37,99,235,0.8],
                width: 1
              }
            }
          });
          liveLocationLayer.add(circleGraphic);

          const akurasiMeter = Math.round(accuracy);
          accuracyWidget.innerHTML = `📍 Lokasi aktif<br><span>Akurasi ± ${akurasiMeter.toLocaleString('id-ID')} m</span>`;
        } else {
          accuracyWidget.innerHTML = "📍 Lokasi aktif<br><span>Akurasi tidak diketahui</span>";
        }
      });

      trackWidget.watch("tracking", (isTracking) => {
        if (!isTracking) {
          liveLocationLayer.removeAll();
          accuracyWidget.innerHTML = "📍 Lokasi belum aktif";
        }
      });

      trackWidget.on("track-error", ({ error }) => {
        liveLocationLayer.removeAll();
        console.warn("Track error:", error);
        let msg = "Tidak dapat mengambil lokasi.";
        if (error && error.message) {
          msg += " " + error.message;
        }
        accuracyWidget.innerHTML = `⚠️ ${msg}`;
      });

      // ===== Widgets lain =====
      view.ui.add(new Search({ view, allPlaceholder:"Temukan alamat dan tempat" }), "top-right");
      view.ui.add(new ScaleBar({view, unit:"metric"}), "bottom-left");

      const legendExpand = new Expand({
        view,
        content: new Legend({
          view,
          layerInfos: [
            { layer: batasProvLayer, title: "Batas Provinsi" },
            { layer: batasKabLayer,  title: "Batas Kabupaten/Kota" },
            ...asetLayers.map(l => ({ layer: l, title: l.title }))
          ]
        }),
        expanded:false, expandIconClass:"esri-icon-layer-list", expandTooltip:"Legenda"
      });
      view.ui.add(legendExpand, "bottom-right");

      const localSource = new LocalBasemapsSource({
        basemaps:[
          Basemap.fromId("satellite"), Basemap.fromId("osm"),
          Basemap.fromId("hybrid"), Basemap.fromId("terrain"),
          Basemap.fromId("topo-vector"), Basemap.fromId("gray-vector"),
          Basemap.fromId("dark-gray-vector"), Basemap.fromId("streets-vector")
        ]
      });
      const bgExpand = new Expand({
        view,
        content: new BasemapGallery({view, source: localSource}),
        expanded:false, expandIconClass:"esri-icon-basemap", expandTooltip:"Ganti basemap"
      });
      view.ui.add(bgExpand, "bottom-right");
      view.ui.add(new BasemapToggle({view, nextBasemap: Basemap.fromId("osm")}), "bottom-right");

      view.ui.add($('filterPanel'), { position: "bottom-left", index: 0 });

      $('filterToggle').addEventListener('click', function(){
        const p=$('filterPanel'); p.classList.toggle('collapsed');
        this.textContent = p.classList.contains('collapsed') ? '▶' : '▼';
      });

      // ===== Helper filter di semua layer aset =====
      function setWhereOnAll(where){ asetLayers.forEach(l => l.definitionExpression = where); }

      async function queryFeatureCountAll(where){
        const counts = await Promise.all(asetLayers.map(l => l.queryFeatureCount({ where })));
        return counts.reduce((a,b)=>a+(b||0), 0);
      }

      async function zoomToWhere(where, fallbackCenterLngLat=null, fallbackZoom=11){
        try{
          const extents = await Promise.all(asetLayers.map(l => l.queryExtent({ where })));
          const exts = extents.map(e=>e?.extent).filter(x => x && isFinite(x.xmin));
          let target = null;
          if (exts.length === 1) target = exts[0].expand(1.2);
          if (exts.length > 1)  target = exts.reduce((u, e) => u ? u.union(e) : e, null).expand(1.2);
          if (target) { await view.goTo({ target }, { duration: 800 }); return true; }
        }catch(e){ console.warn('queryExtent failed:', e); }
        if (fallbackCenterLngLat){ await view.goTo({ center: fallbackCenterLngLat, zoom: fallbackZoom }, { duration: 800 }); }
        return false;
      }

      function updateFilterCount(n){
        const el=$('filterCount');
        el.style.display='block';
        el.innerHTML=`Menampilkan <strong>${Number(n||0).toLocaleString('id-ID')}</strong> aset`;
      }

      await Promise.all(asetLayers.map(l => l.when()));
      try{
        const total = await queryFeatureCountAll('1=1');
        updateFilterCount(total);
      }catch(e){ console.warn(e); }

      // ===== Region Coordinates untuk Zoom =====
      const regionCoordinates = {
        regencies: {
          '6471': { name: 'Balikpapan', lat: -1.2379, lng: 116.8529, zoom: 12 },
          '6472': { name: 'Samarinda', lat: -0.5022, lng: 117.1536, zoom: 12 },
          '6474': { name: 'Bontang', lat:  0.1333, lng: 117.5000, zoom: 13 },
          '6401': { name: 'Paser', lat: -1.7431, lng: 116.2289, zoom: 10 },
          '6402': { name: 'Kutai Kartanegara', lat:-0.2700, lng: 117.1500, zoom:10 },
          '6403': { name: 'Berau', lat:  2.1667, lng: 117.5000, zoom: 10 },
          '6407': { name: 'Kutai Barat', lat: 0.5000, lng: 116.0000, zoom: 9 },
          '6408': { name: 'Kutai Timur', lat: 0.5500, lng: 117.4197, zoom: 9 },
          '6409': { name: 'Penajam Paser Utara', lat:-1.2636, lng:116.6325, zoom:11 },
          '6411': { name: 'Mahakam Ulu', lat: 0.5000, lng: 115.5000, zoom: 9 }
        }
      };

      async function geocodeRegionByName(name, type){
        try{
          const q = `${name}, Kalimantan Timur, Indonesia`;
          const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`;
          const res = await fetch(url, { headers: { 'Accept-Language':'id' }});
          const data = await res.json();
          if(Array.isArray(data) && data.length){
            const lat = parseFloat(data[0].lat);
            const lon = parseFloat(data[0].lon);
            const zoom = type==='district' ? 13 : 14;
            await view.goTo({ center:[lon, lat], zoom }, { duration: 800 });
          }
        }catch(e){ console.warn('Geocode error:', e); }
      }

      // ===== Setup Cascading & Zoom untuk Searchable Selects =====
      function setupCascadingAndZoom() {
        kabupatenSelect = createSearchableSelect(
          'filterKabupatenInput',
          'filterKabupatenDropdown',
          [{value: '', text: 'Pilih Kabupaten/Kota'}, ...(filterData.kabupaten||[]).map(it => ({value: it.id, text: it.name}))],
          'Pilih Kabupaten/Kota',
          false,
          async (regId) => {
            kecamatanSelect.clear();
            kecamatanSelect.setDisabled(!regId);
            kelurahanSelect.clear();
            kelurahanSelect.setDisabled(true);

            if (!regId) {
              kecamatanSelect.updateOptions([{value: '', text: 'Pilih Kabupaten terlebih dahulu'}], 'Pilih Kabupaten terlebih dahulu');
              kelurahanSelect.updateOptions([{value: '', text: 'Pilih Kecamatan terlebih dahulu'}], 'Pilih Kecamatan terlebih dahulu');
              await applyFilters();
              return;
            }

            const kecOptions = (filterData.kecamatan||[])
              .filter(x => String(x.regency_id) === String(regId))
              .map(x => ({value: x.id, text: x.name}));

            if (kecOptions.length === 0) {
              kecamatanSelect.updateOptions([{value: '', text: 'Tidak ada kecamatan'}], 'Tidak ada kecamatan');
              kecamatanSelect.setDisabled(true);
            } else {
              kecamatanSelect.updateOptions([{value: '', text: 'Semua Kecamatan'}, ...kecOptions], 'Semua Kecamatan');
              kecamatanSelect.setDisabled(false);
            }

            await applyFilters();
            const fallback = regionCoordinates.regencies[regId] ? [regionCoordinates.regencies[regId].lng, regionCoordinates.regencies[regId].lat] : null;
            const fallbackZoom = regionCoordinates.regencies[regId]?.zoom || 10;
            await zoomToWhere(`regency_id='${regId}'`, fallback, fallbackZoom);
          }
        );

        kecamatanSelect = createSearchableSelect(
          'filterKecamatanInput',
          'filterKecamatanDropdown',
          [{value: '', text: 'Pilih Kabupaten terlebih dahulu'}],
          'Pilih Kabupaten terlebih dahulu',
          false,
          async (disId) => {
            kelurahanSelect.clear();
            kelurahanSelect.setDisabled(!disId);

            if (!disId) {
              kelurahanSelect.updateOptions([{value: '', text: 'Pilih Kecamatan terlebih dahulu'}], 'Pilih Kecamatan terlebih dahulu');
              await applyFilters();
              return;
            }

            const kelOptions = (filterData.kelurahan||[])
              .filter(x => String(x.district_id) === String(disId))
              .map(x => ({value: x.id, text: x.name}));

            if (kelOptions.length === 0) {
              kelurahanSelect.updateOptions([{value: '', text: 'Tidak ada kelurahan'}], 'Tidak ada kelurahan');
              kelurahanSelect.setDisabled(true);
            } else {
              kelurahanSelect.updateOptions([{value: '', text: 'Semua Kelurahan'}, ...kelOptions], 'Semua Kelurahan');
              kelurahanSelect.setDisabled(false);
            }

            await applyFilters();
            const ok = await zoomToWhere(`district_id='${disId}'`);
            if(!ok){ 
              const kecSelected = (filterData.kecamatan||[]).find(k => String(k.id) === String(disId));
              if(kecSelected) await geocodeRegionByName(kecSelected.name, 'district'); 
            }
          }
        );
        kecamatanSelect.setDisabled(true);

        kelurahanSelect = createSearchableSelect(
          'filterKelurahanInput',
          'filterKelurahanDropdown',
          [{value: '', text: 'Pilih Kecamatan terlebih dahulu'}],
          'Pilih Kecamatan terlebih dahulu',
          false,
          async (vilId) => {
            if(!vilId){ await applyFilters(); return; }

            await applyFilters();
            const ok = await zoomToWhere(`village_id='${vilId}'`);
            if(!ok){ 
              const kelSelected = (filterData.kelurahan||[]).find(k => String(k.id) === String(vilId));
              if(kelSelected) await geocodeRegionByName(kelSelected.name, 'village'); 
            }
          }
        );
        kelurahanSelect.setDisabled(true);
      }

      setupCascadingAndZoom();

      // ===== APPLY & RESET FILTERS =====
      function readFiltersFromUI(){
        currentFilters = {
          regency_id: kabupatenSelect ? kabupatenSelect.getValue() : '',
          district_id: kecamatanSelect ? kecamatanSelect.getValue() : '',
          village_id: kelurahanSelect ? kelurahanSelect.getValue() : '',
          kat_tanah: katTanahSelect ? katTanahSelect.getValue() : '',
          unit_kerja: unitKerjaSelect ? unitKerjaSelect.getValue() : '',
          asal: asalSelect ? asalSelect.getValue() : '',
          pemanfaatan: pemanfaatanSelect ? pemanfaatanSelect.getValue() : '',
          search: $('filterSearch').value || ''
        };
      }
      
      async function applyFilters(){
        readFiltersFromUI();
        const where = buildWhere(currentFilters);
        setWhereOnAll(where);
        try{
          const count = await queryFeatureCountAll(where);
          updateFilterCount(count);
        }catch(e){ console.warn('queryFeatureCount failed:', e); }
      }
      
      // Create global reference for autocomplete to use
      window.applyFiltersIfReady = applyFilters;

      $('btnApplyFilter').addEventListener('click', applyFilters);

      // Search input with debounce for manual typing (not from suggestions)
      let searchTimeout;
      $('filterSearch').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          // Only apply if not selecting from dropdown
          if (!$('filterSearchDropdown').classList.contains('active')) {
            applyFilters();
          }
        }, 800);
      });

      $('btnResetFilter').addEventListener('click', async function(){
        if (kabupatenSelect) kabupatenSelect.clear();
        if (kecamatanSelect) {
          kecamatanSelect.clear();
          kecamatanSelect.setDisabled(true);
          kecamatanSelect.updateOptions([{value: '', text: 'Pilih Kabupaten terlebih dahulu'}], 'Pilih Kabupaten terlebih dahulu');
        }
        if (kelurahanSelect) {
          kelurahanSelect.clear();
          kelurahanSelect.setDisabled(true);
          kelurahanSelect.updateOptions([{value: '', text: 'Pilih Kecamatan terlebih dahulu'}], 'Pilih Kecamatan terlebih dahulu');
        }
        if (katTanahSelect) katTanahSelect.clear();
        if (unitKerjaSelect) unitKerjaSelect.clear();
        if (asalSelect) asalSelect.clear();
        if (pemanfaatanSelect) pemanfaatanSelect.clear();
        
        // Clear search
        $('filterSearch').value = '';
        $('filterSearchClear').style.display = 'none';
        $('filterSearchDropdown').classList.remove('active');
        
        currentFilters = {regency_id:'',district_id:'',village_id:'',kat_tanah:'',unit_kerja:'',asal:'',pemanfaatan:'',search:''};

        const where = '1=1';
        setWhereOnAll(where);
        try{
          const count = await queryFeatureCountAll(where);
          updateFilterCount(count);
        }catch(e){ console.warn('queryFeatureCount failed:', e); }
        view.goTo({ center:[117.15, -0.5], zoom: 10 }, { duration: 600 });
      });

      // ===== Detail panel =====
      const panel=$('detailPanel');
      $('dpClose').addEventListener('click',()=>panel.classList.remove('show'));
      document.addEventListener('keydown',e=>{ if(e.key==='Escape' && !$('sertifikatModal').classList.contains('show')) panel.classList.remove('show'); });

      const setText=(id,val)=>{ $(id).textContent=(val && String(val).trim()!=='')?val:'-'; };

      function openPanel(attrs){
        const m2 = Number(attrs.luas_m2 || 0);
        setText('dpId', attrs.id || '-');
        setText('dpKodeAsset', attrs.kode_asset || '-');
        setText('dpNama', attrs.nama_asset || '-');
        setText('dpNoRegister', attrs.no_register || '-');
        setText('dpKode', attrs.kode || '-');
        setText('dpLuas', fmt(m2));
        setText('dpNilaiPerolehan', (attrs.nilai_perolehan && !isNaN(attrs.nilai_perolehan)) ? ('Rp ' + fmt(attrs.nilai_perolehan)) : '-');

        setText('dpUnitKerja', attrs.unit_kerja || '-');
        setText('dpKatTanah', attrs.kat_tanah || '-');
        setText('dpPenggunaan', attrs.penggunaan_spma || '-');
        setText('dpPemanfaatan', attrs.pemanfaatan || '-');
        setText('dpAsal', attrs.asal || '-');

        setText('dpAlamat', attrs.alamat || '-');
        setText('dpKelurahan', attrs.kelurahan || attrs.village || '-');
        setText('dpKecamatan', attrs.kecamatan || attrs.district || '-');
        setText('dpKabupaten', attrs.kabupaten || attrs.regency || '-');
        setText('dpProvinsi', attrs.provinsi || attrs.province || '-');

        const lat = attrs.latitude ?? attrs.lat ?? null;
        const lng = attrs.longitude ?? attrs.lng ?? null;
        setText('dpLatitude',  (lat!==null && lat!=='' && !isNaN(lat)) ? Number(lat).toFixed(6) : '-');
        setText('dpLongitude', (lng!==null && lng!=='' && !isNaN(lng)) ? Number(lng).toFixed(6) : '-');

        setText('dpNomorHak', attrs.nomor_hak || '-');
        setText('dpJenisHak', attrs.jenis_hak || '-');

        setText('dpTglSertif', formatTanggalIndonesia(attrs.tgl_sertif));
        setText('dpTahun', attrs.tahun || '-');

        const link = attrs.sertifikat_url || attrs.link_sertif || attrs.file_url || null;
        const namaAset = attrs.nama_asset || 'Dokumen';
        
        if (link) {
          $('dpSertifikat').innerHTML = `<a class="dp-link" data-sertifikat-url="${link}" data-nama-aset="${namaAset}">Lihat Dokumen ⦿</a>`;
          
          // Event listener untuk buka modal
          $('dpSertifikat').querySelector('a').addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-sertifikat-url');
            const nama = this.getAttribute('data-nama-aset');
            openSertifikatModal(url, nama);
          });
        } else {
          $('dpSertifikat').innerHTML = '-';
        }

        panel.classList.add('show');
      }

      // HitTest & highlight pada semua layer aset aktif
      let highlightHandle=null;
      const layerViews = new Map();
      asetLayers.forEach(l => view.whenLayerView(l).then(lv => layerViews.set(l.uid, lv)));

      view.on("pointer-move", evt=>{
        view.hitTest(evt,{include: asetLayers}).then(res=>{
          const hit = res.results.some(r=>r.graphic && asetLayers.includes(r.graphic.layer));
          view.container.style.cursor = hit ? "pointer" : "default";
        });
      });

      view.on("click", evt=>{
        view.hitTest(evt,{include: asetLayers}).then(res=>{
          const r = res.results.find(x=>x.graphic && asetLayers.includes(x.graphic.layer));
          if(!r||!r.graphic){
            panel.classList.remove('show');
            if(highlightHandle){highlightHandle.remove();highlightHandle=null;}
            return;
          }
          if(highlightHandle){highlightHandle.remove();highlightHandle=null;}
          const lv = layerViews.get(r.graphic.layer.uid);
          if(lv){ highlightHandle = lv.highlight(r.graphic); }
          openPanel(r.graphic.attributes||{});
        });
      });

    })();
  });
})();
</script>
@endpush