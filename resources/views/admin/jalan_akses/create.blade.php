@includeWhen(true,'admin.data_gardu.create')

<div class="modal" id="modalCreateJalan" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="ttlJalan">
    <div class="modal-hd">
      <div id="ttlJalan" class="modal-ttl">Tambah Data Jalan & Akses</div>
      <button type="button" class="modal-x" onclick="__closeModal('modalCreateJalan')"><i class="ri-close-line" style="color:#16a34a"></i></button>
    </div>
    <form class="modal-bd form" id="formCreateJalan">
      <div class="f">
        <label>Nama Jalan/Akses</label>
        <input class="input" name="nama" placeholder="Contoh: Jalan Poros Utama" required>
      </div>
      <div class="row">
        <div class="f">
          <label>Kondisi</label>
          <select class="select" name="kondisi" required>
            <option value="">Pilih</option>
            <option>Baik</option>
            <option>Sedang</option>
            <option>Rusak</option>
          </select>
        </div>
        <div class="f">
          <label>Panjang (km)</label>
          <input class="input" name="panjang" type="number" step="0.01" placeholder="0.00">
        </div>
      </div>
      <div class="row">
        <div class="f">
          <label>Jenis Akses</label>
          <select class="select" name="akses" required>
            <option value="">Pilih</option>
            <option>Darat</option>
            <option>Air</option>
            <option>Udara</option>
          </select>
        </div>
        <div class="f">
          <label>Koordinat (opsional)</label>
          <input class="input" name="koor" placeholder="-0.50, 117.15">
        </div>
      </div>
      <div class="f">
        <label>Catatan</label>
        <textarea class="textarea" name="catatan" placeholder="Keterangan tambahan"></textarea>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-soft" onclick="__closeModal('modalCreateJalan')">Batal</button>
        <button class="btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btn = document.querySelector('.page-actions .btn-add');
    if(btn) btn.addEventListener('click', ()=> __openModal('modalCreateJalan'));
    const form = document.getElementById('formCreateJalan');
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      console.log('Simpan Jalan (dummy):', Object.fromEntries(new FormData(form)));
      __closeModal('modalCreateJalan');
    });
    document.getElementById('modalCreateJalan')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreateJalan') __closeModal('modalCreateJalan');
    });
  })();
</script>
@endpush
