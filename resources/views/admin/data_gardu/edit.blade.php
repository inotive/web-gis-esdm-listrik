{{-- resources/views/admin/data_gardu/edit.blade.php --}}

<div id="modalEditGardu" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditGarduTitle">
    <div class="modal-header">
      <h3 id="modalEditGarduTitle">Ubah Gardu</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalEditGardu')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formEditGardu" method="POST" action="#">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="f">
            <label>Nama Gardu <span class="text-danger">*</span></label>
            <input type="text" id="nama_edit" name="nama" class="input" required>
          </div>

          <div class="f">
            <label>Jenis Gardu <span class="text-danger">*</span></label>
            <select id="jenis_edit" name="jenis_gardu_distribusi" class="select" required>
              <option value="">Pilih Jenis</option>
              @foreach($jenisOptions as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
              @endforeach
            </select>
          </div>

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
  document.getElementById('modalEditGardu')?.addEventListener('click', e => {
    if (e.target.id === 'modalEditGardu') __closeModal('modalEditGardu');
  });
})();
</script>
@endpush