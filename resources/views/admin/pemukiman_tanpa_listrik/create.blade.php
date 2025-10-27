@includeWhen(true,'admin.data_gardu.create')

<div class="modal" id="modalCreatePTL" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="ttlPTL">
    <div class="modal-hd">
      <div id="ttlPTL" class="modal-ttl">Tambah Pemukiman Tanpa Listrik</div>
      <button type="button" class="modal-x" onclick="__closeModal('modalCreatePTL')"><i class="ri-close-line" style="color:#16a34a"></i></button>
    </div>
    <form class="modal-bd form" id="formCreatePTL">
      <div class="f">
        <label>Nama Desa</label>
        <input class="input" name="desa" placeholder="Contoh: Long Pelay" required>
      </div>
      <div class="row">
        <div class="f">
          <label>Jumlah KK</label>
          <input class="input" name="kk" type="number" min="0" placeholder="0" required>
        </div>
        <div class="f">
          <label>Koordinat (Lat,Lng)</label>
          <input class="input" name="koor" placeholder="-0.50, 117.15">
        </div>
      </div>
      <div class="f">
        <label>Keterangan</label>
        <textarea class="textarea" name="ket" placeholder="Akses darat 4 jam, dsb."></textarea>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-soft" onclick="__closeModal('modalCreatePTL')">Batal</button>
        <button class="btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btn = document.querySelector('.page-actions .btn-add');
    if(btn) btn.addEventListener('click', ()=> __openModal('modalCreatePTL'));
    const form = document.getElementById('formCreatePTL');
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      console.log('Simpan PTL (dummy):', Object.fromEntries(new FormData(form)));
      __closeModal('modalCreatePTL');
    });
    document.getElementById('modalCreatePTL')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreatePTL') __closeModal('modalCreatePTL');
    });
  })();
</script>
@endpush
