@includeWhen(true,'admin.data_gardu.create') {{-- memanfaatkan CSS/JS modal yang sudah @once --}}

<div class="modal" id="modalCreatePembangkit" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="ttlPembangkit">
    <div class="modal-hd">
      <div id="ttlPembangkit" class="modal-ttl">Tambah Pembangkit Lokal</div>
      <button type="button" class="modal-x" onclick="__closeModal('modalCreatePembangkit')" aria-label="Tutup">
        <i class="ri-close-line" style="color:#16a34a"></i>
      </button>
    </div>
    <form class="modal-bd form" id="formCreatePembangkit">
      <div class="f">
        <label>Nama Pembangkit</label>
        <input class="input" name="nama" placeholder="Contoh: PLTD Sungai K" required>
      </div>
      <div class="row">
        <div class="f">
          <label>Tipe</label>
          <select class="select" name="tipe" required>
            <option value="">Pilih Tipe</option>
            <option>PLTD</option>
            <option>PLTS</option>
            <option>PLTMH</option>
          </select>
        </div>
        <div class="f">
          <label>Daya Terpasang</label>
          <input class="input" name="daya" placeholder="Contoh: 2 MW / 250 kWp" required>
        </div>
      </div>
      <div class="row">
        <div class="f">
          <label>Koordinat (Lat,Lng)</label>
          <input class="input" name="koor" placeholder="-0.5, 117.15">
        </div>
        <div class="f">
          <label>Status</label>
          <select class="select" name="status" required>
            <option value="">Pilih Status</option>
            <option>Beroperasi</option>
            <option>Uji Coba</option>
            <option>Perawatan</option>
          </select>
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-soft" onclick="__closeModal('modalCreatePembangkit')">Batal</button>
        <button class="btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btn = document.querySelector('.page-actions .btn-add');
    if(btn) btn.addEventListener('click', ()=> __openModal('modalCreatePembangkit'));
    const form = document.getElementById('formCreatePembangkit');
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      console.log('Simpan Pembangkit (dummy):', Object.fromEntries(new FormData(form)));
      __closeModal('modalCreatePembangkit');
    });
    document.getElementById('modalCreatePembangkit')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreatePembangkit') __closeModal('modalCreatePembangkit');
    });
  })();
</script>
@endpush
