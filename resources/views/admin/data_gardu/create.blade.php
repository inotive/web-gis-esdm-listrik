@once
@push('styles')
<style>
  .modal{position:fixed;inset:0;display:grid;place-items:center;background:rgba(2,6,23,.36);opacity:0;pointer-events:none;transition:.18s;z-index:1000}
  .modal.show{opacity:1;pointer-events:auto}
  .modal-card{width:min(760px,92vw);background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-2);}
  .modal-hd{display:flex;align-items:center;justify-content:space-between;padding:16px 18px;border-bottom:1px solid var(--line)}
  .modal-ttl{font-weight:700;font-size:18px}
  .modal-x{width:34px;height:34px;border:1px solid #D1FAE5;background:#ECFDF5;border-radius:8px;display:grid;place-items:center;cursor:pointer}
  .modal-bd{padding:18px}
  .form{display:grid;gap:14px}
  .f{display:flex;flex-direction:column;gap:8px}
  .f label{font-size:13px;color:#475569}
  .input,.select,.textarea{height:42px;border:1px solid var(--line);border-radius:10px;background:#FCFCFD;padding:0 12px;font:inherit}
  .textarea{height:100px;resize:vertical;padding:10px 12px}
  .row{display:grid;gap:12px;grid-template-columns:1fr 1fr}
  .modal-ft{padding:16px 18px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:10px}
  .btn-soft{height:40px;padding:0 14px;border:1px solid var(--line);background:#fff;border-radius:10px;cursor:pointer}
  .btn-primary{height:40px;padding:0 16px;border:none;background:var(--accent-2);color:#fff;border-radius:10px;font-weight:700;cursor:pointer}
</style>
@endpush
@push('scripts')
<script>
  // helper global sederhana
  window.__openModal = (id)=>{ const m=document.getElementById(id); if(m) m.classList.add('show'); }
  window.__closeModal = (id)=>{ const m=document.getElementById(id); if(m) m.classList.remove('show'); }
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') document.querySelectorAll('.modal.show').forEach(m=>m.classList.remove('show')); });
</script>
@endpush
@endonce

<div class="modal" id="modalCreateGardu" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="ttlGardu">
    <div class="modal-hd">
      <div id="ttlGardu" class="modal-ttl">Tambah Data Gardu</div>
      <button type="button" class="modal-x" onclick="__closeModal('modalCreateGardu')" aria-label="Tutup">
        <i class="ri-close-line" style="color:#16a34a"></i>
      </button>
    </div>
    <form class="modal-bd form" id="formCreateGardu">
      <div class="f">
        <label>Nama Gardu</label>
        <input class="input" name="nama" placeholder="Contoh: Gardu A-01" required>
      </div>
      <div class="row">
        <div class="f">
          <label>Kapasitas</label>
          <input class="input" name="kapasitas" placeholder="Contoh: 250 kVA" required>
        </div>
        <div class="f">
          <label>Status</label>
          <select class="select" name="status" required>
            <option value="">Pilih Status</option>
            <option>Aktif</option>
            <option>Perawatan</option>
          </select>
        </div>
      </div>
      <div class="f">
        <label>Lokasi (Kecamatan, Kabupaten)</label>
        <input class="input" name="lokasi" placeholder="Kec. A, Kab. A" required>
      </div>
      <div class="f">
        <label>Catatan</label>
        <textarea class="textarea" name="catatan" placeholder="Opsional"></textarea>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn-soft" onclick="__closeModal('modalCreateGardu')">Batal</button>
        <button class="btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const btn = document.querySelector('.page-actions .btn-add');
    if(btn) btn.addEventListener('click', ()=> __openModal('modalCreateGardu'));
    const form = document.getElementById('formCreateGardu');
    form?.addEventListener('submit', (e)=>{
      e.preventDefault();
      console.log('Simpan Gardu (dummy):', Object.fromEntries(new FormData(form)));
      __closeModal('modalCreateGardu');
    });
    document.getElementById('modalCreateGardu')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreateGardu') __closeModal('modalCreateGardu');
    });
  })();
</script>
@endpush
