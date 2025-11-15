{{-- resources/views/admin/jalan_akses/create.blade.php --}}
<div id="modalCreateJalan" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateJalanTitle">
    <div class="modal-header">
      <h3 id="modalCreateJalanTitle">Tambah Data Jalan & Akses</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalCreateJalan')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreateJalan" method="POST" action="#">
      @csrf
      <div class="modal-body">
        {{-- Nama Jalan/Akses --}}
        <div class="form-group">
          <label>Nama Jalan/Akses <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="input" placeholder="Contoh: Jalan Poros Utama" required>
          @error('nama')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-grid">
          {{-- Kondisi --}}
          <div class="f">
            <label>Kondisi <span class="text-danger">*</span></label>
            <select name="kondisi" class="select" required>
              <option value="">Pilih Kondisi</option>
              <option value="Baik">Baik</option>
              <option value="Sedang">Sedang</option>
              <option value="Rusak">Rusak</option>
            </select>
            @error('kondisi')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>

          {{-- Panjang --}}
          <div class="f">
            <label>Panjang (km)</label>
            <input type="number" step="0.01" min="0" name="panjang" class="input" placeholder="0.00">
            @error('panjang')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>

        <div class="form-grid">
          {{-- Jenis Akses --}}
          <div class="f">
            <label>Jenis Akses <span class="text-danger">*</span></label>
            <select name="jenis_akses" class="select" required>
              <option value="">Pilih Jenis</option>
              <option value="Darat">Darat</option>
              <option value="Air">Air</option>
              <option value="Udara">Udara</option>
            </select>
            @error('jenis_akses')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>

          {{-- Koordinat --}}
          <div class="f">
            <label>Koordinat (opsional)</label>
            <input type="text" name="koordinat" class="input" placeholder="-0.502000, 117.153000">
            @error('koordinat')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>

        {{-- Catatan --}}
        <div class="form-group">
          <label>Catatan/Keterangan</label>
          <textarea name="catatan" class="textarea" placeholder="Keterangan tambahan (opsional)" rows="3"></textarea>
          @error('catatan')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="__closeModal('modalCreateJalan')">
          <i class="ri-close-line"></i> Batal
        </button>
        <button type="submit" class="btn-save">
          <i class="ri-save-3-line"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btnAdd = document.querySelector('.page-actions .btn-add');
    const form = document.getElementById('formCreateJalan');
    
    // Open modal
    if(btnAdd) {
      btnAdd.addEventListener('click', (e) => {
        e.preventDefault();
        __openModal('modalCreateJalan');
      });
    }
    
    // Form submit (dummy for demonstration)
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      const formData = Object.fromEntries(new FormData(form));
      console.log('Data Jalan yang akan disimpan:', formData);
      
      // Simulasi berhasil
      alert('Data berhasil disimpan! (mode demo)');
      __closeModal('modalCreateJalan');
      form.reset();
      
      // Uncomment untuk production:
      // form.submit();
    });
  })();
</script>
@endpush