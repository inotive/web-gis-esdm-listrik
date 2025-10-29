@extends('admin.layouts.app')

@section('title', 'Edit Data Wilayah')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"/>
<style>
  .card{border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-1);}
  .form-group{margin-bottom:16px;}
  .label{display:block;font-size:14px;color:#374151;margin:6px 0 8px;font-weight:600;}
  .input{width:100%;height:44px;padding:0 12px;border:1px solid #E2E8F0;border-radius:10px;background:#FCFCFD;outline:none;font:inherit;color:#111827;}
  .map{width:100%;height:460px;border:1px solid #E2E8F0;border-radius:12px;}
  .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;border:1px solid var(--line);cursor:pointer;background:#fff;}
  .btn-primary{background:var(--accent-2);color:#fff;border:none;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">Edit Data Wilayah</div>
  </div>
</div>

<section class="card" style="padding:18px;margin-top:18px;">
  <form method="POST" action="{{ route('admin.data-wilayah.update', $wilayah) }}">
    @csrf @method('PUT')

    <div class="row">
      <div class="col-md-6">
        {{-- Kabupaten --}}
        <div class="form-group">
          <label class="label">Kabupaten/Kota</label>
          <select name="regency_id" id="eRegency" class="input" required>
            @foreach($regencies as $r)
              <option value="{{ $r->id }}" @selected($wilayah->regency_id===$r->id)>{{ $r->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <select name="district_id" id="eDistrict" class="input" required>
            @foreach($districts as $d)
              <option value="{{ $d->id }}" @selected($wilayah->district_id===$d->id)>{{ $d->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Desa --}}
        <div class="form-group">
          <label class="label">Desa/Kelurahan</label>
          <select name="village_id" id="eVillage" class="input" required>
            @foreach($villages as $v)
              <option value="{{ $v->id }}" @selected($wilayah->village_id===$v->id)>{{ $v->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Koordinat --}}
        <div class="form-group">
          <label class="label">Koordinat (opsional)</label>
          <input type="text" id="eKoordinat" class="input" value="{{ isset($wilayah->lat,$wilayah->lng) ? number_format($wilayah->lat,6).', '.number_format($wilayah->lng,6) : '' }}" placeholder="-0.502000, 117.153000">
          <input type="hidden" name="lat" id="eLat" value="{{ $wilayah->lat }}">
          <input type="hidden" name="lng" id="eLng" value="{{ $wilayah->lng }}">
        </div>
      </div>

      <div class="col-md-6">
        <label class="label">Peta & Polygon</label>
        <div id="eMap" class="map"></div>
        <input type="hidden" name="polygon_geojson" id="ePolygon">
      </div>
    </div>

    <div style="margin-top:16px; display:flex; gap:10px;">
      <a href="{{ route('admin.data-wilayah.index') }}" class="btn"><i class="ri-arrow-go-back-line"></i> Kembali</a>
      <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i> Simpan Perubahan</button>
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
  (function(){
    // cascading
    const selReg = document.getElementById('eRegency');
    const selDis = document.getElementById('eDistrict');
    const selVil = document.getElementById('eVillage');

    selReg?.addEventListener('change', async () => {
      const rid = selReg.value;
      selDis.innerHTML = ''; addOpt(selDis,'','Pilih Kecamatan');
      selVil.innerHTML = ''; addOpt(selVil,'','Pilih Desa/Kelurahan');
      if (!rid) return;
      const res = await fetch(`{{ route('admin.data-wilayah.options.districts') }}?regency_id=${encodeURIComponent(rid)}`);
      const rows = await res.json();
      rows.forEach(r=>addOpt(selDis, r.id, r.name));
    });

    selDis?.addEventListener('change', async () => {
      const did = selDis.value;
      selVil.innerHTML = ''; addOpt(selVil,'','Pilih Desa/Kelurahan');
      if (!did) return;
      const res = await fetch(`{{ route('admin.data-wilayah.options.villages') }}?district_id=${encodeURIComponent(did)}`);
      const rows = await res.json();
      rows.forEach(r=>addOpt(selVil, r.id, r.name));
    });

    function addOpt(el,v,t){ const o=document.createElement('option'); o.value=v; o.textContent=t; el.appendChild(o); }

    // map
    const eMap = L.map('eMap');
    const lat = parseFloat(document.getElementById('eLat').value) || -0.502;
    const lng = parseFloat(document.getElementById('eLng').value) || 117.153;
    eMap.setView([lat,lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:20,attribution:'&copy; OpenStreetMap'}).addTo(eMap);

    const marker = L.marker([lat,lng], { draggable:true }).addTo(eMap);
    const eKoordinat = document.getElementById('eKoordinat');
    const eLat = document.getElementById('eLat');
    const eLng = document.getElementById('eLng');
    function writeInputs(latlng){
      eKoordinat.value = `${(+latlng.lat).toFixed(6)}, ${(+latlng.lng).toFixed(6)}`;
      eLat.value = (+latlng.lat).toFixed(8);
      eLng.value = (+latlng.lng).toFixed(8);
    }
    function setPosition(latlng){ marker.setLatLng(latlng); eMap.panTo(latlng); writeInputs(latlng); }
    eMap.on('click', (e)=> setPosition(e.latlng));
    marker.on('dragend', (e)=> setPosition(e.target.getLatLng()));
    eKoordinat?.addEventListener('change', ()=>{
      const m = eKoordinat.value.replace(/\s+/g,'').match(/(-?\d+\.?\d*)[,|-](-?\d+\.?\d*)/);
      if (!m) return writeInputs(marker.getLatLng());
      const lt = parseFloat(m[1]), lg = parseFloat(m[2]);
      if (isFinite(lt)&&isFinite(lg)&&Math.abs(lt)<=90&&Math.abs(lg)<=180) setPosition({lat:lt,lng:lg});
      else writeInputs(marker.getLatLng());
    });

    // draw polygon
    const drawnItems = new L.FeatureGroup();
    eMap.addLayer(drawnItems);
    const drawControl = new L.Control.Draw({
      draw: { marker:false, polyline:false, rectangle:false, circle:false, circlemarker:false, polygon:{ allowIntersection:false, showArea:true } },
      edit: { featureGroup: drawnItems, remove: true }
    });
    eMap.addControl(drawControl);

    const ePolygon = document.getElementById('ePolygon');
    // load existing polygon
    @if($wilayah->polygon_geojson)
      try {
        const gj = JSON.parse(@json($wilayah->polygon_geojson));
        const layer = L.geoJSON(gj);
        layer.eachLayer(l => drawnItems.addLayer(l));
        const b = drawnItems.getBounds();
        if (b.isValid()) eMap.fitBounds(b.pad(0.2));
        ePolygon.value = JSON.stringify(gj);
      } catch(e){}
    @endif

    function savePoly(){ const gj = drawnItems.toGeoJSON(); ePolygon.value = JSON.stringify(gj); }
    eMap.on(L.Draw.Event.CREATED, function (e) { drawnItems.clearLayers(); drawnItems.addLayer(e.layer); savePoly(); });
    eMap.on(L.Draw.Event.EDITED, savePoly);
    eMap.on(L.Draw.Event.DELETED, function(){ ePolygon.value = ''; });

    // initial fill
    writeInputs({lat,lng});
  })();
</script>
@endpush
