@extends('admin.layouts.app')

@section('title', 'Peta Persebaran Tanah - BPKAD')
@section('page-title', 'Peta Persebaran Tanah')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Manajemen Aset</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Peta Persebaran Tanah</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
<style>
    #viewDiv{height:750px;width:100%;border-radius:8px;overflow:hidden;position:relative}
    .map-stats{display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap}
    .stat-card{flex:1;min-width:200px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:1.5rem;border-radius:12px;color:#fff;box-shadow:0 4px 12px rgba(102,126,234,.3)}
    .stat-card.primary{background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%)}
    .stat-card.success{background:linear-gradient(135deg,#10b981 0%,#059669 100%)}
    .stat-card.warning{background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)}
    .stat-card .stat-icon{font-size:2.5rem;opacity:.8;margin-bottom:.5rem}
    .stat-card .stat-label{font-size:.875rem;opacity:.9;margin-bottom:.25rem}
    .stat-card .stat-value{font-size:1.75rem;font-weight:700}

    /* Widget di kanan bawah rapi */
    .esri-ui.bottom-right>.esri-component{box-shadow:0 6px 18px rgba(0,0,0,.15);border-radius:12px;overflow:hidden;margin:8px 0 0 0}
    .esri-expand__container{border-radius:12px}
    .esri-basemap-gallery__item-title{font-size:12px}

    /* Panel detail kanan */
    .detail-panel{position:absolute;right:12px;top:12px;bottom:12px;width:360px;max-width:88vw;background:#fff;border-radius:16px;box-shadow:0 12px 28px rgba(0,0,0,.18);padding:16px 16px 12px 16px;display:none;z-index:20;overflow:auto;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial}
    .detail-panel.show{display:block}
    .dp-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
    .dp-title{font-weight:700;font-size:16px}
    .dp-close{border:none;background:#f1f5f9;width:34px;height:34px;border-radius:10px;cursor:pointer}
    .dp-badge{display:inline-flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid #e5e7eb;padding:8px 10px;border-radius:10px;font-size:13px;margin:4px 0 10px 0}
    .dp-badge .dot{width:12px;height:12px;border-radius:9999px;background:#fbbf24;border:2px solid #fde68a}
    .dp-metric{margin-left:auto;font-weight:700}
    .dp-section{background:#f8fafc;border:1px solid #eef2f7;padding:10px;border-radius:12px;margin-bottom:10px}
    .dp-section h5{font-size:13px;margin:0 0 8px 0;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px}
    .dp-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
    .dp-item{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:8px}
    .dp-label{font-size:11px;color:#64748b;margin-bottom:2px}
    .dp-value{font-size:13px;font-weight:600;color:#111827;word-break:break-word}
    .dp-link{font-size:13px;font-weight:700}
</style>
@endpush

@section('content')
<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h3 class="card-title fw-bold fs-2 mb-1">
                        <i class="ki-duotone ki-geolocation fs-1 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Peta Persebaran Aset Tanah
                    </h3>
                    <p class="text-muted mb-0">Visualisasi geografis seluruh aset tanah Pemerintah Provinsi Kalimantan Timur</p>
                </div>
                <div>
                    <a href="{{ route('admin.asset.index') }}" class="btn btn-light-primary btn-sm">
                        <i class="ki-duotone ki-left fs-3"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            

            <div class="position-relative">
                <div id="viewDiv">
                    {{-- Panel kanan --}}
                    <aside id="detailPanel" class="detail-panel">
                        <div class="dp-head">
                            <div class="dp-title">Peta Persebaran Tanah</div>
                            <button id="dpClose" class="dp-close" title="Tutup">✕</button>
                        </div>

                        <div class="dp-badge">
                            <span class="dot"></span>
                            <span id="dpBadgeTitle">Aset Tanah</span>
                            <span class="dp-metric" id="dpLuasHa">0 (ha)</span>
                        </div>

                        <div class="dp-section">
                            <h5>📍 Alamat</h5>
                            <div class="dp-grid">
                                <div class="dp-item"><div class="dp-label">Kecamatan</div><div class="dp-value" id="dpKecamatan">-</div></div>
                                <div class="dp-item"><div class="dp-label">Kelurahan</div><div class="dp-value" id="dpKelurahan">-</div></div>
                                <div class="dp-item"><div class="dp-label">Kabupaten</div><div class="dp-value" id="dpKabupaten">-</div></div>
                                <div class="dp-item"><div class="dp-label">Provinsi</div><div class="dp-value" id="dpProvinsi">-</div></div>
                                <div class="dp-item" style="grid-column:1/-1"><div class="dp-label">Alamat Lengkap</div><div class="dp-value" id="dpAlamat">-</div></div>
                            </div>
                        </div>

                        <div class="dp-section">
                            <h5>🧾 Keterangan</h5>
                            <div class="dp-grid">
                                <div class="dp-item"><div class="dp-label">Nomor Hak</div><div class="dp-value" id="dpNomorHak">-</div></div>
                                <div class="dp-item"><div class="dp-label">Jenis Hak</div><div class="dp-value" id="dpJenisHak">-</div></div>
                                <div class="dp-item"><div class="dp-label">Tahun</div><div class="dp-value" id="dpTahun">-</div></div>
                                <div class="dp-item"><div class="dp-label">Pemilik Tanah</div><div class="dp-value" id="dpPemilik">-</div></div>
                                <div class="dp-item"><div class="dp-label">Luas m²</div><div class="dp-value" id="dpLuasM2">-</div></div>
                                <div class="dp-item"><div class="dp-label">Sertifikat</div><div class="dp-value" id="dpSertifikat">-</div></div>
                                <div class="dp-item"><div class="dp-label">Kode</div><div class="dp-value" id="dpKode">-</div></div>
                                <div class="dp-item"><div class="dp-label">Layer</div><div class="dp-value" id="dpLayer">-</div></div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.arcgis.com/4.29/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  require([
    "esri/Map","esri/Basemap","esri/views/MapView","esri/layers/GeoJSONLayer",
    "esri/widgets/Legend","esri/widgets/Expand","esri/widgets/Home","esri/widgets/Search",
    "esri/widgets/ScaleBar","esri/widgets/BasemapGallery","esri/widgets/BasemapToggle",
    "esri/widgets/BasemapGallery/support/LocalBasemapsSource"
  ], function (Map,Basemap,MapView,GeoJSONLayer,Legend,Expand,Home,Search,ScaleBar,BasemapGallery,BasemapToggle,LocalBasemapsSource) {

    const fmt = (n, m=0) => (n===null||n===undefined||isNaN(n)) ? "-" : Number(n).toLocaleString('id-ID', {maximumFractionDigits:m});
    const $ = id => document.getElementById(id);

    // Map
    const map = new Map({ basemap: Basemap.fromId("osm") });

    // View
    const view = new MapView({
      container: "viewDiv",
      map, center: [117.15, -0.5], zoom: 8,
      popup: { autoOpenEnabled: false } // gunakan panel sendiri
    });

    // Layer GeoJSON
    const asetLayer = new GeoJSONLayer({
      url: "{{ url('/api/aset') }}",
      title: "Aset Tanah Pemerintah",
      outFields: ["*"],
      renderer: {
        type: "simple",
        symbol: { type:"simple-fill", color:[37,99,235,0.5], outline:{color:[29,78,216], width:2.5} }
      }
    });
    map.add(asetLayer);

    // Basemap list
    const bm_osm=Basemap.fromId("osm"); bm_osm.title="Peta (OSM)";
    const bm_sat=Basemap.fromId("satellite"); bm_sat.title="Satelit";
    const bm_hybrid=Basemap.fromId("hybrid"); bm_hybrid.title="Hybrid";
    const bm_terrain=Basemap.fromId("terrain"); bm_terrain.title="Medan";
    const bm_topo=Basemap.fromId("topo-vector"); bm_topo.title="Topografi";
    const bm_gray=Basemap.fromId("gray-vector"); bm_gray.title="Abu-abu";
    const bm_dark=Basemap.fromId("dark-gray-vector"); bm_dark.title="Gelap";
    const bm_street=Basemap.fromId("streets-vector"); bm_street.title="Streets";
    const localSource = new LocalBasemapsSource({ basemaps:[bm_osm,bm_sat,bm_hybrid,bm_terrain,bm_topo,bm_gray,bm_dark,bm_street] });

    // Widgets
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

    view.ui.add(new BasemapToggle({view, nextBasemap: bm_sat}), "bottom-right");

    // Statistik
    asetLayer.when(() => {
      asetLayer.queryFeatureCount().then((count)=>{
        $('total-assets').textContent=count.toLocaleString('id-ID');
        $('geolocated-assets').textContent=count.toLocaleString('id-ID');
      });
      asetLayer.queryFeatures({where:"1=1", outFields:["luas_m2"]}).then((res)=>{
        let tot=0; res.features.forEach(f=>{const v=f.attributes.luas_m2; if(v && !isNaN(v)) tot+=parseFloat(v);});
        $('total-area').textContent = fmt(tot) + " m²";
      });
    });

    // ====== Detail Panel ======
    const panel = $('detailPanel');
    $('dpClose').addEventListener('click', ()=> panel.classList.remove('show'));

    function setText(id, val){ $(id).textContent = (val && String(val).trim()!=='') ? val : '-'; }

    function openPanel(attrs){
      setText('dpBadgeTitle', attrs.nama_asset || 'Aset Tanah');

      const m2 = Number(attrs.luas_m2 || 0);
      $('dpLuasHa').textContent = fmt(m2/10000, 3) + " (ha)";
      setText('dpLuasM2', fmt(m2));

      setText('dpKecamatan', attrs.kecamatan || attrs.district || '-');
      setText('dpKelurahan', attrs.kelurahan || attrs.village || '-');
      setText('dpKabupaten', attrs.kabupaten || attrs.regency || '-');
      setText('dpProvinsi', attrs.provinsi || attrs.province || attrs.province_name || attrs.nama_provinsi || '-');
      setText('dpAlamat', attrs.alamat || '-');

      setText('dpNomorHak', attrs.nomor_hak || attrs.no_sertif || '-');
      setText('dpJenisHak', attrs.jenis_hak || attrs.JN_Hak || '-');

      // Tahun – utamakan tgl_sertif, fallback ke 'tahun'
      let tahun = '-';
      const tgl = attrs.tgl_sertif || attrs.tanggal_sertif || attrs.tanggal_dokumen || '';
      const match = String(tgl).match(/\b(19|20)\d{2}\b/);
      if (match && match[0]) {
        tahun = match[0];
      } else if (attrs.tahun) {
        tahun = String(attrs.tahun);
      }
      setText('dpTahun', tahun);

      setText('dpPemilik', attrs.pemilik_tanah || attrs.unit_kerja || '-');

      // Link sertifikat dari API
      const link = attrs.sertifikat_url || attrs.link_sertif || attrs.file_url || null;
      $('dpSertifikat').innerHTML = link ? `<a class="dp-link" target="_blank" href="${link}">Lihat ⦿</a>` : '-';

      setText('dpKode', attrs.kode || attrs.kode_asset || '-');
      setText('dpLayer', attrs.layer || attrs.kat_tanah || (attrs.kategori ?? '-'));

      panel.classList.add('show');
    }

    // Klik peta -> tampilkan detail panel
    view.on("click", function(event){
      view.hitTest(event).then(function(response){
        const r = response.results.find(x => x.graphic && x.graphic.layer === asetLayer);
        if (r && r.graphic) openPanel(r.graphic.attributes || {});
      });
    });

  });
});
</script>
@endpush
