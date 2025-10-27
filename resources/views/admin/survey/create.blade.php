<!-- Modal Create Survei -->
<div id="modalSurvey" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalSurveyTitle">
  <div class="modal-backdrop" data-close></div>
  <div class="modal-card">
    <div class="modal-head">
      <div class="modal-title" id="modalSurveyTitle">Tambah Hasil Survei</div>
      <button class="btn-ghost" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <div class="modal-body">
      <form id="surveyForm" action="#" method="POST" enctype="multipart/form-data"
            onsubmit="event.preventDefault(); alert('Demo submit: data tidak disimpan (tanpa DB).');">
        @csrf
        <div class="grid-2">
          <div class="form-row">
            <label class="label">Tanggal Survei</label>
            <input class="input" type="date" name="tanggal" required>
          </div>

          <div class="form-row">
            <label class="label">Petugas</label>
            <input class="input" type="text" name="petugas" placeholder="Nama petugas" required>
          </div>

          <div class="form-row">
            <label class="label">Lokasi / Desa</label>
            <input class="input" type="text" name="lokasi" placeholder="Nama desa/lokasi" required>
          </div>

          <div class="form-row">
            <label class="label">Kecamatan</label>
            <input class="input" type="text" name="kecamatan" placeholder="Nama kecamatan">
          </div>

          <div class="form-row">
            <label class="label">Latitude</label>
            <input id="surveyLat" class="input" type="text" name="lat" placeholder="-0.504200" required>
          </div>

          <div class="form-row">
            <label class="label">Longitude</label>
            <input id="surveyLng" class="input" type="text" name="lng" placeholder="117.150100" required>
          </div>

          <div class="form-row" style="grid-column:1 / -1;">
            <label class="label">Peta Lokasi</label>
            <div id="surveyMap" class="map-shell" aria-label="Peta Survei"></div>
          </div>

          <div class="form-row" style="grid-column:1 / -1;">
            <label class="label">Temuan / Catatan</label>
            <textarea class="textarea" name="temuan" placeholder="Ringkasan temuan lapangan..." required></textarea>
          </div>

          <div class="form-row" style="grid-column:1 / -1;">
            <label class="label">Lampiran Foto (opsional)</label>
            <input class="input" type="file" name="foto" accept="image/*">
          </div>
        </div>
      </form>
    </div>

    <div class="modal-actions">
      <button class="btn btn-primary" form="surveyForm" type="submit">
        <i class="ri-save-3-line"></i> Simpan
      </button>
      <button class="btn btn-ghost" data-close>Batalkan</button>
    </div>
  </div>
</div>
