@includeWhen(true,'admin.data_gardu.create')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
  #map-create-layer{width:100%;height:340px;border:1px solid var(--line);border-radius:12px}
  .input-group-mini{display:flex;gap:10px}
  .input-group-mini .input{flex:1}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endpush

<div class="modal" id="modalCreateLayer" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="ttlLayer">
    <div class="modal-hd">
      <div id="ttlLayer" class="modal-ttl">Tambah Layer / Titik GIS</div>
      <button type="button" class="modal-x" onclick="__closeModal('modalCreateLayer')"><i class="ri-close-line" style="color:#16a34a"></i></button>
    </div>
    <form class="modal-bd form" id="formCreateLayer">
      <div class="f">
        <label>Nama Fitur</label>
        <input class="input" name="nama" placeholder="Contoh: Gardu A-01 atau PLTS Desa L" required>
      </div>
      <div class="row">
        <div class="f">
          <label>Jenis</label>
          <select class="select" name="jenis" required>
            <option value="">Pilih</option>
            <option value="gardu">Gardu</option>
            <option value="pembangkit">Pembangkit</option>
            <option value="pemukiman">Pemukiman Tanpa Listrik</option>
          </select>
        </div>
        <div class="f">
          <label>Keterangan Singkat</label>
          <input class="input" name="ket" placeholder="Opsional">
        </div>
      </div>

      <div class="f">
        <label>Koordinat</label>
        <div class="input-group-mini">
          <input class="input" name="lat" id="layerLat" placeholder="Lat" required>
          <input class="input" name="lng" id="layerLng" placeholder="Lng" required>
          <button class="btn-soft" type="button" id="btnUseCenter" title="Pakai pusat peta"><i class="ri-focus-2-line"></i></button>
        </div>
      </div>

      <div id="map-create-layer" aria-label="Map pilih koordinat"></div>

      <div class="modal-ft">
        <button type="button" class="btn-soft" onclick="__closeModal('modalCreateLayer')">Batal</button>
        <button class="btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btn = document.querySelector('.page-actions .btn-add');   // di halaman GIS: "Import Layer" atau "Tambah Layer"
    if(btn) btn.addEventListener('click', ()=> __openModal('modalCreateLayer'));

    let map, marker;
    const modal = document.getElementById('modalCreateLayer');
    const latEl = document.getElementById('layerLat');
    const lngEl = document.getElementById('layerLng');

    function initMap(){
      if(map) return;
      map = L.map('map-create-layer').setView([-0.5021, 117.1537], 11);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18}).addTo(map);
      map.on('click', e => place(e.latlng));
    }
    function place(latlng){
      if(marker) marker.setLatLng(latlng);
      else marker = L.marker(latlng, {draggable:true}).addTo(map).on('dragend', e=>{
        const p = e.target.getLatLng(); latEl.value = p.lat.toFixed(6); lngEl.value = p.lng.toFixed(6);
      });
      latEl.value = latlng.lat.toFixed(6); lngEl.value = latlng.lng.toFixed(6);
    }

    modal?.addEventListener('click', (e)=>{ if(e.target.id==='modalCreateLayer') __closeModal('modalCreateLayer'); });
    // saat modal dibuka: init map & invalidateSize
    const obs = new MutationObserver(() => {
      if(modal.classList.contains('show')){
        initMap();
        setTimeout(()=> map?.invalidateSize(), 120);
      }
    });
    obs.observe(modal, {attributes:true, attributeFilter:['class']});

    document.getElementById('btnUseCenter')?.addEventListener('click', ()=>{
      if(!map) return; place(map.getCenter());
    });

    const form = document.getElementById('formCreateLayer');
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      const data = Object.fromEntries(new FormData(form));
      console.log('Simpan Layer (dummy):', data);
      __closeModal('modalCreateLayer');
    });
  })();
</script>
@endpush
