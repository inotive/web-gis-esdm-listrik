@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Peta Persebaran Aset')

@push('styles')
<style>
  #adminMapWrap { height: 70vh; width: 100%; background:#f3f4f6; border-radius: 16px; overflow: hidden; }
  #viewDiv { height: 100%; width: 100%; }

  .detail-panel{
    position:absolute;
    right:16px;
    top:16px;
    bottom:16px;
    width:320px;
    max-height:calc(100% - 32px);
    background:#fff;border:1px solid #e5e7eb;border-radius:14px;
    box-shadow:0 12px 28px rgba(0,0,0,.15);
    padding:12px;display:none;z-index:40;overflow:auto
  }
  .detail-panel.show{display:block}
  .dp-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
  .dp-title{font-weight:800;color:#0f172a;font-size:16px}
  .dp-close{border:none;background:#f1f5f9;width:32px;height:32px;border-radius:10px;cursor:pointer}

  .dp-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
  .dp-item{background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:8px}
  .dp-item.full{grid-column:1 / -1}
  .dp-label{font-size:11px;color:#64748b;margin-bottom:3px}
  .dp-value{font-size:13px;font-weight:700;color:#0f172a;word-break:break-word}
  .dp-link{font-size:13px;font-weight:700}

  .esri-ui.bottom-right>.esri-component{box-shadow:0 8px 20px rgba(0,0,0,.18);border-radius:12px;overflow:hidden}

  .filter-panel{
    position:absolute;
    left:20px;
    bottom:20px;
    width:320px;
    max-height:420px;
    background:#fff;
    border-radius:18px;
    box-shadow:0 20px 60px rgba(15,23,42,.25);
    border:1px solid #e2e8f0;
    display:flex;
    flex-direction:column;
    overflow:hidden;
    z-index:45;
  }
  .filter-head{
    display:flex;align-items:center;justify-content:space-between;
    padding:10px 14px;border-bottom:1px solid #e2e8f0;background:#f8fafc;
  }
  .filter-title{font-weight:700;font-size:14px;color:#0f172a;display:flex;align-items:center;gap:6px}
  .filter-body{padding:10px 14px;overflow:auto;gap:10px;display:flex;flex-direction:column;}
  .filter-row{display:flex;flex-direction:column;gap:4px;font-size:13px;}
  .filter-label{color:#64748b;font-weight:600;display:flex;align-items:center;gap:6px;}
  .filter-select{
    width:100%;border-radius:10px;border:1px solid #e2e8f0;min-height:36px;
    padding:6px 10px;font-size:13px;background:#f8fafc;color:#0f172a;
  }
  .filter-toggle{
    width:28px;height:28px;border-radius:999px;border:1px solid #cbd5e1;
    background:#fff;display:inline-grid;place-items:center;cursor:pointer;color:#64748b;
  }
  .filter-panel.is-collapsed .filter-body{display:none;}
  .filter-panel.is-collapsed{width:auto;min-width:52px;}

  @media (max-width:1024px){
    #adminMapWrap{height:60vh; min-height:360px;}
    .detail-panel{position:relative;top:auto;right:auto;bottom:auto;width:100%;max-height:260px;margin-top:10px;}
    .filter-panel{left:12px;right:12px;bottom:12px;width:auto;}
  }
</style>

<link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">Peta Interaktif</div>
      <div class="page-title">Peta Persebaran Aset Tanah</div>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-body" style="position:relative;">
      <div id="filterPanel" class="filter-panel">
        <div class="filter-head">
          <div class="filter-title">
            <i class="ri-filter-3-line"></i>
            <span>Filter Peta</span>
          </div>
          <button id="filterToggle" type="button" class="filter-toggle" title="Sembunyikan / tampilkan filter">
            <i class="ri-arrow-up-s-line"></i>
          </button>
        </div>
        <div class="filter-body">
          <div class="filter-row">
            <span class="filter-label"><i class="ri-government-line"></i>Kabupaten/Kota</span>
            <select class="filter-select" id="filterKabupaten">
              <option value="">Semua Kabupaten/Kota</option>
            </select>
          </div>

          <div class="filter-row">
            <span class="filter-label"><i class="ri-community-line"></i>Kecamatan</span>
            <select class="filter-select" id="filterKecamatan" disabled>
              <option value="">Pilih Kabupaten terlebih dahulu</option>
            </select>
          </div>

          <div class="filter-row">
            <span class="filter-label"><i class="ri-home-4-line"></i>Kelurahan/Desa</span>
            <select class="filter-select" id="filterKelurahan" disabled>
              <option value="">Pilih Kecamatan terlebih dahulu</option>
            </select>
          </div>

          <div class="filter-row">
            <span class="filter-label"><i class="ri-shape-2-line"></i>Desa Berlistrik</span>
            <select class="filter-select" id="filterDesaBerlistrik">
              <option value="">Semua Desa Berlistrik</option>
              <option value="">Desa Berlistrik</option>
              <option value="">Desa Tidak Berlistrik</option>
            </select>
          </div>
        </div>
      </div>

      <div id="adminMapWrap">
        <div id="viewDiv"></div>
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
    </div>
  </section>
@endsection

@push('scripts')
<script src="https://js.arcgis.com/4.29/"></script>

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

    const map = new Map({ basemap: Basemap.fromId("satellite") });

    const view = new MapView({
      container: "viewDiv",
      map, center: [117.15, -0.5], zoom: 10,
      popup: { autoOpenEnabled: false }
    });

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

    const filterPanel = $('filterPanel');
    const filterToggle = $('filterToggle');
    if (filterPanel && filterToggle) {
      filterToggle.addEventListener('click', function(){
        const collapsed = filterPanel.classList.toggle('is-collapsed');
        this.innerHTML = collapsed
          ? '<i class="ri-arrow-down-s-line"></i>'
          : '<i class="ri-arrow-up-s-line"></i>';
      });
    }

    const panel = $('detailPanel');
    $('dpClose').addEventListener('click', ()=> panel.classList.remove('show'));
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') panel.classList.remove('show'); });

    const setText = (id,val)=>{ $(id).textContent = (val && String(val).trim()!=='') ? val : '-'; };

    function openPanel(attrs){
      setText('dpUnitKerja', attrs.unit_kerja || '-');
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
          uniqueValueInfos
        };
      } catch (err) {
        console.error("Gagal membuat renderer unik unit_kerja:", err);
      }
    });
  });
})();
</script>
@endpush
