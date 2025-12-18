@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Peta Persebaran Listrik')

@push('styles')
<style>
  #adminMapWrap { height: 70vh; width: 100%; background:#f3f4f6; border-radius: 16px; overflow: hidden; position: relative; }
  #viewDiv { height: 100%; width: 100%; }

  /* Detail Modal */
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
    position: absolute; top: 8px; right: 8px;
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5; width: 26px; height: 26px;
    border-radius: 6px; cursor: pointer; font-size: 16px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s; z-index: 1; font-weight: bold;
  }
  .dm-close:hover { background: rgba(239, 68, 68, 0.35); color: #fee2e2; transform: scale(1.1); }
  .dm-content { display: flex; flex-direction: column; }
  .dm-head {
    padding: 10px 40px 10px 14px; font-weight: 700; font-size: 15px;
    border-bottom: 1px solid rgba(226, 232, 240, 0.16);
    background: linear-gradient(90deg, rgba(34,197,94,0.18), rgba(15,23,42,0.05));
  }
  .dm-body { padding: 10px 14px; overflow-y: auto; max-height: 50vh; display: grid; gap: 6px; }
  .dm-row {
    display: grid; grid-template-columns: 1fr 1.2fr; gap: 8px; font-size: 12px;
    padding: 6px 8px; background: rgba(255,255,255,0.04);
    border: 1px solid rgba(148, 163, 184, 0.16); border-radius: 8px;
  }
  .dm-key { color: #94a3b8; font-weight: 600; word-break: break-word; }
  .dm-val { color: #e2e8f0; word-break: break-word; }

  /* Layer Filter Panel */
  .layer-filter {
    position: absolute; left: 12px; top: 12px;
    width: 260px; max-height: 420px; overflow: hidden;
    background: rgba(255,255,255,0.97); color: #0f172a;
    border-radius: 14px; box-shadow: 0 12px 32px rgba(0,0,0,0.18);
    border: 1px solid rgba(226,232,240,0.5); z-index: 50;
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .layer-filter.lf-minimized { transform: translateX(-280px); opacity: 0; pointer-events: none; }
  .lf-head {
    padding: 10px 12px; font-weight: 700; font-size: 14px;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(90deg, rgba(37,99,235,0.15), rgba(255,255,255,0.9));
    display: flex; justify-content: space-between; align-items: center;
  }
  .lf-close-btn {
    background: rgba(239,68,68,0.15); color: #ef4444;
    border: 1px solid rgba(239,68,68,0.3); border-radius: 6px;
    width: 24px; height: 24px; font-size: 18px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
  }
  .lf-close-btn:hover { background: rgba(239,68,68,0.25); }
  .lf-open-btn {
    position: absolute; left: 12px; top: 12px;
    background: rgba(37,99,235,0.92); color: white;
    border: 1px solid rgba(59,130,246,0.4); border-radius: 10px;
    width: 44px; height: 44px; font-size: 18px; cursor: pointer;
    display: none; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(37,99,235,0.35); z-index: 50;
  }
  .lf-open-btn:hover { background: rgba(59,130,246,0.95); }
  .lf-body { padding: 8px 10px; overflow-y: auto; max-height: 340px; display: flex; flex-direction: column; gap: 4px; }
  .lf-row { display: flex; align-items: center; gap: 6px; padding: 6px 8px; border-radius: 8px; cursor: pointer; font-size: 13px; }
  .lf-row:hover { background: #f1f5f9; }
  .lf-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: #2563eb; }
  .lf-icon { font-size: 14px; }
  .lf-parent { background: #f8fafc; font-weight: 600; }
  .lf-child { padding-left: 24px; font-weight: 400; }
  .lf-toggle { font-size: 10px; color: #64748b; width: 16px; }
  .lf-children { display: flex; flex-direction: column; gap: 2px; }
  .lf-children.collapsed { display: none; }
  .lf-divider { height: 1px; background: #e2e8f0; margin: 6px 0; }
  .lf-all { background: linear-gradient(90deg, rgba(34,197,94,0.1), rgba(255,255,255,0.5)); }

  .esri-ui.bottom-right>.esri-component{box-shadow:0 8px 20px rgba(0,0,0,.18);border-radius:12px;overflow:hidden}

  @media (max-width:1024px){
    #adminMapWrap{height:60vh; min-height:360px;}
    .layer-filter{width:220px; max-height:320px;}
  }
</style>

<link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">Peta Interaktif</div>
      <div class="page-title">Peta Persebaran Listrik</div>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-body" style="position:relative; padding: 0;">
      <div id="adminMapWrap">
        <div id="viewDiv"></div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script src="https://js.arcgis.com/4.29/"></script>

<script>
(function(){
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

    // ========= Detail Modal =========
    const detailModal = document.createElement('div');
    detailModal.id = 'detailModal';
    detailModal.className = 'detail-modal hidden';
    detailModal.innerHTML = `
      <div class="dm-close" title="Tutup">✕</div>
      <div class="dm-content"></div>
    `;
    document.getElementById('adminMapWrap').appendChild(detailModal);

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
        <div class="dm-body">${rows || '<div style="color:#94a3b8;padding:8px;">Tidak ada atribut</div>'}</div>
      `;
    };

    const showDetailModal = (event, graphic) => {
      renderDetailContent(graphic);
      detailModal.classList.remove('hidden');
      let x = event.x, y = event.y;
      const modalRect = detailModal.getBoundingClientRect();
      const vw = window.innerWidth, vh = window.innerHeight;
      let finalX = x + 15, finalY = y + 15;
      if (finalX + modalRect.width > vw) finalX = Math.max(10, x - modalRect.width - 15);
      if (finalY + modalRect.height > vh) finalY = Math.max(10, y - modalRect.height - 15);
      detailModal.style.left = `${finalX}px`;
      detailModal.style.top = `${finalY}px`;
    };

    const hideDetailModal = () => detailModal.classList.add('hidden');
    closeBtn.addEventListener('click', (e) => { e.stopPropagation(); hideDetailModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideDetailModal(); });

    // ================== LAYERS ==================
    // Desa Berlistrik PLN
    const desaBerlistrikLayer = new GeoJSONLayer({
      url: "{{ url('/api/data-berlistrik') }}",
      title: "Desa Berlistrik PLN",
      outFields: ["*"],
      renderer: {
        type: "unique-value",
        field: "H_Survei",
        defaultSymbol: { type: "simple-fill", color: [148, 163, 184, 0.35], outline: { color: [100, 116, 139, 1], width: 1 } },
        uniqueValueInfos: [
          { value: "Belum Terlayani Listrik", label: "Belum Terlayani Listrik", symbol: { type: "simple-fill", color: [220, 38, 38, 0.45], outline: { color: [185, 28, 28, 1], width: 1.5 } } },
          { value: "Terlayani Listrik", label: "Sudah Terlayani Listrik", symbol: { type: "simple-fill", color: [34, 197, 94, 0.45], outline: { color: [22, 163, 74, 1], width: 1.5 } } }
        ]
      }
    });
    map.add(desaBerlistrikLayer);

    // Jalan Layers
    const jalanNasionalLayer = new GeoJSONLayer({ url: "{{ url('/api/data-jalan-nasional') }}", title: "Jalan Nasional", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 215, 0, 1], width: 2.5 } } });
    const jalanProvinsiLayer = new GeoJSONLayer({ url: "{{ url('/api/data-jalan-provinsi') }}", title: "Jalan Provinsi", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 255, 255, 1], width: 2.5 } } });
    const jalanBalikpapanLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-balikpapan') }}", title: "Jalan Balikpapan", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 0, 0, 1], width: 2 } } });
    const jalanBerauLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-berau') }}", title: "Jalan Berau", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 165, 0, 1], width: 2 } } });
    const jalanBontangLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-bontang') }}", title: "Jalan Bontang", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 0, 255, 1], width: 2 } } });
    const jalanKubarLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-kubar') }}", title: "Jalan Kubar", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 255, 0, 1], width: 2 } } });
    const jalanKutaiKartanegaraLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-kutai-kartanegara') }}", title: "Jalan Kutai Kartanegara", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 165, 100, 1], width: 2 } } });
    const jalanKutimLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-kutim') }}", title: "Jalan Kutim", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [148, 0, 211, 1], width: 2 } } });
    const jalanPaserLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-paser') }}", title: "Jalan Paser", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 100, 0, 1], width: 2 } } });
    const jalanPPULayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-ppu') }}", title: "Jalan PPU", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [75, 0, 130, 1], width: 2 } } });
    const jalanSamarindaLayer = new GeoJSONLayer({ url: "{{ url('/api/jalan-samarinda') }}", title: "Jalan Samarinda", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 255, 255, 1], width: 2 } } });
    [jalanNasionalLayer, jalanProvinsiLayer, jalanBalikpapanLayer, jalanBerauLayer, jalanBontangLayer, jalanKubarLayer, jalanKutaiKartanegaraLayer, jalanKutimLayer, jalanPaserLayer, jalanPPULayer, jalanSamarindaLayer].forEach(l => map.add(l));

    // Jaringan Listrik Layers
    const jaringanListrikBalikpapanLayer = new GeoJSONLayer({ url: "{{ url('/api/jaringan-listrik-balikpapan') }}", title: "Jaringan Listrik Balikpapan (SUTM)", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 255, 0, 1], width: 2 } } });
    const jaringanListrikBontangLayer = new GeoJSONLayer({ url: "{{ url('/api/jaringan-listrik-bontang') }}", title: "Rencana Jaringan Listrik Bontang", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 0, 255, 1], width: 2 } } });
    const sistemJaringanEnergiKukarLayer = new GeoJSONLayer({ url: "{{ url('/api/sistem-jaringan-energi-kukar') }}", title: "Sistem Energi Kukar (SUTT)", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 140, 0, 1], width: 2.5 } } });
    const sistemJaringanEnergiMahuluLayer = new GeoJSONLayer({ url: "{{ url('/api/sistem-jaringan-energi-mahulu') }}", title: "Sistem Energi Mahulu (SUTR)", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 191, 255, 1], width: 2 } } });
    const sistemJaringanEnergiKubarLayer = new GeoJSONLayer({ url: "{{ url('/api/sistem-jaringan-energi-kubar') }}", title: "Sistem Energi Kubar (SUTM)", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [50, 205, 50, 1], width: 2 } } });
    const sistemJaringanEnergiKubarUP2KBLayer = new GeoJSONLayer({ url: "{{ url('/api/sistem-jaringan-energi-kubar-up2kb') }}", title: "Sistem Energi Kubar UP2KB", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [138, 43, 226, 1], width: 2 } } });
    const sistemJaringanEnergiKutimLayer = new GeoJSONLayer({ url: "{{ url('/api/sistem-jaringan-energi-kutim') }}", title: "Sistem Energi Kutim (SUTM)", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 20, 147, 1], width: 2 } } });
    const sistemJaringanEnergiPaserLayer = new GeoJSONLayer({ url: "{{ url('/api/sistem-jaringan-energi-paser') }}", title: "Sistem Energi Paser (SUTM)", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 128, 128, 1], width: 2 } } });
    const sutmPPULayer = new GeoJSONLayer({ url: "{{ url('/api/sutm-ppu') }}", title: "LN SUTM PPU", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 99, 71, 1], width: 2 } } });
    const sutrKutimLayer = new GeoJSONLayer({ url: "{{ url('/api/sutr-kutim') }}", title: "LN SUTR Kutim", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [30, 144, 255, 1], width: 2 } } });
    const lnTransmisiLayer = new GeoJSONLayer({ url: "{{ url('/api/ln-transmisi') }}", title: "LN Transmisi", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 215, 0, 1], width: 3 } } });
    const ln2SutmPaserLayer = new GeoJSONLayer({ url: "{{ url('/api/ln2-sutm-paser') }}", title: "LN2 SUTM Paser", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [60, 179, 113, 1], width: 2 } } });
    const ln2SutmPPULayer = new GeoJSONLayer({ url: "{{ url('/api/ln2-sutm-ppu') }}", title: "LN2 SUTM PPU", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [106, 90, 205, 1], width: 2 } } });
    const sutmBerauLayer = new GeoJSONLayer({ url: "{{ url('/api/sutm-berau') }}", title: "LN SUTM Berau", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [255, 127, 80, 1], width: 2 } } });
    [jaringanListrikBalikpapanLayer, jaringanListrikBontangLayer, sistemJaringanEnergiKukarLayer, sistemJaringanEnergiMahuluLayer, sistemJaringanEnergiKubarLayer, sistemJaringanEnergiKubarUP2KBLayer, sistemJaringanEnergiKutimLayer, sistemJaringanEnergiPaserLayer, sutmPPULayer, sutrKutimLayer, lnTransmisiLayer, ln2SutmPaserLayer, ln2SutmPPULayer, sutmBerauLayer].forEach(l => map.add(l));

    // Infrastruktur Point Layers
    const ptGarduBerauLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-gardu-berau') }}", title: "PT Gardu Berau", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [255, 0, 0], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptGarduDistribusiKutimLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-gardu-distribusi-kutim') }}", title: "PT Gardu Distribusi Kutim", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [0, 128, 0], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptGarduHubungKutimLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-gardu-hubung-kutim') }}", title: "PT Gardu Hubung Kutim", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [0, 0, 255], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptGarduIndukKutimLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-gardu-induk-kutim') }}", title: "PT Gardu Induk Kutim", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [255, 165, 0], size: 10, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptTrafoBerauLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-trafo-berau') }}", title: "PT Trafo Berau", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [128, 0, 128], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptTrafoGarduDistribusiPpuLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-trafo-gardu-distribusi-ppu') }}", title: "PT Trafo Gardu Distribusi PPU", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [0, 206, 209], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptTrafoGarduKubarLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-trafo-gardu-kubar') }}", title: "PT Trafo Gardu Kubar", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [255, 20, 147], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const pt1TrafoGarduPaserLayer = new GeoJSONLayer({ url: "{{ url('/api/pt1-trafo-gardu-paser') }}", title: "PT1 Trafo Gardu Paser", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [154, 205, 50], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const pt2TrafoGarduPaserLayer = new GeoJSONLayer({ url: "{{ url('/api/pt2-trafo-gardu-paser') }}", title: "PT2 Trafo Gardu Paser", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [70, 130, 180], size: 8, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptSistemEnergiBalikpapanLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-sistem-energi-balikpapan') }}", title: "PT Sistem Energi Balikpapan", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [220, 20, 60], size: 10, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptSistemEnergiKukarLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-sistem-energi-kukar') }}", title: "PT Sistem Energi Kukar", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [34, 139, 34], size: 10, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptSistemEnergiMahuluLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-sistem-energi-mahulu') }}", title: "PT Sistem Energi Mahulu", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [75, 0, 130], size: 10, outline: { color: [255, 255, 255], width: 1 } } } });
    const ptSistemEnergiSamarindaLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-sistem-energi-samarinda') }}", title: "PT Sistem Energi Samarinda", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [255, 69, 0], size: 10, outline: { color: [255, 255, 255], width: 1 } } } });
    [ptGarduBerauLayer, ptGarduDistribusiKutimLayer, ptGarduHubungKutimLayer, ptGarduIndukKutimLayer, ptTrafoBerauLayer, ptTrafoGarduDistribusiPpuLayer, ptTrafoGarduKubarLayer, pt1TrafoGarduPaserLayer, pt2TrafoGarduPaserLayer, ptSistemEnergiBalikpapanLayer, ptSistemEnergiKukarLayer, ptSistemEnergiMahuluLayer, ptSistemEnergiSamarindaLayer].forEach(l => map.add(l));

    // Pembangkit Layers
    const ptPembangkitEksistingLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-pembangkit-eksisting') }}", title: "PT Pembangkit Eksisting", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [0, 100, 0], size: 12, outline: { color: [255, 255, 255], width: 2 } } } });
    const ptRencanaPembangkitBontangLayer = new GeoJSONLayer({ url: "{{ url('/api/pt-rencana-pembangkit-bontang') }}", title: "PT Rencana Pembangkit Bontang", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-marker", color: [255, 140, 0], size: 12, outline: { color: [255, 255, 255], width: 2 } } } });
    [ptPembangkitEksistingLayer, ptRencanaPembangkitBontangLayer].forEach(l => map.add(l));

    // Batas Administrasi Layers
    const lnBatasDesaLayer = new GeoJSONLayer({ url: "{{ url('/api/ln-batas-desa') }}", title: "LN Batas Desa", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [139, 69, 19, 0.6], width: 1, style: "dash" } } });
    const lnBatasKabKotaLayer = new GeoJSONLayer({ url: "{{ url('/api/ln-batas-kabkota') }}", title: "LN Batas Kab/Kota", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [128, 0, 0, 0.8], width: 2 } } });
    const lnBatasKecamatanLayer = new GeoJSONLayer({ url: "{{ url('/api/ln-batas-kecamatan') }}", title: "LN Batas Kecamatan", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 0, 139, 0.7], width: 1.5 } } });
    const lnBatasNegaraLayer = new GeoJSONLayer({ url: "{{ url('/api/ln-batas-negara') }}", title: "LN Batas Negara", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [0, 0, 0, 1], width: 3 } } });
    const lnBatasProvinsiLayer = new GeoJSONLayer({ url: "{{ url('/api/ln-batas-provinsi') }}", title: "LN Batas Provinsi", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-line", color: [139, 0, 139, 0.9], width: 2.5 } } });
    const arBatasKaltimLayer = new GeoJSONLayer({ url: "{{ url('/api/ar-batas-kaltim') }}", title: "AR Batas Kaltim Full", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-fill", color: [0, 0, 0, 0], outline: { color: [0, 100, 0, 0.8], width: 2 } } } });
    const arBatasKecamatanLayer = new GeoJSONLayer({ url: "{{ url('/api/ar-batas-kaltim-kecamatan') }}", title: "AR Batas Kec.", outFields: ["*"], renderer: { type: "simple", symbol: { type: "simple-fill", color: [0, 0, 0, 0], outline: { color: [100, 100, 100, 0.6], width: 1 } } } });
    [lnBatasDesaLayer, lnBatasKabKotaLayer, lnBatasKecamatanLayer, lnBatasNegaraLayer, lnBatasProvinsiLayer, arBatasKaltimLayer, arBatasKecamatanLayer].forEach(l => map.add(l));

    // Set all layers invisible initially
    const allLayers = [desaBerlistrikLayer, jalanNasionalLayer, jalanProvinsiLayer, jalanBalikpapanLayer, jalanBerauLayer, jalanBontangLayer, jalanKubarLayer, jalanKutaiKartanegaraLayer, jalanKutimLayer, jalanPaserLayer, jalanPPULayer, jalanSamarindaLayer, jaringanListrikBalikpapanLayer, jaringanListrikBontangLayer, sistemJaringanEnergiKukarLayer, sistemJaringanEnergiMahuluLayer, sistemJaringanEnergiKubarLayer, sistemJaringanEnergiKubarUP2KBLayer, sistemJaringanEnergiKutimLayer, sistemJaringanEnergiPaserLayer, sutmPPULayer, sutrKutimLayer, lnTransmisiLayer, ln2SutmPaserLayer, ln2SutmPPULayer, sutmBerauLayer, ptGarduBerauLayer, ptGarduDistribusiKutimLayer, ptGarduHubungKutimLayer, ptGarduIndukKutimLayer, ptTrafoBerauLayer, ptTrafoGarduDistribusiPpuLayer, ptTrafoGarduKubarLayer, pt1TrafoGarduPaserLayer, pt2TrafoGarduPaserLayer, ptSistemEnergiBalikpapanLayer, ptSistemEnergiKukarLayer, ptSistemEnergiMahuluLayer, ptSistemEnergiSamarindaLayer, ptPembangkitEksistingLayer, ptRencanaPembangkitBontangLayer, lnBatasDesaLayer, lnBatasKabKotaLayer, lnBatasKecamatanLayer, lnBatasNegaraLayer, lnBatasProvinsiLayer, arBatasKaltimLayer, arBatasKecamatanLayer];
    allLayers.forEach(l => l.visible = false);

    // Click event for detail modal
    view.on('click', (event) => {
      view.hitTest(event).then((response) => {
        const graphic = response.results?.[0]?.graphic;
        if (!graphic) { hideDetailModal(); return; }
        showDetailModal(event, graphic);
      }).catch(() => hideDetailModal());
    });

    // ================== LAYER FILTER PANEL ==================
    const layerCategories = {
      transportasi: [
        { label: 'Jalan Nasional', layer: jalanNasionalLayer, icon: '🛣️' },
        { label: 'Jalan Provinsi', layer: jalanProvinsiLayer, icon: '🛤️' },
        { label: 'Jalan Balikpapan', layer: jalanBalikpapanLayer, icon: '🚗' },
        { label: 'Jalan Berau', layer: jalanBerauLayer, icon: '🚚' },
        { label: 'Jalan Bontang', layer: jalanBontangLayer, icon: '🚛' },
        { label: 'Jalan Kubar', layer: jalanKubarLayer, icon: '🛣️' },
        { label: 'Jalan Kutai Kartanegara', layer: jalanKutaiKartanegaraLayer, icon: '🛣️' },
        { label: 'Jalan Kutim', layer: jalanKutimLayer, icon: '🛣️' },
        { label: 'Jalan Paser', layer: jalanPaserLayer, icon: '🛣️' },
        { label: 'Jalan PPU', layer: jalanPPULayer, icon: '🛣️' },
        { label: 'Jalan Samarinda', layer: jalanSamarindaLayer, icon: '🛣️' }
      ],
      jaringan: [
        { label: 'Jaringan Listrik Balikpapan', layer: jaringanListrikBalikpapanLayer, icon: '⚡' },
        { label: 'Rencana Jaringan Listrik Bontang', layer: jaringanListrikBontangLayer, icon: '📋' },
        { label: 'Sistem Energi Kukar (SUTT)', layer: sistemJaringanEnergiKukarLayer, icon: '🔌' },
        { label: 'Sistem Energi Mahulu (SUTR)', layer: sistemJaringanEnergiMahuluLayer, icon: '🔌' },
        { label: 'Sistem Energi Kubar (SUTM)', layer: sistemJaringanEnergiKubarLayer, icon: '🔌' },
        { label: 'Sistem Energi Kubar UP2KB', layer: sistemJaringanEnergiKubarUP2KBLayer, icon: '🔌' },
        { label: 'Sistem Energi Kutim (SUTM)', layer: sistemJaringanEnergiKutimLayer, icon: '🔌' },
        { label: 'Sistem Energi Paser (SUTM)', layer: sistemJaringanEnergiPaserLayer, icon: '🔌' },
        { label: 'LN SUTM PPU', layer: sutmPPULayer, icon: '⚡' },
        { label: 'LN SUTR Kutim', layer: sutrKutimLayer, icon: '⚡' },
        { label: 'LN Transmisi', layer: lnTransmisiLayer, icon: '🔋' },
        { label: 'LN2 SUTM Paser', layer: ln2SutmPaserLayer, icon: '⚡' },
        { label: 'LN2 SUTM PPU', layer: ln2SutmPPULayer, icon: '⚡' },
        { label: 'LN SUTM Berau', layer: sutmBerauLayer, icon: '⚡' }
      ],
      infrastruktur: [
        { label: 'PT Gardu Berau', layer: ptGarduBerauLayer, icon: '🏭' },
        { label: 'PT Gardu Distribusi Kutim', layer: ptGarduDistribusiKutimLayer, icon: '🏭' },
        { label: 'PT Gardu Hubung Kutim', layer: ptGarduHubungKutimLayer, icon: '🏭' },
        { label: 'PT Gardu Induk Kutim', layer: ptGarduIndukKutimLayer, icon: '🏭' },
        { label: 'PT Trafo Berau', layer: ptTrafoBerauLayer, icon: '🔧' },
        { label: 'PT Trafo Gardu Distribusi PPU', layer: ptTrafoGarduDistribusiPpuLayer, icon: '🔧' },
        { label: 'PT Trafo Gardu Kubar', layer: ptTrafoGarduKubarLayer, icon: '🔧' },
        { label: 'PT1 Trafo Gardu Paser', layer: pt1TrafoGarduPaserLayer, icon: '🔧' },
        { label: 'PT2 Trafo Gardu Paser', layer: pt2TrafoGarduPaserLayer, icon: '🔧' },
        { label: 'PT Sistem Energi Balikpapan', layer: ptSistemEnergiBalikpapanLayer, icon: '⚙️' },
        { label: 'PT Sistem Energi Kukar', layer: ptSistemEnergiKukarLayer, icon: '⚙️' },
        { label: 'PT Sistem Energi Mahulu', layer: ptSistemEnergiMahuluLayer, icon: '⚙️' },
        { label: 'PT Sistem Energi Samarinda', layer: ptSistemEnergiSamarindaLayer, icon: '⚙️' }
      ],
      pembangkit: [
        { label: 'PT Pembangkit Eksisting', layer: ptPembangkitEksistingLayer, icon: '🏗️' },
        { label: 'PT Rencana Pembangkit Bontang', layer: ptRencanaPembangkitBontangLayer, icon: '📐' }
      ],
      administrasi: [
        { label: 'LN Batas Desa', layer: lnBatasDesaLayer, icon: '🏘️' },
        { label: 'LN Batas Kab/Kota', layer: lnBatasKabKotaLayer, icon: '🏙️' },
        { label: 'LN Batas Kecamatan', layer: lnBatasKecamatanLayer, icon: '🏛️' },
        { label: 'LN Batas Negara', layer: lnBatasNegaraLayer, icon: '🌍' },
        { label: 'LN Batas Provinsi', layer: lnBatasProvinsiLayer, icon: '🗺️' },
        { label: 'AR Batas Kaltim Full', layer: arBatasKaltimLayer, icon: '📍' },
        { label: 'AR Batas Kec.', layer: arBatasKecamatanLayer, icon: '📍' }
      ]
    };

    const layerFilter = document.createElement('div');
    layerFilter.className = 'layer-filter';

    // Build category HTML
    let categoriesHTML = '';

    // Status Listrik Desa
    categoriesHTML += `
      <label class="lf-row lf-parent" data-category="desa">
        <span class="lf-toggle">▼</span>
        <input type="checkbox" id="lf-desa-parent">
        <span class="lf-icon">🏠</span>
        <span><strong>Status Listrik Desa</strong></span>
      </label>
      <div class="lf-children" data-category="desa">
        <label class="lf-row lf-child"><input type="checkbox" id="lf-desa-belum"> <span class="lf-icon">🔴</span> <span>Belum Terlayani Listrik</span></label>
        <label class="lf-row lf-child"><input type="checkbox" id="lf-desa-terlayani"> <span class="lf-icon">🟢</span> <span>Terlayani Listrik</span></label>
      </div>
    `;

    // Other categories
    const categoryConfig = [
      { key: 'transportasi', title: 'Data Jalan', icon: '🚧' },
      { key: 'jaringan', title: 'Jaringan Listrik', icon: '⚡' },
      { key: 'infrastruktur', title: 'Infrastruktur Listrik', icon: '🏭' },
      { key: 'pembangkit', title: 'Pembangkit', icon: '🏗️' },
      { key: 'administrasi', title: 'Administrasi', icon: '🗺️' }
    ];

    categoryConfig.forEach(cfg => {
      categoriesHTML += `<label class="lf-row lf-parent" data-category="${cfg.key}">
        <span class="lf-toggle">▼</span>
        <input type="checkbox" id="lf-${cfg.key}-parent">
        <span class="lf-icon">${cfg.icon}</span>
        <span><strong>${cfg.title}</strong></span>
      </label>
      <div class="lf-children collapsed" data-category="${cfg.key}">`;
      layerCategories[cfg.key].forEach((item, idx) => {
        categoriesHTML += `<label class="lf-row lf-child"><input type="checkbox" id="lf-${cfg.key}-${idx}"> <span class="lf-icon">${item.icon}</span> <span>${item.label}</span></label>`;
      });
      categoriesHTML += `</div>`;
    });

    layerFilter.innerHTML = `
      <div class="lf-head">
        <span>🗂️ Layer Filter</span>
        <button class="lf-close-btn" title="Tutup panel">×</button>
      </div>
      <div class="lf-body">
        <label class="lf-row lf-all"><input type="checkbox" id="lf-all"> <span class="lf-icon">📊</span> <span><strong>Semua Layer</strong></span></label>
        <div class="lf-divider"></div>
        ${categoriesHTML}
      </div>
    `;

    // Open button for when panel is closed
    const openButton = document.createElement('button');
    openButton.className = 'lf-open-btn';
    openButton.innerHTML = '☰';
    openButton.title = 'Buka Layer Filter';
    openButton.style.display = 'none';

    document.getElementById('adminMapWrap').appendChild(layerFilter);
    document.getElementById('adminMapWrap').appendChild(openButton);

    // Panel close/open handlers
    const closeButton = layerFilter.querySelector('.lf-close-btn');
    closeButton.addEventListener('click', () => { layerFilter.classList.add('lf-minimized'); openButton.style.display = 'flex'; });
    openButton.addEventListener('click', () => { layerFilter.classList.remove('lf-minimized'); openButton.style.display = 'none'; });

    // Desa Berlistrik filter handlers
    const desaParentCheckbox = layerFilter.querySelector('#lf-desa-parent');
    const desaBelumCheckbox = layerFilter.querySelector('#lf-desa-belum');
    const desaTerlayaniCheckbox = layerFilter.querySelector('#lf-desa-terlayani');

    const updateDesaBerlistrikFilter = () => {
      const showBelum = desaBelumCheckbox.checked;
      const showTerlayani = desaTerlayaniCheckbox.checked;
      if (!showBelum && !showTerlayani) { desaBerlistrikLayer.visible = false; return; }
      desaBerlistrikLayer.visible = true;
      const uniqueValueInfos = [];
      if (showBelum) uniqueValueInfos.push({ value: "Belum Terlayani Listrik", label: "Belum Terlayani Listrik", symbol: { type: "simple-fill", color: [220, 38, 38, 0.45], outline: { color: [185, 28, 28, 1], width: 1.5 } } });
      if (showTerlayani) uniqueValueInfos.push({ value: "Terlayani Listrik", label: "Sudah Terlayani Listrik", symbol: { type: "simple-fill", color: [34, 197, 94, 0.45], outline: { color: [22, 163, 74, 1], width: 1.5 } } });
      desaBerlistrikLayer.renderer = { type: "unique-value", field: "H_Survei", defaultSymbol: { type: "simple-fill", color: [148, 163, 184, 0.35], outline: { color: [100, 116, 139, 1], width: 1 } }, uniqueValueInfos };
    };

    desaParentCheckbox.addEventListener('change', () => { desaBelumCheckbox.checked = desaTerlayaniCheckbox.checked = desaParentCheckbox.checked; updateDesaBerlistrikFilter(); });
    desaBelumCheckbox.addEventListener('change', () => { updateDesaBerlistrikFilter(); desaParentCheckbox.checked = desaBelumCheckbox.checked || desaTerlayaniCheckbox.checked; });
    desaTerlayaniCheckbox.addEventListener('change', () => { updateDesaBerlistrikFilter(); desaParentCheckbox.checked = desaBelumCheckbox.checked || desaTerlayaniCheckbox.checked; });

    // Category handlers
    const setCategoryCheckboxes = (categoryName, isChecked) => {
      const parent = layerFilter.querySelector(`#lf-${categoryName}-parent`);
      if (parent) parent.checked = isChecked;
      layerCategories[categoryName]?.forEach((item, idx) => {
        const checkbox = layerFilter.querySelector(`#lf-${categoryName}-${idx}`);
        if (checkbox) checkbox.checked = isChecked;
        item.layer.visible = isChecked;
      });
    };

    const setupCategoryHandlers = (categoryName) => {
      const parentCheckbox = layerFilter.querySelector(`#lf-${categoryName}-parent`);
      if (!parentCheckbox) return;
      parentCheckbox.addEventListener('change', () => {
        layerCategories[categoryName].forEach((item, idx) => {
          const childCheckbox = layerFilter.querySelector(`#lf-${categoryName}-${idx}`);
          if (childCheckbox) childCheckbox.checked = parentCheckbox.checked;
          item.layer.visible = parentCheckbox.checked;
        });
      });
      layerCategories[categoryName].forEach((item, idx) => {
        const childCheckbox = layerFilter.querySelector(`#lf-${categoryName}-${idx}`);
        if (!childCheckbox) return;
        childCheckbox.addEventListener('change', () => {
          item.layer.visible = childCheckbox.checked;
          parentCheckbox.checked = layerCategories[categoryName].some((_, i) => layerFilter.querySelector(`#lf-${categoryName}-${i}`)?.checked);
        });
      });
    };

    categoryConfig.forEach(cfg => setupCategoryHandlers(cfg.key));

    // Semua Layer checkbox
    const allCheckbox = layerFilter.querySelector('#lf-all');
    allCheckbox.addEventListener('change', () => {
      const isChecked = allCheckbox.checked;
      desaParentCheckbox.checked = desaBelumCheckbox.checked = desaTerlayaniCheckbox.checked = isChecked;
      updateDesaBerlistrikFilter();
      categoryConfig.forEach(cfg => setCategoryCheckboxes(cfg.key, isChecked));
    });

    // Expand/collapse functionality
    layerFilter.querySelectorAll('.lf-parent').forEach(parentLabel => {
      const toggle = parentLabel.querySelector('.lf-toggle');
      const category = parentLabel.getAttribute('data-category');
      const childrenContainer = layerFilter.querySelector(`.lf-children[data-category="${category}"]`);
      if (!toggle || !childrenContainer) return;
      parentLabel.addEventListener('click', (e) => {
        if (e.target.type === 'checkbox') return;
        e.preventDefault(); e.stopPropagation();
        const isCollapsed = childrenContainer.classList.toggle('collapsed');
        toggle.textContent = isCollapsed ? '▶' : '▼';
      });
    });

    // ================== WIDGETS ==================
    const bm_osm = Basemap.fromId("osm"); bm_osm.title = "Peta (OSM)";
    const bm_sat = Basemap.fromId("satellite"); bm_sat.title = "Satelit";
    const bm_hybrid = Basemap.fromId("hybrid"); bm_hybrid.title = "Hybrid";
    const bm_terrain = Basemap.fromId("terrain"); bm_terrain.title = "Medan";
    const bm_topo = Basemap.fromId("topo-vector"); bm_topo.title = "Topografi";
    const bm_gray = Basemap.fromId("gray-vector"); bm_gray.title = "Abu-abu";
    const bm_dark = Basemap.fromId("dark-gray-vector"); bm_dark.title = "Gelap";
    const bm_street = Basemap.fromId("streets-vector"); bm_street.title = "Streets";
    const localSource = new LocalBasemapsSource({ basemaps: [bm_osm, bm_sat, bm_hybrid, bm_terrain, bm_topo, bm_gray, bm_dark, bm_street] });

    view.ui.add(new Home({ view }), "top-left");
    view.ui.add(new Search({ view, allPlaceholder: "Cari lokasi" }), "top-right");
    view.ui.add(new ScaleBar({ view, unit: "metric" }), "bottom-left");

    view.ui.add(new Expand({ view, content: new Legend({ view }), expanded: false, expandIconClass: "esri-icon-layer-list", expandTooltip: "Legenda" }), "bottom-right");
    view.ui.add(new Expand({ view, content: new BasemapGallery({ view, source: localSource }), expanded: false, expandIconClass: "esri-icon-basemap", expandTooltip: "Ganti basemap" }), "bottom-right");
    view.ui.add(new BasemapToggle({ view, nextBasemap: bm_osm }), "bottom-right");
  });
})();
</script>
@endpush
