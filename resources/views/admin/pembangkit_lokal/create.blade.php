@once
@push('styles')
<style>
  .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
  .modal-overlay.show{display:block;}
  .modal{max-width:680px;margin:20px auto;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-2);overflow:hidden;}
  .modal-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid var(--line);}
  .modal-header h3{margin:0;font-weight:800;font-size:20px;}
  .btn-x{width:36px;height:36px;display:grid;place-items:center;border:1px solid #E2E8F0;background:#fff;border-radius:10px;cursor:pointer;}
  .btn-x:hover{background:#F8FAFC;}
  .modal-body{padding:18px 20px 6px;}
  .modal-footer{padding:14px 20px 18px;}
  .btn-save{width:100%;height:44px;border:none;border-radius:10px;font-weight:700;color:#fff;background:var(--accent-2);box-shadow:0 10px 22px rgba(34,197,94,.22);cursor:pointer;}

  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .form-grid .full{grid-column:1/-1}
  .f{display:flex;flex-direction:column;gap:8px}
  .f label{font-size:13px;color:#475569}
  .input,.select{height:42px;border:1px solid var(--line,#E5E7EB);border-radius:10px;background:#FCFCFD;padding:0 12px;font:inherit;color:#111827}
  .input:focus,.select:focus{outline:none;border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .muted{color:#64748B;font-size:12px}

  .suggest-wrap{position:relative}
  .suggest-box{position:absolute;left:0;right:0;top:100%;margin-top:4px;background:#fff;border:1px solid #E5E7EB;border-radius:10px;box-shadow:0 6px 20px rgba(2,6,23,.08);max-height:280px;overflow:auto;z-index:50}
  .suggest-item{padding:10px 12px;cursor:pointer}
  .suggest-item:hover,.suggest-item.active{background:#F0FDF4}
</style>
@endpush
@push('scripts')
<script>
  // modal helpers
  window.__openModal  = id => { const o = document.getElementById(id); if(o){o.classList.add('show'); document.body.style.overflow='hidden';} };
  window.__closeModal = id => { const o = document.getElementById(id); if(o){o.classList.remove('show'); document.body.style.overflow='';} };
  document.addEventListener('keydown', e => { if(e.key==='Escape') document.querySelectorAll('.modal-overlay.show').forEach(m=>m.classList.remove('show')); });

  // autocomplete CREATE
  (function(){
    const elInput = document.getElementById('lokasiInputCreate');
    const elBox   = document.getElementById('suggestBoxCreate');
    const route   = "{{ route('admin.pembangkit.location.suggest') }}";
    const hid = {
      wilayah:  document.getElementById('wilayah_id_create'),
      province: document.getElementById('province_id_create'),
      regency:  document.getElementById('regency_id_create'),
      district: document.getElementById('district_id_create'),
      village:  document.getElementById('village_id_create'),
    };
    let items=[], activeIndex=-1, lastQuery='';

    const debounce=(fn,ms=250)=>{let t;return(...a)=>{clearTimeout(t);t=setTimeout(()=>fn(...a),ms);};};
    function clearIds(){ hid.wilayah.value=''; hid.province.value=''; hid.regency.value=''; hid.district.value=''; hid.village.value=''; }
    function closeList(){ items=[]; activeIndex=-1; elBox.style.display='none'; elBox.innerHTML=''; }
    function renderList(){
      if(!items.length){ closeList(); return; }
      elBox.innerHTML = items.map((it,i)=>`<div class="suggest-item ${i===activeIndex?'active':''}" data-idx="${i}">${it.label}<div class="muted">${it.type.toUpperCase()}</div></div>`).join('');
      elBox.style.display='block';
      elBox.querySelectorAll('.suggest-item').forEach(el=>el.addEventListener('mousedown', e=>{e.preventDefault();apply(items[+el.dataset.idx]);}));
    }
    function apply(it){
      elInput.value = it.value || it.label;
      hid.wilayah.value  = it.wilayah_id || '';
      hid.province.value = it.ids?.province_id || '';
      hid.regency.value  = it.ids?.regency_id  || '';
      hid.district.value = it.ids?.district_id || '';
      hid.village.value  = it.ids?.village_id  || '';
      closeList();
    }
    async function suggest(q){ const r=await fetch(route+'?q='+encodeURIComponent(q),{headers:{'Accept':'application/json'}}); return r.ok? r.json():[]; }
    const onType = debounce(async ()=>{
      const q = elInput.value.trim();
      if(q.length<2){ closeList(); clearIds(); return; }
      if(q!==lastQuery) clearIds(); lastQuery=q;
      items = await suggest(q); activeIndex=-1; renderList();
    },250);

    elInput?.addEventListener('input', onType);
    elInput?.addEventListener('keydown', e=>{
      if(!['ArrowDown','ArrowUp','Enter','Escape'].includes(e.key)) return;
      if(e.key==='Escape'){ closeList(); return; }
      if(!items.length) return;
      if(e.key==='ArrowDown'){ e.preventDefault(); activeIndex=(activeIndex+1)%items.length; renderList(); }
      if(e.key==='ArrowUp'){ e.preventDefault(); activeIndex=(activeIndex-1+items.length)%items.length; renderList(); }
      if(e.key==='Enter'){ e.preventDefault(); if(activeIndex>=0) apply(items[activeIndex]); else closeList(); }
    });
    document.getElementById('modalCreatePembangkit')?.addEventListener('click', e=>{ if(e.target.id==='modalCreatePembangkit') __closeModal('modalCreatePembangkit'); });
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
            <label>Lokasi <span class="muted">(ketik lalu pilih dari saran)</span></label>
            <div class="suggest-wrap">
              <input type="text" id="lokasiInputCreate" name="lokasi" class="input" autocomplete="off"
                     placeholder="Buana Jaya, Tenggarong, Kutai Kartanegara, Kalimantan Timur">
              <div id="suggestBoxCreate" class="suggest-box" style="display:none;"></div>
            </div>
            <input type="hidden" name="wilayah_id" id="wilayah_id_create">
            <input type="hidden" name="province_id" id="province_id_create">
            <input type="hidden" name="regency_id"  id="regency_id_create">
            <input type="hidden" name="district_id" id="district_id_create">
            <input type="hidden" name="village_id"  id="village_id_create">
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
