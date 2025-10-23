@extends('landing.layout.app')

@section('title', 'ASET PEMPROV KALTIM')

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
<script>
(function(){
  const $ = id => document.getElementById(id);
  const fmt = (n) => (n===null||n===undefined||isNaN(n)) ? "-" : Number(n).toLocaleString('id-ID');

  require([
    "esri/Map","esri/Basemap","esri/views/MapView","esri/layers/GeoJSONLayer",
    "esri/widgets/Legend","esri/widgets/Expand","esri/widgets/Home","esri/widgets/Search",
    "esri/widgets/ScaleBar","esri/widgets/BasemapGallery","esri/widgets/BasemapToggle",
    "esri/widgets/BasemapGallery/support/LocalBasemapsSource"
  ], function(Map,Basemap,MapView,GeoJSONLayer,Legend,Expand,Home,Search,ScaleBar,BasemapGallery,BasemapToggle,LocalBasemapsSource){

    // ================== MAP & VIEW ==================
    const map = new Map({ basemap: Basemap.fromId("satellite") });

    const view = new MapView({
      container: "viewDiv",
      map, center: [117.15, -0.5], zoom: 10,
      popup: { autoOpenEnabled: false }
    });

    // ================== LAYER ==================
    // Mulai dengan simbol default; nanti diganti UniqueValueRenderer berdasarkan unit_kerja
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

    // ================== WIDGETS ==================
    const bm_osm=Basemap.fromId("osm"); bm_osm.title="Peta (OSM)";
    const bm_sat=Basemap.fromId("satellite"); bm_sat.title="Satelit";
    const bm_hybrid=Basemap.fromId("hybrid"); bm_hybrid.title="Hybrid";
    const bm_terrain=Basemap.fromId("terrain"); bm_terrain.title="Medan";
    const bm_topo=Basemap.fromId("topo-vector"); bm_topo.title="Topografi";
    const bm_gray=Basemap.fromId("gray-vector"); bm_gray.title="Abu-abu";
    const bm_dark=Basemap.fromId("dark-gray-vector"); bm_dark.title="Gelap";
    const bm_street=Basemap.fromId("streets-vector"); bm_street.title="Streets";
    const localSource = new LocalBasemapsSource({ basemaps:[bm_osm,bm_sat,bm_hybrid,bm_terrain,bm_topo,bm_gray,bm_dark,bm_street] });

    view.ui.add(new Home({view}), "top-left");
    view.ui.add(new Search({view, allPlaceholder:"Cari lokasi atau aset"}), "top-right");
    view.ui.add(new ScaleBar({view, unit:"metric"}), "bottom-left");

    const legendExpand = new Expand({
      view, content: new Legend({view, layerInfos:[{layer: asetLayer, title:"Aset Tanah Pemerintah"}]}),
      expanded:false, expandIconClass:"esri-icon-layer-list", expandTooltip:"Legenda"
    });
    view.ui.add(legendExpand, "bottom-right");

    const bgExpand = new Expand({
      view, content: new BasemapGallery({view, source: localSource}),
      expanded:false, expandIconClass:"esri-icon-basemap", expandTooltip:"Ganti basemap"
    });
    view.ui.add(bgExpand, "bottom-right");

    view.ui.add(new BasemapToggle({view, nextBasemap: bm_osm}), "bottom-right");

    // ================== DETAIL PANEL ==================
    const panel = $('detailPanel');
    $('dpClose').addEventListener('click', ()=> panel.classList.remove('show'));
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') panel.classList.remove('show'); });

    const setText = (id,val)=>{ $(id).textContent = (val && String(val).trim()!=='') ? val : '-'; };

    function openPanel(attrs){
      setText('dpUnitKerja', attrs.unit_kerja || '-');           // << tambahan UNIT KERJA
      setText('dpNama', attrs.nama_asset || '-');
      setText('dpLuas', fmt(attrs.luas_m2 || 0));
      setText('dpKelurahan', attrs.kelurahan || attrs.village || '-');
      setText('dpKecamatan', attrs.kecamatan || attrs.district || '-');
      setText('dpKabupaten', attrs.kabupaten || attrs.regency || '-');
      setText('dpProvinsi', attrs.provinsi || attrs.province || '-');
      setText('dpAlamat', attrs.alamat || '-');
      const link = attrs.sertifikat_url || attrs.link_sertif || attrs.file_url || null;
      $('dpSertifikat').innerHTML = link ? `<a class="dp-link" target="_blank" href="${link}">Lihat ⦿</a>` : '-';
      panel.classList.add('show');
    }

    // Highlight + pointer
    let layerView, highlightHandle=null;
    view.whenLayerView(asetLayer).then(lv => { layerView = lv; });

    view.on("pointer-move", function(evt){
      view.hitTest(evt, { include: [asetLayer] }).then((res)=>{
        const hit = res.results.some(r => r.graphic && r.graphic.layer === asetLayer);
        view.container.style.cursor = hit ? "pointer" : "default";
      });
    });

    view.on("click", function(event){
      view.hitTest(event, { include: [asetLayer] }).then(function(response){
        const r = response.results.find(x => x.graphic && x.graphic.layer === asetLayer);
        if (!r || !r.graphic) { panel.classList.remove('show'); if(highlightHandle){highlightHandle.remove();highlightHandle=null;} return; }
        if (layerView) {
          if (highlightHandle) { highlightHandle.remove(); }
          highlightHandle = layerView.highlight(r.graphic);
        }
        openPanel(r.graphic.attributes || {});
      });
    });

    // ================== GROUPING BY UNIT_KERJA ==================
    // Buat renderer unik per "unit_kerja" dengan palet warna
    asetLayer.when(async () => {
      try {
        const q = asetLayer.createQuery();
        q.where = "1=1";
        q.outFields = ["unit_kerja"];
        q.returnGeometry = false;

        const res = await asetLayer.queryFeatures(q);
        const values = Array.from(
          new Set(
            res.features
              .map(f => (f.attributes.unit_kerja ? String(f.attributes.unit_kerja).trim() : "-"))
          )
        ).sort((a,b)=>a.localeCompare(b,'id'));

        // Palet warna (RGB)
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
            color: [148, 163, 184, 0.35], // slate-400 transparan
            outline: { color: [100, 116, 139, 1], width: 1.2 }
          },
          uniqueValueInfos
        };

        // Perbarui legend jika sebelumnya belum terbuka
        // (Legend akan mengikuti renderer baru secara otomatis)
      } catch (err) {
        console.error("Gagal membuat renderer unik unit_kerja:", err);
      }
    });

  });
})();
</script>
@endpush
