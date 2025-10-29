<div id="modalEditPembangkit" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditTitle">
    <div class="modal-header">
      <h3 id="modalEditTitle">Ubah Pembangkit Lokal</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalEditPembangkit')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formEditPembangkit" method="POST" action="#">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="f full">
            <label>Lokasi <span class="muted">(ketik lalu pilih dari saran)</span></label>
            <div class="suggest-wrap">
              <input type="text" id="lokasiInputEdit" name="lokasi" class="input" autocomplete="off"
                     placeholder="Buana Jaya, Tenggarong, Kutai Kartanegara, Kalimantan Timur">
              <div id="suggestBoxEdit" class="suggest-box" style="display:none;"></div>
            </div>
            <input type="hidden" name="wilayah_id" id="wilayah_id_edit">
            <input type="hidden" name="province_id" id="province_id_edit">
            <input type="hidden" name="regency_id"  id="regency_id_edit">
            <input type="hidden" name="district_id" id="district_id_edit">
            <input type="hidden" name="village_id"  id="village_id_edit">
          </div>

          <div class="f">
            <label>Kapasitas Gardu <span class="text-danger">*</span></label>
            <input type="text" id="kapasitas_edit" name="kapasitas_gardu" class="input" placeholder="cth: 250 kVA / 2 MW" required>
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
  // autocomplete EDIT
  (function(){
    const elInput = document.getElementById('lokasiInputEdit');
    const elBox   = document.getElementById('suggestBoxEdit');
    const route   = "{{ route('admin.pembangkit.location.suggest') }}";
    const hid = {
      wilayah:  document.getElementById('wilayah_id_edit'),
      province: document.getElementById('province_id_edit'),
      regency:  document.getElementById('regency_id_edit'),
      district: document.getElementById('district_id_edit'),
      village:  document.getElementById('village_id_edit'),
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
      if(q.length<2){ closeList(); /*jangan clear id saat edit kalau user belum ngetik*/ return; }
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

    // untuk reflow ketika modal baru dibuka
    window.__invalidateSuggestEdit = ()=>{ if(elInput.value.trim().length>=2){ onType(); } };

    document.getElementById('modalEditPembangkit')?.addEventListener('click', e=>{
      if(e.target.id==='modalEditPembangkit') __closeModal('modalEditPembangkit');
    });
  })();
</script>
@endpush
