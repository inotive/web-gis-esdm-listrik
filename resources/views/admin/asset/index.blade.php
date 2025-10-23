@extends('admin.layouts.app')

@section('title', 'Data Asset - Dinas ESDM')
@section('page-title', 'Data Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Data Asset</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
<style>
    .table thead tr { background-color: #f9fafb; }
    .table th, .table td { vertical-align: middle; }
    .table th { color: #6b7280; font-weight: 600; }
    .filter-label { font-size: 0.8rem; color: #6b7280; margin-bottom: 4px; }
    .filter-section { 
        background-color: #f9fafb; 
        border-radius: 8px; 
        padding: 1rem 1.5rem; 
        margin-bottom: 1.5rem;
    }
    .filter-section .form-control, .filter-section .form-select {
        border-radius: 0.475rem;
    }
    .action-buttons .btn {
        border-radius: 0.475rem !important;
        padding: 0.4rem 0.55rem;
    }
    
    /* Pagination Styling */
    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-link {
        border-radius: 0.475rem !important;
        margin: 0 2px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .pagination .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
        color: #374151;
    }
    .pagination .page-item.active .page-link {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: #ffffff;
        font-weight: 600;
    }
    .pagination .page-item.disabled .page-link {
        background-color: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
    }
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    /* ✅ View Toggle Buttons */
    #viewDiv {
        height: 600px;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    .view-toggle-btn {
        padding: 0.6rem 1.5rem;
        border: 2px solid #e5e7eb;
        background: #ffffff;
        color: #6b7280;
        font-weight: 600;
        transition: all 0.3s ease;
        border-radius: 0.475rem;
        cursor: pointer;
    }

    .view-toggle-btn:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        color: #374151;
    }

    .view-toggle-btn.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .view-toggle-btn i {
        margin-right: 8px;
    }

    .view-section {
        display: none;
        animation: fadeIn 0.4s ease-in-out;
    }

    .view-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .toggle-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .toggle-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .stats-badge {
        background: #ffffff;
        padding: 0.5rem 1rem;
        border-radius: 0.475rem;
        border: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .stats-badge strong {
        color: #2563eb;
        font-weight: 700;
    }

    /* Sertifikat Badge Styling */
    .cert-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .cert-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .cert-badge-file {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .cert-badge-file:hover {
        background: #a7f3d0;
    }

    .cert-badge-link {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .cert-badge-link:hover {
        background: #93c5fd;
    }

    .cert-badge-none {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
</style>
@endpush

@section('content')
{{-- Alert Handling --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-information fs-2x text-danger me-4"></i>
            <div>
                <h4 class="mb-1 text-danger">Terjadi Kesalahan</h4>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-shield-tick fs-2x text-success me-4"></i>
            <div>
                <h4 class="mb-1 text-success">Berhasil!</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="ki-duotone ki-information fs-2x text-danger me-4"></i>
            <div>
                <h4 class="mb-1 text-danger">Error!</h4>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="card-title fw-bold fs-3 mb-0">Data Asset</h3>
                <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
            </div>
            <a href="{{ route('admin.asset.create') }}" class="btn btn-success btn-sm d-flex align-items-center">
                <i class="ki-duotone ki-plus fs-3 me-2"></i>Tambah Asset
            </a>
        </div>

        <div class="card-body py-4">
            
           

            {{-- ✅ SECTION 1: TABLE VIEW --}}
            <div id="table-view" class="view-section active">
                {{-- Filter Section --}}
                <form method="GET" class="filter-section">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="search" class="filter-label">Cari Asset</label>
                            <input type="text" name="search" id="search" class="form-control form-control-sm" 
                                   placeholder="Nama atau Kode Asset" value="{{ $filters['search'] }}">
                        </div>
                        <div class="col-md-4">
                            <label for="filter" class="filter-label">Filter Berdasarkan</label>
                            <select name="filter" id="filter" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <optgroup label="Kategori Asset">
                                    @foreach ($kategoriList as $kategori)
                                        <option value="kategori:{{ $kategori->id }}" 
                                            @selected($filters['filter'] === 'kategori:' . $kategori->id)>
                                            {{ $kategori->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Status Hukum Asset">
                                    @foreach ($statusList as $status)
                                        <option value="status:{{ $status->id }}" 
                                            @selected($filters['filter'] === 'status:' . $status->id)>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Unit Kerja">
                                    @foreach ($unitKerjaList as $unit)
                                        <option value="unit:{{ $unit->id }}" 
                                            @selected($filters['filter'] === 'unit:' . $unit->id)>
                                            {{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Provinsi">
                                    @foreach ($provinsiList as $provinsi)
                                        <option value="provinsi:{{ $provinsi->id }}" 
                                            @selected($filters['filter'] === 'provinsi:' . $provinsi->id)>
                                            {{ $provinsi->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="per_page" class="filter-label">Per Halaman</label>
                            <select name="per_page" id="per_page" class="form-select form-select-sm">
                                @foreach ([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="ki-duotone ki-filter fs-4 me-2"></i> Terapkan
                            </button>
                            <a href="{{ route('admin.asset.index') }}" class="btn btn-light btn-sm flex-fill">
                                <i class="ki-duotone ki-reload fs-4 me-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Table Section --}}
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="text-center" style="width: 60px;">No</th>
                                <th>Kode Asset</th>
                                <th>Nama Asset</th>
                                <th>Kategori</th>
                                <th>Unit Kerja</th>
                                <th>Status Hukum</th>
                                 <th>Asal</th>
                                <th>Kat. Tanah</th>
                                <th>Kabupaten</th>
                                <th>Kecamatan</th>
                                <th>Lokasi</th>
                                <th class="text-center">Sertifikat</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assets as $asset)
                                <tr>
                                    <td class="text-center">{{ ($assets->currentPage() - 1) * $assets->perPage() + $loop->iteration }}</td>
                                    <td>{{ $asset->kode_asset }}</td>
                                    <td>{{ $asset->nama_asset }}</td>
                                    <td>{{ $asset->kategori->name ?? '-' }}</td>
                                    <td>{{ $asset->unitKerja->nama_unit ?? '-' }}</td>
                                    <td>{{ $asset->statusHukum->name ?? '-' }}</td>
                                     <td>
                                        @if($asset->asal)
                                            <span class="badge badge-light-info">{{ $asset->asal }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($asset->kat_tanah)
                                            <span class="badge badge-light-success">{{ $asset->kat_tanah }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $asset->regency->name ?? '-' }}</td>
                                    <td>{{ $asset->district->name ?? '-' }}</td>
                                    <td>{{ $asset->village->name ?? $asset->province->name ?? '-' }}</td>
                                    
                                    {{-- ✅ KOLOM SERTIFIKAT --}}
<td class="text-center">
    @if($asset->dokumenUtama && $asset->dokumenUtama->file_sertif)
        <a href="{{ asset('storage/' . $asset->dokumenUtama->file_sertif) }}" 
           target="_blank" 
           class="cert-badge cert-badge-file" 
           title="Lihat File Sertifikat">
            <i class="ki-duotone ki-file fs-5">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            File
        </a>
    @elseif($asset->dokumenUtama && $asset->dokumenUtama->link_sertif)
        <a href="{{ $asset->dokumenUtama->link_sertif }}" 
           target="_blank" 
           class="cert-badge cert-badge-link" 
           title="Buka Link Sertifikat">
            <i class="ki-duotone ki-link fs-5">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            Link
        </a>
    @else
        <span class="cert-badge cert-badge-none">
            <i class="ki-duotone ki-information fs-5">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            Belum Ada
        </span>
    @endif
</td>
                                    
                                    <td class="text-center action-buttons">
                                        <a href="{{ route('admin.asset.edit', $asset) }}" 
                                           class="btn btn-light-warning btn-sm me-1" title="Edit">
                                            <i class="ki-duotone ki-pencil fs-4">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <form action="{{ route('admin.asset.destroy', $asset) }}" 
                                              method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-light-danger btn-sm btn-delete" title="Hapus">
                                                <i class="ki-duotone ki-trash fs-4">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-5">Tidak ada data asset.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $assets->firstItem() ?? 0 }}</strong> - <strong>{{ $assets->lastItem() ?? 0 }}</strong> dari <strong>{{ $assets->total() }}</strong> data
                    </div>
                    <div>
                        {{ $assets->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

            {{-- ✅ SECTION 2: MAP VIEW --}}
            <div id="map-view" class="view-section">
                <div class="mb-3">
                    <h4 class="fw-bold mb-2">
                        <i class="ki-duotone ki-geolocation fs-2 text-primary me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Peta Sebaran Aset
                    </h4>
                    <p class="text-muted mb-0">Klik pada polygon untuk melihat detail informasi aset</p>
                </div>
                <div id="viewDiv"></div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.arcgis.com/4.29/"></script>
<script>
// ✅ VIEW TOGGLE FUNCTIONALITY
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.view-toggle-btn');
    const tableView = document.getElementById('table-view');
    const mapView = document.getElementById('map-view');
    let mapInitialized = false;

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const viewType = this.getAttribute('data-view');
            
            // Update active button
            toggleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Toggle views
            if (viewType === 'table') {
                tableView.classList.add('active');
                mapView.classList.remove('active');
            } else if (viewType === 'map') {
                tableView.classList.remove('active');
                mapView.classList.add('active');
                
                // Initialize map only once
                if (!mapInitialized) {
                    initializeMap();
                    mapInitialized = true;
                }
            }
        });
    });
});

// ✅ MAP INITIALIZATION
function initializeMap() {
    require([
      "esri/Map",
      "esri/views/MapView",
      "esri/layers/GeoJSONLayer",
      "esri/layers/WebTileLayer",
      "esri/widgets/Legend",
      "esri/widgets/Expand",
      "esri/widgets/Home",
      "esri/widgets/BasemapToggle"
    ], function(Map, MapView, GeoJSONLayer, WebTileLayer, Legend, Expand, Home, BasemapToggle) {

      console.log("🚀 Memulai inisialisasi peta...");

      // --- 1️⃣ Buat basemap dengan OSM
      const osmLayer = new WebTileLayer({
        urlTemplate: "https://{subDomain}.tile.openstreetmap.org/{level}/{col}/{row}.png",
        subDomains: ["a", "b", "c"],
        copyright: "© OpenStreetMap contributors",
        title: "OpenStreetMap"
      });

      const map = new Map({
        layers: [osmLayer]
      });

      // --- 2️⃣ Inisialisasi MapView
      const view = new MapView({
        container: "viewDiv",
        map: map,
        center: [117.15, -0.5], // Kalimantan Timur
        zoom: 8,
        popup: {
          dockEnabled: true,
          dockOptions: {
            buttonEnabled: false,
            breakpoint: false
          }
        }
      });

      // --- 3️⃣ Konfigurasi GeoJSON Layer
      const asetLayer = new GeoJSONLayer({
        url: "{{ url('/api/aset') }}",
        title: "Data Aset Pemerintah",
        outFields: ["*"],
        
        popupTemplate: {
          title: "<b style='color: #2563eb; font-size: 16px;'>{nama_asset}</b>",
          content: function(feature) {
            const p = feature.graphic.attributes;
            console.log("📍 Popup Data:", p);
            
            const lat = p.latitude ? parseFloat(p.latitude).toFixed(6) : '-';
            const long = p.longitude ? parseFloat(p.longitude).toFixed(6) : '-';
            
            return `
              <div style="font-family: system-ui; font-size: 13px; line-height: 1.6;">
                <table style="width: 100%; border-collapse: collapse;">
                  <tr style="background: #f8fafc;">
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Kode Aset</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.kode_asset || "-"}</td>
                  </tr>
                  <tr>
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Unit Kerja</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.unit_kerja || "-"}</td>
                  </tr>
                  <tr style="background: #f8fafc;">
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Penggunaan</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.penggunaan_spma || "-"}</td>
                  </tr>
                  <tr>
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Alamat</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.alamat || "-"}</td>
                  </tr>
                  <tr style="background: #f8fafc;">
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Nomor Hak</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.nomor_hak || "-"}</td>
                  </tr>
                  <tr>
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Jenis Hak</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.jenis_hak || "-"}</td>
                  </tr>
                  <tr style="background: #f8fafc;">
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Kabupaten</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.kabupaten || p.regency || "-"}</td>
                  </tr>
                  <tr>
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Kecamatan</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.kecamatan || p.district || "-"}</td>
                  </tr>
                  <tr style="background: #f8fafc;">
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Kelurahan</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">${p.kelurahan || p.village || "-"}</td>
                  </tr>
                  <tr>
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Latitude</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;"><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f766e;">${lat}</code></td>
                  </tr>
                  <tr style="background: #f8fafc;">
                    <td style="padding: 8px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0;">Longitude</td>
                    <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;"><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f766e;">${long}</code></td>
                  </tr>
                  <tr>
                    <td style="padding: 8px; font-weight: 600; color: #475569;">Luas</td>
                    <td style="padding: 8px;"><span style="font-weight: 700; color: #2563eb;">${p.luas_m2 || "0"} m²</span></td>
                  </tr>
                </table>
              </div>
            `;
          },
          overwriteActions: true
        },
        
        renderer: {
          type: "simple",
          symbol: {
            type: "simple-fill",
            color: [37, 99, 235, 0.4],
            outline: {
              color: [29, 78, 216],
              width: 2
            }
          }
        }
      });

      map.add(asetLayer);

      // --- Widgets
      const homeBtn = new Home({ view: view });
      view.ui.add(homeBtn, "top-left");

      const legend = new Legend({
        view: view,
        layerInfos: [{ layer: asetLayer, title: "Aset Pemerintah" }]
      });

      const legendExpand = new Expand({
        view: view,
        content: legend,
        expanded: false,
        expandIconClass: "esri-icon-layer-list"
      });
      view.ui.add(legendExpand, "top-right");

      // --- Event handlers
      view.when(() => {
        console.log("✅ Map view siap");
      }).catch((error) => {
        console.error("❌ Error loading map view:", error);
      });

      asetLayer.when(() => {
        console.log("✅ Layer aset berhasil dimuat!");
        
        asetLayer.queryFeatureCount().then((count) => {
          console.log(`📊 Total aset di peta: ${count}`);
          if (count === 0) {
            console.warn("⚠️ Tidak ada data aset dengan geometry!");
          }
        });
      }).catch((error) => {
        console.error("❌ Gagal memuat layer aset:", error);
      });

      console.log("✅ Script peta selesai diinisialisasi");
    });
}
</script>

{{-- ✅ SWEETALERT DELETE CONFIRMATION --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('.delete-form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data asset ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush