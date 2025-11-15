{{-- resources/views/admin/pembangkit_lokal/edit.blade.php --}}

<div id="modalEditPembangkit" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditTitle">
    <div class="modal-header">
      <h3 id="modalEditTitle">Ubah Pembangkit Lokal</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalEditPembangkit')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formEditPembangkit" method="POST" action="#">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="f full">
            <label>Wilayah/Lokasi <span class="text-danger">*</span></label>
            <select id="wilayah_id_edit" name="wilayah_id" class="select select-search" required>
              <option value="">Pilih Wilayah</option>
              @foreach($wilayahs as $w)
                <option value="{{ $w['id'] }}">{{ $w['label'] }}</option>
              @endforeach
            </select>
            <span class="muted">Pilih wilayah dari daftar data wilayah yang tersedia</span>
          </div>

          <div class="f">
            <label>Kapasitas Gardu <span class="text-danger">*</span></label>
            <input type="text" id="kapasitas_edit" name="kapasitas_gardu" class="input" placeholder="cth: 250 kVA / 2 MW" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-save" type="submit">Perbarui</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
  document.getElementById('modalEditPembangkit')?.addEventListener('click', e => {
    if (e.target.id === 'modalEditPembangkit') __closeModal('modalEditPembangkit');
  });
})();
</script>
@endpush