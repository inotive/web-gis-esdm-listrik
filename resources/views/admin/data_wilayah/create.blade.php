{{-- resources/views/admin/data_wilayah/create.blade.php --}}
<div id="modalCreateWilayah" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateTitle">
    <div class="modal-header">
      <h3 id="modalCreateTitle">Tambah Data Wilayah</h3>
      <button type="button" class="btn-x" id="btnCloseCreate" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreateWilayah" method="POST" action="{{ route('admin.data-wilayah.store') }}">
      @csrf

      <div class="modal-body">
        {{-- Kabupaten/Kota --}}
        <div class="form-group">
          <label class="label">Kabupaten/Kota</label>
          <div class="control">
            <select name="regency_id" id="cRegency" class="input" required></select>
          </div>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <div class="control">
            <select name="district_id" id="cDistrict" class="input" required></select>
          </div>
        </div>

        {{-- Desa/Kelurahan --}}
        <div class="form-group">
          <label class="label">Desa/Kelurahan</label>
          <div class="control">
            <select name="village_id" id="cVillage" class="input" required></select>
          </div>
        </div>

        {{-- Koordinat (auto dari klik/drag marker) --}}
        <div class="form-group">
          <label class="label">Koordinat (opsional)</label>
          <div class="control with-addon">
            <input type="text" id="inputKoordinat" class="input" placeholder="-0.502000, 117.153000 (lat, lng)" autocomplete="off">
            <button class="addon" type="button" id="btnGeo" title="Gunakan lokasi saya">
              <i class="ri-map-pin-2-line"></i>
            </button>
          </div>
          <input type="hidden" id="latField" name="lat">
          <input type="hidden" id="lngField" name="lng">
        </div>

        {{-- Map + Draw Polygon --}}
        <div class="map-wrap">
          <div id="mapWilayah" class="map"></div>
          <input type="hidden" name="polygon_geojson" id="polygonField">
          <small style="display:block;color:#64748B;margin-top:6px;">
            Tips: klik di peta untuk memindahkan marker. Gunakan tombol <em>draw polygon</em> untuk menggambar batas wilayah (opsional).
          </small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('styles')
  {{-- Leaflet CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  {{-- Leaflet Draw CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"/>
  <style>
    .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
    .modal-overlay.show{display:block;}
    .modal{max-width:820px;margin:20px auto;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-2);overflow:hidden;}
    .modal-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid var(--line);}
    .modal-header h3{margin:0;font-weight:800;font-size:20px;letter-spacing:-.2px;}
    .btn-x{width:36px;height:36px;display:grid;place-items:center;border:1px solid #E2E8F0;background:#fff;border-radius:10px;cursor:pointer;}
    .btn-x:hover{background:#F8FAFC;}
    .modal-body{padding:18px 20px 6px;}
    .modal-footer{padding:14px 20px 18px;}
    .btn-save{width:100%;height:44px;border:none;border-radius:10px;font-weight:700;color:#fff;background:var(--accent-2);box-shadow:0 10px 22px rgba(34,197,94,.22);cursor:pointer;}
    .btn-save:hover{filter:brightness(.95);}
    .form-group{margin-bottom:16px;}
    .label{display:block;font-size:14px;color:#374151;margin:6px 0 8px;font-weight:600;}
    .control{position:relative;}
    .input{width:100%;height:44px;padding:0 12px;border:1px solid #E2E8F0;border-radius:10px;background:#FCFCFD;outline:none;font:inherit;color:#111827;}
    .input::placeholder{color:#94A3B8;}
    .input:focus{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
    .with-addon{display:flex;align-items:center;gap:8px;}
    .with-addon .input{flex:1;}
    .addon{width:44px;height:44px;display:grid;place-items:center;border:1px solid #E2E8F0;background:#fff;border-radius:10px;cursor:pointer;}
    .addon:hover{background:#F8FAFC;}
    .addon.muted{color:#94A3B8;cursor:default;}
    .map-wrap{margin-top:10px;border:1px solid #E2E8F0;border-radius:12px;overflow:hidden;}
    .map{width:100%;height:420px;}
    .leaflet-container{font:inherit;}
  </style>
@endpush

@push('scripts')
  {{-- Leaflet JS --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  {{-- Leaflet Draw --}}
  <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
  <script>
    (function () {
      const overlay = document.getElementById('modalCreateWilayah');
      const btnClose = document.getElementById('btnCloseCreate');
      const btnAdd = document.querySelector('.btn-add');

      // selects
      const selReg = document.getElementById('cRegency');
      const selDis = document.getElementById('cDistrict');
      const selVil = document.getElementById('cVillage');

      // map/coords
      const btnGeo = document.getElementById('btnGeo');
      const inputKoordinat = document.getElementById('inputKoordinat');
      const latField = document.getElementById('latField');
      const lngField = document.getElementById('lngField');
      const polygonField = document.getElementById('polygonField');

      let map, marker, drawnItems, drawControl, mapInited = false;
      const DEFAULT_POS = { lat: -0.502, lng: 117.153 };

      function option(el, value, label) {
        const o = document.createElement('option');
        o.value = value; o.textContent = label;
        el.appendChild(o);
      }

      async function loadRegencies() {
        selReg.innerHTML = '';
        option(selReg, '', 'Pilih Kabupaten/Kota');
        const res = await fetch(`{{ route('admin.data-wilayah.options.regencies') }}`);
        const rows = await res.json();
        rows.forEach(r => option(selReg, r.id, r.name));
        selDis.innerHTML = ''; option(selDis, '', 'Pilih Kecamatan');
        selVil.innerHTML = ''; option(selVil, '', 'Pilih Desa/Kelurahan');
      }

      async function loadDistricts(regencyId) {
        selDis.innerHTML = ''; option(selDis, '', 'Pilih Kecamatan');
        selVil.innerHTML = ''; option(selVil, '', 'Pilih Desa/Kelurahan');
        if (!regencyId) return;
        const res = await fetch(`{{ route('admin.data-wilayah.options.districts') }}?regency_id=${encodeURIComponent(regencyId)}`);
        const rows = await res.json();
        rows.forEach(r => option(selDis, r.id, r.name));
      }

      async function loadVillages(districtId) {
        selVil.innerHTML = ''; option(selVil, '', 'Pilih Desa/Kelurahan');
        if (!districtId) return;
        const res = await fetch(`{{ route('admin.data-wilayah.options.villages') }}?district_id=${encodeURIComponent(districtId)}`);
        const rows = await res.json();
        rows.forEach(r => option(selVil, r.id, r.name));
      }

      selReg?.addEventListener('change', () => loadDistricts(selReg.value));
      selDis?.addEventListener('change', () => loadVillages(selDis.value));

      function openModal() {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
          !mapInited ? initMap() : setTimeout(()=>map.invalidateSize(), 100);
        }, 50);
      }
      function closeModal() {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
      }

      function initMap() {
        mapInited = true;
        map = L.map('mapWilayah').setView([DEFAULT_POS.lat, DEFAULT_POS.lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 20, attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([DEFAULT_POS.lat, DEFAULT_POS.lng], { draggable:true }).addTo(map);
        writeInputs(DEFAULT_POS);

        map.on('click', (e)=> setPosition(e.latlng));
        marker.on('dragend', (e)=> setPosition(e.target.getLatLng()));

        // Draw polygon
        drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        drawControl = new L.Control.Draw({
          draw: {
            marker: false, polyline: false, rectangle: false, circle: false, circlemarker: false,
            polygon: { allowIntersection: false, showArea: true }
          },
          edit: { featureGroup: drawnItems, remove: true }
        });
        map.addControl(drawControl);

        map.on(L.Draw.Event.CREATED, function (e) {
          drawnItems.clearLayers();
          drawnItems.addLayer(e.layer);
          savePolygon();
        });
        map.on(L.Draw.Event.EDITED, savePolygon);
        map.on(L.Draw.Event.DELETED, () => { polygonField.value = ''; });
      }

      function setPosition(latlng) {
        marker.setLatLng(latlng);
        map.panTo(latlng, { animate:true });
        writeInputs(latlng);
      }

      function writeInputs(latlng) {
        const lat = +latlng.lat, lng = +latlng.lng;
        inputKoordinat.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        latField.value = lat.toFixed(8);
        lngField.value = lng.toFixed(8);
      }

      function savePolygon() {
        const gj = drawnItems.toGeoJSON();
        polygonField.value = JSON.stringify(gj);
      }

      inputKoordinat?.addEventListener('change', () => {
        const txt = inputKoordinat.value.replace(/\s+/g,'');
        const m = txt.match(/(-?\d+\.?\d*)[,|-](-?\d+\.?\d*)/);
        if (!m) return writeInputs(marker.getLatLng());
        const lat = parseFloat(m[1]), lng = parseFloat(m[2]);
        if (isFinite(lat) && isFinite(lng) && Math.abs(lat)<=90 && Math.abs(lng)<=180) setPosition({lat, lng});
        else writeInputs(marker.getLatLng());
      });

      btnGeo?.addEventListener('click', () => {
        if (!navigator.geolocation) return alert('Geolocation tidak didukung peramban Anda.');
        navigator.geolocation.getCurrentPosition(
          (pos) => {
            setPosition({ lat: pos.coords.latitude, lng: pos.coords.longitude });
            map.setZoom(16);
          },
          () => alert('Gagal memperoleh lokasi.')
        );
      });

      // Open/close
      btnAdd?.addEventListener('click', (e) => { e.preventDefault(); openModal(); loadRegencies(); });
      btnClose?.addEventListener('click', closeModal);
      overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

      if (location.hash === '#create') { openModal(); loadRegencies(); }
    })();
  </script>
@endpush
