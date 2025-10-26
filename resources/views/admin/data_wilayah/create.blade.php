{{-- resources/views/admin/data_wilayah/create.blade.php --}}
<div id="modalCreateWilayah" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateTitle">
    <div class="modal-header">
      <h3 id="modalCreateTitle">Tambah Data Wilayah</h3>
      <button type="button" class="btn-x" id="btnCloseCreate" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreateWilayah" method="POST" action="">
      @csrf

      <div class="modal-body">
        {{-- Nama Desa --}}
        <div class="form-group">
          <label class="label">Nama Desa</label>
          <div class="control">
            <input type="text" name="nama_desa" class="input" placeholder="Ketik Disini" required>
          </div>
        </div>

        {{-- Koordinat + tombol geolokasi --}}
        <div class="form-group">
          <label class="label">Koordinat</label>
          <div class="control with-addon">
            <input type="text" id="inputKoordinat" name="koordinat" class="input" placeholder="-0.502, 117.153 (lat, lng)" autocomplete="off">
            <button class="addon" type="button" id="btnGeo" title="Gunakan lokasi saya">
              <i class="ri-map-pin-2-line"></i>
            </button>
          </div>
          <input type="hidden" id="latField" name="lat">
          <input type="hidden" id="lngField" name="lng">
        </div>

        {{-- Map --}}
        <div class="map-wrap">
          <div id="mapWilayah" class="map"></div>
        </div>

        {{-- Kecamatan/Kabupaten (dummy search text) --}}
        <div class="form-group">
          <label class="label">Kecamatan, Kabupaten</label>
          <div class="control with-addon">
            <input type="text" name="lokasi_text" class="input" placeholder="Cari Disini">
            <span class="addon muted"><i class="ri-search-line"></i></span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn-save">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
  {{-- Leaflet CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <style>
    /* ===== Modal ===== */
    .modal-overlay{ position:fixed; inset:0; background:rgba(15,23,42,.45); display:none; z-index:1000;
      padding:18px; overflow:auto; }
    .modal-overlay.show{ display:block; }
    .modal{ max-width:720px; margin:20px auto; background:#fff; border:1px solid var(--line);
      border-radius:16px; box-shadow:var(--shadow-2); overflow:hidden; }
    .modal-header{ display:flex; justify-content:space-between; align-items:center;
      padding:18px 20px; border-bottom:1px solid var(--line); }
    .modal-header h3{ margin:0; font-weight:800; font-size:20px; letter-spacing:-.2px; }
    .btn-x{ width:36px; height:36px; display:grid; place-items:center; border:1px solid #E2E8F0;
      background:#fff; border-radius:10px; cursor:pointer; }
    .btn-x:hover{ background:#F8FAFC; }

    .modal-body{ padding:18px 20px 6px; }
    .modal-footer{ padding:14px 20px 18px; }
    .btn-save{ width:100%; height:44px; border:none; border-radius:10px; font-weight:700;
      color:#fff; background:var(--accent-2); box-shadow:0 10px 22px rgba(34,197,94,.22); cursor:pointer; }
    .btn-save:hover{ filter:brightness(.95); }

    /* ===== Form controls ===== */
    .form-group{ margin-bottom:16px; }
    .label{ display:block; font-size:14px; color:#374151; margin:6px 0 8px; font-weight:600; }
    .control{ position:relative; }
    .input{
      width:100%; height:44px; padding:0 12px; border:1px solid #E2E8F0; border-radius:10px; background:#FCFCFD;
      outline:none; font:inherit; color:#111827;
    }
    .input::placeholder{ color:#94A3B8; }
    .input:focus{ border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12); }
    .with-addon{ display:flex; align-items:center; gap:8px; }
    .with-addon .input{ flex:1; }
    .addon{
      width:44px; height:44px; display:grid; place-items:center; border:1px solid #E2E8F0;
      background:#fff; border-radius:10px; cursor:pointer;
    }
    .addon:hover{ background:#F8FAFC; }
    .addon.muted{ color:#94A3B8; cursor:default; }

    /* ===== Map ===== */
    .map-wrap{ margin-top:10px; border:1px solid #E2E8F0; border-radius:12px; overflow:hidden; }
    .map{ width:100%; height:360px; }
    .leaflet-container{ font: inherit; }
    .leaflet-control-attribution{ font-size:11px; }
  </style>
@endpush

@push('scripts')
  {{-- Leaflet JS --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    (function () {
      const overlay = document.getElementById('modalCreateWilayah');
      const btnClose = document.getElementById('btnCloseCreate');
      const btnAdd = document.querySelector('.btn-add'); // tombol di index
      const btnGeo = document.getElementById('btnGeo');
      const inputKoordinat = document.getElementById('inputKoordinat');
      const latField = document.getElementById('latField');
      const lngField = document.getElementById('lngField');

      let map, marker, circle, mapInited = false;
      // Default center: Kaltim (Samarinda kurang lebih)
      const DEFAULT_POS = { lat: -0.502, lng: 117.153 };

      function openModal() {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        setTimeout(initMapIfNeeded, 60);
      }
      function closeModal() {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
      }

      function initMapIfNeeded() {
        if (mapInited) { setTimeout(() => map.invalidateSize(), 120); return; }
        mapInited = true;
        map = L.map('mapWilayah', { zoomControl: true, scrollWheelZoom: true })
              .setView([DEFAULT_POS.lat, DEFAULT_POS.lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([DEFAULT_POS.lat, DEFAULT_POS.lng], { draggable: true }).addTo(map);
        circle = L.circle([DEFAULT_POS.lat, DEFAULT_POS.lng], { radius: 200, color: '#3B82F6', fillColor: '#60A5FA', fillOpacity: 0.25 }).addTo(map);

        writeInputs(DEFAULT_POS);

        // click pindah marker
        map.on('click', (e) => setPosition(e.latlng));
        marker.on('dragend', (e) => setPosition(e.target.getLatLng()));
      }

      function setPosition(latlng) {
        marker.setLatLng(latlng);
        circle.setLatLng(latlng);
        map.panTo(latlng, { animate: true });
        writeInputs(latlng);
      }

      function writeInputs(latlng) {
        const lat = +latlng.lat, lng = +latlng.lng;
        inputKoordinat.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        latField.value = lat.toFixed(8);
        lngField.value = lng.toFixed(8);
      }

      // Parse koordinat manual (lat,lng) / (lat - lng)
      inputKoordinat?.addEventListener('change', () => {
        const txt = inputKoordinat.value.replace(/\s+/g,'');
        // ambil dua angka (boleh minus & desimal)
        const m = txt.match(/(-?\d+\.?\d*)[,|-](-?\d+\.?\d*)/);
        if (!m) return writeInputs(marker.getLatLng());
        const lat = parseFloat(m[1]), lng = parseFloat(m[2]);
        if (isFinite(lat) && isFinite(lng) && Math.abs(lat)<=90 && Math.abs(lng)<=180) {
          setPosition({lat, lng});
        } else {
          writeInputs(marker.getLatLng());
        }
      });

      // Geolocation
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

      // Buka/tutup modal
      btnAdd?.addEventListener('click', (e) => { e.preventDefault(); openModal(); });
      btnClose?.addEventListener('click', closeModal);
      overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

      // Optional: buka melalui hash #create
      if (location.hash === '#create') openModal();
    })();
  </script>
@endpush
