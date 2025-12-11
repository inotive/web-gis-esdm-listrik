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

    // 🔹 Rencana Jaringan Listrik Bontang
    const jaringanListrikBontangLayer = new GeoJSONLayer({
      url: "{{ url('/api/jaringan-listrik-bontang') }}",
      title: "Rencana Jaringan Listrik Bontang",
      outFields: ["*"],
      renderer: {
        type: "simple",
        symbol: {
          type: "simple-line",
          color: [255, 0, 255, 1], // magenta biar beda jelas
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
          { layer: jaringanListrikBontangLayer,  title: "Rencana Jaringan Listrik Bontang" }
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
