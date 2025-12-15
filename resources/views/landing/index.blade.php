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

        <div class="dp-item"><div class="dp-label">Nama</div><div class="dp-value" id="dpNama">-</div></div>
        <div class="dp-item"><div class="dp-label">Luas (m²)</div><div class="dp-value" id="dpLuas">-</div></div>
        <div class="dp-item"><div class="dp-label">Kelurahan</div><div class="dp-value" id="dpKelurahan">-</div></div>
        <div class="dp-item"><div class="dp-label">Kecamatan</div><div class="dp-value" id="dpKecamatan">-</div></div>
        <div class="dp-item"><div class="dp-label">Kabupaten</div><div class="dp-value" id="dpKabupaten">-</div></div>
        <div class="dp-item"><div class="dp-label">Provinsi</div><div class="dp-value" id="dpProvinsi">-</div></div>
        <div class="dp-item full"><div class="dp-label">Alamat</div><div class="dp-value" id="dpAlamat">-</div></div>
        <div class="dp-item full"><div class="dp-label">Sertifikat</div><div class="dp-value" id="dpSertifikat">-</div></div>
      </div>
    </aside>
  </div>
@endsection

@push('scripts')
<script>window.dojoConfig = { async: true };</script>
<script src="https://js.arcgis.com/4.29/"></script>

<script>
(function(){
  const $ = id => document.getElementById(id);
  const fmt = (n) => (n===null||n===undefined||isNaN(n)) ? "-" : Number(n).toLocaleString('id-ID');

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
    "esri/widgets/DistanceMeasurement2D"
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
    DistanceMeasurement2D
  ){

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

    const renderDetailContent = (graphic) => {
      const attrs = graphic?.attributes || {};
      const layerTitle = graphic?.layer?.title || 'Detail Fitur';
      const rows = Object.entries(attrs)
        .map(([k, v]) => `<div class="dm-row"><div class="dm-key">${k}</div><div class="dm-val">${v ?? '-'}</div></div>`)
        .join('');

      dmContent.innerHTML = `
        <div class="dm-head">${layerTitle}</div>
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

    // Desa Berlistrik PLN
    const desaBerlistrikLayer = new GeoJSONLayer({
      url: "{{ url('/api/data-berlistrik') }}",
      title: "Desa Berlistrik PLN",
      outFields: ["*"],
      popupTemplate: {
        title: "{NAMOBJ}",
        content: `
          <b>Status Listrik:</b> {H_Survei}<br>
          <b>Kecamatan:</b> {WADMKC}<br>
          <b>Desa:</b> {WADMKD}<br>
          <b>Kabupaten:</b> {WADMKK}<br>
          <b>Provinsi:</b> {WADMPR}
        `
      },
      renderer: {
        type: "unique-value",
        field: "H_Survei",
        defaultLabel: "Status tidak diketahui",
        defaultSymbol: {
          type: "simple-fill",
          color: [148, 163, 184, 0.35],
          outline: { color: [100, 116, 139, 1], width: 1 }
        },
        uniqueValueInfos: [
          {
            value: "Belum Terlayani Listrik",
            label: "Belum Terlayani Listrik",
            symbol: {
              type: "simple-fill",
              color: [220, 38, 38, 0.45],
              outline: { color: [185, 28, 28, 1], width: 1.5 }
            }
          },
          {
            value: "Terlayani Listrik",
            label: "Sudah Terlayani Listrik",
            symbol: {
              type: "simple-fill",
              color: [34, 197, 94, 0.45],
              outline: { color: [22, 163, 74, 1], width: 1.5 }
            }
          }
        ]
      }
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
          outline: { color: [255, 165, 0, 1], width: 1.5 }
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
          outline: { color: [34, 139, 34, 1], width: 1.2 }
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
          type: "simple-marker",
          color: [255, 165, 0, 0.8], // oranye
          size: 8,
          outline: { color: [0, 0, 0, 0.6], width: 0.5 }
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
          type: "simple-marker",
          color: [0, 191, 255, 0.85], // biru muda
          size: 8,
          outline: { color: [0, 0, 0, 0.6], width: 0.5 }
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
          type: "simple-marker",
          color: [186, 85, 211, 0.85], // ungu muda
          size: 9,
          outline: { color: [0, 0, 0, 0.6], width: 0.6 }
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
          type: "simple-marker",
          color: [255, 99, 132, 0.9], // merah muda cerah
          size: 9,
          outline: { color: [0, 0, 0, 0.6], width: 0.6 }
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
          type: "simple-marker",
          color: [0, 255, 127, 0.85], // spring green
          size: 10,
          outline: { color: [0, 0, 0, 0.7], width: 0.6 }
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
          type: "simple-marker",
          color: [255, 215, 0, 0.9], // emas
          size: 10,
          outline: { color: [0, 0, 0, 0.7], width: 0.6 }
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
          type: "simple-marker",
          color: [30, 144, 255, 0.9], // dodger blue
          size: 10,
          outline: { color: [0, 0, 0, 0.7], width: 0.6 }
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
          type: "simple-marker",
          color: [255, 0, 0, 0.9], // merah
          size: 11,
          outline: { color: [0, 0, 0, 0.8], width: 0.7 }
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
          type: "simple-marker",
          color: [255, 165, 0, 0.9], // oranye
          size: 11,
          outline: { color: [0, 0, 0, 0.8], width: 0.7 }
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
          type: "simple-marker",
          color: [0, 255, 255, 0.9], // cyan
          size: 11,
          outline: { color: [0, 0, 0, 0.8], width: 0.7 }
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
          type: "simple-marker",
          color: [128, 0, 128, 0.9], // ungu
          size: 11,
          outline: { color: [0, 0, 0, 0.8], width: 0.7 }
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
          type: "simple-marker",
          color: [255, 192, 203, 0.9], // pink
          size: 9,
          outline: { color: [0, 0, 0, 0.8], width: 0.6 }
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
          type: "simple-marker",
          color: [255, 140, 0, 0.9], // oranye
          size: 9,
          outline: { color: [0, 0, 0, 0.8], width: 0.6 }
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
          type: "simple-marker",
          color: [0, 128, 0, 0.9], // hijau
          size: 10,
          outline: { color: [0, 0, 0, 0.8], width: 0.6 }
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
          type: "simple-marker",
          color: [255, 0, 0, 0.9], // merah
          size: 10,
          outline: { color: [0, 0, 0, 0.8], width: 0.6 }
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
    lnBatasDesaLayer.visible = false;
    lnBatasKabKotaLayer.visible = false;
    lnBatasKecamatanLayer.visible = false;
    lnBatasNegaraLayer.visible = false;
    lnBatasProvinsiLayer.visible = false;

    // ================== LAYER FILTER PANEL ==================
    // Organize layers into categories
    const layerCategories = {
      transportasi: [
        { label: 'Jalan Nasional', layer: jalanNasionalLayer },
        { label: 'Jalan Provinsi', layer: jalanProvinsiLayer },
        { label: 'Jalan Balikpapan', layer: jalanBalikpapanLayer }
      ],
      jaringan: [
        { label: 'Jaringan Listrik Balikpapan', layer: jaringanListrikBalikpapanLayer },
        { label: 'Rencana Jaringan Listrik Bontang', layer: jaringanListrikBontangLayer },
        { label: 'Sistem Energi Kukar (SUTT)', layer: sistemJaringanEnergiKukarLayer },
        { label: 'Sistem Energi Mahulu (SUTR)', layer: sistemJaringanEnergiMahuluLayer },
        { label: 'Sistem Energi Kubar (SUTM)', layer: sistemJaringanEnergiKubarLayer },
        { label: 'Sistem Energi Kubar UP2KB', layer: sistemJaringanEnergiKubarUP2KBlayer },
        { label: 'Sistem Energi Kutim (SUTM)', layer: sistemJaringanEnergiKutimLayer },
        { label: 'Sistem Energi Paser (SUTM)', layer: sistemJaringanEnergiPaserLayer },
        { label: 'LN SUTM PPU', layer: sutmPPULayer },
        { label: 'LN SUTR Kutim', layer: sutrKutimLayer },
        { label: 'LN Transmisi', layer: lnTransmisiLayer },
        { label: 'LN2 SUTM Paser', layer: ln2SutmPaserLayer },
        { label: 'LN2 SUTM PPU', layer: ln2SutmPPULayer },
        { label: 'LN SUTM Berau', layer: sutmBerauLayer }
      ],
      infrastruktur: [
        { label: 'PT Gardu Berau', layer: ptGarduBerauLayer },
        { label: 'PT Gardu Distribusi Kutim', layer: ptGarduDistribusiKutimLayer },
        { label: 'PT Gardu Hubung Kutim', layer: ptGarduHubungKutimLayer },
        { label: 'PT Gardu Induk Kutim', layer: ptGarduIndukKutimLayer },
        { label: 'PT Trafo Berau', layer: ptTrafoBerauLayer },
        { label: 'PT Trafo Gardu Distribusi PPU', layer: ptTrafoGarduDistribusiPpuLayer },
        { label: 'PT Trafo Gardu Kubar', layer: ptTrafoGarduKubarLayer },
        { label: 'PT1 Trafo Gardu Paser', layer: pt1TrafoGarduPaserLayer },
        { label: 'PT2 Trafo Gardu Paser', layer: pt2TrafoGarduPaserLayer },
        { label: 'PT Sistem Energi Balikpapan', layer: ptSistemEnergiBalikpapanLayer },
        { label: 'PT Sistem Energi Kukar', layer: ptSistemEnergiKukarLayer },
        { label: 'PT Sistem Energi Mahulu', layer: ptSistemEnergiMahuluLayer },
        { label: 'PT Sistem Energi Samarinda', layer: ptSistemEnergiSamarindaLayer }
      ],
      pembangkit: [
        { label: 'PT Pembangkit Eksisting', layer: ptPembangkitEksistingLayer },
        { label: 'PT Rencana Pembangkit Bontang', layer: ptRencanaPembangkitBontangLayer }
      ],
      administrasi: [
        { label: 'LN Batas Desa', layer: lnBatasDesaLayer },
        { label: 'LN Batas Kab/Kota', layer: lnBatasKabKotaLayer },
        { label: 'LN Batas Kecamatan', layer: lnBatasKecamatanLayer },
        { label: 'LN Batas Negara', layer: lnBatasNegaraLayer },
        { label: 'LN Batas Provinsi', layer: lnBatasProvinsiLayer },
        { label: 'AR Batas Kaltim Full', layer: arBatasKaltimLayer },
        { label: 'AR Batas Kec.', layer: arBatasKecamatanLayer }
      ]
    };

    const layerFilter = document.createElement('div');
    layerFilter.className = 'layer-filter';

    // Build category HTML
    let categoriesHTML = '';

    // Status Listrik Desa (special case with custom filter)
    categoriesHTML += `
      <label class="lf-row lf-parent" data-category="desa">
        <span class="lf-toggle">▼</span>
        <input type="checkbox" id="lf-desa-parent">
        <span><strong>Status Listrik Desa</strong></span>
      </label>
      <div class="lf-children" data-category="desa">
        <label class="lf-row lf-child"><input type="checkbox" id="lf-desa-belum"> <span>Belum Terlayani Listrik</span></label>
        <label class="lf-row lf-child"><input type="checkbox" id="lf-desa-terlayani"> <span>Terlayani Listrik</span></label>
      </div>
    `;

    // Transportasi
    categoriesHTML += `<label class="lf-row lf-parent" data-category="transportasi">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-transportasi-parent">
      <span><strong>Data Jalan</strong></span>
    </label>
    <div class="lf-children" data-category="transportasi">`;
    layerCategories.transportasi.forEach((item, idx) => {
      categoriesHTML += `<label class="lf-row lf-child"><input type="checkbox" id="lf-transportasi-${idx}"> <span>${item.label}</span></label>`;
    });
    categoriesHTML += `</div>`;

    // Jaringan Listrik
    categoriesHTML += `<label class="lf-row lf-parent" data-category="jaringan">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-jaringan-parent">
      <span><strong>Jaringan Listrik</strong></span>
    </label>
    <div class="lf-children" data-category="jaringan">`;
    layerCategories.jaringan.forEach((item, idx) => {
      categoriesHTML += `<label class="lf-row lf-child"><input type="checkbox" id="lf-jaringan-${idx}"> <span>${item.label}</span></label>`;
    });
    categoriesHTML += `</div>`;

    // Infrastruktur Listrik
    categoriesHTML += `<label class="lf-row lf-parent" data-category="infrastruktur">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-infrastruktur-parent">
      <span><strong>Infrastruktur Listrik</strong></span>
    </label>
    <div class="lf-children" data-category="infrastruktur">`;
    layerCategories.infrastruktur.forEach((item, idx) => {
      categoriesHTML += `<label class="lf-row lf-child"><input type="checkbox" id="lf-infrastruktur-${idx}"> <span>${item.label}</span></label>`;
    });
    categoriesHTML += `</div>`;

    // Pembangkit
    categoriesHTML += `<label class="lf-row lf-parent" data-category="pembangkit">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-pembangkit-parent">
      <span><strong>Pembangkit</strong></span>
    </label>
    <div class="lf-children" data-category="pembangkit">`;
    layerCategories.pembangkit.forEach((item, idx) => {
      categoriesHTML += `<label class="lf-row lf-child"><input type="checkbox" id="lf-pembangkit-${idx}"> <span>${item.label}</span></label>`;
    });
    categoriesHTML += `</div>`;

    // Administrasi
    categoriesHTML += `<label class="lf-row lf-parent" data-category="administrasi">
      <span class="lf-toggle">▼</span>
      <input type="checkbox" id="lf-administrasi-parent">
      <span><strong>Administrasi</strong></span>
    </label>
    <div class="lf-children" data-category="administrasi">`;
    layerCategories.administrasi.forEach((item, idx) => {
      categoriesHTML += `<label class="lf-row lf-child"><input type="checkbox" id="lf-administrasi-${idx}"> <span>${item.label}</span></label>`;
    });
    categoriesHTML += `</div>`;

    layerFilter.innerHTML = `
      <div class="lf-head">
        <span>Layer Filter</span>
        <button class="lf-close-btn" title="Tutup panel">×</button>
      </div>
      <div class="lf-body">
        <label class="lf-row lf-all"><input type="checkbox" id="lf-all"> <span><strong>Semua Layer</strong></span></label>
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
              outline: { color: [185, 28, 28, 1], width: 1.5 }
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
              outline: { color: [22, 163, 74, 1], width: 1.5 }
            }
          });
        }

        desaBerlistrikLayer.renderer = {
          type: "unique-value",
          field: "H_Survei",
          defaultLabel: "Status tidak diketahui",
          defaultSymbol: {
            type: "simple-fill",
            color: [148, 163, 184, 0.35],
            outline: { color: [100, 116, 139, 1], width: 1 }
          },
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
      setCategoryCheckboxes('transportasi', isChecked);
      setCategoryCheckboxes('jaringan', isChecked);
      setCategoryCheckboxes('infrastruktur', isChecked);
      setCategoryCheckboxes('pembangkit', isChecked);
      setCategoryCheckboxes('administrasi', isChecked);
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
      desaParentCheckbox.checked = desaBelumCheckbox.checked || desaTerlayaniCheckbox.checked;
    });

    desaTerlayaniCheckbox.addEventListener('change', () => {
      updateDesaBerlistrikFilter();
      desaParentCheckbox.checked = desaBelumCheckbox.checked || desaTerlayaniCheckbox.checked;
    });

    // Setup handlers for each category
    const setupCategoryHandlers = (categoryName) => {
      const parentCheckbox = layerFilter.querySelector(`#lf-${categoryName}-parent`);
      if (!parentCheckbox) return;

      // Parent checkbox controls all children
      parentCheckbox.addEventListener('change', () => {
        const isChecked = parentCheckbox.checked;
        layerCategories[categoryName].forEach((item, idx) => {
          const childCheckbox = layerFilter.querySelector(`#lf-${categoryName}-${idx}`);
          if (childCheckbox) childCheckbox.checked = isChecked;
          item.layer.visible = isChecked;
        });
      });

      // Each child checkbox
      layerCategories[categoryName].forEach((item, idx) => {
        const childCheckbox = layerFilter.querySelector(`#lf-${categoryName}-${idx}`);
        if (!childCheckbox) return;

        childCheckbox.addEventListener('change', () => {
          item.layer.visible = childCheckbox.checked;

          // Update parent checkbox state
          const childIds = layerCategories[categoryName].map((_, i) => `lf-${categoryName}-${i}`);
          updateParentCheckbox(`lf-${categoryName}-parent`, childIds);
        });
      });
    };

    // Setup all categories
    setupCategoryHandlers('transportasi');
    setupCategoryHandlers('jaringan');
    setupCategoryHandlers('infrastruktur');
    setupCategoryHandlers('pembangkit');
    setupCategoryHandlers('administrasi');

    // ================== EXPAND/COLLAPSE FUNCTIONALITY ==================
    // Add toggle functionality for all parent categories
    const parentLabels = layerFilter.querySelectorAll('.lf-parent');
    parentLabels.forEach(parentLabel => {
      const toggle = parentLabel.querySelector('.lf-toggle');
      const category = parentLabel.getAttribute('data-category');
      const childrenContainer = layerFilter.querySelector(`.lf-children[data-category="${category}"]`);

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

    // ================== CLOSE/OPEN PANEL FUNCTIONALITY ==================
    // Create open button (shown when panel is closed)
    const openButton = document.createElement('button');
    openButton.className = 'lf-open-btn';
    openButton.innerHTML = '☰<br><span style="font-size: 9px; font-weight: 600;"></span>';
    openButton.title = 'Buka Layer Filter';
    openButton.style.display = 'none'; // Hidden by default

    // Get close button
    const closeButton = layerFilter.querySelector('.lf-close-btn');

    // Close panel handler
    closeButton.addEventListener('click', () => {
      layerFilter.classList.add('lf-minimized');
      openButton.style.display = 'flex';
    });

    // Open panel handler
    openButton.addEventListener('click', () => {
      layerFilter.classList.remove('lf-minimized');
      openButton.style.display = 'none';
    });

    view.ui.add(layerFilter, 'top-left');
    view.ui.add(openButton, 'top-left');

    // ================== WIDGETS ==================
    const bm_osm     = Basemap.fromId("osm");          bm_osm.title     = "Peta (OSM)";
    const bm_sat     = Basemap.fromId("satellite");    bm_sat.title     = "Satelit";
    const bm_hybrid  = Basemap.fromId("hybrid");       bm_hybrid.title  = "Hybrid";
    const bm_terrain = Basemap.fromId("terrain");      bm_terrain.title = "Medan";
    const bm_topo    = Basemap.fromId("topo-vector");  bm_topo.title    = "Topografi";
    const bm_gray    = Basemap.fromId("gray-vector");  bm_gray.title    = "Abu-abu";
    const bm_dark    = Basemap.fromId("dark-gray-vector"); bm_dark.title = "Gelap";
    const bm_street  = Basemap.fromId("streets-vector");   bm_street.title = "Streets";

    const localSource = new LocalBasemapsSource({
      basemaps: [bm_osm, bm_sat, bm_hybrid, bm_terrain, bm_topo, bm_gray, bm_dark, bm_street]
    });

    const homeWidget = new Home({ view: view });
    view.ui.add(homeWidget, "top-left");

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
        layerInfos: [
          { layer: desaBerlistrikLayer,          title: "Status Listrik Desa" },
          { layer: jalanNasionalLayer,           title: "Jalan Nasional" },
          { layer: jalanProvinsiLayer,           title: "Jalan Provinsi" },
          { layer: jalanBalikpapanLayer,         title: "Jalan Balikpapan" },
          { layer: jaringanListrikBalikpapanLayer, title: "Jaringan Listrik Balikpapan" },
          { layer: jaringanListrikBontangLayer,  title: "Rencana Jaringan Listrik Bontang" },
          { layer: sistemJaringanEnergiKukarLayer, title: "Sistem Jaringan Energi Kukar (SUTT)" },
          { layer: sistemJaringanEnergiMahuluLayer, title: "Sistem Jaringan Energi Mahulu (SUTR)" },
          { layer: sistemJaringanEnergiKubarLayer, title: "Sistem Jaringan Energi Kubar (SUTM)" },
          { layer: sistemJaringanEnergiKubarUP2KBlayer, title: "Sistem Jaringan Energi Kubar UP2KB (SUTM)" },
          { layer: sistemJaringanEnergiKutimLayer, title: "Sistem Jaringan Energi Kutim (SUTM)" },
          { layer: sistemJaringanEnergiPaserLayer, title: "Sistem Jaringan Energi Paser (SUTM)" },
          { layer: sutrKutimLayer,                 title: "LN SUTR Kutim" },
          { layer: sutmPPULayer,                 title: "LN SUTM PPU" },
          { layer: lnTransmisiLayer,              title: "LN Transmisi" },
          { layer: ln2SutmPaserLayer,             title: "LN2 SUTM Paser" },
          { layer: ln2SutmPPULayer,               title: "LN2 SUTM PPU" },
          { layer: lnBatasNegaraLayer,            title: "LN Batas Negara" },
          { layer: lnBatasProvinsiLayer,          title: "LN Batas Provinsi" },
          { layer: lnBatasKabKotaLayer,           title: "LN Batas Kabupaten/Kota" },
          { layer: lnBatasKecamatanLayer,         title: "LN Batas Kecamatan" },
          { layer: lnBatasDesaLayer,             title: "LN Batas Desa" },
          { layer: ptGarduBerauLayer,             title: "PT Gardu Berau" },
          { layer: ptGarduDistribusiKutimLayer,   title: "PT Gardu Distribusi Kutim" },
          { layer: ptGarduHubungKutimLayer,       title: "PT Gardu Hubung Kutim" },
          { layer: ptGarduIndukKutimLayer,        title: "PT Gardu Induk Kutim" },
          { layer: ptPembangkitEksistingLayer,    title: "PT Pembangkit Eksisting" },
          { layer: ptRencanaPembangkitBontangLayer, title: "PT Rencana Pembangkit Bontang" },
          { layer: ptSistemEnergiBalikpapanLayer, title: "PT Sistem Energi Balikpapan" },
          { layer: ptSistemEnergiKukarLayer,      title: "PT Sistem Infrastruktur Energi Kutai Kartanegara" },
          { layer: ptSistemEnergiMahuluLayer,     title: "PT Sistem Infrastruktur Energi Mahakam Ulu" },
          { layer: ptSistemEnergiSamarindaLayer,  title: "PT Sistem Infrastruktur Energi Kota Samarinda" },
          { layer: ptTrafoBerauLayer,             title: "PT Trafo Berau" },
          { layer: ptTrafoGarduDistribusiPpuLayer, title: "PT Trafo Gardu Distribusi PPU" },
          { layer: ptTrafoGarduKubarLayer,        title: "PT Trafo Gardu Kubar (Arrester)" },
          { layer: pt1TrafoGarduPaserLayer,       title: "PT1 Trafo Gardu Paser" },
          { layer: pt2TrafoGarduPaserLayer,       title: "PT2 Trafo Gardu Paser" },
          { layer: arBatasKaltimLayer,            title: "AR Batas Kaltim Full KK KC KD" },
          { layer: arBatasKecamatanLayer,         title: "AR Batas Kaltim KK Kecamatan" },
          { layer: sutmBerauLayer,                 title: "LN SUTM Berau" }
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

            const costDistanceEl = costPanel.querySelector('#costDistance');
            const costTotalEl = costPanel.querySelector('#costTotal');

            if (distanceKm > 0) {
              costDistanceEl.textContent = `${distanceKm.toFixed(2)} km`;
              costTotalEl.textContent = `Rp. ${totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
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
          distanceMeasurement.viewModel.watch('measurement', (measurement) => {
            if (measurement) {
              const distanceKm = measurement.length;
              const pricePerKm = 150000;
              const totalCost = distanceKm * pricePerKm;

              const costDistanceEl = costPanel.querySelector('#costDistance');
              const costTotalEl = costPanel.querySelector('#costTotal');

              if (distanceKm > 0) {
                costDistanceEl.textContent = `${distanceKm.toFixed(2)} km`;
                costTotalEl.textContent = `Rp. ${totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
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
          costTotalEl.textContent = `Rp. ${totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
        } else {
          costDistanceEl.textContent = '-';
          costTotalEl.textContent = 'Rp. 0';
        }
      }
    });

  });
})();
</script>

<style>
  /* Detail modal muncul saat klik fitur */
  #viewDiv { position: relative; }
  .detail-modal {
    position: absolute;
    width: min(360px, 86vw);
    max-height: 60vh;
    overflow: hidden;
    background: rgba(15, 23, 42, 0.95);
    color: #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.45);
    border: 1px solid rgba(226, 232, 240, 0.18);
    backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    z-index: 100;
    pointer-events: auto;
  }
  .detail-modal.hidden { display: none; }

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
    background: linear-gradient(90deg, rgba(34,197,94,0.18), rgba(15,23,42,0.05));
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
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 8px;
  }
  .dm-key { color: #94a3b8; font-weight: 600; word-break: break-word; }
  .dm-val { color: #e2e8f0; word-break: break-word; }
  .dm-empty { color: #94a3b8; font-size: 12px; padding: 8px; }

  /* Layer filter panel */
  .layer-filter {
    width: 240px;
    max-height: 440px;
    overflow: hidden;
    background: rgba(15,23,42,0.92);
    color: #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.28);
    border: 1px solid rgba(226,232,240,0.18);
    backdrop-filter: blur(8px);
  }
  .lf-head {
    padding: 10px 12px;
    font-weight: 700;
    font-size: 14px;
    border-bottom: 1px solid rgba(226,232,240,0.16);
    background: linear-gradient(90deg, rgba(37,99,235,0.24), rgba(15,23,42,0.12));
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .lf-close-btn {
    background: rgba(239,68,68,0.2);
    color: #fca5a5;
    border: 1px solid rgba(239,68,68,0.3);
    border-radius: 6px;
    width: 24px;
    height: 24px;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
  }
  .lf-close-btn:hover {
    background: rgba(239,68,68,0.35);
    color: #fee2e2;
    transform: scale(1.1);
  }
  .lf-open-btn {
    background: rgba(37,99,235,0.92);
    color: white;
    border: 1px solid rgba(59,130,246,0.4);
    border-radius: 10px;
    width: 50px;
    height: 60px;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    box-shadow: 0 4px 12px rgba(37,99,235,0.35);
    backdrop-filter: blur(8px);
    margin-top: -140px;
  }
  .lf-open-btn:hover {
    background: rgba(59,130,246,0.95);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37,99,235,0.45);
  }
  .layer-filter.lf-minimized {
    transform: translateX(-280px);
    opacity: 0;
    pointer-events: none;
  }
  .layer-filter {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .lf-body {
    max-height: 380px;
    overflow-y: auto;
    padding: 8px 10px 10px;
    display: grid;
    gap: 6px;
  }
  .lf-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    padding: 6px 8px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(148,163,184,0.18);
    border-radius: 8px;
    cursor: pointer;
  }
  .lf-row input { accent-color: #22c55e; }
  .lf-row span { line-height: 1.35; }
  .lf-row:hover { background: rgba(34,197,94,0.08); }
  .lf-all {
    background: rgba(37,99,235,0.14) !important;
    border-color: rgba(59,130,246,0.3) !important;
  }
  .lf-all:hover { background: rgba(37,99,235,0.22) !important; }
  .lf-parent {
    background: rgba(34,197,94,0.12) !important;
    border-color: rgba(34,197,94,0.3) !important;
  }
  .lf-parent:hover { background: rgba(34,197,94,0.18) !important; }
  .lf-child {
    margin-left: 20px;
    background: rgba(255,255,255,0.02) !important;
    border-left: 3px solid rgba(34,197,94,0.4);
    font-size: 11.5px;
  }
  .lf-divider {
    height: 1px;
    background: rgba(148,163,184,0.24);
    margin: 4px 0;
  }
  .lf-toggle {
    font-size: 10px;
    margin-right: 4px;
    transition: transform 0.2s ease;
    user-select: none;
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
    top: 70px;
    right: 16px;
    width: 280px;
    background: rgba(15, 23, 42, 0.94);
    color: #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.32);
    border: 1px solid rgba(226, 232, 240, 0.18);
    backdrop-filter: blur(10px);
    z-index: 10;
  }
  .cost-panel.hidden { display: none; }

  .cost-head {
    padding: 12px 14px;
    border-bottom: 1px solid rgba(226, 232, 240, 0.16);
    background: linear-gradient(90deg, rgba(37,99,235,0.24), rgba(15,23,42,0.12));
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
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 8px;
  }
  .cost-row.total {
    background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(16,185,129,0.1));
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

  @media (max-width: 640px) {
    .detail-modal { width: min(340px, 94vw); }
    .dm-row { grid-template-columns: 1fr; }
    .layer-filter { width: 260px; }
    .cost-panel { width: min(280px, 90vw); right: 10px; }
  }
</style>
@endpush
