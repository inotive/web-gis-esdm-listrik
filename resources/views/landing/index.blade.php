@extends('landing.layout.app')

@section('title', 'ESDM')

@section('content')
  <div id="viewDiv">
    {{-- PANEL DETAIL TRAFO --}}
    <aside id="detailPanel" class="detail-panel" aria-live="polite">
      <div class="dp-head">
        <div class="dp-title">Detail Trafo</div>
        <button id="dpClose" class="dp-close" title="Tutup">✕</button>
      </div>

      <div class="dp-grid">
        <div class="dp-item full">
          <div class="dp-label">Nama Trafo</div>
          <div class="dp-value" id="dpNamaTrafo">-</div>
        </div>

        <div class="dp-item">
          <div class="dp-label">Asset Num</div>
          <div class="dp-value" id="dpAssetNum">-</div>
        </div>

        <div class="dp-item">
          <div class="dp-label">Kapasitas (kVA)</div>
          <div class="dp-value" id="dpKapasitas">-</div>
        </div>

        <div class="dp-item">
          <div class="dp-label">Status</div>
          <div class="dp-value" id="dpStatus">-</div>
        </div>

        <div class="dp-item">
          <div class="dp-label">Tegangan</div>
          <div class="dp-value" id="dpTegangan">-</div>
        </div>

        <div class="dp-item">
          <div class="dp-label">Fasa</div>
          <div class="dp-value" id="dpFasa">-</div>
        </div>

        <div class="dp-item">
          <div class="dp-label">Penyulang</div>
          <div class="dp-value" id="dpPenyulang">-</div>
        </div>

        <div class="dp-item full">
          <div class="dp-label">Peruntukan</div>
          <div class="dp-value" id="dpPeruntukan">-</div>
        </div>

        <div class="dp-item full">
          <div class="dp-label">Lokasi (Kode)</div>
          <div class="dp-value" id="dpLocation">-</div>
        </div>

        <div class="dp-item full">
          <div class="dp-label">Alamat</div>
          <div class="dp-value" id="dpAlamat">-</div>
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
      </div>
    </aside>
  </div>
@endsection

@push('scripts')
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

    // ================ MAP & VIEW =================
    const map = new Map({
      basemap: Basemap.fromId("satellite")
    });

    const view = new MapView({
      container: "viewDiv",
      map: map,
      center: [117.15, -0.5],
      zoom: 10,
      popup: { autoOpenEnabled: false }
    });

    // ================ LAYERS =====================

    // 1) Aset Tanah (opsional, kalau masih mau ditampilkan)
    const asetLayer = new GeoJSONLayer({
      url: "{{ url('/api/aset') }}",
      title: "Aset Tanah Pemerintah",
      outFields: ["*"],
      renderer: {
        type: "simple",
        symbol: {
          type: "simple-fill",
          color: [37, 99, 235, 0.45],
          outline: { color: [29, 78, 216, 1], width: 1.5 }
        }
      }
    });
    map.add(asetLayer);

    // 2) Desa Berlistrik PLN
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

    // 3) PT_Trafo_Berau
    const trafoLayer = new GeoJSONLayer({
      url: "{{ url('/api/pt-trafo') }}",
      title: "Trafo Berau",
      outFields: ["*"],
      renderer: {
        type: "simple",
        symbol: {
          type: "simple-marker",
          size: 10,
          color: [249, 115, 22, 0.95], // oranye
          outline: { color: [255, 255, 255, 1], width: 1.5 }
        }
      }
      // popupTemplate tidak dipakai karena kita pakai panel sendiri
    });
    map.add(trafoLayer);

    // ================ WIDGETS ====================

    const bm_osm     = Basemap.fromId("osm");
    const bm_sat     = Basemap.fromId("satellite");
    const bm_hybrid  = Basemap.fromId("hybrid");
    const bm_terrain = Basemap.fromId("terrain");
    const bm_topo    = Basemap.fromId("topo-vector");
    const bm_gray    = Basemap.fromId("gray-vector");
    const bm_dark    = Basemap.fromId("dark-gray-vector");
    const bm_street  = Basemap.fromId("streets-vector");

    const localSource = new LocalBasemapsSource({
      basemaps: [bm_osm, bm_sat, bm_hybrid, bm_terrain, bm_topo, bm_gray, bm_dark, bm_street]
    });

    view.ui.add(new Home({ view }), "top-left");
    view.ui.add(new Search({ view, allPlaceholder: "Cari lokasi atau aset" }), "top-right");
    view.ui.add(new ScaleBar({ view, unit: "metric" }), "bottom-left");

    const legendExpand = new Expand({
      view,
      content: new Legend({
        view,
        layerInfos: [
          { layer: asetLayer,        title: "Aset Tanah Pemerintah" },
          { layer: desaBerlistrikLayer, title: "Desa Berlistrik PLN" },
          { layer: trafoLayer,       title: "Trafo Berau" }
        ]
      }),
      expanded: false,
      expandIconClass: "esri-icon-layer-list",
      expandTooltip: "Legenda"
    });
    view.ui.add(legendExpand, "bottom-right");

    const basemapGalleryExpand = new Expand({
      view,
      content: new BasemapGallery({ view, source: localSource }),
      expanded: false,
      expandIconClass: "esri-icon-basemap",
      expandTooltip: "Ganti basemap"
    });
    view.ui.add(basemapGalleryExpand, "bottom-right");

    view.ui.add(new BasemapToggle({ view, nextBasemap: bm_osm }), "bottom-right");

    // ================ DETAIL PANEL TRAFO ==========

    const panel = $('detailPanel');

    $('dpClose').addEventListener('click', () => panel.classList.remove('show'));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') panel.classList.remove('show');
    });

    const setText = (id, val) => {
      $(id).textContent = (val && String(val).trim() !== '') ? val : '-';
    };

    function openTrafoPanel(attrs) {
      setText('dpNamaTrafo', attrs.nama_trafo || attrs.descriptio || '-');
      setText('dpAssetNum', attrs.assetnum || '-');
      setText('dpKapasitas', attrs.kapasitas ? fmt(attrs.kapasitas) : '-');
      setText('dpStatus', attrs.status || '-');
      setText('dpTegangan', attrs.tegangan_t || '-');
      setText('dpFasa', attrs.fasa_trafo || '-');
      setText('dpPenyulang', attrs.penyulang || '-');
      setText('dpPeruntukan', attrs.peruntukan || '-');
      setText('dpLocation', attrs.location || '-');

      const alamat = attrs.streetaddr || '';
      const city   = attrs.city || '';
      setText('dpAlamat', (alamat || city) ? [alamat, city].filter(Boolean).join(', ') : '-');

      setText('dpKelurahan', attrs.kelurahan || attrs.village || '-');
      setText('dpKecamatan', attrs.kecamatan || attrs.district || '-');
      setText('dpKabupaten', attrs.kabupaten || attrs.regency || '-');
      setText('dpProvinsi', attrs.provinsi || attrs.province || '-');

      panel.classList.add('show');
    }

    // Highlight & pointer untuk trafoLayer
    let trafoLayerView, trafoHighlight = null;

    view.whenLayerView(trafoLayer).then(function(lv) {
      trafoLayerView = lv;
    });

    // Pointer cursor saat hover trafo
    view.on("pointer-move", function(evt){
      view.hitTest(evt, { include: [trafoLayer] }).then(function(res){
        const hit = res.results.some(r => r.graphic && r.graphic.layer === trafoLayer);
        view.container.style.cursor = hit ? "pointer" : "default";
      });
    });

    // Klik → buka panel detail trafo
    view.on("click", function(event){
      view.hitTest(event, { include: [trafoLayer] }).then(function(response){
        const r = response.results.find(x => x.graphic && x.graphic.layer === trafoLayer);

        if (!r || !r.graphic) {
          panel.classList.remove('show');
          if (trafoHighlight) {
            trafoHighlight.remove();
            trafoHighlight = null;
          }
          return;
        }

        if (trafoLayerView) {
          if (trafoHighlight) {
            trafoHighlight.remove();
          }
          trafoHighlight = trafoLayerView.highlight(r.graphic);
        }

        openTrafoPanel(r.graphic.attributes || {});
      });
    });

  });
})();
</script>
@endpush
