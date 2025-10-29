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
  .suggest-wrap{position:relative}
  .suggest-box{position:absolute;left:0;right:0;top:100%;margin-top:4px;background:#fff;border:1px solid #E5E7EB;border-radius:10px;box-shadow:0 6px 20px rgba(2,6,23,.08);max-height:280px;overflow:auto;z-index:50}
  .suggest-item{padding:10px 12px;cursor:pointer}
  .suggest-item:hover,.suggest-item.active{background:#F0FDF4}
</style>
@endpush
@push('scripts')
<script>
(function(){
  const route = "{{ route('admin.gardu.location.suggest') }}";
  const debounce=(fn,ms=250)=>{let t;return(...a)=>{clearTimeout(t);t=setTimeout(()=>fn(...a),ms);}};

  function initSuggest(namespace){
    const elInput = document.getElementById(`lokasiInput${namespace}`);
    const elBox   = document.getElementById(`suggestBox${namespace}`);
    const hid = {
      wilayah:  document.getElementById(`wilayah_id${namespace}`),
      province: document.getElementById(`province_id${namespace}`),
      regency:  document.getElementById(`regency_id${namespace}`),
      district: document.getElementById(`district_id${namespace}`),
      village:  document.getElementById(`village_id${namespace}`),
    };
    let items=[], activeIndex=-1, lastQuery='';
    function clearIds(){ hid.wilayah.value='';hid.province.value='';hid.regency.value='';hid.district.value='';hid.village.value=''; }
    function closeList(){ items=[]; activeIndex=-1; elBox.style.display='none'; elBox.innerHTML=''; }
    function render(){ if(!items.length){ closeList(); return; }
      elBox.innerHTML = items.map((it,i)=>`<div class="suggest-item ${i===activeIndex?'active':''}" data-i="${i}">${it.label}<div class="muted">${it.type.toUpperCase()}</div></div>`).join('');
      elBox.style.display='block';
      elBox.querySelectorAll('.suggest-item').forEach(el=>el.addEventListener('mousedown', e=>{e.preventDefault();apply(items[+el.dataset.i]);}));
    }
    function apply(it){ elInput.value=it.value||it.label;
      hid.wilayah.value=it.wilayah_id||''; hid.province.value=it.ids?.province_id||''; hid.regency.value=it.ids?.regency_id||''; hid.district.value=it.ids?.district_id||''; hid.village.value=it.ids?.village_id||'';
      closeList();
    }
    async function get(q){ const r=await fetch(route+'?q='+encodeURIComponent(q),{headers:{'Accept':'application/json'}}); return r.ok? r.json():[]; }
    const onType = debounce(async ()=>{
      const q = elInput.value.trim();
      if(q.length<2){ closeList(); clearIds(); return; }
      if(q!==lastQuery) clearIds(); lastQuery=q;
      items = await get(q); activeIndex=-1; render();
    }, 250);
    elInput?.addEventListener('input', onType);
    elInput?.addEventListener('keydown', e=>{
      if(!['ArrowDown','ArrowUp','Enter','Escape'].includes(e.key)) return;
      if(e.key==='Escape'){ closeList(); return; }
      if(!items.length) return;
      if(e.key==='ArrowDown'){ e.preventDefault(); activeIndex=(activeIndex+1)%items.length; render(); }
      if(e.key==='ArrowUp'){ e.preventDefault(); activeIndex=(activeIndex-1+items.length)%items.length; render(); }
      if(e.key==='Enter'){ e.preventDefault(); if(activeIndex>=0) apply(items[activeIndex]); else closeList(); }
    });
  }

  // init for CREATE
  initSuggest('Create');

  // close by backdrop
  document.getElementById('modalCreateGardu')?.addEventListener('click', e=>{ if(e.target.id==='modalCreateGardu') __closeModal('modalCreateGardu'); });
})();
</script>
@endpush
@endonce

<div id="modalCreateGardu" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateGarduTitle">
    <div class="modal-header">
      <h3 id="modalCreateGarduTitle">Tambah Gardu</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalCreateGardu')" aria-label="Tutup"><i class="ri-close-line"></i></button>
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
            <label>Lokasi <span class="muted">(ketik lalu pilih dari saran)</span></label>
            <div class="suggest-wrap">
              <input type="text" id="lokasiInputCreate" name="lokasi" class="input" autocomplete="off"
                     placeholder="Buana Jaya, Tenggarong, Kutai Kartanegara, Kalimantan Timur">
              <div id="suggestBoxCreate" class="suggest-box" style="display:none;"></div>
            </div>
            <input type="hidden" name="wilayah_id" id="wilayah_idCreate">
            <input type="hidden" name="province_id" id="province_idCreate">
            <input type="hidden" name="regency_id"  id="regency_idCreate">
            <input type="hidden" name="district_id" id="district_idCreate">
            <input type="hidden" name="village_id"  id="village_idCreate">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-save" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>
