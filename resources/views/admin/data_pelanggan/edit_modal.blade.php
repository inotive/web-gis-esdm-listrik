{{-- Modal: Edit Data Pelanggan --}}
<div class="modal" id="modalEditPelanggan" aria-hidden="true" role="dialog" aria-labelledby="modalEditPelangganTitle">
  <div class="modal-panel" role="document">
    <div class="modal-head">
      <h3 id="modalEditPelangganTitle">Edit Data Pelanggan</h3>
      <button type="button" class="btn-x" aria-label="Tutup" data-close-edit>
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formEditPelanggan" action="" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">

        <label class="field">
          <span class="label">Tipe Pelanggan</span>
          <div class="control">
            <i class="ri-user-3-line"></i>
            <select name="tipe" id="edit_tipe" required>
              <option value="" disabled>Pilih Tipe</option>
              <option value="Rumah Tangga">Rumah Tangga</option>
              <option value="Bisnis">Bisnis</option>
              <option value="Industri">Industri</option>
              <option value="Pemerintah">Pemerintah</option>
              <option value="Sosial">Sosial</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
        </label>

        <label class="field">
          <span class="label">Jumlah</span>
          <div class="control">
            <i class="ri-hashtag"></i>
            <input type="number" name="jumlah" id="edit_jumlah" min="0" step="1" placeholder="0" required>
          </div>
        </label>

        <label class="field">
          <span class="label">Daya Tersambung</span>
          <div class="control split">
            <i class="ri-flashlight-line"></i>
            <input type="number" name="daya_val" id="edit_daya_val" min="0" step="0.01" placeholder="Masukkan angka" required>
            <div class="unit">
              <select name="daya_unit" id="edit_daya_unit" aria-label="Satuan daya">
                <option value="VA">VA</option>
                <option value="kVA">kVA</option>
                <option value="MVA">MVA</option>
              </select>
            </div>
          </div>
        </label>

        <label class="field">
          <span class="label">Keterangan (opsional)</span>
          <div class="control textarea">
            <textarea name="ket" id="edit_ket" rows="3" placeholder="Catatan tambahan..."></textarea>
          </div>
        </label>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" data-close-edit>Batal</button>
        <button type="submit" class="btn-save">
          <i class="ri-check-line"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
