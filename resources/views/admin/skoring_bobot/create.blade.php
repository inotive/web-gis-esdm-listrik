@includeWhen(true,'admin.data_gardu.create')

<div class="modal" id="modalCreateSkoring" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="ttlSkoring">
    <div class="modal-hd">
      <div id="ttlSkoring" class="modal-ttl">Tambah Variabel Skoring</div>
      <button type="button" class="modal-x" onclick="__closeModal('modalCreateSkoring')"><i class="ri-close-line" style="color:#16a34a"></i></button>
    </div>
    <form class="modal-bd form" id="formCreateSkoring">
      <div class="f">
        <label>Nama Variabel</label>
        <input class="input" name="variabel" placeholder="Contoh: Jumlah KK" required>
      </div>
      <div class="row">
        <div class="f">
          <label>Bobot (0 - 1)</label>
          <input class="input" name="bobot" type="number" step="0.01" min="0" max="1" placeholder="0.25" required>
        </div>
        <div class="f">
          <label>Grup/Kategori (opsional)</label>
          <input class="input" name="grup" placeholder="Contoh: Sosial, Teknis">
        </div>
      </div>
      <div class="f">
        <label>Keterangan</label>
        <textarea class="textarea" name="keterangan" placeholder="Penjelasan variabel"></textarea>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-soft" onclick="__closeModal('modalCreateSkoring')">Batal</button>
        <button class="btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btn = document.querySelector('.page-actions .btn-add');
    if(btn) btn.addEventListener('click', ()=> __openModal('modalCreateSkoring'));
    const form = document.getElementById('formCreateSkoring');
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      console.log('Simpan Variabel Skoring (dummy):', Object.fromEntries(new FormData(form)));
      __closeModal('modalCreateSkoring');
    });
    document.getElementById('modalCreateSkoring')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreateSkoring') __closeModal('modalCreateSkoring');
    });
  })();
</script>
@endpush
