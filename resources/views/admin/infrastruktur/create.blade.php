<div id="modalCreateInfra" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateInfraTitle">
    <div class="modal-header">
      <h3 id="modalCreateInfraTitle">Tambah Infrastruktur</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalCreateInfra')" aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <form id="formCreateInfra" method="POST" action="{{ route('admin.infrastruktur.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="f">
            <label>Jaringan <span class="text-danger">*</span></label>
            <select name="jaringan" class="select" required>
              <option value="">Pilih</option>
              <option value="distribusi" {{ old('jaringan')==='distribusi'?'selected':'' }}>Distribusi</option>
              <option value="transmisi" {{ old('jaringan')==='transmisi'?'selected':'' }}>Transmisi</option>
            </select>
            @error('jaringan')<small class="text-danger">{{ $message }}</small>@enderror
          </div>

          <div class="f">
            <label>Jenis <span class="text-danger">*</span></label>
            <input type="text" name="jenis" value="{{ old('jenis') }}" class="input" placeholder="cth: JTM / JTR / Gardu / Trafo" required>
            @error('jenis')<small class="text-danger">{{ $message }}</small>@enderror
          </div>

          <div class="f full">
            <label>Panjang Jaringan (km) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="panjang_jaringan" value="{{ old('panjang_jaringan') }}" class="input" placeholder="cth: 12.50" required>
            @error('panjang_jaringan')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn-save" type="submit"><i class="ri-save-3-line"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>
