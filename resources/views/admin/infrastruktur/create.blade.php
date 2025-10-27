<!-- Modal Create Infrastruktur -->
<div id="modalInfra" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalInfraTitle">
  <div class="modal-backdrop" data-close></div>
  <div class="modal-card">
    <div class="modal-head">
      <div class="modal-title" id="modalInfraTitle">Tambah Infrastruktur</div>
      <button class="btn-ghost" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <div class="modal-body">
      <form id="infraForm" action="#" method="POST" onsubmit="event.preventDefault(); alert('Demo submit: data tidak disimpan (tanpa DB).');">
        @csrf
        <div class="grid-2">
          <div class="form-row">
            <label class="label">Jenis Infrastruktur</label>
            <select class="select" name="jenis" required>
              <option value="">Pilih jenis...</option>
              <option value="JTM">JTM</option>
              <option value="JTR">JTR</option>
              <option value="Gardu">Gardu</option>
              <option value="Trafo">Trafo</option>
            </select>
          </div>

          <div class="form-row">
            <label class="label">Kode Aset</label>
            <input class="input" type="text" name="kode" placeholder="AS-007" required>
          </div>

          <div class="form-row">
            <label class="label">Nama Aset</label>
            <input class="input" type="text" name="nama" placeholder="JTM 20kV Segmen C" required>
          </div>

          <div class="form-row">
            <label class="label">Kondisi</label>
            <select class="select" name="kondisi" required>
              <option value="">Pilih kondisi...</option>
              <option>Baik</option>
              <option>Sedang</option>
              <option>Rusak</option>
            </select>
          </div>

          <div class="form-row">
            <label class="label">Latitude</label>
            <input id="infraLat" class="input" type="text" name="lat" placeholder="-0.502100" required>
          </div>

          <div class="form-row">
            <label class="label">Longitude</label>
            <input id="infraLng" class="input" type="text" name="lng" placeholder="117.153200" required>
          </div>

          <div class="form-row" style="grid-column:1 / -1;">
            <label class="label">Peta Lokasi</label>
            <div id="infraMap" class="map-shell" aria-label="Peta Infrastruktur"></div>
          </div>

          <div class="form-row" style="grid-column:1 / -1;">
            <label class="label">Keterangan</label>
            <textarea class="textarea" name="keterangan" placeholder="Catatan tambahan..."></textarea>
          </div>
        </div>
      </form>
    </div>

    <div class="modal-actions">
      <button class="btn btn-primary" form="infraForm" type="submit">
        <i class="ri-save-3-line"></i> Simpan
      </button>
      <button class="btn btn-ghost" data-close>Batalkan</button>
    </div>
  </div>
</div>
