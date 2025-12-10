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
    "esri/widgets/BasemapGallery/support/LocalBasemapsSource"
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
    LocalBasemapsSource
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

    // ================== LAYERS ==================

    // Aset Tanah
    const asetLayer = new GeoJSONLayer({
      url: "{{ url('/api/aset') }}",
      title: "Aset Tanah Pemerintah",
      outFields: ["*"],
      renderer: {
        type: "simple",
        symbol: {
          type: "simple-fill",
          color: [37, 99, 235, 0.45],
          outline: { color: [29, 78, 216, 1], width: 2 }
        }
      }
    });
    map.add(asetLayer);

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
          { layer: asetLayer,                    title: "Aset Tanah Pemerintah" },
          { layer: desaBerlistrikLayer,          title: "Desa Berlistrik PLN" },
          { layer: jalanNasionalLayer,           title: "Jalan Nasional" },
          { layer: jalanProvinsiLayer,           title: "Jalan Provinsi" },
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

    // ================== PANEL DETAIL ASET ==================
    const panel = $('detailPanel');
    $('dpClose').addEventListener('click', () => panel.classList.remove('show'));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') panel.classList.remove('show');
    });

    const setText = (id, val) => {
      $(id).textContent = (val && String(val).trim() !== '') ? val : '-';
    };

    function openPanel(attrs){
      setText('dpUnitKerja',  attrs.unit_kerja || '-');
      setText('dpNama',       attrs.nama_asset || '-');
      setText('dpLuas',       fmt(attrs.luas_m2 || 0));
      setText('dpKelurahan',  attrs.kelurahan || attrs.village || '-');
      setText('dpKecamatan',  attrs.kecamatan || attrs.district || '-');
      setText('dpKabupaten',  attrs.kabupaten || attrs.regency || '-');
      setText('dpProvinsi',   attrs.provinsi  || attrs.province || '-');
      setText('dpAlamat',     attrs.alamat || '-');

      const link = attrs.sertifikat_url || attrs.link_sertif || attrs.file_url || null;
      $('dpSertifikat').innerHTML = link
        ? `<a class="dp-link" target="_blank" href="${link}">Lihat ⦿</a>`
        : '-';

      panel.classList.add('show');
    }

    let layerView, highlightHandle = null;
    view.whenLayerView(asetLayer).then(function(lv) {
      layerView = lv;
    });

    view.on("pointer-move", function(evt){
      view.hitTest(evt, { include: [asetLayer] }).then(function(res){
        const hit = res.results.some(function(r){
          return r.graphic && r.graphic.layer === asetLayer;
        });
        view.container.style.cursor = hit ? "pointer" : "default";
      });
    });

    view.on("click", function(event){
      view.hitTest(event, { include: [asetLayer] }).then(function(response){
        const r = response.results.find(function(x){
          return x.graphic && x.graphic.layer === asetLayer;
        });

        if (!r || !r.graphic) {
          panel.classList.remove('show');
          if (highlightHandle) {
            highlightHandle.remove();
            highlightHandle = null;
          }
          return;
        }

        if (layerView) {
          if (highlightHandle) {
            highlightHandle.remove();
          }
          highlightHandle = layerView.highlight(r.graphic);
        }

        openPanel(r.graphic.attributes || {});
      });
    });

    // Grouping by unit_kerja
    asetLayer.when(async () => {
      try {
        const q = asetLayer.createQuery();
        q.where = "1=1";
        q.outFields = ["unit_kerja"];
        q.returnGeometry = false;

        const res = await asetLayer.queryFeatures(q);

        const values = Array.from(
          new Set(
            res.features.map(f =>
              f.attributes.unit_kerja ? String(f.attributes.unit_kerja).trim() : "-"
            )
          )
        ).sort((a, b) => a.localeCompare(b, 'id'));

        const palette = [
          [59,130,246], [16,185,129], [245,158,11], [236,72,153],
          [99,102,241], [34,197,94],  [249,115,22], [139,92,246],
          [2,132,199],  [234,179,8],  [239,68,68],  [20,184,166],
          [168,85,247], [14,165,233], [217,119,6],  [5,150,105]
        ];

        const uniqueValueInfos = values.map((v, i) => {
          const rgb = palette[i % palette.length];
          return {
            value: v === "-" ? null : v,
            label: v === "-" ? "Tanpa Unit Kerja" : v,
            symbol: {
              type: "simple-fill",
              color: [rgb[0], rgb[1], rgb[2], 0.45],
              outline: { color: [rgb[0], rgb[1], rgb[2], 1], width: 1.5 }
            }
          };
        });

        asetLayer.renderer = {
          type: "unique-value",
          field: "unit_kerja",
          defaultLabel: "Tanpa Unit Kerja",
          defaultSymbol: {
            type: "simple-fill",
            color: [148, 163, 184, 0.35],
            outline: { color: [100, 116, 139, 1], width: 1.2 }
          },
          uniqueValueInfos: uniqueValueInfos
        };
      } catch (err) {
        console.error("Gagal membuat renderer unik unit_kerja:", err);
      }
    });

  });
})();
</script>
@endpush
