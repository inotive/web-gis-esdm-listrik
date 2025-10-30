<div id="modalEditInfra" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditInfraTitle">
    <div class="modal-header">
      <h3 id="modalEditInfraTitle">Ubah Infrastruktur</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalEditInfra')" aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <form id="formEditInfra" method="POST" action="#">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="f">
            <label>Jaringan <span class="text-danger">*</span></label>
            <select id="jaringan_edit" name="jaringan" class="select" required>
              <option value="">Pilih</option>
              <option value="distribusi">Distribusi</option>
              <option value="transmisi">Transmisi</option>
            </select>
            @error('jaringan')<small class="text-danger">{{ $message }}</small>@enderror
          </div>

          <div class="f">
            <label>Jenis <span class="text-danger">*</span></label>
            <input id="jenis_edit" type="text" name="jenis" class="input" required>
            @error('jenis')<small class="text-danger">{{ $message }}</small>@enderror
          </div>

          <div class="f full">
            <label>Panjang Jaringan (km) <span class="text-danger">*</span></label>
            <input id="panjang_edit" type="number" step="0.01" min="0" name="panjang_jaringan" class="input" required>
            @error('panjang_jaringan')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn-save" type="submit"><i class="ri-save-3-line"></i> Perbarui</button>
      </div>
    </form>
  </div>
</div>
