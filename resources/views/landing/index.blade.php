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
                "esri/layers/GraphicsLayer",
                "esri/Graphic",
                "esri/widgets/Legend",
                "esri/widgets/Expand",
                "esri/widgets/Home",
                "esri/widgets/Compass",
                "esri/widgets/Search",
                "esri/widgets/ScaleBar",
                "esri/widgets/BasemapGallery",
                "esri/widgets/BasemapToggle",
                "esri/widgets/BasemapGallery/support/LocalBasemapsSource",
                "esri/widgets/DistanceMeasurement2D",
                "esri/widgets/Print",
                "esri/widgets/Track",
                "esri/geometry/Circle"
            ], function(
                Map,
                Basemap,
                MapView,
                GeoJSONLayer,
                GraphicsLayer,
                Graphic,
                Legend,
                Expand,
                Home,
                Compass,
                Search,
                ScaleBar,
                BasemapGallery,
                BasemapToggle,
                LocalBasemapsSource,
                DistanceMeasurement2D,
                Print,
                Track,
                Circle
            ) {

                // ================== MAP & VIEW ==================
                const map = new Map({
                    basemap: Basemap.fromId("satellite")
                });

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

                    dmContent.innerHTML = `
        <div class="dm-head">${escapeHtml(layerTitle)}</div>
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

                // Desa Berlistrik PLN
                const desaBerlistrikLayer = new GeoJSONLayer({
                    url: "{{ url('/api/data-berlistrik') }}",
                    title: "H_Survei",
                    outFields: ["*"],
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Desa:</b> {WADMKD}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Status:</b> {H_Survei}
        `
                    },
                    renderer: {
                        type: "unique-value",
                        field: "H_Survei",
                        defaultSymbol: null,
                        uniqueValueInfos: [{
                                value: "Belum Terlayani Listrik",
                                label: "Belum Terlayani Listrik",
                                symbol: {
                                    type: "simple-fill",
                                    color: [220, 38, 38, 0.45],
                                    outline: {
                                        color: [185, 28, 28, 1],
                                        width: 1.5
                                    }
                                }
                            },
                            {
                                value: "Terlayani Listrik",
                                label: "Sudah Terlayani Listrik",
                                symbol: {
                                    type: "simple-fill",
                                    color: [34, 197, 94, 0.45],
                                    outline: {
                                        color: [22, 163, 74, 1],
                                        width: 1.5
                                    }
                                }
                            }
                        ]
                    },
                    labelingInfo: [{
                        symbol: {
                            type: "text",
                            color: [255, 255, 255, 1],
                            haloColor: [0, 0, 0, 0.8],
                            haloSize: 1.5,
                            font: {
                                family: "Arial",
                                size: 10,
                                weight: "bold"
                            }
                        },
                        labelPlacement: "always-horizontal",
                        labelExpressionInfo: {
                            expression: "$feature.WADMKD"
                        },
                        minScale: 150000,
                        maxScale: 0
                    }]
                });
                map.add(desaBerlistrikLayer);

                // Jalan Nasional
                const jalanNasionalLayer = new GeoJSONLayer({
                    url: "{{ url('/api/data-jalan-nasional') }}",
                    title: "Jalan Nasional",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 215, 0, 1],
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{Nama_Jln}",
                        content: `
          <b>Fungsi Jalan:</b> {Fungsi_Jal}<br>
          <b>Sumber:</b> {Sumber}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(jalanNasionalLayer);

                // Jalan Provinsi
                const jalanProvinsiLayer = new GeoJSONLayer({
                    url: "{{ url('/api/data-jalan-provinsi') }}",
                    title: "Jalan Provinsi",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 255, 255, 1],
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{Nm_Ruas}",
                        content: `
          <b>Status:</b> {Status}<br>
          <b>Fungsi:</b> {Fungsi}<br>
          <b>Tahun Data:</b> {Thn_Data}<br>
          <b>Provinsi:</b> {Propinsi}<br>
          <b>Kab/Kota:</b> {Kab_Kot}<br>
          <b>Kecamatan:</b> {Kecamatan}<br>
          <b>Desa/Kel:</b> {Desa_Kel}<br>
          <b>Panjang (km):</b> {Panjang}<br>
          <b>Status Pembangunan:</b> {Status_J_1}
        `
                    }
                });
                map.add(jalanProvinsiLayer);

                // Jalan Balikpapan
                const jalanBalikpapanLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-balikpapan') }}",
                    title: "Jalan Balikpapan",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 0, 0, 1], // merah
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{NAMA_RUAS}",
                        content: `
          <b>Kecamatan:</b> {Kecamatan}<br>
          <b>F18:</b> {F18}<br>
          <b>F19:</b> {F19}<br>
          <b>F20:</b> {F20}<br>
          <b>F21:</b> {F21}<br>
          <b>Kode Ruas:</b> {KODE_RUAS}<br>
          <b>Nama Ruas:</b> {NAMA_RUAS}<br>
          <b>Tahun Data:</b> {TAHUN_DATA}<br>
          <b>Fungsi:</b> {FUNGSI}<br>
          <b>Lebar:</b> {LEBAR} m<br>
          <b>Panjang:</b> {PANJANG} m<br>
          <b>Koordinat Awal X:</b> {KOORD_X_AW}<br>
          <b>Koordinat Awal Y:</b> {KOORD_Y_AW}<br>
          <b>Koordinat Akhir X:</b> {KOORD_X_AK}<br>
          <b>Koordinat Akhir Y:</b> {KOORD_Y_AK}<br>
          <b>Panjang (Shape_Le_1):</b> {Shape_Le_1} km
        `
                    }
                });
                map.add(jalanBalikpapanLayer);

                // Jalan Berau
                const jalanBerauLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-berau') }}",
                    title: "Jalan Berau",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 165, 0, 1], // orange
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{NAMA_RUAS}",
                        content: `
          <b>No. Ruas:</b> {NO_RUAS}<br>
          <b>Nama Ruas:</b> {NAMA_RUAS}<br>
          <b>Kab/Kota:</b> {KAB_KOTA}<br>
          <b>Titik Awal:</b> {TTK_PNGKAL}<br>
          <b>Titik Akhir:</b> {TTK_AKHIR}<br>
          <b>Panjang:</b> {PANJANG} km<br>
          <b>Jumlah Jalur (2):</b> {JKP_2}<br>
          <b>Jumlah Jalur (3):</b> {JKP_3}<br>
          <b>Jumlah Jalur (4):</b> {JKP_4}<br>
          <b>Jumlah Lajur Perjalanan:</b> {JLP}<br>
          <b>Jalan Lingkar Propinsi:</b> {Jling_P}<br>
          <b>Jumlah Akses Struktur:</b> {JAS}<br>
          <b>Jumlah Konektivitas Struktur:</b> {JKS}<br>
          <b>Jumlah Lintas Struktur:</b> {JLS}<br>
          <b>Jalan Lingkar Struktur:</b> {Jling_S}<br>
          <b>Fungsi:</b> {FUNGSI}<br>
          <b>Status:</b> {STATUS}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng} m
        `
                    }
                });
                map.add(jalanBerauLayer);

                // Jalan Bontang
                const jalanBontangLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-bontang') }}",
                    title: "Jalan Bontang",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 0, 255, 1], // biru
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{Nm_Ruas}",
                        content: `
          <b>Klasifikasi Data Dasar:</b> {Kl_Dat_Das}<br>
          <b>Nama Ruas:</b> {Nm_Ruas}<br>
          <b>Tahun Data:</b> {Thn_Data}<br>
          <b>Status:</b> {Status}<br>
          <b>Fungsi:</b> {Fungsi}<br>
          <b>Mendukung:</b> {Mendukung}<br>
          <b>Uraian Dukung:</b> {Ura_Dukung}<br>
          <b>Kode Bangunan PU:</b> {Kd_Bd_PU}<br>
          <b>Kode Jenis Infrastruktur:</b> {Kd_Jns_Inf}<br>
          <b>Kode Infrastruktur:</b> {Kd_Inf}<br>
          <b>Provinsi:</b> {Propinsi}<br>
          <b>Kabupaten/Kota:</b> {Kab_Kota}<br>
          <b>Kecamatan:</b> {Kecamatan}<br>
          <b>Desa/Kelurahan:</b> {Desa_Kel}<br>
          <b>Titik Ruas Awal:</b> {Tk_Ruas_Aw}<br>
          <b>Titik Ruas Akhir:</b> {Tk_Ruas_Ak}<br>
          <b>Kode Patok:</b> {Kd_Patok}<br>
          <b>Kilometer Awal:</b> {Km_Awal}<br>
          <b>Kilometer Akhir:</b> {Km_Akhir}<br>
          <b>Nama Lintas:</b> {Nm_Lintas}<br>
          <b>Kondisi Baik (%):</b> {Kon_Baik}<br>
          <b>Kondisi Sedang (%):</b> {Kon_Sdg}<br>
          <b>Kondisi Renggang (%):</b> {Kon_Rgn}<br>
          <b>Kondisi Rusak (%):</b> {Kon_Rusak}<br>
          <b>Kondisi Mantap (%):</b> {Kon_Mntp}<br>
          <b>Kondisi Tidak Mantap (%):</b> {Kon_T_Mntp}<br>
          <b>Panjang (km):</b> {Panjang}<br>
          <b>Lebar Keras (m):</b> {Lbr_Keras}<br>
          <b>LHRT:</b> {LHRT}<br>
          <b>VCR:</b> {VCR}<br>
          <b>Tipe Jalan:</b> {Tipe_Jln}<br>
          <b>MST:</b> {MST}<br>
          <b>Tipe Keras:</b> {Tipe_Keras}<br>
          <b>Tanah Krikil (%):</b> {Tanah_Kri}<br>
          <b>Macadam (%):</b> {Macadam}<br>
          <b>Aspal (%):</b> {Aspal}<br>
          <b>Rigid (%):</b> {Rigid}<br>
          <b>Tahun Penanganan Akhir:</b> {Thn_Pen_Ak}<br>
          <b>Jenis Penanganan:</b> {Jns_Pen}<br>
          <b>Koordinat X Awal:</b> {Koord_X_Aw}<br>
          <b>Koordinat Y Awal:</b> {Koord_Y_Aw}<br>
          <b>Koordinat X Akhir:</b> {Koord_X_Ak}<br>
          <b>Koordinat Y Akhir:</b> {Koord_Y_Ak}
        `
                    }
                });
                map.add(jalanBontangLayer);

                // Jalan Kubar
                const jalanKubarLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-kubar') }}",
                    title: "Jalan Kubar",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 255, 0, 1], // kuning
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{Nm_Ruas}",
                        content: `
          <b>Nama Ruas:</b> {Nm_Ruas}<br>
          <b>Fungsi:</b> {Fungsi}<br>
          <b>Panjang (km):</b> {Panjang}
        `
                    }
                });
                map.add(jalanKubarLayer);

                // Jalan Kutai Kartanegara
                const jalanKutaiKartanegaraLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-kutai-kartanegara') }}",
                    title: "Jalan Kutai Kartanegara",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 165, 100, 1], // orange
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{NAMA_BARU}",
                        content: `
          <b>No. Lama:</b> {NO_LAMA}<br>
          <b>No. Baru:</b> {NO_BARU}<br>
          <b>Nama Lama:</b> {NAMA_LAMA}<br>
          <b>Nama Baru:</b> {NAMA_BARU}<br>
          <b>Panjang (P_Km):</b> {P_Km}<br>
          <b>Kecamatan:</b> {KECAMATAN}<br>
          <b>Urutan:</b> {URUT}<br>
          <b>Pangkal:</b> {PANGKAL}<br>
          <b>Ujung:</b> {UJUNG}<br>
          <b>Koordinat Pangkal:</b> {KOOR_PANGK}<br>
          <b>Koordinat Ujung:</b> {KOOR_UJUNG}<br>
          <b>Lebar (m):</b> {LEBAR_M}<br>
          <b>Fungsi:</b> {FUNGSI}<br>
          <b>History:</b> {HISTORY}<br>
          <b>Panjang:</b> {Panjang}
        `
                    }
                });
                map.add(jalanKutaiKartanegaraLayer);

                // Jalan Kutim
                const jalanKutimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-kutim') }}",
                    title: "Jalan Kutim",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [148, 0, 211, 1], // violet
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{Nm_Ruas}",
                        content: `
          <b>Klasifikasi Data Dasar:</b> {Kl_Dat_Das}<br>
          <b>No. Ruas:</b> {No_Ruas}<br>
          <b>Nama Ruas:</b> {Nm_Ruas}<br>
          <b>Fungsi:</b> {Fungsi}<br>
          <b>Kecamatan:</b> {Kecamatan}<br>
          <b>Desa/Kelurahan:</b> {Desa_Kel}<br>
          <b>Titik Ruas Awal:</b> {Tk_Ruas_Aw}<br>
          <b>Titik Ruas Akhir:</b> {Tk_Ruas_Ak}<br>
          <b>Panjang:</b> {Panjang}<br>
          <b>Koordinat X Awal:</b> {Koord_X_Aw}<br>
          <b>Koordinat Y Awal:</b> {Koord_Y_Aw}<br>
          <b>Koordinat X Akhir:</b> {Koord_X_Ak}<br>
          <b>Koordinat Y Akhir:</b> {Koord_Y_Ak}
        `
                    }
                });
                map.add(jalanKutimLayer);

                // Jalan Paser
                const jalanPaserLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-paser') }}",
                    title: "Jalan Paser",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 100, 0, 1], // dark green
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{Nm_Ruas}",
                        content: `
          <b>OBJECTID_1:</b> {OBJECTID_1}<br>
          <b>OBJECTID_2:</b> {OBJECTID_2}<br>
          <b>OBJECTID:</b> {OBJECTID}<br>
          <b>Klasifikasi Data Dasar:</b> {Kl_Dat_Das}<br>
          <b>Nama Ruas:</b> {Nm_Ruas}<br>
          <b>Tahun Data:</b> {Thn_Data}<br>
          <b>Status:</b> {Status}<br>
          <b>Fungsi:</b> {Fungsi}<br>
          <b>Mendukung:</b> {Mendukung}<br>
          <b>Uraian Dukung:</b> {Ura_Dukung}<br>
          <b>Kode Bidang PU:</b> {Kd_Bd_PU}<br>
          <b>Kode Jenis Infrastruktur:</b> {Kd_Jns_inf}<br>
          <b>Kode Infrastruktur:</b> {Kd_Inf}<br>
          <b>Propinsi:</b> {Propinsi}<br>
          <b>Kabupaten/Kota:</b> {Kab_Kot}<br>
          <b>Kecamatan:</b> {Kecamatan}<br>
          <b>Desa/Kelurahan:</b> {Desa_Kel}<br>
          <b>Titik Ruas Awal:</b> {Tk_Ruas_Aw}<br>
          <b>Titik Ruas Akhir:</b> {Tk_Ruas_Ak}<br>
          <b>Kode Patok:</b> {Kd_Patok}<br>
          <b>Nama Lintas:</b> {Nm_Lintas}<br>
          <b>Kilometer Awal:</b> {Km_Awal}<br>
          <b>Kilometer Akhir:</b> {Km_Akhir}<br>
          <b>Kondisi Baik (%):</b> {Kon_Baik}<br>
          <b>Kondisi Sedang (%):</b> {Kon_Sdg}<br>
          <b>Kondisi Renggang (%):</b> {Kon_Rgn}<br>
          <b>Kondisi Rusak (%):</b> {Kon_Rusak}<br>
          <b>Kondisi Mantap (%):</b> {Kon_Mntp}<br>
          <b>Kondisi Tidak Mantap (%):</b> {Kon_T_Mntp}<br>
          <b>Panjang (km):</b> {Panjang}<br>
          <b>Lebar Keras (m):</b> {Lbr_Keras}<br>
          <b>LHRT:</b> {LHRT}<br>
          <b>VCR:</b> {VCR}<br>
          <b>Tipe Jalan:</b> {Tipe_Jln}<br>
          <b>MST:</b> {MST}<br>
          <b>Tipe Keras:</b> {Tipe_Keras}<br>
          <b>Tanah Krikil (%):</b> {Tanah_Kri}<br>
          <b>Macadam (%):</b> {Macadam}<br>
          <b>Aspal (%):</b> {Aspal}<br>
          <b>Rigid (%):</b> {Rigid}<br>
          <b>Tahun Penanganan Akhir:</b> {Thn_Pen_Ak}<br>
          <b>Jenis Penanganan:</b> {Jns_Pen}<br>
          <b>Panjang (pnj):</b> {pnj}<br>
          <b>ID:</b> {Id}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>Koordinat X Akhir:</b> {X_Ak}<br>
          <b>Koordinat Y Akhir:</b> {Y_Ak}<br>
          <b>Koordinat X Awal:</b> {X_Aw}<br>
          <b>Koordinat Y Awal:</b> {Y_Aw}<br>
          <b>No:</b> {No}
        `
                    }
                });
                map.add(jalanPaserLayer);

                // Jalan PPU
                const jalanPPULayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-ppu') }}",
                    title: "Jalan PPU",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [75, 0, 130, 1], // purple
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>OID:</b> {OID_}<br>
          <b>Nama:</b> {Name}<br>
          <b>Folder Path:</b> {FolderPath}<br>
          <b>Symbol ID:</b> {SymbolID}<br>
          <b>Clamped:</b> {Clamped}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(jalanPPULayer);

                // Jalan Samarinda
                const jalanSamarindaLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jalan-samarinda') }}",
                    title: "Jalan Samarinda",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 255, 255, 1], // cyan
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{NAME}",
                        content: `
          <b>Nama:</b> {NAME}<br>
          <b>Layer:</b> {LAYER}<br>
          <b>OBJECTID_1:</b> {OBJECTID_1}<br>
          <b>OBJECTID:</b> {OBJECTID}<br>
          <b>Klasifikasi Data Dasar:</b> {Kl_Dat_Das}<br>
          <b>Nama Ruas:</b> {Nm_Ruas}<br>
          <b>Tahun Data:</b> {Thn_Data}<br>
          <b>Status:</b> {Status}<br>
          <b>Fungsi:</b> {Fungsi}<br>
          <b>Mendukung:</b> {Mendukung}<br>
          <b>Uraian Dukung:</b> {Ura_Dukung}<br>
          <b>Kode Bangunan PU:</b> {Kd_Bd_PU}<br>
          <b>Kode Jenis Infrastruktur:</b> {Kd_Jns_Inf}<br>
          <b>Kode Infrastruktur:</b> {Kd_Inf}<br>
          <b>Propinsi:</b> {Propinsi}<br>
          <b>Kabupaten/Kota:</b> {Kab_Kot}<br>
          <b>Kecamatan:</b> {Kecamatan}<br>
          <b>Desa/Kelurahan:</b> {Desa_Kel}<br>
          <b>Titik Ruas Awal:</b> {Tk_Ruas_Aw}<br>
          <b>Titik Ruas Akhir:</b> {Tk_Ruas_Ak}<br>
          <b>Kode Patok:</b> {Kd_Patok}<br>
          <b>Kilometer Awal:</b> {Km_Awal}<br>
          <b>Kilometer Akhir:</b> {Km_Akhir}<br>
          <b>Nama Lintas:</b> {Nm_Lintas}<br>
          <b>Kondisi Baik (%):</b> {Kon_Baik}<br>
          <b>Kondisi Sedang (%):</b> {Kon_Sdg}<br>
          <b>Kondisi Renggang (%):</b> {Kon_Rgn}<br>
          <b>Kondisi Rusak (%):</b> {Kon_Rusak}<br>
          <b>Kondisi Mantap (%):</b> {Kon_Mntp}<br>
          <b>Kondisi Tidak Mantap (%):</b> {Kon_T_Mntp}<br>
          <b>Panjang (km):</b> {Panjang}<br>
          <b>Lebar Keras (m):</b> {Lbr_Keras}<br>
          <b>LHRT:</b> {LHRT}<br>
          <b>VCR:</b> {VCR}<br>
          <b>Tipe Jalan:</b> {Tipe_Jln}<br>
          <b>MST:</b> {MST}<br>
          <b>Tanah Krikil (%):</b> {Tanah_Kri}<br>
          <b>Macadam (%):</b> {Macadam}<br>
          <b>Aspal (%):</b> {Aspal}<br>
          <b>Rigid (%):</b> {Rigid}<br>
          <b>Tahun Penanganan Akhir:</b> {Thn_Pen_Ak}<br>
          <b>Jenis Penanganan:</b> {Jns_Pen}<br>
          <b>Koordinat X Awal:</b> {Koord_X_Aw}<br>
          <b>Koordinat Y Awal:</b> {Koord_Y_Aw}<br>
          <b>Koordinat X Akhir:</b> {Koord_X_Ak}<br>
          <b>Koordinat Y Akhir:</b> {Koord_Y_Ak}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>Panjang (Shape_Le_1):</b> {Shape_Le_1}<br>
          <b>Length:</b> {LENGTH}<br>
          <b>Length 3D:</b> {LENGTH_3D}<br>
          <b>Bearing:</b> {BEARING}<br>
          <b>Line Style:</b> {LINE_STYLE}<br>
          <b>Line Color:</b> {LINE_COLOR}<br>
          <b>Line Width:</b> {LINE_WIDTH}<br>
          <b>Font Size:</b> {FONT_SIZE}<br>
          <b>Font Color:</b> {FONT_COLOR}<br>
          <b>Font Chars:</b> {FONT_CHARS}<br>
          <b>Font Weight:</b> {FONT_WEIGH}<br>
          <b>Elevation:</b> {ELEVATION}<br>
          <b>Map Name:</b> {MAP_NAME}<br>
          <b>GM Layer:</b> {GM_LAYER}<br>
          <b>GM Type:</b> {GM_TYPE}<br>
          <b>Version:</b> {version}<br>
          <b>Highway:</b> {highway}<br>
          <b>OSM ID:</b> {osm_id}<br>
          <b>Oneway:</b> {oneway}<br>
          <b>Boat:</b> {boat}<br>
          <b>Smoothness:</b> {smoothness}<br>
          <b>Start Time:</b> {START_TIME}<br>
          <b>End Time:</b> {END_TIME}<br>
          <b>Koordinat X Awal:</b> {Kord_X_Awa}<br>
          <b>Koordinat X Akhir:</b> {Kord_X_Akh}<br>
          <b>Koordinat Y Awal:</b> {Kord_Y_Awa}<br>
          <b>Koordinat Y Akhir:</b> {Kord_Y_Akh}<br>
          <b>Koordinat Y Akhir 1:</b> {Kord_Y_a_1}
        `
                    }
                });
                map.add(jalanSamarindaLayer);

                // Jaringan Listrik Balikpapan
                const jaringanListrikBalikpapanLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jaringan-listrik-balikpapan') }}",
                    title: "Jaringan Listrik Balikpapan (SUTM)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 255, 0, 1],
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Nama Objek:</b> {NAMOBJ}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Sumber Data:</b> {SBDATA}<br>
          <b>Panjang (km):</b> {Length}<br>
          <b>Keterangan:</b> {REMARK}
        `
                    }
                });
                map.add(jaringanListrikBalikpapanLayer);

                // Rencana Jaringan Listrik Bontang
                const jaringanListrikBontangLayer = new GeoJSONLayer({
                    url: "{{ url('/api/jaringan-listrik-bontang') }}",
                    title: "Rencana Jaringan Listrik Bontang",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 0, 255, 1],
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{Rencana}",
                        content: `
          <b>Rencana:</b> {Rencana}<br>
          <b>Fungsi Eksisting:</b> {fungsi_eks}<br>
          <b>Fungsi Rencana:</b> {fungsi_ren}<br>
          <b>Keterangan:</b> {Keterangan}<br>
          <b>Sumber:</b> {Sumber}
        `
                    }
                });
                map.add(jaringanListrikBontangLayer);

                // 🔹 Sistem Jaringan Energi Kukar (SUTT)
                const sistemJaringanEnergiKukarLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sistem-jaringan-energi-kukar') }}",
                    title: "Sistem Jaringan Energi Kukar (SUTT)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 69, 0, 1], // oranye-merah biar beda
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Nama Objek:</b> {NAMOBJ}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Keterangan:</b> {REMARK}<br>
          <b>Sumber Data:</b> {SBDATA}<br>
          <b>Panjang (SHAPE_Leng):</b> {SHAPE_Leng}
        `
                    }
                });
                map.add(sistemJaringanEnergiKukarLayer);

                // 🔹 Sistem Jaringan Energi Mahulu (SUTR)
                const sistemJaringanEnergiMahuluLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sistem-jaringan-energi-mahulu') }}",
                    title: "Sistem Jaringan Energi Mahulu (SUTR)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [52, 152, 219, 1], // biru muda
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Nama Objek:</b> {NAMOBJ}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Keterangan:</b> {REMARK}<br>
          <b>Sumber Data:</b> {SBDATA}<br>
          <b>Panjang (SHAPE_Leng):</b> {SHAPE_Leng}
        `
                    }
                });
                map.add(sistemJaringanEnergiMahuluLayer);

                // 🔹 Sistem Jaringan Energi Kubar (SUTM)
                const sistemJaringanEnergiKubarLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sistem-jaringan-energi-kubar') }}",
                    title: "Sistem Jaringan Energi Kubar (SUTM)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [128, 0, 128, 1], // ungu
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>Nama:</b> {Name}<br>
          <b>Layer:</b> {layer}<br>
          <b>Path:</b> {path}<br>
          <b>Deskripsi:</b> {descriptio}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(sistemJaringanEnergiKubarLayer);

                // 🔹 Sistem Jaringan Energi Kubar UP2KB (SUTM)
                const sistemJaringanEnergiKubarUP2KBlayer = new GeoJSONLayer({
                    url: "{{ url('/api/sistem-jaringan-energi-kubar-up2kb') }}",
                    title: "Sistem Jaringan Energi Kubar UP2KB (SUTM)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 215, 0, 1], // emas
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{descriptio}",
                        content: `
          <b>Deskripsi:</b> {descriptio}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(sistemJaringanEnergiKubarUP2KBlayer);

                // 🔹 Sistem Jaringan Energi Kutim (SUTM)
                const sistemJaringanEnergiKutimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sistem-jaringan-energi-kutim') }}",
                    title: "Sistem Jaringan Energi Kutim (SUTM)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 128, 128, 1], // teal
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{classifica}",
                        content: `
          <b>Klasifikasi:</b> {classifica}<br>
          <b>GlobalID:</b> {GlobalID}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(sistemJaringanEnergiKutimLayer);

                // 🔹 Sistem Jaringan Energi Paser (SUTM)
                const sistemJaringanEnergiPaserLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sistem-jaringan-energi-paser') }}",
                    title: "Sistem Jaringan Energi Paser (SUTM)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [210, 105, 30, 1], // cokelat kemerahan
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{Jalan}",
                        content: `
          <b>Status Jalan:</b> {Jalan}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Desa:</b> {WADMKD}<br>
          <b>Kabupaten:</b> {WADMKK}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(sistemJaringanEnergiPaserLayer);

                // 🔹 LN SUTM PPU
                const sutmPPULayer = new GeoJSONLayer({
                    url: "{{ url('/api/sutm-ppu') }}",
                    title: "LN SUTM PPU",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 140, 0, 1], // oranye tua
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{Nama_Jalan}",
                        content: `
          <b>Nama Jalan:</b> {Nama_Jalan}<br>
          <b>Ujung:</b> {Nama_Ujung}<br>
          <b>Panjang:</b> {Panjang} km<br>
          <b>Lebar:</b> {Lebar} m<br>
          <b>Kondisi:</b> {Kondisi}<br>
          <b>Status Jalan:</b> {Status_Jal}<br>
          <b>Fungsi Jalan:</b> {Fungsi_Jal}<br>
          <b>Panjang (SHAPE_Leng):</b> {SHAPE_Leng}
        `
                    }
                });
                map.add(sutmPPULayer);

                // 🔹 LN SUTR Kutim
                const sutrKutimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sutr-kutim') }}",
                    title: "LN SUTR Kutim",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [153, 102, 255, 1], // ungu muda
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{classifica}",
                        content: `
          <b>Klasifikasi:</b> {classifica}<br>
          <b>GlobalID:</b> {globalid_1}<br>
          <b>Panjang (shape_Leng):</b> {shape_Leng}
        `
                    }
                });
                map.add(sutrKutimLayer);

                // 🔹 LN Transmisi
                const lnTransmisiLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln-transmisi') }}",
                    title: "LN Transmisi",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 99, 132, 1], // merah muda
                            width: 3
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Nama:</b> {NAMOBJ}<br>
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Keterangan:</b> {REMARK}<br>
          <b>Sumber Data:</b> {SBDATA}<br>
          <b>Panjang (SHAPE_Leng):</b> {SHAPE_Leng}
        `
                    }
                });
                map.add(lnTransmisiLayer);

                // 🔹 LN2 SUTM Paser
                const ln2SutmPaserLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln2-sutm-paser') }}",
                    title: "LN2 SUTM Paser",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [75, 0, 130, 1], // ungu tua
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>Nama:</b> {Name}<br>
          <b>Layer:</b> {layer}<br>
          <b>Path:</b> {path}<br>
          <b>Tessellate:</b> {tessellate}<br>
          <b>Panjang (shape_Leng):</b> {shape_Leng}
        `
                    }
                });
                map.add(ln2SutmPaserLayer);

                // 🔹 LN2 SUTM PPU
                const ln2SutmPPULayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln2-sutm-ppu') }}",
                    title: "LN2 SUTM PPU",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 191, 255, 1], // deep sky blue
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Nama:</b> {NAMOBJ}<br>
          <b>Remark:</b> {REMARK}<br>
          <b>Jalan Listr:</b> {JalanListr}<br>
          <b>FCODE:</b> {FCODE}<br>
          <b>Panjang (SHAPE_Leng):</b> {SHAPE_Leng}
        `
                    }
                });
                map.add(ln2SutmPPULayer);

                // 🔹 AR Batas Kaltim Full KK KC KD
                const arBatasKaltimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ar-batas-kaltim') }}",
                    title: "AR Batas Kaltim Full KK KC KD",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-fill",
                            color: [255, 215, 0, 0.25],
                            outline: {
                                color: [255, 165, 0, 1],
                                width: 1.5
                            }
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kabupaten:</b> {WADMKK}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Desa:</b> {WADMKD}<br>
          <b>Remark:</b> {REMARK}<br>
          <b>Luas:</b> {Luas}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(arBatasKaltimLayer);

                // 🔹 AR Batas Kaltim KK Kecamatan
                const arBatasKecamatanLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ar-batas-kaltim-kecamatan') }}",
                    title: "AR Batas Kaltim KK Kecamatan",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-fill",
                            color: [144, 238, 144, 0.25], // hijau muda transparan
                            outline: {
                                color: [34, 139, 34, 1],
                                width: 1.2
                            }
                        }
                    },
                    popupTemplate: {
                        title: "{WADMKC}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kabupaten:</b> {WADMKK}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Desa:</b> {WADMKD}<br>
          <b>Nama:</b> {NAMOBJ}<br>
          <b>Remark:</b> {REMARK}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>Luas (Shape_Area):</b> {Shape_Area}
        `
                    }
                });
                map.add(arBatasKecamatanLayer);

                // 🔹 LN SUTM Berau
                const sutmBerauLayer = new GeoJSONLayer({
                    url: "{{ url('/api/sutm-berau') }}",
                    title: "LN SUTM Berau",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [46, 204, 113, 1], // hijau tosca
                            width: 2.5
                        }
                    },
                    popupTemplate: {
                        title: "{kode_hanta}",
                        content: `
          <b>Nama Segmen:</b> {kode_hanta}<br>
          <b>Lokasi:</b> {location}<br>
          <b>Penyulang:</b> {penyulang}<br>
          <b>Asset Num:</b> {assetnum}<br>
          <b>Klasifikasi:</b> {classifica}<br>
          <b>Bahan Kawat:</b> {bahan_kawa}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(sutmBerauLayer);

                // PT Gardu Berau
                const ptGarduBerauLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-gardu-berau') }}",
                    title: "PT Gardu Berau",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 165, 0], [139, 69, 19]),
                            width: "24px",
                            height: "24px"
                        }
                    },
                    popupTemplate: {
                        title: "{descriptio}",
                        content: `
          <b>Klasifikasi:</b> {classifica}<br>
          <b>Lokasi:</b> {location}<br>
          <b>Alamat:</b> {streetaddr}<br>
          <b>Kota:</b> {city}<br>
          <b>Penyulang:</b> {penyulang}<br>
          <b>Status:</b> {status}<br>
          <b>Type Gardu:</b> {type_gardu}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>Luas (Shape_Area):</b> {Shape_Area}
        `
                    }
                });
                map.add(ptGarduBerauLayer);

                // PT Gardu Distribusi Kutim
                const ptGarduDistribusiKutimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-gardu-distribusi-kutim') }}",
                    title: "PT Gardu Distribusi Kutim",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([0, 191, 255], [0, 100, 140]),
                            width: "24px",
                            height: "24px"
                        }
                    },
                    popupTemplate: {
                        title: "{classifica}",
                        content: `
          <b>Klasifikasi:</b> {classifica}<br>
          <b>Global ID:</b> {globalid}<br>
          <b>ORIG_FID:</b> {ORIG_FID}
        `
                    }
                });
                map.add(ptGarduDistribusiKutimLayer);

                // PT Gardu Hubung Kutim
                const ptGarduHubungKutimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-gardu-hubung-kutim') }}",
                    title: "PT Gardu Hubung Kutim",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([186, 85, 211], [128, 0, 128]),
                            width: "24px",
                            height: "24px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMA}",
                        content: `
          <b>GlobalID:</b> {GlobalID}<br>
          <b>ORIG_FID:</b> {ORIG_FID}
        `
                    }
                });
                map.add(ptGarduHubungKutimLayer);

                // PT Gardu Induk Kutim
                const ptGarduIndukKutimLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-gardu-induk-kutim') }}",
                    title: "PT Gardu Induk Kutim",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 99, 132], [180, 50, 80]),
                            width: "24px",
                            height: "24px"
                        }
                    },
                    popupTemplate: {
                        title: "{classifica}",
                        content: `
          <b>GlobalID:</b> {globalid}<br>
          <b>ORIG_FID:</b> {ORIG_FID}
        `
                    }
                });
                map.add(ptGarduIndukKutimLayer);

                // PT Pembangkit Eksisting
                const ptPembangkitEksistingLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-pembangkit-eksisting') }}",
                    title: "PT Pembangkit Eksisting",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([0, 255, 127], [0, 128, 64]),
                            width: "26px",
                            height: "26px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Jenis Pembangkit:</b> {J_Pmbngkt}<br>
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Remark:</b> {REMARK}<br>
          <b>Sumber:</b> {SBDATA}<br>
          <b>STSJRN:</b> {STSJRN}
        `
                    }
                });
                map.add(ptPembangkitEksistingLayer);

                // PT Rencana Pembangkit Tenaga Listrik Bontang
                const ptRencanaPembangkitBontangLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-rencana-pembangkit-bontang') }}",
                    title: "PT Rencana Pembangkit Bontang",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 215, 0], [184, 134, 11]),
                            width: "26px",
                            height: "26px"
                        }
                    },
                    popupTemplate: {
                        title: "{Nama}",
                        content: `
          <b>Arahan:</b> {Arahan}<br>
          <b>Fungsi Eksisting:</b> {fungsi_eks}<br>
          <b>Fungsi Rencana:</b> {fungsi_ren}<br>
          <b>Penjelasan:</b> {penjelasan}<br>
          <b>Sumber:</b> {Sumber}
        `
                    }
                });
                map.add(ptRencanaPembangkitBontangLayer);

                // PT Sistem Infrastruktur Energi Balikpapan
                const ptSistemEnergiBalikpapanLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-sistem-energi-balikpapan') }}",
                    title: "PT Sistem Energi Balikpapan",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([30, 144, 255], [0, 90, 180]),
                            width: "26px",
                            height: "26px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Remark:</b> {REMARK}<br>
          <b>Sumber:</b> {SBDATA}<br>
          <b>STSJRN:</b> {STSJRN}
        `
                    }
                });
                map.add(ptSistemEnergiBalikpapanLayer);

                // 🔹 PT Sistem Infrastruktur Energi Kutai Kartanegara (PLTD)
                const ptSistemEnergiKukarLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-sistem-energi-kukar') }}",
                    title: "PT Sistem Infrastruktur Energi Kutai Kartanegara",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 0, 0], [139, 0, 0]),
                            width: "28px",
                            height: "28px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Nama Objek:</b> {NAMOBJ}<br>
          <b>Keterangan:</b> {REMARK}<br>
          <b>Sumber:</b> {SBDATA}<br>
          <b>ORDE01:</b> {ORDE01}<br>
          <b>ORDE02:</b> {ORDE02}<br>
          <b>ORDE03:</b> {ORDE03}<br>
          <b>ORDE04:</b> {ORDE04}<br>
          <b>JNSRSR:</b> {JNSRSR}<br>
          <b>STSJRN:</b> {STSJRN}
        `
                    }
                });
                map.add(ptSistemEnergiKukarLayer);

                // 🔹 PT Sistem Infrastruktur Energi Mahakam Ulu (PLTS)
                const ptSistemEnergiMahuluLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-sistem-energi-mahulu') }}",
                    title: "PT Sistem Infrastruktur Energi Mahakam Ulu",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 165, 0], [200, 100, 0]),
                            width: "28px",
                            height: "28px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Nama Objek:</b> {NAMOBJ}<br>
          <b>Keterangan:</b> {REMARK}<br>
          <b>Sumber:</b> {SBDATA}<br>
          <b>ORDE01:</b> {ORDE01}<br>
          <b>ORDE02:</b> {ORDE02}<br>
          <b>ORDE03:</b> {ORDE03}<br>
          <b>ORDE04:</b> {ORDE04}<br>
          <b>JNSRSR:</b> {JNSRSR}<br>
          <b>STSJRN:</b> {STSJRN}
        `
                    }
                });
                map.add(ptSistemEnergiMahuluLayer);

                // 🔹 PT Sistem Infrastruktur Energi Kota Samarinda (Gardu Listrik)
                const ptSistemEnergiSamarindaLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-sistem-energi-samarinda') }}",
                    title: "PT Sistem Infrastruktur Energi Kota Samarinda",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([0, 255, 255], [0, 139, 139]),
                            width: "28px",
                            height: "28px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kab/Kota:</b> {WADMKK}<br>
          <b>Nama Objek:</b> {NAMOBJ}<br>
          <b>Keterangan:</b> {REMARK}<br>
          <b>Sumber:</b> {SBDATA}<br>
          <b>ORDE01:</b> {ORDE01}<br>
          <b>ORDE02:</b> {ORDE02}<br>
          <b>ORDE03:</b> {ORDE03}<br>
          <b>ORDE04:</b> {ORDE04}<br>
          <b>JNSRSR:</b> {JNSRSR}<br>
          <b>STSJRN:</b> {STSJRN}
        `
                    }
                });
                map.add(ptSistemEnergiSamarindaLayer);

                // 🔹 PT Trafo Berau
                const ptTrafoBerauLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-trafo-berau') }}",
                    title: "PT Trafo Berau",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([128, 0, 128], [75, 0, 75]),
                            width: "28px",
                            height: "28px"
                        }
                    },
                    popupTemplate: {
                        title: "{descriptio}",
                        content: `
          <b>Deskripsi:</b> {descriptio}<br>
          <b>Klasifikasi:</b> {classifica}<br>
          <b>Lokasi:</b> {location}<br>
          <b>Nomor Aset:</b> {assetnum}<br>
          <b>Status:</b> {status}<br>
          <b>Fasa:</b> {fasa_trafo}<br>
          <b>Jenis Trafo:</b> {jenis_traf}<br>
          <b>Kapasitas:</b> {kapasitas}<br>
          <b>Peruntukan:</b> {peruntukan}<br>
          <b>Status Kepemilikan:</b> {status_kep}<br>
          <b>Tegangan:</b> {tegangan_t}<br>
          <b>Tahun Pembuatan:</b> {th_buat}<br>
          <b>Penyulang:</b> {penyulang}<br>
          <b>Serial Number:</b> {serialnum}
        `
                    }
                });
                map.add(ptTrafoBerauLayer);

                // 🔹 PT Trafo Gardu Distribusi PPU
                const ptTrafoGarduDistribusiPpuLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-trafo-gardu-distribusi-ppu') }}",
                    title: "PT Trafo Gardu Distribusi PPU",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 192, 203], [199, 21, 133]),
                            width: "24px",
                            height: "24px"
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>Nama:</b> {Name}<br>
          <b>Kode:</b> {Nama}<br>
          <b>Path Folder:</b> {FolderPath}<br>
          <b>Nama (Jika Ada):</b> {Nama}<br>
          <b>Symbol ID:</b> {SymbolID}<br>
          <b>Alt Mode:</b> {AltMode}<br>
          <b>Base:</b> {Base}<br>
          <b>Time Span:</b> {TimeSpan}<br>
          <b>Time Stamp:</b> {TimeStamp}<br>
          <b>Begin Time:</b> {BeginTime}<br>
          <b>End Time:</b> {EndTime}<br>
          <b>Has Label:</b> {HasLabel}<br>
          <b>Label ID:</b> {LabelID}
        `
                    }
                });
                map.add(ptTrafoGarduDistribusiPpuLayer);

                // 🔹 PT Trafo Gardu Kubar (Arrester)
                const ptTrafoGarduKubarLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt-trafo-gardu-kubar') }}",
                    title: "PT Trafo Gardu Kubar (Arrester)",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 140, 0], [205, 92, 0]),
                            width: "24px",
                            height: "24px"
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>Nama:</b> {Name}<br>
          <b>Kapasitas:</b> {KAPASITAS}<br>
          <b>Feeder:</b> {FEEDER}<br>
          <b>Zona:</b> {ZONA}<br>
          <b>Nilai Pentanahan:</b> {NILAI_PENT}<br>
          <b>Latitude:</b> {LATITUDE}<br>
          <b>Longitude:</b> {LONGITUDE}<br>
          <b>Layer:</b> {layer}<br>
          <b>Path:</b> {path}<br>
          <b>Deskripsi:</b> {descriptio}
        `
                    }
                });
                map.add(ptTrafoGarduKubarLayer);

                // 🔹 PT1 Trafo Gardu Paser (Gardu dan Trafo Lainnya)
                const pt1TrafoGarduPaserLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt1-trafo-gardu-paser') }}",
                    title: "PT1 Trafo Gardu Paser",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([0, 128, 0], [0, 80, 0]),
                            width: "26px",
                            height: "26px"
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>Nama:</b> {Name}<br>
          <b>Jenis:</b> {Data}<br>
          <b>Tipe:</b> {Type}<br>
          <b>Deskripsi:</b> {Descript}<br>
          <b>Komentar:</b> {Comment}<br>
          <b>Simbol:</b> {Symbol}<br>
          <b>Tanggal Waktu:</b> {DateTimeS}<br>
          <b>Elevasi:</b> {Elevation} m<br>
          <b>Nama (Jika Ada):</b> {Nama}
        `
                    }
                });
                map.add(pt1TrafoGarduPaserLayer);

                // 🔹 PT2 Trafo Gardu Paser (Gardu Induk Grogot)
                const pt2TrafoGarduPaserLayer = new GeoJSONLayer({
                    url: "{{ url('/api/pt2-trafo-gardu-paser') }}",
                    title: "PT2 Trafo Gardu Paser",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([255, 0, 0], [139, 0, 0]),
                            width: "26px",
                            height: "26px"
                        }
                    },
                    popupTemplate: {
                        title: "{Name}",
                        content: `
          <b>Nama:</b> {Name}<br>
          <b>Deskripsi:</b> {descriptio}<br>
          <b>Timestamp:</b> {timestamp}<br>
          <b>Tes 1:</b> {TES_1}<br>
          <b>Tes 2:</b> {TES_2}<br>
          <b>Tes 4:</b> {TES_4}<br>
          <b>Tes 5 (Koordinat):</b> {TES_5}<br>
          <b>Tes 6:</b> {TES_6}
        `
                    }
                });
                map.add(pt2TrafoGarduPaserLayer);

                // 🔹 PT Hasil Lokasi Survei ESDM
                const ptHasilLokasiSurveiEsdmLayer = new GeoJSONLayer({
                    url: "{{ url('/api/hasil-lokasi-survei-esdm') }}",
                    title: "PT Hasil Lokasi Survei ESDM",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "picture-marker",
                            url: createLocationPinSvg([0, 128, 255], [0, 64, 128]),
                            width: "26px",
                            height: "26px"
                        }
                    },
                    popupTemplate: {
                        title: "{NAMOBJ}",
                        content: function(feature) {
                            const attrs = feature.graphic.attributes;

                            // Helper function to escape HTML attributes
                            const escapeAttr = (str) => {
                                if (!str) return '';
                                return String(str).replace(/"/g, '&quot;').replace(/'/g,
                                    '&#39;');
                            };

                            const pickVideoLink = (itemAttrs) => {
                                const raw = itemAttrs?.link_dokumen;
                                if (!raw) return null;

                                const links = String(raw)
                                    .split('|')
                                    .map((v) => v.trim())
                                    .filter(Boolean);

                                if (!links.length) return null;

                                const isPenajam = (itemAttrs.WADMKC || '').toLowerCase()
                                    .includes('penajam');
                                const chosen = isPenajam ? links[Math.floor(Math.random() *
                                    links.length)] : links[0];

                                return {
                                    url: chosen,
                                    title: itemAttrs.Kodifikasi || itemAttrs.NAMOBJ ||
                                        'Video 360',
                                };
                            };

                            let content = `
            <b>Nama Objek:</b> ${attrs.NAMOBJ || '-'}<br>
            <b>Lokasi:</b> ${attrs.Lokasi || '-'}<br>
            <b>Desa:</b> ${attrs.WADMKD || '-'}<br>
            <b>Kecamatan:</b> ${attrs.WADMKC || '-'}<br>
            <b>Kabupaten/Kota:</b> ${attrs.WADMKK || '-'}<br>
            <b>Provinsi:</b> ${attrs.WADMPR || '-'}<br>
            <b>Status:</b> ${attrs.Status || '-'}<br>
            <b>Dusun:</b> ${attrs.DUSUN || '-'}<br>
            <b>Jumlah KK:</b> ${attrs.J_KK || '-'}<br>
            <b>Jumlah Penduduk:</b> ${attrs.J_Pnddk || '-'}<br>
            <b>Jumlah Rumah:</b> ${attrs.J_BRumah || '-'}<br>
            <b>Koordinat X:</b> ${attrs.Koor_X || '-'}<br>
            <b>Koordinat Y:</b> ${attrs.Koor_Y || '-'}<br>
            <b>Potensi:</b> ${attrs.Potensi || '-'}<br>
            <b>Prioritas:</b> ${attrs.Priorita_1 || '-'}<br>
            <b>Kendala:</b> ${attrs.KENDALA || '-'}<br>
            <b>Kodifikasi:</b> ${attrs.Kodifikasi || '-'}<br>
          `;



                            return content;
                        },
                        actions: []
                    }
                });
                map.add(ptHasilLokasiSurveiEsdmLayer);

                // LN Batas Desa
                const lnBatasDesaLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln-batas-desa') }}",
                    title: "LN Batas Desa",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [220, 20, 60, 1], // merah tua
                            width: 1.6
                        }
                    },
                    popupTemplate: {
                        title: "{WADMKD}",
                        content: `
          <b>Nama:</b> {NAMOBJ}<br>
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kabupaten:</b> {WADMKK}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Desa:</b> {WADMKD}<br>
          <b>Remark:</b> {REMARK}<br>
          <b>Luas:</b> {Luas}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}
        `
                    }
                });
                map.add(lnBatasDesaLayer);

                // LN Batas Kabupaten/Kota
                const lnBatasKabKotaLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln-batas-kabkota') }}",
                    title: "LN Batas Kabupaten/Kota",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [0, 122, 255, 1], // biru terang
                            width: 2
                        }
                    },
                    popupTemplate: {
                        title: "{WADMKK}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kabupaten/Kota:</b> {WADMKK}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>FID:</b> {FID_AR_BAT}
        `
                    }
                });
                map.add(lnBatasKabKotaLayer);

                // LN Batas Kecamatan
                const lnBatasKecamatanLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln-batas-kecamatan') }}",
                    title: "LN Batas Kecamatan",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [255, 140, 0, 1], // oranye tua
                            width: 1.8
                        }
                    },
                    popupTemplate: {
                        title: "{WADMKC}",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Kabupaten:</b> {WADMKK}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>FID:</b> {FID_AR_BAT}
        `
                    }
                });
                map.add(lnBatasKecamatanLayer);

                // LN Batas Negara
                const lnBatasNegaraLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln-batas-negara') }}",
                    title: "LN Batas Negara",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [34, 139, 34, 1], // hijau gelap
                            width: 2.2
                        }
                    },
                    popupTemplate: {
                        title: "Batas Negara",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>FID Export:</b> {FID_Export}
        `
                    }
                });
                map.add(lnBatasNegaraLayer);

                // LN Batas Provinsi
                const lnBatasProvinsiLayer = new GeoJSONLayer({
                    url: "{{ url('/api/ln-batas-provinsi') }}",
                    title: "LN Batas Provinsi",
                    outFields: ["*"],
                    renderer: {
                        type: "simple",
                        symbol: {
                            type: "simple-line",
                            color: [128, 0, 0, 1], // maroon
                            width: 2.4
                        }
                    },
                    popupTemplate: {
                        title: "Batas Provinsi",
                        content: `
          <b>Provinsi:</b> {WADMPR}<br>
          <b>Panjang (Shape_Leng):</b> {Shape_Leng}<br>
          <b>FID Export:</b> {FID_Export}
        `
                    }
                });
                map.add(lnBatasProvinsiLayer);

                // ================== SET INITIAL LAYER VISIBILITY ==================
                // Hide all layers on initial load
                desaBerlistrikLayer.visible = false;
                jalanNasionalLayer.visible = false;
                jalanProvinsiLayer.visible = false;
                jalanBalikpapanLayer.visible = false;
                jalanBerauLayer.visible = false;
                jalanBontangLayer.visible = false;
                jalanKubarLayer.visible = false;
                jalanKutaiKartanegaraLayer.visible = false;
                jalanKutimLayer.visible = false;
                jalanPaserLayer.visible = false;
                jalanPPULayer.visible = false;
                jalanSamarindaLayer.visible = false;
                jaringanListrikBalikpapanLayer.visible = false;
                jaringanListrikBontangLayer.visible = false;
                sistemJaringanEnergiKukarLayer.visible = false;
                sistemJaringanEnergiMahuluLayer.visible = false;
                sistemJaringanEnergiKubarLayer.visible = false;
                sistemJaringanEnergiKubarUP2KBlayer.visible = false;
                sistemJaringanEnergiKutimLayer.visible = false;
                sistemJaringanEnergiPaserLayer.visible = false;
                sutmPPULayer.visible = false;
                sutrKutimLayer.visible = false;
                lnTransmisiLayer.visible = false;
                ln2SutmPaserLayer.visible = false;
                ln2SutmPPULayer.visible = false;
                arBatasKaltimLayer.visible = false;
                arBatasKecamatanLayer.visible = false;
                sutmBerauLayer.visible = false;
                ptGarduBerauLayer.visible = false;
                ptGarduDistribusiKutimLayer.visible = false;
                ptGarduHubungKutimLayer.visible = false;
                ptGarduIndukKutimLayer.visible = false;
                ptPembangkitEksistingLayer.visible = false;
                ptRencanaPembangkitBontangLayer.visible = false;
                ptSistemEnergiBalikpapanLayer.visible = false;
                ptSistemEnergiKukarLayer.visible = false;
                ptSistemEnergiMahuluLayer.visible = false;
                ptSistemEnergiSamarindaLayer.visible = false;
                ptTrafoBerauLayer.visible = false;
                ptTrafoGarduDistribusiPpuLayer.visible = false;
                ptTrafoGarduKubarLayer.visible = false;
                pt1TrafoGarduPaserLayer.visible = false;
                pt2TrafoGarduPaserLayer.visible = false;
                ptHasilLokasiSurveiEsdmLayer.visible = false;
                lnBatasDesaLayer.visible = false;
                lnBatasKabKotaLayer.visible = false;
                lnBatasKecamatanLayer.visible = false;
                lnBatasNegaraLayer.visible = false;
                lnBatasProvinsiLayer.visible = false;

                // ================== LAYER FILTER PANEL ==================
                // Organize layers into categories with icons
                const layerCategories = {
                    // 3. Titik Aset Jaringan - Now with sub-categories
                    gardu: [{
                            label: 'Gardu Induk Kutim',
                            layer: ptGarduIndukKutimLayer,
                            icon: '🏭'
                        },
                        {
                            label: 'Gardu Distribusi Kutim',
                            layer: ptGarduDistribusiKutimLayer,
                            icon: '🏭'
                        },
                        {
                            label: 'Gardu Hubung Kutim',
                            layer: ptGarduHubungKutimLayer,
                            icon: '🏭'
                        },
                        {
                            label: 'Gardu Berau',
                            layer: ptGarduBerauLayer,
                            icon: '🏭'
                        }
                    ],
                    trafo: [{
                            label: 'Trafo Berau',
                            layer: ptTrafoBerauLayer,
                            icon: '🔧'
                        },
                        {
                            label: 'Trafo Gardu Distribusi PPU',
                            layer: ptTrafoGarduDistribusiPpuLayer,
                            icon: '🔧'
                        },
                        {
                            label: 'Trafo Gardu Kubar',
                            layer: ptTrafoGarduKubarLayer,
                            icon: '🔧'
                        },
                        {
                            label: 'Trafo Gardu Paser 1',
                            layer: pt1TrafoGarduPaserLayer,
                            icon: '🔧'
                        },
                        {
                            label: 'Trafo Gardu Paser 2',
                            layer: pt2TrafoGarduPaserLayer,
                            icon: '🔧'
                        }
                    ],
                    // 4. Data Jaringan Saluran - Now with sub-categories Distribusi and Transmisi
                    distribusi: [{
                            label: 'Sistem Energi Kubar (SUTM)',
                            layer: sistemJaringanEnergiKubarLayer,
                            icon: '⚡'
                        },
                        {
                            label: 'Sistem Energi Kutim (SUTM)',
                            layer: sistemJaringanEnergiKutimLayer,
                            icon: '⚡'
                        },
                        {
                            label: 'Sistem Energi Paser (SUTM)',
                            layer: sistemJaringanEnergiPaserLayer,
                            icon: '⚡'
                        },
                        {
                            label: 'SUTM PPU',
                            layer: sutmPPULayer,
                            icon: '⚡'
                        },
                        {
                            label: 'SUTM Paser',
                            layer: ln2SutmPaserLayer,
                            icon: '⚡'
                        },
                        {
                            label: 'SUTM PPU 2',
                            layer: ln2SutmPPULayer,
                            icon: '⚡'
                        },
                        {
                            label: 'SUTM Berau',
                            layer: sutmBerauLayer,
                            icon: '⚡'
                        }
                    ],
                    transmisi: [{
                            label: 'LN Transmisi (SUTT/SUTET)',
                            layer: lnTransmisiLayer,
                            icon: '🔋'
                        },
                        {
                            label: 'Sistem Energi Kukar (SUTT)',
                            layer: sistemJaringanEnergiKukarLayer,
                            icon: '🔋'
                        },
                        {
                            label: 'Jaringan Listrik Balikpapan',
                            layer: jaringanListrikBalikpapanLayer,
                            icon: '🔋'
                        },
                        {
                            label: 'Rencana Jaringan Bontang',
                            layer: jaringanListrikBontangLayer,
                            icon: '📋'
                        },
                        {
                            label: 'Sistem Energi Kubar UP2KB',
                            layer: sistemJaringanEnergiKubarUP2KBlayer,
                            icon: '🔋'
                        },
                        {
                            label: 'Sistem Energi Mahulu (SUTR)',
                            layer: sistemJaringanEnergiMahuluLayer,
                            icon: '🔋'
                        },
                        {
                            label: 'SUTR Kutim',
                            layer: sutrKutimLayer,
                            icon: '🔋'
                        },
                        {
                            label: 'Sistem Energi Balikpapan',
                            layer: ptSistemEnergiBalikpapanLayer,
                            icon: '⚙️'
                        },
                        {
                            label: 'Sistem Energi Kukar',
                            layer: ptSistemEnergiKukarLayer,
                            icon: '⚙️'
                        },
                        {
                            label: 'Sistem Energi Mahulu',
                            layer: ptSistemEnergiMahuluLayer,
                            icon: '⚙️'
                        },
                        {
                            label: 'Sistem Energi Samarinda',
                            layer: ptSistemEnergiSamarindaLayer,
                            icon: '⚙️'
                        },

                    ],
                    // 5. Data Dasar (Batas Administrasi)
                    administrasi: [{
                            label: 'Batas Desa',
                            layer: lnBatasDesaLayer,
                            icon: '🏘️'
                        },
                        {
                            label: 'Batas Kecamatan',
                            layer: lnBatasKecamatanLayer,
                            icon: '🏛️'
                        },
                        {
                            label: 'Batas Kab/Kota',
                            layer: lnBatasKabKotaLayer,
                            icon: '🏙️'
                        },
                        {
                            label: 'Batas Provinsi',
                            layer: lnBatasProvinsiLayer,
                            icon: '🗺️'
                        },
                        {
                            label: 'Batas Negara',
                            layer: lnBatasNegaraLayer,
                            icon: '🌍'
                        },
                        {
                            label: 'AR Batas Kaltim Full',
                            layer: arBatasKaltimLayer,
                            icon: '📍'
                        },
                        {
                            label: 'AR Batas Kec.',
                            layer: arBatasKecamatanLayer,
                            icon: '📍'
                        }
                    ],
                    // 6. Data Jalan
                    jalan: [{
                            label: 'Jalan Nasional',
                            layer: jalanNasionalLayer,
                            icon: '🛣️'
                        },
                        {
                            label: 'Jalan Provinsi',
                            layer: jalanProvinsiLayer,
                            icon: '🛤️'
                        },
                        {
                            label: 'Jalan Balikpapan',
                            layer: jalanBalikpapanLayer,
                            icon: '🚗'
                        },
                        {
                            label: 'Jalan Berau',
                            layer: jalanBerauLayer,
                            icon: '🚚'
                        },
                        {
                            label: 'Jalan Bontang',
                            layer: jalanBontangLayer,
                            icon: '🚛'
                        },
                        {
                            label: 'Jalan Kubar',
                            layer: jalanKubarLayer,
                            icon: '🛣️'
                        },
                        {
                            label: 'Jalan Kutai Kartanegara',
                            layer: jalanKutaiKartanegaraLayer,
                            icon: '🛣️'
                        },
                        {
                            label: 'Jalan Kutim',
                            layer: jalanKutimLayer,
                            icon: '🛣️'
                        },
                        {
                            label: 'Jalan Paser',
                            layer: jalanPaserLayer,
                            icon: '🛣️'
                        },
                        {
                            label: 'Jalan PPU',
                            layer: jalanPPULayer,
                            icon: '🛣️'
                        },
                        {
                            label: 'Jalan Samarinda',
                            layer: jalanSamarindaLayer,
                            icon: '🛣️'
                        }
                    ]

                };

                // ===== LIVE LOCATION (seperti Google Maps) =====
                const liveLocationLayer = new GraphicsLayer({
                    title: "Lokasi saya",
                    listMode: "hide"
                });
                map.add(liveLocationLayer);

                const accuracyWidget = document.createElement("div");
                accuracyWidget.className = "live-location-accuracy-widget esri-widget esri-component";
                accuracyWidget.innerHTML = "📍 Lokasi belum aktif";

                // ===== CREATE LAYER FILTER FIRST (before other widgets) =====
                const layerFilter = document.createElement('div');
                layerFilter.className = 'layer-filter';

                // Build category HTML
                let categoriesHTML = '';

                // 2. Status Listrik (special case with custom filter)
                categoriesHTML += `
      <label class="lf-row lf-parent" data-category="desa">
        <span class="lf-toggle">▼</span>
        <input type="checkbox" id="lf-desa-parent">
        <span class="lf-icon">💡</span>
        <span><strong>Status Listrik</strong></span>
      </label>
      <div class="lf-children" data-category="desa">
        <label class="lf-row lf-child"><input type="checkbox" id="lf-desa-terlayani"> <span class="lf-icon">🟢</span> <span>Terlayani Listrik</span></label>
        <label class="lf-row lf-child"><input type="checkbox" id="lf-desa-belum"> <span class="lf-icon">🔴</span> <span>Belum Terlayani Listrik</span></label>
      </div>
    `;

                function setWhereOnAll(where) {
                    asetLayers.forEach(l => l.definitionExpression = where);
                }

                async function queryFeatureCountAll(where) {
                    const counts = await Promise.all(asetLayers.map(l => l.queryFeatureCount({
                        where
                    })));
                    return counts.reduce((a, b) => a + (b || 0), 0);
                }

                async function zoomToWhere(where, fallbackCenterLngLat = null, fallbackZoom = 11) {
                    try {
                        const extents = await Promise.all(asetLayers.map(l => l.queryExtent({
                            where
                        })));
                        const exts = extents.map(e => e?.extent).filter(x => x && isFinite(x.xmin));
                        let target = null;
                        if (exts.length === 1) target = exts[0].expand(1.2);
                        if (exts.length > 1) target = exts.reduce((u, e) => u ? u.union(e) : e, null)
                            .expand(1.2);
                        if (target) {
                            await view.goTo({
                                target
                            }, {
                                duration: 800
                            });
                            return true;
                        }
                    } catch (e) {
                        console.warn('queryExtent failed:', e);
                    }
                    if (fallbackCenterLngLat) {
                        await view.goTo({
                            center: fallbackCenterLngLat,
                            zoom: fallbackZoom
                        }, {
                            duration: 800
                        });
                    }
                    return false;
                }

                categoriesHTML += `<label class="lf-row lf-parent" data-category="infrastruktur">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-infrastruktur-parent">
      <span class="lf-icon">🏭</span>
      <span><strong>Infrastruktur</strong></span>
    </label>
    <div class="lf-children" data-category="infrastruktur">
      <!-- Gardu sub-parent -->
      <label class="lf-row lf-subparent" data-subcategory="gardu">
        <span class="lf-toggle lf-subtoggle">▼</span>
        <input type="checkbox" id="lf-gardu-parent">
        <span class="lf-icon">🏭</span>
        <span><strong>Gardu</strong></span>
      </label>
      <div class="lf-subchildren" data-subcategory="gardu">`;
                layerCategories.gardu.forEach((item, idx) => {
                    categoriesHTML +=
                        `<label class="lf-row lf-subchild"><input type="checkbox" id="lf-gardu-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
                });
                categoriesHTML += `</div>
      <!-- Trafo sub-parent -->
      <label class="lf-row lf-subparent" data-subcategory="trafo">
        <span class="lf-toggle lf-subtoggle">▼</span>
        <input type="checkbox" id="lf-trafo-parent">
        <span class="lf-icon">🔧</span>
        <span><strong>Trafo</strong></span>
      </label>
      <div class="lf-subchildren" data-subcategory="trafo">`;
                layerCategories.trafo.forEach((item, idx) => {
                    categoriesHTML +=
                        `<label class="lf-row lf-subchild"><input type="checkbox" id="lf-trafo-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
                });
                categoriesHTML += `</div>
    </div>`;

                // 4. Data Jaringan Saluran - with nested Distribusi and Transmisi sub-categories
                categoriesHTML += `<label class="lf-row lf-parent" data-category="saluran">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-saluran-parent">
      <span class="lf-icon">⚡</span>
      <span><strong>Data Jaringan Saluran</strong></span>
    </label>
    <div class="lf-children" data-category="saluran">
      <!-- Distribusi sub-parent -->
      <label class="lf-row lf-subparent" data-subcategory="distribusi">
        <span class="lf-toggle lf-subtoggle">▼</span>
        <input type="checkbox" id="lf-distribusi-parent">
        <span class="lf-icon">⚡</span>
        <span><strong>Distribusi</strong></span>
      </label>
      <div class="lf-subchildren" data-subcategory="distribusi">`;
                layerCategories.distribusi.forEach((item, idx) => {
                    categoriesHTML +=
                        `<label class="lf-row lf-subchild"><input type="checkbox" id="lf-distribusi-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
                });
                categoriesHTML += `</div>
      <!-- Transmisi sub-parent -->
      <label class="lf-row lf-subparent" data-subcategory="transmisi">
        <span class="lf-toggle lf-subtoggle">▼</span>
        <input type="checkbox" id="lf-transmisi-parent">
        <span class="lf-icon">🔋</span>
        <span><strong>Transmisi</strong></span>
      </label>
      <div class="lf-subchildren" data-subcategory="transmisi">`;
                layerCategories.transmisi.forEach((item, idx) => {
                    categoriesHTML +=
                        `<label class="lf-row lf-subchild"><input type="checkbox" id="lf-transmisi-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
                });
                categoriesHTML += `</div>
    </div>`;

                // 5. Data Dasar (Batas Administrasi)
                categoriesHTML += `<label class="lf-row lf-parent" data-category="administrasi">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-administrasi-parent">
      <span class="lf-icon">🗺️</span>
      <span><strong>Data Dasar (Batas Administrasi)</strong></span>
    </label>
    <div class="lf-children" data-category="administrasi">`;
                layerCategories.administrasi.forEach((item, idx) => {
                    categoriesHTML +=
                        `<label class="lf-row lf-child"><input type="checkbox" id="lf-administrasi-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
                });
                categoriesHTML += `</div>`;

                // 6. Data Jalan
                categoriesHTML += `<label class="lf-row lf-parent" data-category="jalan">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-jalan-parent">
      <span class="lf-icon">🚧</span>
      <span><strong>Data Jalan</strong></span>
    </label>
    <div class="lf-children collapsed" data-category="jalan">`;
                layerCategories.jalan.forEach((item, idx) => {
                    categoriesHTML +=
                        `<label class="lf-row lf-child"><input type="checkbox" id="lf-jalan-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
                });
                categoriesHTML += `</div>`;
                categoriesHTML += `</div>`;

                layerFilter.innerHTML = `
      <div class="lf-head">
        <div class="lf-title">🔍 Filter Peta</div>
        <button class="lf-toggle-btn" title="Tutup/Buka">▼</button>
      </div>
      <div class="lf-body">
        <div class="lf-wilayah-filter">
          <div class="lf-wilayah-title">
            <span class="lf-icon">🗺️</span>
            <span><strong>Filter Wilayah Administratif</strong></span>
          </div>
          <div class="lf-wilayah-dropdowns">
            <select id="lf-filter-regency" class="lf-dropdown">
              <option value="">Semua Kabupaten/Kota</option>
            </select>
            <select id="lf-filter-district" class="lf-dropdown" disabled>
              <option value="">Semua Kecamatan</option>
            </select>
            <select id="lf-filter-village" class="lf-dropdown" disabled>
              <option value="">Semua Kelurahan/Desa</option>
            </select>
            <button id="lf-reset-filter" class="lf-reset-btn">Reset Filter</button>
          </div>
        </div>
        <div class="lf-divider"></div>
        <label class="lf-row lf-all"><input type="checkbox" id="lf-all"> <span class="lf-icon">📊</span> <span><strong>Semua Data</strong></span></label>
        <div class="lf-divider"></div>
        ${categoriesHTML}
      </div>
    `;

                // Handle \"Semua Layer\" checkbox
                const allCheckbox = layerFilter.querySelector('#lf-all');
                const desaParentCheckbox = layerFilter.querySelector('#lf-desa-parent');
                const desaBelumCheckbox = layerFilter.querySelector('#lf-desa-belum');
                const desaTerlayaniCheckbox = layerFilter.querySelector('#lf-desa-terlayani');

                // Function to update desaBerlistrikLayer renderer based on filter
                const updateDesaBerlistrikFilter = () => {
                    const showBelum = desaBelumCheckbox.checked;
                    const showTerlayani = desaTerlayaniCheckbox.checked;

                    if (!showBelum && !showTerlayani) {
                        desaBerlistrikLayer.visible = false;
                    } else {
                        desaBerlistrikLayer.visible = true;
                        const uniqueValueInfos = [];

                        if (showBelum) {
                            uniqueValueInfos.push({
                                value: "Belum Terlayani Listrik",
                                label: "Belum Terlayani Listrik",
                                symbol: {
                                    type: "simple-fill",
                                    color: [220, 38, 38, 0.45],
                                    outline: {
                                        color: [185, 28, 28, 1],
                                        width: 1.5
                                    }
                                }
                            });
                        }

                        if (showTerlayani) {
                            uniqueValueInfos.push({
                                value: "Terlayani Listrik",
                                label: "Sudah Terlayani Listrik",
                                symbol: {
                                    type: "simple-fill",
                                    color: [34, 197, 94, 0.45],
                                    outline: {
                                        color: [22, 163, 74, 1],
                                        width: 1.5
                                    }
                                }
                            });
                        }

                        desaBerlistrikLayer.renderer = {
                            type: "unique-value",
                            field: "H_Survei",
                            defaultSymbol: null,
                            uniqueValueInfos: uniqueValueInfos
                        };
                    }
                };

                // Helper function to update parent checkbox based on children
                const updateParentCheckbox = (parentId, childrenIds) => {
                    const parent = layerFilter.querySelector(`#${parentId}`);
                    const anyChecked = childrenIds.some(id => layerFilter.querySelector(`#${id}`)?.checked);
                    if (parent) parent.checked = anyChecked;
                };

                // Helper function to set all category checkboxes
                const setCategoryCheckboxes = (categoryName, isChecked) => {
                    const parent = layerFilter.querySelector(`#lf-${categoryName}-parent`);
                    if (parent) parent.checked = isChecked;

                    layerCategories[categoryName]?.forEach((item, idx) => {
                        const checkbox = layerFilter.querySelector(`#lf-${categoryName}-${idx}`);
                        if (checkbox) checkbox.checked = isChecked;
                        item.layer.visible = isChecked;
                    });
                };

                // "Semua Layer" checkbox - controls all categories
                allCheckbox.addEventListener('change', () => {
                    const isChecked = allCheckbox.checked;

                    // Update desa berlistrik filters
                    desaParentCheckbox.checked = isChecked;
                    desaBelumCheckbox.checked = isChecked;
                    desaTerlayaniCheckbox.checked = isChecked;
                    updateDesaBerlistrikFilter();

                    // Update all other categories
                    // Update Infrastruktur parent and sub-categories
                    const infrastrukturParent = layerFilter.querySelector('#lf-infrastruktur-parent');
                    if (infrastrukturParent) infrastrukturParent.checked = isChecked;
                    setCategoryCheckboxes('gardu', isChecked);
                    setCategoryCheckboxes('trafo', isChecked);

                    // Update Saluran parent and sub-categories
                    const saluranParent = layerFilter.querySelector('#lf-saluran-parent');
                    if (saluranParent) saluranParent.checked = isChecked;
                    setCategoryCheckboxes('distribusi', isChecked);
                    setCategoryCheckboxes('transmisi', isChecked);

                    // Update other categories
                    setCategoryCheckboxes('administrasi', isChecked);
                    setCategoryCheckboxes('jalan', isChecked);
                    setCategoryCheckboxes('lainnya', isChecked);
                    setCategoryCheckboxes('surveiVideo360', isChecked);
                });

                // Status Listrik Desa handlers
                desaParentCheckbox.addEventListener('change', () => {
                    const isChecked = desaParentCheckbox.checked;
                    desaBelumCheckbox.checked = isChecked;
                    desaTerlayaniCheckbox.checked = isChecked;
                    updateDesaBerlistrikFilter();
                });

                desaBelumCheckbox.addEventListener('change', () => {
                    updateDesaBerlistrikFilter();
                    desaParentCheckbox.checked = desaBelumCheckbox.checked || desaTerlayaniCheckbox
                        .checked;
                });

                desaTerlayaniCheckbox.addEventListener('change', () => {
                    updateDesaBerlistrikFilter();
                    desaParentCheckbox.checked = desaBelumCheckbox.checked || desaTerlayaniCheckbox
                        .checked;
                });

                // Setup handlers for each category
                const setupCategoryHandlers = (categoryName) => {
                    const parentCheckbox = layerFilter.querySelector(`#lf-${categoryName}-parent`);
                    if (!parentCheckbox) return;

                    // Parent checkbox controls all children
                    parentCheckbox.addEventListener('change', () => {
                        const isChecked = parentCheckbox.checked;
                        layerCategories[categoryName].forEach((item, idx) => {
                            const childCheckbox = layerFilter.querySelector(
                                `#lf-${categoryName}-${idx}`);
                            if (childCheckbox) childCheckbox.checked = isChecked;
                            item.layer.visible = isChecked;
                        });
                    });

                    // Each child checkbox
                    layerCategories[categoryName].forEach((item, idx) => {
                        const childCheckbox = layerFilter.querySelector(
                            `#lf-${categoryName}-${idx}`);
                        if (!childCheckbox) return;

                        childCheckbox.addEventListener('change', () => {
                            item.layer.visible = childCheckbox.checked;

                            // Update parent checkbox state
                            const childIds = layerCategories[categoryName].map((_, i) =>
                                `lf-${categoryName}-${i}`);
                            updateParentCheckbox(`lf-${categoryName}-parent`, childIds);

                            // Update grandparent checkboxes for nested categories
                            if (categoryName === 'gardu' || categoryName === 'trafo') {
                                const garduParent = layerFilter.querySelector(
                                    '#lf-gardu-parent');
                                const trafoParent = layerFilter.querySelector(
                                    '#lf-trafo-parent');
                                const infrastrukturParent = layerFilter.querySelector(
                                    '#lf-infrastruktur-parent');
                                if (infrastrukturParent) {
                                    infrastrukturParent.checked = (garduParent?.checked ||
                                        trafoParent?.checked);
                                }
                            }
                            if (categoryName === 'distribusi' || categoryName ===
                                'transmisi') {
                                const distribusiParent = layerFilter.querySelector(
                                    '#lf-distribusi-parent');
                                const transmisiParent = layerFilter.querySelector(
                                    '#lf-transmisi-parent');
                                const saluranParent = layerFilter.querySelector(
                                    '#lf-saluran-parent');
                                if (saluranParent) {
                                    saluranParent.checked = (distribusiParent?.checked ||
                                        transmisiParent?.checked);
                                }
                            }
                        });
                    });
                };

                // Setup all categories (including new sub-categories)
                setupCategoryHandlers('gardu');
                setupCategoryHandlers('trafo');
                setupCategoryHandlers('distribusi');
                setupCategoryHandlers('transmisi');
                setupCategoryHandlers('administrasi');
                setupCategoryHandlers('jalan');


                // Helper function to update grandparent checkbox based on sub-parents
                const updateGrandparentCheckbox = (grandparentId, subparentIds) => {
                    const grandparent = layerFilter.querySelector(`#${grandparentId}`);
                    const anyChecked = subparentIds.some(id => layerFilter.querySelector(`#${id}`)
                        ?.checked);
                    if (grandparent) grandparent.checked = anyChecked;
                };

                // Infrastruktur parent checkbox - controls Gardu and Trafo
                const infrastrukturParentCheckbox = layerFilter.querySelector('#lf-infrastruktur-parent');
                if (infrastrukturParentCheckbox) {
                    infrastrukturParentCheckbox.addEventListener('change', () => {
                        const isChecked = infrastrukturParentCheckbox.checked;
                        setCategoryCheckboxes('gardu', isChecked);
                        setCategoryCheckboxes('trafo', isChecked);
                    });
                }

                // Gardu parent checkbox - update Infrastruktur parent when changed
                const garduParentCheckbox = layerFilter.querySelector('#lf-gardu-parent');
                if (garduParentCheckbox) {
                    garduParentCheckbox.addEventListener('change', () => {
                        updateGrandparentCheckbox('lf-infrastruktur-parent', ['lf-gardu-parent',
                            'lf-trafo-parent'
                        ]);
                    });
                }

                // Trafo parent checkbox - update Infrastruktur parent when changed
                const trafoParentCheckbox = layerFilter.querySelector('#lf-trafo-parent');
                if (trafoParentCheckbox) {
                    trafoParentCheckbox.addEventListener('change', () => {
                        updateGrandparentCheckbox('lf-infrastruktur-parent', ['lf-gardu-parent',
                            'lf-trafo-parent'
                        ]);
                    });
                }

                // Saluran parent checkbox - controls Distribusi and Transmisi
                const saluranParentCheckbox = layerFilter.querySelector('#lf-saluran-parent');
                if (saluranParentCheckbox) {
                    saluranParentCheckbox.addEventListener('change', () => {
                        const isChecked = saluranParentCheckbox.checked;
                        setCategoryCheckboxes('distribusi', isChecked);
                        setCategoryCheckboxes('transmisi', isChecked);
                    });
                }

                // Distribusi parent checkbox - update Saluran parent when changed
                const distribusiParentCheckbox = layerFilter.querySelector('#lf-distribusi-parent');
                if (distribusiParentCheckbox) {
                    distribusiParentCheckbox.addEventListener('change', () => {
                        updateGrandparentCheckbox('lf-saluran-parent', ['lf-distribusi-parent',
                            'lf-transmisi-parent'
                        ]);
                    });
                }

                // Transmisi parent checkbox - update Saluran parent when changed
                const transmisiParentCheckbox = layerFilter.querySelector('#lf-transmisi-parent');
                if (transmisiParentCheckbox) {
                    transmisiParentCheckbox.addEventListener('change', () => {
                        updateGrandparentCheckbox('lf-saluran-parent', ['lf-distribusi-parent',
                            'lf-transmisi-parent'
                        ]);
                    });
                }

                // ================== EXPAND/COLLAPSE FUNCTIONALITY ==================
                // Add toggle functionality for all parent categories
                const parentLabels = layerFilter.querySelectorAll('.lf-parent');
                parentLabels.forEach(parentLabel => {
                    const toggle = parentLabel.querySelector('.lf-toggle');
                    const category = parentLabel.getAttribute('data-category');
                    const childrenContainer = layerFilter.querySelector(
                        `.lf-children[data-category="${category}"]`);

                    if (!toggle || !childrenContainer) return;

                    // Click on toggle or parent label (but not checkbox) to collapse/expand
                    const handleToggle = (e) => {
                        // Don't toggle if clicking on checkbox
                        if (e.target.type === 'checkbox') return;

                        e.preventDefault();
                        e.stopPropagation();

                        const isCollapsed = childrenContainer.classList.contains('collapsed');

                        if (isCollapsed) {
                            childrenContainer.classList.remove('collapsed');
                            toggle.textContent = '▼';
                        } else {
                            childrenContainer.classList.add('collapsed');
                            toggle.textContent = '▶';
                        }
                    };

                    // Add click handler to the parent label
                    parentLabel.addEventListener('click', handleToggle);
                });

                // Add toggle functionality for all sub-parent categories (Gardu, Trafo, Distribusi, Transmisi)
                const subparentLabels = layerFilter.querySelectorAll('.lf-subparent');
                subparentLabels.forEach(subparentLabel => {
                    const toggle = subparentLabel.querySelector('.lf-subtoggle');
                    const subcategory = subparentLabel.getAttribute('data-subcategory');
                    const subchildrenContainer = layerFilter.querySelector(
                        `.lf-subchildren[data-subcategory="${subcategory}"]`);

                    if (!toggle || !subchildrenContainer) return;

                    // Click on toggle or sub-parent label (but not checkbox) to collapse/expand
                    const handleSubToggle = (e) => {
                        // Don't toggle if clicking on checkbox
                        if (e.target.type === 'checkbox') return;

                        e.preventDefault();
                        e.stopPropagation();

                        const isCollapsed = subchildrenContainer.classList.contains('collapsed');

                        if (isCollapsed) {
                            subchildrenContainer.classList.remove('collapsed');
                            toggle.textContent = '▼';
                        } else {
                            subchildrenContainer.classList.add('collapsed');
                            toggle.textContent = '▶';
                        }
                    };

                    // Add click handler to the sub-parent label
                    subparentLabel.addEventListener('click', handleSubToggle);
                });

                // ================== WILAYAH FILTER FUNCTIONALITY ==================
                const regencyDropdown = layerFilter.querySelector('#lf-filter-regency');
                const districtDropdown = layerFilter.querySelector('#lf-filter-district');
                const villageDropdown = layerFilter.querySelector('#lf-filter-village');
                const resetFilterBtn = layerFilter.querySelector('#lf-reset-filter');

                // Store current filter values
                let currentRegencyId = '';
                let currentDistrictId = '';
                let currentVillageId = '';

                // Load Kabupaten/Kota data
                const loadRegencies = async () => {
                    try {
                        const response = await fetch("{{ url('/api/wilayah/regencies') }}");
                        const data = await response.json();

                        regencyDropdown.innerHTML = '<option value="">Semua Kabupaten/Kota</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            regencyDropdown.appendChild(option);
                        });
                    } catch (error) {
                        console.error('Error loading regencies:', error);
                    }
                };

                // Load Kecamatan data based on Kabupaten/Kota
                const loadDistricts = async (regencyId = '') => {
                    try {
                        const url = regencyId ?
                            "{{ url('/api/wilayah/districts') }}?regency_id=" + regencyId :
                            "{{ url('/api/wilayah/districts') }}";
                        const response = await fetch(url);
                        const data = await response.json();

                        districtDropdown.innerHTML = '<option value="">Semua Kecamatan</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            districtDropdown.appendChild(option);
                        });
                        districtDropdown.disabled = !regencyId;
                    } catch (error) {
                        console.error('Error loading districts:', error);
                    }
                };

                // Load Kelurahan/Desa data based on Kecamatan
                const loadVillages = async (districtId = '') => {
                    try {
                        const url = districtId ?
                            "{{ url('/api/wilayah/villages') }}?district_id=" + districtId :
                            "{{ url('/api/wilayah/villages') }}";
                        const response = await fetch(url);
                        const data = await response.json();

                        villageDropdown.innerHTML = '<option value="">Semua Kelurahan/Desa</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            villageDropdown.appendChild(option);
                        });
                        villageDropdown.disabled = !districtId;
                    } catch (error) {
                        console.error('Error loading villages:', error);
                    }
                };

                // Apply filter to layers and zoom to extent
                const applyWilayahFilter = async () => {
                    let targetLayer = null;
                    let whereClause = '';

                    console.log('Applying filter:', {
                        currentRegencyId,
                        currentDistrictId,
                        currentVillageId
                    });

                    // Filter for LN Batas Desa layer
                    if (currentVillageId) {
                        lnBatasDesaLayer.definitionExpression = `WADMKD = '${currentVillageId}'`;
                        targetLayer = lnBatasDesaLayer;
                        whereClause = `WADMKD = '${currentVillageId}'`;
                    } else if (currentDistrictId) {
                        lnBatasDesaLayer.definitionExpression = `WADMKC = '${currentDistrictId}'`;
                    } else if (currentRegencyId) {
                        lnBatasDesaLayer.definitionExpression = `WADMKK = '${currentRegencyId}'`;
                    } else {
                        lnBatasDesaLayer.definitionExpression = null;
                    }

                    // Filter for LN Batas Kecamatan layer
                    if (currentDistrictId) {
                        lnBatasKecamatanLayer.definitionExpression = `WADMKC = '${currentDistrictId}'`;
                        if (!targetLayer) {
                            targetLayer = lnBatasKecamatanLayer;
                            whereClause = `WADMKC = '${currentDistrictId}'`;
                        }
                    } else if (currentRegencyId) {
                        lnBatasKecamatanLayer.definitionExpression = `WADMKK = '${currentRegencyId}'`;
                    } else {
                        lnBatasKecamatanLayer.definitionExpression = null;
                    }

                    // Filter for LN Batas Kab/Kota layer
                    if (currentRegencyId) {
                        lnBatasKabKotaLayer.definitionExpression = `WADMKK = '${currentRegencyId}'`;
                        if (!targetLayer) {
                            targetLayer = lnBatasKabKotaLayer;
                            whereClause = `WADMKK = '${currentRegencyId}'`;
                        }
                    } else {
                        lnBatasKabKotaLayer.definitionExpression = null;
                    }

                    // Filter for Data Berlistrik layer
                    if (currentVillageId) {
                        desaBerlistrikLayer.definitionExpression = `WADMKD = '${currentVillageId}'`;
                    } else if (currentDistrictId) {
                        desaBerlistrikLayer.definitionExpression = `WADMKC = '${currentDistrictId}'`;
                    } else if (currentRegencyId) {
                        desaBerlistrikLayer.definitionExpression = `WADMKK = '${currentRegencyId}'`;
                    } else {
                        desaBerlistrikLayer.definitionExpression = null;
                    }

                    // Zoom to selected area
                    if (targetLayer && whereClause) {
                        console.log('Zooming to:', targetLayer.title, 'with clause:', whereClause);
                        try {
                            // Wait for layer to load if not already loaded
                            await targetLayer.load();

                            const query = targetLayer.createQuery();
                            query.where = whereClause;
                            query.returnGeometry = true;

                            const results = await targetLayer.queryFeatures(query);
                            console.log('Query results:', results.features.length, 'features found');

                            if (results.features.length > 0) {
                                // Calculate extent from all features
                                let extent = null;
                                results.features.forEach(feature => {
                                    if (feature.geometry) {
                                        if (!extent) {
                                            extent = feature.geometry.extent;
                                        } else {
                                            extent = extent.union(feature.geometry.extent);
                                        }
                                    }
                                });

                                if (extent) {
                                    console.log('Zooming to extent:', extent);
                                    // Zoom to extent with some padding
                                    await view.goTo({
                                        target: extent.expand(1.2),
                                        duration: 1000
                                    });
                                }
                            } else {
                                console.warn('No features found for query');
                            }
                        } catch (error) {
                            console.error('Error querying features for zoom:', error);
                        }
                    }
                };

                // Handle Kabupaten/Kota change
                regencyDropdown.addEventListener('change', async (e) => {
                    currentRegencyId = e.target.value;
                    currentDistrictId = '';
                    currentVillageId = '';

                    districtDropdown.value = '';
                    villageDropdown.value = '';

                    await loadDistricts(currentRegencyId);
                    villageDropdown.innerHTML = '<option value="">Semua Kelurahan/Desa</option>';
                    villageDropdown.disabled = true;

                    await applyWilayahFilter();
                });

                // Handle Kecamatan change
                districtDropdown.addEventListener('change', async (e) => {
                    currentDistrictId = e.target.value;
                    currentVillageId = '';

                    villageDropdown.value = '';

                    await loadVillages(currentDistrictId);

                    await applyWilayahFilter();
                });

                // Handle Kelurahan/Desa change
                villageDropdown.addEventListener('change', async (e) => {
                    currentVillageId = e.target.value;
                    await applyWilayahFilter();
                });

                // Handle reset filter
                resetFilterBtn.addEventListener('click', async () => {
                    currentRegencyId = '';
                    currentDistrictId = '';
                    currentVillageId = '';

                    regencyDropdown.value = '';
                    districtDropdown.value = '';
                    villageDropdown.value = '';

                    districtDropdown.innerHTML = '<option value="">Semua Kecamatan</option>';
                    districtDropdown.disabled = true;

                    villageDropdown.innerHTML = '<option value="">Semua Kelurahan/Desa</option>';
                    villageDropdown.disabled = true;

                    await applyWilayahFilter();
                });

                // Initialize dropdowns
                loadRegencies();
                districtDropdown.disabled = true;
                villageDropdown.disabled = true;

                // ================== TOGGLE PANEL FUNCTIONALITY ==================
                // Get toggle button
                const toggleButton = layerFilter.querySelector('.lf-toggle-btn');
                const filterBody = layerFilter.querySelector('.lf-body');

                // Toggle panel handler
                toggleButton.addEventListener('click', () => {
                    layerFilter.classList.toggle('lf-collapsed');
                    toggleButton.textContent = layerFilter.classList.contains('lf-collapsed') ? '▶' :
                        '▼';
                });

                // Add layer filter to view with proper positioning
                view.when(() => {
                    view.ui.add(layerFilter, {
                        position: 'top-left',
                        index: 0
                    });
                    console.log('Layer filter added to view');

                    // Add other widgets AFTER layer filter
                    // Home widget
                    const home = new Home({
                        view
                    });
                    view.ui.add(home, {
                        position: "top-left",
                        index: 1
                    });

                    // Compass widget
                    const compass = new Compass({
                        view
                    });
                    view.ui.add(compass, {
                        position: "top-left",
                        index: 2
                    });

                    // Print widget
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
                        index: 3
                    });

                    // Track widget
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
                        index: 4
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
                            accuracyWidget.innerHTML =
                                `📍 Lokasi aktif<br><span>Akurasi ± ${akurasiMeter.toLocaleString('id-ID')} m</span>`;
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

                    // Add accuracy widget
                    view.ui.add(accuracyWidget, {
                        position: "bottom-right",
                        index: 1
                    });
                }).catch(error => {
                    console.error('Error adding layer filter:', error);
                });

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
                homeLabelDiv.className = 'esri-component esri-widget';
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
                        layerInfos: [{
                                layer: desaBerlistrikLayer,
                                title: "Status Listrik Desa"
                            },
                            {
                                layer: jalanNasionalLayer,
                                title: "Jalan Nasional"
                            },
                            {
                                layer: jalanProvinsiLayer,
                                title: "Jalan Provinsi"
                            },
                            {
                                layer: jalanBalikpapanLayer,
                                title: "Jalan Balikpapan"
                            },
                            {
                                layer: jaringanListrikBalikpapanLayer,
                                title: "Jaringan Listrik Balikpapan"
                            },
                            {
                                layer: jaringanListrikBontangLayer,
                                title: "Rencana Jaringan Listrik Bontang"
                            },
                            {
                                layer: sistemJaringanEnergiKukarLayer,
                                title: "Sistem Jaringan Energi Kukar (SUTT)"
                            },
                            {
                                layer: sistemJaringanEnergiMahuluLayer,
                                title: "Sistem Jaringan Energi Mahulu (SUTR)"
                            },
                            {
                                layer: sistemJaringanEnergiKubarLayer,
                                title: "Sistem Jaringan Energi Kubar (SUTM)"
                            },
                            {
                                layer: sistemJaringanEnergiKubarUP2KBlayer,
                                title: "Sistem Jaringan Energi Kubar UP2KB (SUTM)"
                            },
                            {
                                layer: sistemJaringanEnergiKutimLayer,
                                title: "Sistem Jaringan Energi Kutim (SUTM)"
                            },
                            {
                                layer: sistemJaringanEnergiPaserLayer,
                                title: "Sistem Jaringan Energi Paser (SUTM)"
                            },
                            {
                                layer: sutrKutimLayer,
                                title: "LN SUTR Kutim"
                            },
                            {
                                layer: sutmPPULayer,
                                title: "LN SUTM PPU"
                            },
                            {
                                layer: lnTransmisiLayer,
                                title: "LN Transmisi"
                            },
                            {
                                layer: ln2SutmPaserLayer,
                                title: "LN2 SUTM Paser"
                            },
                            {
                                layer: ln2SutmPPULayer,
                                title: "LN2 SUTM PPU"
                            },
                            {
                                layer: lnBatasNegaraLayer,
                                title: "LN Batas Negara"
                            },
                            {
                                layer: lnBatasProvinsiLayer,
                                title: "LN Batas Provinsi"
                            },
                            {
                                layer: lnBatasKabKotaLayer,
                                title: "LN Batas Kabupaten/Kota"
                            },
                            {
                                layer: lnBatasKecamatanLayer,
                                title: "LN Batas Kecamatan"
                            },
                            {
                                layer: lnBatasDesaLayer,
                                title: "LN Batas Desa"
                            },
                            {
                                layer: ptGarduBerauLayer,
                                title: "PT Gardu Berau"
                            },
                            {
                                layer: ptGarduDistribusiKutimLayer,
                                title: "PT Gardu Distribusi Kutim"
                            },
                            {
                                layer: ptGarduHubungKutimLayer,
                                title: "PT Gardu Hubung Kutim"
                            },
                            {
                                layer: ptGarduIndukKutimLayer,
                                title: "PT Gardu Induk Kutim"
                            },
                            {
                                layer: ptPembangkitEksistingLayer,
                                title: "PT Pembangkit Eksisting"
                            },
                            {
                                layer: ptRencanaPembangkitBontangLayer,
                                title: "PT Rencana Pembangkit Bontang"
                            },
                            {
                                layer: ptSistemEnergiBalikpapanLayer,
                                title: "PT Sistem Energi Balikpapan"
                            },
                            {
                                layer: ptSistemEnergiKukarLayer,
                                title: "PT Sistem Infrastruktur Energi Kutai Kartanegara"
                            },
                            {
                                layer: ptSistemEnergiMahuluLayer,
                                title: "PT Sistem Infrastruktur Energi Mahakam Ulu"
                            },
                            {
                                layer: ptSistemEnergiSamarindaLayer,
                                title: "PT Sistem Infrastruktur Energi Kota Samarinda"
                            },
                            {
                                layer: ptTrafoBerauLayer,
                                title: "PT Trafo Berau"
                            },
                            {
                                layer: ptTrafoGarduDistribusiPpuLayer,
                                title: "PT Trafo Gardu Distribusi PPU"
                            },
                            {
                                layer: ptTrafoGarduKubarLayer,
                                title: "PT Trafo Gardu Kubar (Arrester)"
                            },
                            {
                                layer: pt1TrafoGarduPaserLayer,
                                title: "PT1 Trafo Gardu Paser"
                            },
                            {
                                layer: pt2TrafoGarduPaserLayer,
                                title: "PT2 Trafo Gardu Paser"
                            },
                            {
                                layer: ptHasilLokasiSurveiEsdmLayer,
                                title: "PT Hasil Lokasi Survei ESDM"
                            },
                            {
                                layer: arBatasKaltimLayer,
                                title: "AR Batas Kaltim Full KK KC KD"
                            },
                            {
                                layer: arBatasKecamatanLayer,
                                title: "AR Batas Kaltim KK Kecamatan"
                            },
                            {
                                layer: sutmBerauLayer,
                                title: "LN SUTM Berau"
                            }
                        ]
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

                // ================== DISTANCE MEASUREMENT & COST CALCULATION ==================
                let distanceMeasurement = new DistanceMeasurement2D({
                    view: view,
                    unit: "kilometers"
                });

                // Create cost calculation panel
                const costPanel = document.createElement('div');
                costPanel.id = 'costPanel';
                costPanel.className = 'cost-panel hidden';
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
                measureBtn.innerHTML = '📏 Ukur Jarak';
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
            width: 340px;
            max-width: 88vw;
            max-height: 65vh;
            overflow: hidden;
            background: #fff;
            color: #111827;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .15);
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial;
            z-index: 999 !important;
            position: relative;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
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
            max-height: 800px;
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
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .lf-children.collapsed {
            max-height: 0;
            opacity: 0;
            margin: 0;
        }

        /* Wilayah Filter Styles - Light Theme */
        .lf-wilayah-filter {
            background: #eff6ff;
            border: 1.5px solid #93c5fd;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 8px;
        }

        .lf-wilayah-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1e40af;
        }

        .lf-wilayah-dropdowns {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .lf-dropdown {
            width: 100%;
            padding: 10px 12px;
            font-size: 13px;
            background: #fff;
            color: #111827;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
        }

        .lf-dropdown:hover {
            border-color: #cbd5e1;
        }

        .lf-dropdown:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
        }

        .lf-dropdown:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .lf-dropdown option {
            background: #fff;
            color: #111827;
            padding: 8px;
        }

        .lf-reset-btn {
            width: 100%;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 600;
            background: #f8fafc;
            color: #64748b;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 4px;
        }

        .lf-reset-btn:hover {
            background: #f1f5f9;
            color: #475569;
            transform: translateY(-1px);
        }

        .lf-reset-btn:active {
            transform: translateY(0);
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
