@extends('landing.layout.app')

@section('title', 'ESDM')

@section('content')
    <div id="viewDiv">
        <!-- PANEL DETAIL -->
        <aside id="detailPanel" class="detail-panel" aria-live="polite">
            <div class="dp-head">
                <div class="dp-title">Detail Aset</div>
                <button id="dpClose" class="dp-close" title="Tutup">✕</button>
            </div>

            <div class="dp-grid">
                <div class="dp-item full">
                    <div class="dp-label">Unit Kerja</div>
                    <div class="dp-value" id="dpUnitKerja">-</div>
                </div>

                <div class="dp-item">
                    <div class="dp-label">Nama</div>
                    <div class="dp-value" id="dpNama">-</div>
                </div>
                <div class="dp-item">
                    <div class="dp-label">Luas (m²)</div>
                    <div class="dp-value" id="dpLuas">-</div>
                </div>
                <div class="dp-item">
                    <div class="dp-label">Kelurahan</div>
                    <div class="dp-value" id="dpKelurahan">-</div>
                </div>
                <div class="dp-item">
                    <div class="dp-label">Kecamatan</div>
                    <div class="dp-value" id="dpKecamatan">-</div>
                </div>
                <div class="dp-item">
                    <div class="dp-label">Kabupaten</div>
                    <div class="dp-value" id="dpKabupaten">-</div>
                </div>
                <div class="dp-item">
                    <div class="dp-label">Provinsi</div>
                    <div class="dp-value" id="dpProvinsi">-</div>
                </div>
                <div class="dp-item full">
                    <div class="dp-label">Alamat</div>
                    <div class="dp-value" id="dpAlamat">-</div>
                </div>
                <div class="dp-item full">
                    <div class="dp-label">Sertifikat</div>
                    <div class="dp-value" id="dpSertifikat">-</div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Modal Video 360 -->
    <div id="video360Modal" class="video360-modal" style="display: none;">
        <div class="video360-modal-overlay" onclick="closeVideo360Modal()"></div>
        <div class="video360-modal-content">
            <div class="video360-modal-header">
                <h3 id="video360ModalTitle">Video 360</h3>
                <button class="video360-modal-close" onclick="closeVideo360Modal()">&times;</button>
            </div>
            <div class="video360-modal-body">
                <iframe id="video360Iframe" src="" frameborder="0" allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; xr-spatial-tracking"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.dojoConfig = {
            async: true
        };
    </script>
    <script src="https://js.arcgis.com/4.29/"></script>

    <script>
        (function() {
            const $ = id => document.getElementById(id);
            const fmt = (n) => (n === null || n === undefined || isNaN(n)) ? "-" : Number(n).toLocaleString('id-ID');

            require([
                "esri/Map",
                "esri/Basemap",
                "esri/views/MapView",
                "esri/layers/GeoJSONLayer",
                "esri/widgets/Legend",
                "esri/widgets/Expand",
                "esri/widgets/Home",
                "esri/widgets/Search",
                "esri/widgets/ScaleBar",
                "esri/widgets/BasemapGallery",
                "esri/widgets/BasemapToggle",
                "esri/widgets/BasemapGallery/support/LocalBasemapsSource",
                "esri/widgets/DistanceMeasurement2D",
                "esri/widgets/Compass",
                "esri/widgets/Track",
                "esri/layers/GraphicsLayer",
                "esri/Graphic",
                "esri/geometry/Circle",
                "esri/widgets/Print"
            ], function(
                Map,
                Basemap,
                MapView,
                GeoJSONLayer,
                Legend,
                Expand,
                Home,
                Search,
                ScaleBar,
                BasemapGallery,
                BasemapToggle,
                LocalBasemapsSource,
                DistanceMeasurement2D,
                Compass,
                Track,
                GraphicsLayer,
                Graphic,
                Circle,
                Print
            ) {

                // ================== MAP & VIEW ==================
                const map = new Map({
                    basemap: Basemap.fromId("satellite")
                });

                // Admin Status from Blade
                const isAdmin = {{ auth()->check() && auth()->user()->hasRole('admin') ? 'true' : 'false' }};

                const view = new MapView({
                    container: "viewDiv",
                    map: map,
                    center: [117.15, -0.5],
                    zoom: 10,
                    popup: {
                        autoOpenEnabled: false
                    }
                });

                // ========= Click Detail Modal =========
                const detailModal = document.createElement('div');
                detailModal.id = 'detailModal';
                detailModal.className = 'detail-modal hidden';
                detailModal.innerHTML = `
      <div class="dm-close" title="Tutup">✕</div>
      <div class="dm-content"></div>
    `;
                view.container.appendChild(detailModal);

                const closeBtn = detailModal.querySelector('.dm-close');
                const dmContent = detailModal.querySelector('.dm-content');

                const escapeHtml = (value) => {
                    if (value === null || value === undefined) return '-';
                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                };

                const renderDetailContent = (graphic) => {
                    const attrs = graphic?.attributes || {};
                    let layerTitle = graphic?.layer?.title || 'Detail Fitur';

                    const pickVideoLink = (itemAttrs) => {
                        const raw = itemAttrs?.link_dokumen;
                        if (!raw) return null;

                        const links = String(raw)
                            .split('|')
                            .map((v) => v.trim())
                            .filter(Boolean);

                        if (!links.length) return null;

                        const isPenajam = (itemAttrs.WADMKC || '').toLowerCase().includes('penajam');
                        const chosen = isPenajam ? links[Math.floor(Math.random() * links.length)] :
                            links[0];

                        return {
                            url: chosen,
                            title: itemAttrs.Kodifikasi || itemAttrs.NAMOBJ || 'Video 360',
                        };
                    };

                    // Jika layer adalah H_Survei, gunakan nilai H_Survei sebagai judul
                    if (layerTitle === 'H_Survei' && attrs.H_Survei) {
                        layerTitle = attrs.H_Survei;
                    }

                    const buildRow = (label, value) =>
                        `<div class="dm-row"><div class="dm-key">${label}</div><div class="dm-val">${value}</div></div>`;
                    let rows = '';

                    // Khusus untuk layer Status Listrik (H_Survei)
                    if (layerTitle === 'H_Survei' || attrs.H_Survei) {
                        // Tampilkan hanya field tertentu dengan urutan dan label yang ditentukan
                        const fieldsToShow = [{
                                key: 'WADMKD',
                                label: 'Desa'
                            },
                            {
                                key: 'WADMKC',
                                label: 'Kecamatan'
                            },
                            {
                                key: 'WADMKK',
                                label: 'Kab/Kota'
                            },
                            {
                                key: 'H_Survei',
                                label: 'Status'
                            }
                        ];

                        rows = fieldsToShow
                            .map(({
                                key,
                                label
                            }) => buildRow(label, escapeHtml(attrs[key])))
                            .join('');
                    } else {
                        // Untuk layer lain, tampilkan semua attributes
                        const linkRows = [];
                        const otherRows = [];

                        const pickedVideo = pickVideoLink(attrs);



                        Object.entries(attrs)
                            .filter(([k]) => k !== 'link_dokumen')
                            .forEach(([k, v]) => {
                                otherRows.push(buildRow(k, escapeHtml(v)));
                            });

                        rows = [...linkRows, ...otherRows].join('');
                    }


                    // Check for Video 360 Button condition
                    let video360Button = '';
                    if (attrs.video_360_link) {
                        video360Button = `
                        <div style="padding: 10px 14px; text-align: center;">
                            <button onclick="openVideo360Modal('${attrs.video_360_link}', 'Video 360 - ${escapeHtml(attrs.NAMOBJ || 'Lokasi')}')"
                                style="background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%;">
                                🎥 Lihat Video 360
                            </button>
                        </div>`;
                    }

                    dmContent.innerHTML = `
        <div class="dm-head">${escapeHtml(layerTitle)}</div>
        ${video360Button}
        <div class="dm-body">${rows || '<div class="dm-empty">Tidak ada atribut</div>'}</div>
      `;
                };

                const showDetailModal = (event, graphic) => {
                    renderDetailContent(graphic);

                    // Posisikan modal di lokasi klik
                    let x = event.x;
                    let y = event.y;

                    // Tampilkan dulu agar bisa menghitung dimensi
                    detailModal.classList.remove('hidden');

                    // Ambil dimensi modal dan viewport
                    const modalRect = detailModal.getBoundingClientRect();
                    const viewportWidth = window.innerWidth;
                    const viewportHeight = window.innerHeight;

                    // Offset dari kursor
                    const offsetX = 15;
                    const offsetY = 15;

                    // Hitung posisi dengan offset
                    let finalX = x + offsetX;
                    let finalY = y + offsetY;

                    // Cek jika modal terpotong di kanan
                    if (finalX + modalRect.width > viewportWidth) {
                        finalX = x - modalRect.width - offsetX;
                        // Jika masih keluar di kiri, set ke batas kiri
                        if (finalX < 0) {
                            finalX = 10;
                        }
                    }

                    // Cek jika modal terpotong di bawah
                    if (finalY + modalRect.height > viewportHeight) {
                        finalY = y - modalRect.height - offsetY;
                        // Jika masih keluar di atas, set ke batas atas
                        if (finalY < 0) {
                            finalY = 10;
                        }
                    }

                    detailModal.style.left = `${finalX}px`;
                    detailModal.style.top = `${finalY}px`;
                };

                const hideDetailModal = () => {
                    detailModal.classList.add('hidden');
                };

                // Close button handler
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    hideDetailModal();
                });

                // Keyboard ESC handler
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') hideDetailModal();
                });

                // Variable untuk tracking mode measurement
                let measurementActive = false;

                // Click event untuk menampilkan detail
                view.on('click', (event) => {
                    // Skip jika sedang dalam mode measurement
                    if (measurementActive) {
                        return;
                    }

                    view.hitTest(event).then((response) => {
                        const graphic = response.results?.[0]?.graphic;

                        // Jika klik di area kosong, tutup modal
                        if (!graphic) {
                            hideDetailModal();
                            return;
                        }

                        // Jika klik di graphic, tampilkan detail
                        showDetailModal(event, graphic);
                    }).catch(() => {
                        hideDetailModal();
                    });
                });

                // ================== LAYERS ==================

                // Helper function to create location pin SVG with custom color
                const createLocationPinSvg = (fillColor, strokeColor = '#000000', size = 32) => {
                    // Convert RGBA array to hex if needed
                    const toHex = (color) => {
                        if (Array.isArray(color)) {
                            const [r, g, b] = color;
                            return '#' + [r, g, b].map(x => {
                                const hex = Math.round(x).toString(16);
                                return hex.length === 1 ? '0' + hex : hex;
                            }).join('');
                        }
                        return color;
                    };

                    const fill = toHex(fillColor);
                    const stroke = toHex(strokeColor);

                    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="${fill}" stroke="${stroke}" stroke-width="1.5"/>
        <circle cx="12" cy="9" r="2.5" fill="${stroke}"/>
      </svg>`;

                    return 'data:image/svg+xml;base64,' + btoa(svg);
                };



                // Container for dynamic layers
                const asetLayers = [];
                const desaLayers = []; // Track Desa layers specifically
                let layerCategories = {}; // Will be populated dynamically

                // ===== Widgets dasar =====

                const home = new Home({
                    view
                });
                view.ui.add(home, {
                    position: "top-left",
                    index: 0
                });

                const compass = new Compass({
                    view
                });
                view.ui.add(compass, {
                    position: "top-left",
                    index: 1
                });

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
                view.ui.add(printExpand, {
                    position: "top-left",
                    index: 2
                });

                // ===== LIVE LOCATION (seperti Google Maps) =====
                const liveLocationLayer = new GraphicsLayer({
                    title: "Lokasi saya",
                    listMode: "hide"
                });
                map.add(liveLocationLayer);

                const accuracyWidget = document.createElement("div");
                accuracyWidget.className = "live-location-accuracy-widget esri-widget esri-component";
                accuracyWidget.innerHTML = "📍 Lokasi belum aktif";
                view.ui.add(accuracyWidget, {
                    position: "bottom-right",
                    index: 1
                });

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
                            color: [59, 130, 246, 1],
                            outline: {
                                color: [255, 255, 255, 1],
                                width: 2
                            }
                        }
                    })
                });
                trackWidget.label = "Ikuti lokasi saya";

                view.ui.add(trackWidget, {
                    position: "top-left",
                    index: 3
                });

                trackWidget.on("track", ({
                    position
                }) => {
                    const coords = position && position.coords ? position.coords : null;
                    if (!coords) return;
                    const {
                        longitude,
                        latitude,
                        accuracy
                    } = coords;

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
                                color: [59, 130, 246, 0.12],
                                outline: {
                                    color: [37, 99, 235, 0.8],
                                    width: 1
                                }
                            }
                        });
                        liveLocationLayer.add(circleGraphic);

                        const akurasiMeter = Math.round(accuracy);
                        accuracyWidget.innerHTML = `📍
                        Lokasi aktif < br > < span > Akurasi± $ {
                            akurasiMeter.toLocaleString('id-ID')
                        }
                        m < /span>`;
                    } else {
                        accuracyWidget.innerHTML =
                            "📍 Lokasi aktif<br><span>Akurasi tidak diketahui</span>";
                    }
                });

                trackWidget.watch("tracking", (isTracking) => {
                    if (!isTracking) {
                        liveLocationLayer.removeAll();
                        accuracyWidget.innerHTML = "📍 Lokasi belum aktif";
                    }
                });


                // ================== DYNAMIC LAYERS LOGIC ==================

                // Helper: Generate consistent pastel color from string
                const stringToColor = (str) => {
                    let hash = 0;
                    for (let i = 0; i < str.length; i++) {
                        hash = str.charCodeAt(i) + ((hash << 5) - hash);
                    }
                    // Generate HSL for pastel colors
                    const h = Math.abs(hash % 360);
                    const s = 70 + Math.abs((hash >> 8) % 30); // 70-100% saturation
                    const l = 45 + Math.abs((hash >> 16) % 15); // 45-60% lightness
                    return `hsl(${h}, ${s}%, ${l}%)`; // Return CSS string
                };

                // Helper: Convert HSL string to RGB array [r, g, b, a] for Esri
                const hslToEsriColor = (str, alpha = 1) => {
                    // Simple hash approach again to get RGB directly if parsing is hard
                    // Or just use the same hash logic to generate RGB
                    let hash = 0;
                    for (let i = 0; i < str.length; i++) {
                        hash = str.charCodeAt(i) + ((hash << 5) - hash);
                    }
                    const r = (hash & 0xFF0000) >> 16;
                    const g = (hash & 0x00FF00) >> 8;
                    const b = hash & 0x0000FF;
                    return [Math.abs(r), Math.abs(g), Math.abs(b), alpha];
                };

                // Helper: Get Icon
                const getIconForCategory = (slug) => {
                    const icons = {
                        'gardu': '🏭',
                        'trafo': '🔧',
                        'pembangkit': '⚡',
                        'jalan': '🛣️',
                        'jaringan': '🔗',
                        'batas': 'uwu',
                        'administrasi': '🗺️',
                        'pelanggan': '🏠'
                    };
                    for (const key in icons) {
                        if (slug.includes(key)) return icons[key];
                    }
                    return '📍';
                };

                // Helper: Create Popup Template
                const createPopupTemplate = (title) => {
                    return {
                        title: title,
                        content: [{
                                type: "fields",
                                fieldInfos: [{
                                        fieldName: "kategori",
                                        label: "Kategori"
                                    },
                                    {
                                        fieldName: "sub_kategori",
                                        label: "Sub Kategori"
                                    },
                                    {
                                        fieldName: "regency_id",
                                        label: "Kode Wilayah"
                                    },
                                    // Add generic property display
                                ]
                            },
                            {
                                type: "text",
                                text: "<b>Properties:</b><br>{properties}" // Attempt to show raw JSON if needed, or we rely on default behavior
                            }
                        ]
                    };
                };

                const layerFilter = document.createElement('div');
                layerFilter.className = 'layer-filter';
                view.ui.add(layerFilter, 'top-left');

                // Helper: Fetch with Timeout
                const fetchWithTimeout = async (resource, options = {}) => {
                    const {
                        timeout = 10000
                    } = options; // Default 10 seconds

                    const controller = new AbortController();
                    const id = setTimeout(() => controller.abort(), timeout);

                    try {
                        const response = await fetch(resource, {
                            ...options,
                            signal: controller.signal
                        });
                        clearTimeout(id);
                        return response;
                    } catch (error) {
                        clearTimeout(id);
                        throw error;
                    }
                };

                // Main Function to Load Structure
                const loadFeatureStructure = async () => {
                    try {
                        const response = await fetchWithTimeout(
                            "{{ url('/api/features/structure') }}", {
                                timeout: 10000
                            });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const structure = await response.json();

                        layerCategories = {}; // Reset
                        asetLayers.length = 0; // Clear array

                        // Convert structure to layerCategories format and create layers
                        structure.forEach((cat) => {
                            const catLayers = [];

                            cat.sub_categories.forEach((sub) => {
                                console.log(sub.label ==
                                    'Status Desa Berlistrik Dengan Bantuan');
                                // Filter Restricted Layers for Non-Admin
                                if (!isAdmin) {
                                    if (sub.label ==
                                        'Status Desa Berlistrik Dengan Bantuan' ||
                                        sub.label == 'Rencana Bantuan Lokasi Pemukiman'
                                    ) {
                                        return;
                                    }
                                }

                                // Define dynamic layer
                                const layerUrl =
                                    `{{ url('/api/features/data') }}?kategori=${encodeURIComponent(cat.slug)}&sub_kategori=${encodeURIComponent(sub.slug)}`;

                                const color = hslToEsriColor(sub.slug);

                                // Cek jika layer ini adalah layer desa untuk labeling
                                const isDesa = sub.label.toLowerCase().includes(
                                    'desa') || sub.slug.toLowerCase().includes(
                                    'desa') || sub.slug.toLowerCase().includes(
                                    'berlistrik');

                                const layer = new GeoJSONLayer({
                                    url: layerUrl,
                                    title: sub.label,
                                    outFields: ["*"],
                                    visible: false, // Default hidden
                                    popupTemplate: {
                                        title: "{sub_kategori}",
                                        content: "{properties}" // Use a generic popup
                                    },
                                    labelingInfo: isDesa ? [{
                                        labelExpressionInfo: {
                                            // Prioritaskan WADMKD (nama desa di data BPS/ESDM) lalu NAMOBJ
                                            expression: "DefaultValue($feature.Desa, $feature.Desa)"
                                        },
                                        symbol: {
                                            type: "text",
                                            color: [255, 255, 0,
                                                255
                                            ], // Kuning terang agar kontras
                                            haloColor: [0, 0, 0,
                                                200
                                            ], // Outline hitam
                                            haloSize: "1.5px",
                                            font: {
                                                size: 11,
                                                family: "sans-serif",
                                                weight: "bold"
                                            }
                                        },
                                        labelPlacement: "always-horizontal" // Memaksa label selalu rata
                                    }] : undefined
                                });

                                // Smart Renderer Assignment
                                layer.watch("visible", (visible) => {
                                    if (visible) {
                                        layer.load().then(() => {
                                            const type = layer
                                                .geometryType;

                                            // Only apply if not already using picture-marker to avoid loop/flicker
                                            // although overwriting is generally safe in this context
                                            if (type == "point" ||
                                                type == "multipoint") {
                                                // Check if we already applied our custom symbol
                                                if (layer.renderer
                                                    ?.symbol?.type ===
                                                    'picture-marker')
                                                    return;

                                                const svgUrl =
                                                    createLocationPinSvg(
                                                        color);
                                                layer.renderer = {
                                                    type: "simple",
                                                    symbol: {
                                                        type: "picture-marker",
                                                        url: svgUrl,
                                                        width: "32px",
                                                        height: "32px"
                                                    }
                                                };
                                            } else if (type ===
                                                "polyline") {
                                                layer.renderer = {
                                                    type: "simple",
                                                    symbol: {
                                                        type: "simple-line",
                                                        color: color,
                                                        width: 2,
                                                        style: "solid"
                                                    }
                                                };
                                            } else if (type ===
                                                "polygon") {
                                                // Fix color alpha for polygon fill
                                                const fillColor = [...
                                                    color
                                                ];
                                                if (fillColor.length ===
                                                    4) fillColor[3] =
                                                    0.4; // If RGBA

                                                layer.renderer = {
                                                    type: "simple",
                                                    symbol: {
                                                        type: "simple-fill",
                                                        color: fillColor, // Transparent fill
                                                        outline: {
                                                            color: color,
                                                            width: 1
                                                        }
                                                    }
                                                };
                                            }
                                        });
                                    }

                                    // Logic for showing/hiding label toggle button
                                    if (isDesa) {
                                        checkDesaLabelButtonVisibility();
                                    }
                                });

                                map.add(layer);
                                asetLayers.push(layer);

                                // Add to Desa layers if applicable
                                if (isDesa) {
                                    desaLayers.push(layer);
                                }

                                catLayers.push({
                                    label: sub.label,
                                    layer: layer,
                                    path: sub
                                        .slug, // Capture the full path (which is now in the slug)
                                    icon: getIconForCategory(sub.slug)
                                });
                            });

                            layerCategories[cat.slug] = {
                                label: cat.label,
                                icon: getIconForCategory(cat.slug),
                                items: catLayers
                            };
                        });

                        buildLayerFilter();

                    } catch (error) {
                        console.error("Failed to load feature structure:", error);
                        let msg = error.message;
                        if (error.name === 'AbortError') {
                            msg = 'Permintaan waktu habis (timeout). Silakan muat ulang.';
                        }
                        layerFilter.innerHTML =
                            `<div class="p-2 text-red-500">Gagal memuat layer: ${msg}</div>`;
                    }
                };

                // Function to build Filter HTML dynamically based on path tree
                const buildLayerFilter = () => {
                    let categoriesHTML = '';

                    // Define preferred order
                    const sortOrder = [
                        'Administrasi',
                        'Status Desa Berlistrik',
                        'Infrastruktur Pendukung Ketenagalistrikan',
                        'Data Jaringan',
                        'Jalan',
                        'Kondisi Titik Pemukiman Non Listrik PLN'
                    ];

                    // Convert to array and sort
                    const sortedEntries = Object.entries(layerCategories).sort((a, b) => {
                        const labelA = a[1].label || '';
                        const labelB = b[1].label || '';

                        // Find index in sortOrder (case-insensitive partial match)
                        let idxA = sortOrder.findIndex(key => labelA.toLowerCase().includes(key
                            .toLowerCase()));
                        let idxB = sortOrder.findIndex(key => labelB.toLowerCase().includes(key
                            .toLowerCase()));

                        // If not found, place at the end
                        if (idxA === -1) idxA = 999;
                        if (idxB === -1) idxB = 999;

                        return idxA - idxB;
                    });

                    for (const [catKey, category] of sortedEntries) {
                        if (category.label.toLowerCase().includes('administrasi')) continue;

                        // Build tree from flat items list
                        const tree = {};
                        category.items.forEach((item, index) => {
                            // Ensure safe access to path
                            const pathStr = item.path || item.label;
                            const parts = pathStr.split('/');
                            let current = tree;
                            parts.forEach((part, i) => {
                                if (!current[part]) {
                                    current[part] = {
                                        label: part, // Improve label formatting if needed?
                                        children: {},
                                        itemIndex: null
                                    };
                                }
                                if (i === parts.length - 1) {
                                    current[part].itemIndex =
                                        index; // Leaf node stores index to original item
                                    current[part].layer = item.layer;
                                    current[part].icon = item.icon;
                                }
                                current = current[part].children;
                            });
                        });

                        // Recursive function to render tree
                        const renderTree = (node, pathContext, rootCategoryKey, level = 0) => {
                            let html = '';
                            const nodeKeys = Object.keys(node);
                            if (nodeKeys.length === 0) return '';

                            nodeKeys.forEach(nodeKey => {
                                const branch = node[nodeKey];
                                const currentPath = pathContext ? `${pathContext}-${nodeKey}` :
                                    nodeKey;
                                // Sanitize ID
                                const safeId = currentPath.replace(/[^a-zA-Z0-9]/g, '-');
                                const hasChildren = Object.keys(branch.children).length > 0;
                                const isLeaf = branch.itemIndex !== null;

                                if (hasChildren) {
                                    // Render parent folder
                                    html += `
                                   <label class="lf-row lf-subparent" style="margin-left: ${level * 12 + 8}px" data-path="${safeId}">
                                       <span class="lf-toggle lf-subtoggle">▼</span>
                                       <input type="checkbox" id="lf-node-${safeId}" data-type="parent">
                                       <span class="lf-icon">📂</span>
                                       <span><strong>${branch.label}</strong></span>
                                   </label>
                                   <div class="lf-subchildren" id="children-${safeId}">
                                       ${renderTree(branch.children, currentPath, rootCategoryKey, level + 1)}
                                   </div>
                                   `;
                                } else if (isLeaf) {
                                    // Render leaf item
                                    const itemIdx = branch.itemIndex;
                                    const icon = branch.icon;
                                    html += `
                                   <label class="lf-row lf-subchild" style="margin-left: ${level * 12 + 8}px">
                                       <input type="checkbox" id="lf-${rootCategoryKey}-leaf-${itemIdx}" data-cat="${rootCategoryKey}" data-idx="${itemIdx}" data-type="leaf">
                                       <span class="lf-icon">${icon}</span>
                                       <span>${branch.label}</span>
                                   </label>
                                   `;
                                }
                            });
                            return html;
                        };

                        categoriesHTML += `
                           <label class="lf-row lf-parent" data-category="${catKey}">
                               <span class="lf-toggle">▼</span>
                               <input type="checkbox" id="lf-${catKey}-parent" data-type="root-parent">
                               <span class="lf-icon">${category.icon}</span>
                               <span><strong>${category.label}</strong></span>
                           </label>
                           <div class="lf-children" data-category="${catKey}">
                               ${renderTree(tree, catKey, catKey)}
                           </div>
                       `;
                    }

                    // Add Toggle Button HTML
                    const toggleButtonHtml = `
                    <div id="desa-label-toggle-container" class="lf-sticky-header" style="display: none;">
                        <button id="btn-toggle-desa-labels" class="btn-toggle-labels active" title="Sembunyikan / Tampilkan Nama Desa">
                             <span class="icon">🏷️</span> <span class="text">Sembunyikan Nama Desa</span>
                        </button>
                    </div>
                    `;

                    layerFilter.innerHTML = `
                     <div class="lf-head">
                       <div class="lf-title">🔍 Filter Peta</div>
                       <button class="lf-toggle-btn" title="Tutup/Buka">▼</button>
                     </div>

                     <div class="lf-body">
                       ${toggleButtonHtml}
                       <label class="lf-row lf-all"><input type="checkbox" id="lf-all"> <span class="lf-icon">📊</span> <span><strong>Semua Data</strong></span></label>
                       <div class="lf-divider"></div>
                       ${categoriesHTML}
                     </div>
                   `;

                    bindFilterEvents();
                    bindLabelToggleEvent();
                };

                const checkDesaLabelButtonVisibility = () => {
                    const anyDesaVisible = desaLayers.some(layer => layer.visible);
                    const container = document.getElementById('desa-label-toggle-container');
                    if (container) {
                        container.style.display = anyDesaVisible ? 'block' : 'none';
                    }
                };

                const bindLabelToggleEvent = () => {
                    const btn = document.getElementById('btn-toggle-desa-labels');
                    if (btn) {
                        btn.onclick = () => {
                            const isCurrentlyActive = btn.classList.contains('active');
                            const newState = !isCurrentlyActive; // Toggle state

                            // Update Button UI
                            if (newState) {
                                btn.classList.add('active');
                                btn.querySelector('.text').textContent = 'Sembunyikan Nama Desa';
                                btn.querySelector('.icon').style.opacity = '1';
                            } else {
                                btn.classList.remove('active');
                                btn.querySelector('.text').textContent = 'Tampilkan Nama Desa';
                                btn.querySelector('.icon').style.opacity = '0.5';
                            }

                            // Update Layers
                            desaLayers.forEach(layer => {
                                layer.labelsVisible = newState;
                            });
                        };
                    }
                };

                const bindFilterEvents = () => {
                    // Toggle Panel
                    const toggleBtn = layerFilter.querySelector('.lf-toggle-btn');
                    if (toggleBtn) {
                        toggleBtn.onclick = () => {
                            const body = layerFilter.querySelector('.lf-body');
                            body.style.display = body.style.display === 'none' ? 'block' : 'none';
                            toggleBtn.textContent = body.style.display === 'none' ? '▶' : '▼';
                        };
                    }

                    // Event Delegation for Checkboxes
                    const lfBody = layerFilter.querySelector('.lf-body');
                    if (lfBody) {
                        lfBody.addEventListener('change', (e) => {
                            if (e.target.tagName !== 'INPUT' || e.target.type !== 'checkbox')
                                return;
                            const checkbox = e.target;
                            const id = checkbox.id;

                            // 1. Handle "Semua Data"
                            if (id === 'lf-all') {
                                const checked = checkbox.checked;
                                layerFilter.querySelectorAll('input[type="checkbox"]').forEach(
                                    cb => {
                                        if (cb !== checkbox) {
                                            cb.checked = checked;
                                            // Trigger leaf logic manually if needed, or rely on recursion?
                                            // Better to trigger change event? No, avoiding loop.
                                            // Just update state directly.
                                            if (cb.dataset.type === 'leaf') {
                                                updateLayerVisibility(cb, checked);
                                            }
                                        }
                                    });
                            }
                            // 2. Handle Root Parent (Category)
                            else if (checkbox.dataset.type === 'root-parent') {
                                const categoryKey = id.replace('lf-', '').replace('-parent', '');
                                const container = layerFilter.querySelector(
                                    `.lf-children[data-category="${categoryKey}"]`);
                                if (container) {
                                    const childrenCbs = container.querySelectorAll(
                                        'input[type="checkbox"]');
                                    childrenCbs.forEach(cb => {
                                        cb.checked = checkbox.checked;
                                        if (cb.dataset.type === 'leaf')
                                            updateLayerVisibility(cb, checkbox.checked);
                                    });
                                }
                            }
                            // 3. Handle Intermediate Parent (Folder)
                            else if (checkbox.dataset.type === 'parent') {
                                // Find the container sibling (the next element usually, or by ID)
                                // My HTML structure: label (with input) then div.lf-subchildren
                                const label = checkbox.closest('label');
                                const nextDiv = label.nextElementSibling;
                                if (nextDiv && nextDiv.classList.contains('lf-subchildren')) {
                                    nextDiv.querySelectorAll('input[type="checkbox"]').forEach(
                                        cb => {
                                            cb.checked = checkbox.checked;
                                            if (cb.dataset.type === 'leaf')
                                                updateLayerVisibility(cb, checkbox.checked);
                                        });
                                }
                                // Update upstream parents? (Optional complexity)
                            }
                            // 4. Handle Leaf (Layer)
                            else if (checkbox.dataset.type === 'leaf') {
                                updateLayerVisibility(checkbox, checkbox.checked);
                                // Check/Uncheck parents based on siblings? (Optional complexity)
                            }
                        });

                        // Event Delegation for Toggles (Expand/Collapse)
                        lfBody.addEventListener('click', (e) => {
                            // Find closest toggle
                            if (e.target.classList.contains('lf-toggle') || e.target.closest(
                                    '.lf-toggle')) {
                                e.preventDefault();
                                e.stopPropagation();
                                const toggle = e.target.classList.contains('lf-toggle') ? e.target :
                                    e.target.closest('.lf-toggle');
                                const label = toggle.closest('label');

                                // Determine type
                                if (label.classList.contains('lf-parent')) {
                                    const category = label.dataset.category;
                                    const children = layerFilter.querySelector(
                                        `.lf-children[data-category="${category}"]`);
                                    toggleCollapse(children, toggle);
                                } else if (label.classList.contains('lf-subparent')) {
                                    const children = label.nextElementSibling;
                                    toggleCollapse(children, toggle);
                                }
                            }
                            // Or allow clicking on the label text (excluding checkbox)
                            else {
                                const label = e.target.closest('label');
                                if (label && (label.classList.contains('lf-parent') || label
                                        .classList.contains('lf-subparent'))) {
                                    // Ensure we didn't click the checkbox
                                    if (e.target.tagName !== 'INPUT') {
                                        const toggle = label.querySelector('.lf-toggle');
                                        if (toggle) {
                                            if (label.classList.contains('lf-parent')) {
                                                const category = label.dataset.category;
                                                const children = layerFilter.querySelector(
                                                    `.lf-children[data-category="${category}"]`);
                                                toggleCollapse(children, toggle);
                                            } else if (label.classList.contains('lf-subparent')) {
                                                const children = label.nextElementSibling;
                                                toggleCollapse(children, toggle);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                };

                const toggleCollapse = (element, toggleIcon) => {
                    if (!element) return;
                    if (element.classList.contains('collapsed')) {
                        element.classList.remove('collapsed');
                        toggleIcon.textContent = '▼';
                    } else {
                        element.classList.add('collapsed');
                        toggleIcon.textContent = '▶';
                    }
                };

                const updateLayerVisibility = (checkbox, isVisible) => {
                    const catKey = checkbox.dataset.cat;
                    const idx = checkbox.dataset.idx;

                    if (catKey && idx !== undefined && layerCategories[catKey]) {
                        const item = layerCategories[catKey].items[idx];
                        if (item) {
                            item.layer.visible = isVisible;
                            updateFeatureCount();
                        }
                    }
                };

                let featureCountTimeout;
                const updateFeatureCount = () => {
                    clearTimeout(featureCountTimeout);
                    featureCountTimeout = setTimeout(async () => {
                        const countEl = document.getElementById('featureCountValue');
                        if (!countEl) return;

                        countEl.textContent = '...';

                        let total = 0;
                        const countPromises = [];

                        asetLayers.forEach(layer => {
                            if (layer.visible) {
                                const query = layer.createQuery();
                                const promise = layer.queryFeatureCount(query).then(
                                    count => {
                                        return count;
                                    }).catch(err => {
                                    console.error("Error counting layer:", err);
                                    return 0;
                                });
                                countPromises.push(promise);
                            }
                        });

                        try {
                            const counts = await Promise.all(countPromises);
                            total = counts.reduce((a, b) => a + b, 0);
                            countEl.textContent = total.toLocaleString('id-ID');
                        } catch (error) {
                            console.error("Error calculating total:", error);
                            countEl.textContent = '-';
                        }
                    }, 500);
                };

                // Trigger Initial Load
                loadFeatureStructure();



                // ================== WIDGETS ==================
                const bm_osm = Basemap.fromId("osm");
                bm_osm.title = "Peta (OSM)";
                const bm_sat = Basemap.fromId("satellite");
                bm_sat.title = "Satelit";
                const bm_hybrid = Basemap.fromId("hybrid");
                bm_hybrid.title = "Hybrid";
                const bm_terrain = Basemap.fromId("terrain");
                bm_terrain.title = "Medan";
                const bm_topo = Basemap.fromId("topo-vector");
                bm_topo.title = "Topografi";
                const bm_gray = Basemap.fromId("gray-vector");
                bm_gray.title = "Abu-abu";
                const bm_dark = Basemap.fromId("dark-gray-vector");
                bm_dark.title = "Gelap";
                const bm_street = Basemap.fromId("streets-vector");
                bm_street.title = "Streets";

                const localSource = new LocalBasemapsSource({
                    basemaps: [bm_osm, bm_sat, bm_hybrid, bm_terrain, bm_topo, bm_gray, bm_dark,
                        bm_street
                    ]
                });

                // Custom Home Label & Login Button
                const homeLabelDiv = document.createElement('div');
                homeLabelDiv.className =
                    'esri-component esri-widget';
                homeLabelDiv.innerHTML = `

        `;
                view.ui.add(homeLabelDiv, "top-left");

                // const homeWidget = new Home({ view: view });
                // view.ui.add(homeWidget, "top-left");

                const searchWidget = new Search({
                    view: view,
                    allPlaceholder: "Cari lokasi atau aset"
                });
                view.ui.add(searchWidget, "top-right");

                const scaleBar = new ScaleBar({
                    view: view,
                    unit: "metric"
                });
                view.ui.add(scaleBar, "bottom-left");


                const legendExpand = new Expand({
                    view: view,
                    content: new Legend({
                        view: view,
                        style: "card"
                    }),
                    expanded: false,
                    expandIconClass: "esri-icon-layer-list",
                    expandTooltip: "Legenda"
                });
                view.ui.add(legendExpand, "bottom-right");



                const basemapGalleryExpand = new Expand({
                    view: view,
                    content: new BasemapGallery({
                        view: view,
                        source: localSource
                    }),
                    expanded: false,
                    expandIconClass: "esri-icon-basemap",
                    expandTooltip: "Ganti basemap"
                });
                view.ui.add(basemapGalleryExpand, "bottom-right");

                const basemapToggle = new BasemapToggle({
                    view: view,
                    nextBasemap: bm_osm
                });
                view.ui.add(basemapToggle, "bottom-right");

                // Feature Count Widget
                const featureCountWidget = document.createElement('div');
                featureCountWidget.className = 'feature-count-widget esri-component esri-widget';
                featureCountWidget.innerHTML = `
                   <div class="fc-icon">📊</div>
                   <div class="fc-content">
                       <div class="fc-label">Total Data</div>
                       <div id="featureCountValue" class="fc-value">0</div>
                   </div>
                `;
                view.ui.add(featureCountWidget, "top-right");

                // ================== DISTANCE MEASUREMENT & COST CALCULATION ==================
                let distanceMeasurement = new DistanceMeasurement2D({
                    view: view,
                    unit: "kilometers"
                });

                // Create cost calculation panel
                const costPanel = document.createElement('div');
                costPanel.id = 'costPanel';
                costPanel.className =
                    'cost-panel hidden';
                costPanel.innerHTML = `
          <div class="cost-head">
            <div class="cost-title">Perhitungan Biaya</div>
            <button id="costClose" class="cost-close" title="Tutup">✕</button>
          </div>
          <div class="cost-body">
            <div class="cost-row">
              <div class="cost-label">Jarak</div>
              <div class="cost-value" id="costDistance">-</div>
            </div>
            <div class="cost-row">
              <div class="cost-label">Harga per km</div>
              <div class="cost-value">Rp. 150.000</div>
            </div>
            <div class="cost-divider"></div>
            <div class="cost-row total">
              <div class="cost-label">Total Biaya</div>
              <div class="cost-value" id="costTotal">Rp. 0</div>
            </div>
          </div>
        `;
                view.container.appendChild(costPanel);

                // Distance measurement button
                const measureBtn = document.createElement('div');
                measureBtn.className = 'measure-btn';
                measureBtn.innerHTML =
                    '📏 Ukur Jarak';
                measureBtn.title = 'Klik untuk mengukur jarak dan menghitung biaya';

                // Close button handler for cost panel
                const costClose = costPanel.querySelector('#costClose');
                costClose.addEventListener('click', () => {
                    costPanel.classList.add('hidden');
                    measurementActive = false;
                    measureBtn.classList.remove('active');
                    measureBtn.innerHTML = '📏 Ukur Jarak';

                    // Reset cursor ke default
                    view.container.style.cursor = 'default';

                    // Stop measurement dan clear semua drawing
                    distanceMeasurement.viewModel.clear();
                    distanceMeasurement.destroy();

                    // Recreate measurement widget untuk reset state
                    setTimeout(() => {
                        distanceMeasurement = new DistanceMeasurement2D({
                            view: view,
                            unit: "kilometers"
                        });

                        // Re-attach watcher
                        distanceMeasurement.viewModel.watch('measurement', (measurement) => {
                            if (measurement) {
                                const distanceKm = measurement.length;
                                const pricePerKm = 150000;
                                const totalCost = distanceKm * pricePerKm;

                                const costDistanceEl = costPanel.querySelector(
                                    '#costDistance');
                                const costTotalEl = costPanel.querySelector(
                                    '#costTotal');

                                if (distanceKm > 0) {
                                    costDistanceEl.textContent =
                                        `${distanceKm.toFixed(2)} km`;
                                    costTotalEl.textContent =
                                        `Rp. ${totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                                } else {
                                    costDistanceEl.textContent = '-';
                                    costTotalEl.textContent = 'Rp. 0';
                                }
                            }
                        });
                    }, 100);

                    // Reset cost values
                    const costDistanceEl = costPanel.querySelector('#costDistance');
                    const costTotalEl = costPanel.querySelector('#costTotal');
                    costDistanceEl.textContent = '-';
                    costTotalEl.textContent = 'Rp. 0';
                });

                // Measurement button click handler
                measureBtn.addEventListener('click', () => {
                    measurementActive = !measurementActive;

                    if (measurementActive) {
                        measureBtn.classList.add('active');
                        measureBtn.innerHTML = '⏹️ Stop Ukur';
                        distanceMeasurement.viewModel.start();
                        costPanel.classList.remove('hidden');
                        // Tutup detail modal jika terbuka
                        hideDetailModal();
                        // Set cursor untuk drawing
                        view.container.style.cursor = 'crosshair';
                    } else {
                        measureBtn.classList.remove('active');
                        measureBtn.innerHTML = '📏 Ukur Jarak';

                        // Reset cursor ke default
                        view.container.style.cursor = 'default';

                        // Stop measurement dan clear semua drawing
                        distanceMeasurement.viewModel.clear();
                        distanceMeasurement.destroy();

                        // Recreate measurement widget untuk reset state
                        setTimeout(() => {
                            distanceMeasurement = new DistanceMeasurement2D({
                                view: view,
                                unit: "kilometers"
                            });

                            // Re-attach watcher
                            distanceMeasurement.viewModel.watch('measurement', (
                                measurement) => {
                                if (measurement) {
                                    const distanceKm = measurement.length;
                                    const pricePerKm = 150000;
                                    const totalCost = distanceKm * pricePerKm;

                                    const costDistanceEl = costPanel.querySelector(
                                        '#costDistance');
                                    const costTotalEl = costPanel.querySelector(
                                        '#costTotal');

                                    if (distanceKm > 0) {
                                        costDistanceEl.textContent =
                                            `${distanceKm.toFixed(2)} km`;
                                        costTotalEl.textContent =
                                            `Rp. ${totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                                    } else {
                                        costDistanceEl.textContent = '-';
                                        costTotalEl.textContent = 'Rp. 0';
                                    }
                                }
                            });
                        }, 100);

                        costPanel.classList.add('hidden');

                        // Reset cost values
                        const costDistanceEl = costPanel.querySelector('#costDistance');
                        const costTotalEl = costPanel.querySelector('#costTotal');
                        costDistanceEl.textContent = '-';
                        costTotalEl.textContent = 'Rp. 0';
                    }
                });

                view.ui.add(measureBtn, 'top-right');

                // Watch for measurement changes
                distanceMeasurement.viewModel.watch('measurement', (measurement) => {
                    if (measurement) {
                        const distanceKm = measurement.length;
                        const pricePerKm = 150000;
                        const totalCost = distanceKm * pricePerKm;

                        const costDistanceEl = costPanel.querySelector('#costDistance');
                        const costTotalEl = costPanel.querySelector('#costTotal');

                        if (distanceKm > 0) {
                            costDistanceEl.textContent = `${distanceKm.toFixed(2)} km`;
                            costTotalEl.textContent =
                                `Rp. ${totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                        } else {
                            costDistanceEl.textContent = '-';
                            costTotalEl.textContent = 'Rp. 0';
                        }
                    }
                });

            });

            // Fungsi untuk membuka modal video 360
            window.openVideo360Modal = function(url, title) {
                const modal = document.getElementById('video360Modal');
                const iframe = document.getElementById('video360Iframe');
                const modalTitle = document.getElementById('video360ModalTitle');

                // Konversi Google Drive URL ke embedded format
                let embedUrl = url;
                if (url.includes('drive.google.com/file/d/')) {
                    const fileId = url.match(/\/d\/([^/]+)/)[1];
                    embedUrl = `https://drive.google.com/file/d/${fileId}/preview`;
                } else if (url.includes('youtube.com/watch') || url.includes('youtu.be/')) {
                    // Konversi YouTube URL ke embed format
                    let videoId = '';
                    if (url.includes('youtu.be/')) {
                        videoId = url.split('youtu.be/')[1].split('?')[0];
                    } else if (url.includes('youtube.com/watch')) {
                        const urlParams = new URLSearchParams(new URL(url).search);
                        videoId = urlParams.get('v');
                    }
                    if (videoId) {
                        embedUrl = `https://www.youtube.com/embed/${videoId}`;
                    }
                }

                iframe.src = embedUrl;
                modalTitle.textContent = title || 'Video 360';
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            // Fungsi untuk menutup modal video 360
            window.closeVideo360Modal = function() {
                const modal = document.getElementById('video360Modal');
                const iframe = document.getElementById('video360Iframe');

                modal.style.display = 'none';
                iframe.src = '';
                document.body.style.overflow = 'auto';
            };

            // Tutup modal dengan tombol ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('video360Modal');
                    if (modal.style.display === 'flex') {
                        closeVideo360Modal();
                    }
                }
            });

            // Event delegation untuk link video 360 di dalam popup
            document.addEventListener('click', function(e) {
                // Check if clicked element or its parent is a video360-link
                let target = e.target;
                if (target.classList.contains('video360-link') || target.closest('.video360-link')) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Get the actual link element
                    const linkElement = target.classList.contains('video360-link') ? target : target.closest(
                        '.video360-link');

                    const videoUrl = linkElement.getAttribute('data-video-url');
                    const videoTitle = linkElement.getAttribute('data-video-title');

                    console.log('Video 360 Link Clicked:', {
                        videoUrl,
                        videoTitle
                    });

                    if (videoUrl) {
                        openVideo360Modal(videoUrl, videoTitle);
                    }
                }
            }, true); // Use capture phase to catch events earlier
        })();
    </script>

    <style>
        /* Detail modal muncul saat klik fitur */
        #viewDiv {
            position: relative;
        }

        .detail-modal {
            position: absolute;
            width: min(360px, 86vw);
            max-height: 60vh;
            overflow: hidden;
            background: rgba(15, 23, 42, 0.95);
            color: #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(226, 232, 240, 0.18);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            z-index: 100;
            pointer-events: auto;
        }

        .detail-modal.hidden {
            display: none;
        }

        .dm-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            z-index: 1;
            font-weight: bold;
        }

        .dm-close:hover {
            background: rgba(239, 68, 68, 0.35);
            color: #fee2e2;
            transform: scale(1.1);
        }

        .dm-content {
            display: flex;
            flex-direction: column;
        }

        .dm-head {
            padding: 10px 40px 10px 14px;
            font-weight: 700;
            font-size: 15px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.16);
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.18), rgba(15, 23, 42, 0.05));
        }

        .dm-body {
            padding: 10px 14px;
            overflow-y: auto;
            max-height: 50vh;
            display: grid;
            gap: 6px;
        }

        .dm-row {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 8px;
            font-size: 12px;
            padding: 6px 8px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 8px;
        }

        .dm-key {
            color: #94a3b8;
            font-weight: 600;
            word-break: break-word;
        }

        .dm-val {
            color: #e2e8f0;
            word-break: break-word;
        }

        .dm-empty {
            color: #94a3b8;
            font-size: 12px;
            padding: 8px;
        }

        /* Layer filter panel - Light Theme (like welcome.blade.php) */
        .layer-filter {
            width: 380px;
            /* Increased from 340px */
            max-width: 90vw;
            max-height: 80vh;
            /* Increased height visibility */
            overflow: hidden;
            background: #fff;
            color: #111827;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .15);
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial;
        }

        .lf-head {
            padding: 16px;
            font-weight: 700;
            font-size: 16px;
            border-bottom: 2px solid #e5e7eb;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #111827;
        }

        .lf-title {
            font-weight: 700;
            font-size: 16px;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lf-toggle-btn {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .lf-toggle-btn:hover {
            background: #e2e8f0;
            color: #475569;
        }

        .layer-filter.lf-collapsed .lf-body {
            display: none;
        }

        .layer-filter.lf-collapsed {
            width: auto;
            padding: 0;
        }

        .layer-filter {
            transition: all 0.3s ease;
        }

        .lf-body {
            max-height: calc(65vh - 70px);
            overflow-y: auto;
            padding: 16px;
            display: grid;
            gap: 8px;
        }

        .lf-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            padding: 10px 12px;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            color: #111827;
        }

        .lf-row input {
            accent-color: #3b82f6;
        }

        .lf-row span {
            line-height: 1.35;
        }

        .lf-icon {
            font-size: 14px;
            min-width: 18px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .lf-row:hover .lf-icon {
            transform: scale(1.15);
        }

        .lf-row:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .lf-all {
            background: #dbeafe !important;
            border-color: #93c5fd !important;
            color: #1e40af !important;
        }

        .lf-all:hover {
            background: #bfdbfe !important;
        }

        .lf-parent {
            background: #f0fdf4 !important;
            border-color: #86efac !important;
            color: #166534 !important;
        }

        .lf-parent:hover {
            background: #dcfce7 !important;
        }

        .lf-child {
            margin-left: 20px;
            background: #fff !important;
            border-left: 3px solid #22c55e;
            font-size: 12px;
        }

        /* Sub-parent styles (Gardu, Trafo, Distribusi, Transmisi) */
        .lf-subparent {
            margin-left: 20px;
            background: #fefce8 !important;
            border-color: #fde047 !important;
            color: #854d0e !important;
            font-size: 12px;
            border-left: 3px solid #eab308;
        }

        .lf-subparent:hover {
            background: #fef9c3 !important;
        }

        .lf-subtoggle {
            font-size: 9px;
            margin-right: 4px;
            transition: transform 0.2s ease;
            user-select: none;
            color: #a16207;
        }

        /* Sub-children container styles */
        .lf-subchildren {
            display: grid;
            gap: 4px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
            margin-left: 20px;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .lf-subchildren.collapsed {
            max-height: 0;
            opacity: 0;
            margin: 0;
        }

        /* Sub-child item styles */
        .lf-subchild {
            margin-left: 10px;
            background: #fff !important;
            border-left: 2px solid #facc15;
            font-size: 11px;
            padding: 6px 10px !important;
        }

        .lf-subchild:hover {
            background: #fffbeb !important;
        }

        .lf-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 8px 0;
        }

        .lf-toggle {
            font-size: 10px;
            margin-right: 4px;
            transition: transform 0.2s ease;
            user-select: none;
            color: #64748b;
        }

        .lf-children {
            display: grid;
            gap: 6px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .lf-children.collapsed {
            max-height: 0;
            opacity: 0;
            margin: 0;
        }



        .lf-children.collapsed {
            max-height: 0;
            opacity: 0;
            margin: 0;
        }

        /* Sticky Label Toggle Header */
        .lf-sticky-header {
            position: sticky;
            top: -16px;
            /* Offset parent padding */
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            margin: -16px -16px 8px -16px;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            backdrop-filter: blur(4px);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .btn-toggle-labels {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #475569;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-toggle-labels:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .btn-toggle-labels.active {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #2563eb;
        }

        .btn-toggle-labels.active:hover {
            background: #dbeafe;
        }



        /* Distance measurement button */
        .measure-btn {
            background: rgba(37, 99, 235, 0.95);
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
            border: 1px solid rgba(59, 130, 246, 0.4);
            transition: all 0.2s;
            user-select: none;
        }

        .measure-btn:hover {
            background: rgba(59, 130, 246, 0.95);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.45);
        }

        .measure-btn.active {
            background: rgba(220, 38, 38, 0.95);
            border-color: rgba(239, 68, 68, 0.4);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
        }

        .measure-btn.active:hover {
            background: rgba(239, 68, 68, 0.95);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.45);
        }

        /* Cost calculation panel */
        .cost-panel {
            position: absolute;
            bottom: 200px;
            right: 16px;
            width: 280px;
            background: rgba(15, 23, 42, 0.94);
            color: #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.32);
            border: 1px solid rgba(226, 232, 240, 0.18);
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .cost-panel.hidden {
            display: none;
        }

        .cost-head {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.16);
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.24), rgba(15, 23, 42, 0.12));
            border-radius: 14px 14px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cost-title {
            font-weight: 700;
            font-size: 15px;
        }

        .cost-close {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .cost-close:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #fee2e2;
        }

        .cost-body {
            padding: 14px;
            display: grid;
            gap: 10px;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 8px;
        }

        .cost-row.total {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(16, 185, 129, 0.1));
            border-color: rgba(34, 197, 94, 0.3);
            padding: 12px 12px;
        }

        .cost-label {
            font-size: 13px;
            color: #cbd5e1;
            font-weight: 500;
        }

        .cost-row.total .cost-label {
            font-weight: 700;
            color: #e2e8f0;
            font-size: 14px;
        }

        .cost-value {
            font-size: 14px;
            color: #e2e8f0;
            font-weight: 600;
        }

        .cost-row.total .cost-value {
            font-size: 16px;
            color: #6ee7b7;
            font-weight: 700;
        }

        .cost-divider {
            height: 1px;
            background: rgba(148, 163, 184, 0.24);
            margin: 4px 0;
        }

        /* Video 360 Modal Styles */
        .video360-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .video360-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(4px);
        }

        .video360-modal-content {
            position: relative;
            background: #1e293b;
            border-radius: 12px;
            width: 90vw;
            max-width: 1200px;
            height: 80vh;
            max-height: 800px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .video360-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: linear-gradient(135deg, #334155, #1e293b);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        .video360-modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #f1f5f9;
        }

        .video360-modal-close {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            font-size: 28px;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            line-height: 1;
            padding: 0;
        }

        .video360-modal-close:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
            transform: rotate(90deg);
        }

        .video360-modal-body {
            flex: 1;
            padding: 0;
            overflow: hidden;
            background: #0f172a;
        }

        .video360-modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }


        /* Feature Count Widget */
        .feature-count-widget {
            background: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 160px;
        }

        .fc-icon {
            font-size: 20px;
            background: #eff6ff;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: #3b82f6;
        }

        .fc-content {
            display: flex;
            flex-direction: column;
        }

        .fc-label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .fc-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .detail-modal {
                width: min(340px, 94vw);
            }


            .dm-row {
                grid-template-columns: 1fr;
            }

            .layer-filter {
                width: 280px;
            }

            /* Place cost panel right below the measure button on mobile */
            .cost-panel {
                width: min(280px, 90vw);
                right: 10px;
                top: 110px;
                bottom: auto;
            }

            /* Video 360 Modal Responsive */
            .video360-modal-content {
                width: 95vw;
                height: 90vh;
                border-radius: 8px;
            }

            .video360-modal-header {
                padding: 15px 16px;
            }

            .video360-modal-header h3 {
                font-size: 16px;
            }

            .video360-modal-close {
                width: 36px;
                height: 36px;
                font-size: 24px;
            }
        }
    </style>
@endpush
