{{-- resources/views/admin/data_gardu/create.blade.php --}}

@once
@push('styles')
<style>
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .form-grid .full{grid-column:1/-1}
  .f{display:flex;flex-direction:column;gap:8px}
  .f label{font-size:13px;color:#475569}
  .input,.select{height:42px;border:1px solid var(--line,#E5E7EB);border-radius:10px;background:#FCFCFD;padding:0 12px;font:inherit;color:#111827}
  .input:focus,.select:focus{outline:none;border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .muted{color:#64748B;font-size:12px}
  .select-search{width:100%;max-height:250px;overflow-y:auto;}
</style>
@endpush
@push('scripts')
<script>
(function(){
  // Close modal by backdrop
  document.getElementById('modalCreateGardu')?.addEventListener('click', e => {
    if (e.target.id === 'modalCreateGardu') __closeModal('modalCreateGardu');
  });
})();
</script>
@endpush
@endonce

<div id="modalCreateGardu" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateGarduTitle">
    <div class="modal-header">
      <h3 id="modalCreateGarduTitle">Tambah Gardu</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalCreateGardu')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreateGardu" method="POST" action="{{ route('admin.gardu.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="f">
            <label>Nama Gardu <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="input" placeholder="cth: Gd. Distribusi 25/400" required>
          </div>

          <div class="f">
            <label>Jenis Gardu <span class="text-danger">*</span></label>
            <select name="jenis_gardu_distribusi" class="select" required>
              <option value="">Pilih Jenis</option>
              @foreach($jenisOptions as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
              @endforeach
            </select>
          </div>

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
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-save" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>