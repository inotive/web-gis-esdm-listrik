{{-- resources/views/admin/pembangkit_lokal/create.blade.php --}}

@once
@push('scripts')
<script>
(function(){
  document.getElementById('modalCreatePembangkit')?.addEventListener('click', e => {
    if (e.target.id === 'modalCreatePembangkit') __closeModal('modalCreatePembangkit');
  });
})();
</script>
@endpush
@endonce

<div id="modalCreatePembangkit" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateTitle">
    <div class="modal-header">
      <h3 id="modalCreateTitle">Tambah Pembangkit Lokal</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalCreatePembangkit')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreatePembangkit" method="POST" action="{{ route('admin.pembangkit.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="f full">
            <label>Wilayah/Lokasi <span class="text-danger">*</span></label>
            <select name="wilayah_id" class="select select-search" required>
              <option value="">Pilih Wilayah</option>
              @foreach($wilayahs as $w)
                <option value="{{ $w['id'] }}">{{ $w['label'] }}</option>
              @endforeach
            </select>
            <span class="muted">Pilih wilayah dari daftar data wilayah yang tersedia</span>
          </div>

          <div class="f">
            <label>Kapasitas Gardu <span class="text-danger">*</span></label>
            <input type="text" name="kapasitas_gardu" class="input" placeholder="cth: 250 kVA / 2 MW" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-save" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>