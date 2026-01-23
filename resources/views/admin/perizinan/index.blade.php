@extends('admin.layouts.app')

@section('title', 'Perizinan dan Permohonan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    /* Styling Container Peta */
    #map {
        height: 500px; /* Tinggi peta disesuaikan agar pas */
        width: 100%;
        border-radius: 12px;
        z-index: 1;
    }
    
    /* Card Container untuk Peta */
    .card-section {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    /* Header Halaman */
    .page-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
    }
    .page-meta {
        font-size: 14px;
        color: #6B7280;
    }

    /* Tombol */
    .btn-primary {
        background: var(--accent-2, #2563EB);
        color: #fff;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        transition: opacity 0.2s;
    }
    .btn-primary:hover { opacity: 0.9; }

    /* Legenda Peta */
    .legend-container {
        display: flex;
        gap: 16px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #F3F4F6;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4B5675;
        font-weight: 500;
    }
    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">Perizinan dan Permohonan</div>
  </div>
  <div class="page-actions">
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div style="background:white; padding:16px; border-radius:12px; border:1px solid #E2E8F0;">
        <div style="font-size:12px; color:#6B7280;">Total Perizinan</div>
        <div style="font-size:20px; font-weight:700; color:#111827;">0</div>
    </div>
    </div>


<div class="card-section">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <div>
            <h3 style="font-size:18px; font-weight:700; color:#111827; margin:0;">Peta Sebaran Desa</h3>
            <p style="font-size:14px; color:#6B7280; margin:4px 0 0 0;">Visualisasi status kelistrikan berdasarkan titik koordinat.</p>
        </div>
        <div>
            <a href="{{ route('admin.perizinan.create') }}" class="btn-primary">
                <i class="ri-add-line"></i> Tambah Data
            </a>
        </div>
    </div>

    <div id="map"></div>

    <div class="legend-container">
        <div class="legend-item">
            <span class="dot" style="background: #2AAD27;"></span> Berlistrik PLN
        </div>
        <div class="legend-item">
            <span class="dot" style="background: #FFD326;"></span> Berlistrik Non-PLN
        </div>
        <div class="legend-item">
            <span class="dot" style="background: #CB2B3E;"></span> Tidak Berlistrik
        </div>
    </div>
</div>


<div class="card-section">
    <div style="margin-bottom: 16px;">
        <input type="text" placeholder="Cari Perusahaan..." style="padding:10px; border:1px solid #E2E8F0; border-radius:8px; width:100%; max-width:300px;">
    </div>

    <div style="text-align: center; padding: 40px; color: #9CA3AF;">
        <i class="ri-file-list-3-line" style="font-size: 48px;"></i>
        <p>Area Tabel Data Perizinan (Masukkan kode tabel asli Anda disini)</p>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah elemen map ada di halaman
        if (document.getElementById('map')) {
            
            // --- 1. Inisialisasi Peta ---
            var map = L.map('map').setView([-0.502106, 117.153709], 7); 

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            // --- 2. Definisi Icon Warna ---
            var greenIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });

            var yellowIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });

            var redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });

            function getIconWarna(color) {
                if (color === 'green') return greenIcon;
                if (color === 'yellow') return yellowIcon;
                if (color === 'red') return redIcon;
                return greenIcon;
            }

            // --- 3. Fetch Data ---
            fetch("{{ route('admin.perizinan.map-data') }}")
                .then(response => response.json())
                .then(dataPerizinan => {
                    console.log("Data Map Loaded:", dataPerizinan.length);
                    
                    if(dataPerizinan.length > 0) {
                        dataPerizinan.forEach(desa => {
                            if(desa.lat && desa.lng) {
                                
                                // Popup Detail
                                var popupContent = `
                                    <div style="text-align:center; min-width: 160px;">
                                        <h4 style="margin:0 0 5px 0; font-size:15px; font-weight:700;">${desa.title}</h4>
                                        <div style="font-size:12px; color:#555; margin-bottom:8px;">${desa.lokasi || '-'}</div>
                                        <hr style="margin:6px 0; border-top:1px solid #eee;">
                                        
                                        <div style="margin-top:8px;">
                                            <span style="
                                                display:inline-block;
                                                background:${desa.color === 'green' ? '#d1fae5' : (desa.color === 'yellow' ? '#fef3c7' : '#fee2e2')};
                                                color:${desa.color === 'green' ? '#065f46' : (desa.color === 'yellow' ? '#92400e' : '#991b1b')};
                                                padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold;
                                            ">
                                                ${desa.status_label}
                                            </span>
                                        </div>
                                        
                                        <div style="margin-top:12px;">
                                            <a href="/admin/perizinan/${desa.id}" style="
                                                color:#2563EB; font-size:12px; font-weight:600; text-decoration:none;
                                                display:inline-flex; align-items:center; gap:4px;
                                            ">
                                                Lihat Detail &rarr;
                                            </a>
                                        </div>
                                    </div>
                                `;

                                L.marker([desa.lat, desa.lng], {icon: getIconWarna(desa.color)})
                                    .bindPopup(popupContent)
                                    .addTo(map);
                            }
                        });
                    }
                })
                .catch(error => console.error('Gagal mengambil data map:', error));
        }
    });
</script>
@endpush