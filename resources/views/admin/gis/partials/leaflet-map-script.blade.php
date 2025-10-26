<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // 🗺️ LEAFLET MAP INITIALIZATION
    // ========================================
    
    const map = L.map('map').setView([-0.5, 117.15], 10); // Kalimantan Timur

    // Base layer - OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // ========================================
    // 📊 LOAD EXISTING ASSETS FROM API
    // ========================================
    
    const existingAssetsLayer = L.layerGroup().addTo(map);
    let existingAssetsData = [];
    let showExistingAssets = true;
    
    // Get current asset ID (untuk mode edit)
    const currentAssetId = window.location.pathname.match(/\/asset\/(\d+)\/edit/)?.[1];
    
    async function loadExistingAssets() {
        try {
            const response = await fetch('/api/aset');
            const geojsonData = await response.json();
            
            if (geojsonData && geojsonData.features) {
                existingAssetsData = geojsonData.features;
                renderExistingAssets();
                
                // Update counter badge
                const otherAssetsCount = geojsonData.features.filter(f => 
                    String(f.properties.id) !== String(currentAssetId)
                ).length;
                
                document.getElementById('existingAssetsCount').textContent = otherAssetsCount;
                
                console.log(`✅ Loaded ${otherAssetsCount} existing assets`);
            }
        } catch (error) {
            console.error('❌ Error loading existing assets:', error);
        }
    }
    
    function renderExistingAssets() {
        existingAssetsLayer.clearLayers();
        
        if (!showExistingAssets) return;
        
        existingAssetsData.forEach(feature => {
            // Skip current asset jika sedang edit
            if (String(feature.properties.id) === String(currentAssetId)) {
                return;
            }
            
            const layer = L.geoJSON(feature, {
                style: {
                    color: '#ef4444',        // Abu-abu
                    fillColor: '#ef4444',    // Abu-abu terang
                    fillOpacity: 0.3,
                    weight: 2,
                    dashArray: '5, 5'        // Garis putus-putus
                },
                pointToLayer: function(feature, latlng) {
                    return L.circleMarker(latlng, {
                        radius: 8,
                        fillColor: '#64748b',
                        color: '#475569',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.6
                    });
                }
            });
            
            // Popup untuk existing assets
            layer.bindPopup(`
                <div style="font-family: system-ui; min-width: 200px;">
                    <div style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); 
                                color: white; padding: 8px 12px; margin: -10px -10px 10px -10px; 
                                border-radius: 4px 4px 0 0;">
                        <strong style="font-size: 14px;">${feature.properties.nama_asset || 'Tanpa Nama'}</strong>
                    </div>
                    <table style="width: 100%; font-size: 12px; line-height: 1.6;">
                        <tr>
                            <td style="color: #64748b; padding: 4px 0;">Kode:</td>
                            <td style="font-weight: 600;">${feature.properties.kode_asset || '-'}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; padding: 4px 0;">Unit Kerja:</td>
                            <td style="font-weight: 600;">${feature.properties.unit_kerja || '-'}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; padding: 4px 0;">Kabupaten:</td>
                            <td style="font-weight: 600;">${feature.properties.kabupaten || feature.properties.regency || '-'}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; padding: 4px 0;">Luas:</td>
                            <td style="font-weight: 600; color: #0ea5e9;">${feature.properties.luas_m2 || 0} m²</td>
                        </tr>
                    </table>
                    <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8;">
                        💡 Asset yang sudah ada
                    </div>
                </div>
            `, {
                maxWidth: 300
            });
            
            layer.addTo(existingAssetsLayer);
        });
    }
    
    // Load existing assets saat halaman load
    loadExistingAssets();

    // ========================================
    // 🎨 DRAWING CONTROL (Polygon & Rectangle ONLY)
    // ========================================
    
    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            polygon: {
                allowIntersection: false,
                shapeOptions: {
                    color: '#2563eb',         // Biru untuk asset baru
                    fillColor: '#3b82f6',
                    fillOpacity: 0.5,
                    weight: 3
                }
            },
            polyline: false,
            rectangle: {
                shapeOptions: {
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.5,
                    weight: 3
                }
            },
            circle: false,
            circlemarker: false,
            marker: false  // ❌ MARKER DISABLED
        },
        edit: {
            featureGroup: drawnItems,
            remove: true
        }
    });
    map.addControl(drawControl);

    // ========================================
    // ✏️ DRAWING EVENTS
    // ========================================
    
    map.on(L.Draw.Event.CREATED, function(e) {
        const layer = e.layer;
        
        // Clear previous drawings
        drawnItems.clearLayers();
        drawnItems.addLayer(layer);

        // Get GeoJSON dan centroid
        const geoJSON = layer.toGeoJSON();
        const bounds = layer.getBounds();
        const center = bounds.getCenter();

        // Update coordinates dengan centroid polygon
        updateCoordinates(center.lat, center.lng, geoJSON);
    });

    map.on(L.Draw.Event.EDITED, function(e) {
        const layers = e.layers;
        layers.eachLayer(function(layer) {
            const geoJSON = layer.toGeoJSON();
            const bounds = layer.getBounds();
            const center = bounds.getCenter();
            updateCoordinates(center.lat, center.lng, geoJSON);
        });
    });

    map.on(L.Draw.Event.DELETED, function(e) {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('geojson').value = '';
    });

    // ========================================
    // 🔄 UPDATE FORM FIELDS
    // ========================================
    
    function updateCoordinates(lat, lng, geoJSON = null) {
        // Set latitude & longitude (centroid dari polygon)
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);

        // Set GeoJSON geometry
        if (geoJSON && geoJSON.geometry) {
            document.getElementById('geojson').value = JSON.stringify(geoJSON.geometry);
        } else {
            document.getElementById('geojson').value = '';
        }
    }

    // ========================================
    // 🗑️ CLEAR MAP BUTTON
    // ========================================
    
    document.getElementById('clearMapBtn').addEventListener('click', function() {
        drawnItems.clearLayers();
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('geojson').value = '';
    });

    // ========================================
    // 👁️ TOGGLE EXISTING ASSETS
    // ========================================
    
    document.getElementById('toggleExistingBtn').addEventListener('click', function() {
        showExistingAssets = !showExistingAssets;
        
        if (showExistingAssets) {
            renderExistingAssets();
            this.innerHTML = '<i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Sembunyikan Asset Lain';
            this.classList.remove('btn-light-primary');
            this.classList.add('btn-primary');
        } else {
            existingAssetsLayer.clearLayers();
            this.innerHTML = '<i class="ki-duotone ki-eye-slash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Tampilkan Asset Lain';
            this.classList.remove('btn-primary');
            this.classList.add('btn-light-primary');
        }
    });

    // ========================================
    // 🔍 ZOOM TO ALL ASSETS
    // ========================================
    
    document.getElementById('zoomAllBtn').addEventListener('click', function() {
        if (existingAssetsData.length > 0) {
            const bounds = L.geoJSON(existingAssetsData).getBounds();
            
            // Include current drawn items
            if (drawnItems.getLayers().length > 0) {
                bounds.extend(drawnItems.getBounds());
            }
            
            map.fitBounds(bounds, { padding: [50, 50] });
        } else {
            alert('Tidak ada data asset untuk di-zoom');
        }
    });

    // ========================================
    // 📍 AUTO ZOOM TO REGION
    // ========================================
    
    const regionCoordinates = {
        // Kabupaten/Kota di Kalimantan Timur
        regencies: {
            '6471': { name: 'Balikpapan', lat: -1.2379, lng: 116.8529, zoom: 12 },
            '6472': { name: 'Samarinda', lat: -0.5022, lng: 117.1536, zoom: 12 },
            '6474': { name: 'Bontang', lat: 0.1333, lng: 117.5000, zoom: 13 },
            '6402': { name: 'Berau', lat: 2.1667, lng: 117.5000, zoom: 10 },
            '6403': { name: 'Paser', lat: -1.7431, lng: 116.2289, zoom: 10 },
            '6407': { name: 'Kutai Barat', lat: 0.5000, lng: 116.0000, zoom: 9 },
            '6408': { name: 'Kutai Timur', lat: 0.5500, lng: 117.4197, zoom: 9 },
            '6409': { name: 'Kutai Kartanegara', lat: -0.2700, lng: 117.1500, zoom: 10 },
            '6410': { name: 'Penajam Paser Utara', lat: -1.2636, lng: 116.6325, zoom: 11 },
            '6411': { name: 'Mahakam Ulu', lat: 0.5000, lng: 115.5000, zoom: 9 }
        }
    };

    // Event listener untuk kabupaten
    const regencySelect = document.getElementById('reg_regencies_id');
    const districtSelect = document.getElementById('reg_districts_id');
    const villageSelect = document.getElementById('reg_villages_id');

    if (regencySelect) {
        regencySelect.addEventListener('change', function() {
            const regencyId = this.value;
            if (regencyId && regionCoordinates.regencies[regencyId]) {
                const coord = regionCoordinates.regencies[regencyId];
                map.setView([coord.lat, coord.lng], coord.zoom);
                
                // Tampilkan notifikasi
                L.popup()
                    .setLatLng([coord.lat, coord.lng])
                    .setContent(`<b>${coord.name}</b><br>Gunakan tools di kanan atas untuk menggambar polygon aset`)
                    .openOn(map);
            }
        });
    }

    // Geocoding untuk kecamatan/kelurahan (menggunakan Nominatim OSM)
    async function geocodeRegion(regionName, regionType) {
        try {
            const query = `${regionName}, Kalimantan Timur, Indonesia`;
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`
            );
            const data = await response.json();
            
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                const zoom = regionType === 'district' ? 13 : 14;
                
                map.setView([lat, lng], zoom);
                
                L.popup()
                    .setLatLng([lat, lng])
                    .setContent(`<b>${regionName}</b><br>Gunakan tools di kanan atas untuk menggambar polygon aset`)
                    .openOn(map);
            }
        } catch (error) {
            console.error('Geocoding error:', error);
        }
    }

    if (districtSelect) {
        districtSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const districtName = selectedOption.text;
            if (districtName && districtName !== 'Pilih kecamatan') {
                geocodeRegion(districtName, 'district');
            }
        });
    }

    if (villageSelect) {
        villageSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const villageName = selectedOption.text;
            if (villageName && villageName !== 'Pilih kelurahan') {
                geocodeRegion(villageName, 'village');
            }
        });
    }

    // ========================================
    // 🔄 LOAD EXISTING DATA (for edit mode)
    // ========================================
    
    const existingLat = document.getElementById('latitude').value;
    const existingLng = document.getElementById('longitude').value;
    const existingGeoJSON = document.getElementById('geojson').value;

    if (existingGeoJSON && existingGeoJSON !== '') {
        try {
            const geojson = JSON.parse(existingGeoJSON);
            const layer = L.geoJSON(geojson, {
                style: {
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.5,
                    weight: 3
                }
            });
            
            drawnItems.addLayer(layer.getLayers()[0]);
            map.fitBounds(drawnItems.getBounds());
        } catch (e) {
            console.error('Error loading existing GeoJSON:', e);
        }
    }

    console.log('✅ Leaflet map initialized successfully (Polygon & Rectangle only)');
});
</script>