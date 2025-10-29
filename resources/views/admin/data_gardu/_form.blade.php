@csrf

@push('styles')
<style>
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .form-grid .full{grid-column:1/-1}
  .f{display:flex;flex-direction:column;gap:8px}
  .f label{font-size:13px;color:#475569}
  .input,.select{height:42px;border:1px solid var(--line,#E5E7EB);border-radius:10px;background:#FCFCFD;padding:0 12px;font:inherit;color:#111827}
  .input:focus,.select:focus{outline:none;border-color:#A7F3D0;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .suggest-wrap{position:relative}
  .suggest-box{position:absolute;left:0;right:0;top:100%;margin-top:4px;background:#fff;border:1px solid #E5E7EB;border-radius:10px;box-shadow:0 6px 20px rgba(2,6,23,.08);max-height:280px;overflow:auto;z-index:50}
  .suggest-item{padding:10px 12px;cursor:pointer}
  .suggest-item:hover,.suggest-item.active{background:#F0FDF4}
  .muted{color:#64748B;font-size:12px}
</style>
@endpush

<div class="form-grid">
  <div class="f">
    <label>Nama Gardu <span class="text-danger">*</span></label>
    <input type="text" name="nama" class="input" value="{{ old('nama', $gardu->nama ?? '') }}" required>
  </div>

  <div class="f">
    <label>Jenis Gardu Distribusi <span class="text-danger">*</span></label>
    <select name="jenis_gardu_distribusi" class="select" required>
      <option value="">Pilih Jenis</option>
      @foreach($jenisOptions as $opt)
        <option value="{{ $opt }}" {{ old('jenis_gardu_distribusi', $gardu->jenis_gardu_distribusi ?? '')===$opt ? 'selected':'' }}>
          {{ $opt }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="f full">
    <label>Lokasi <span class="muted">(ketik untuk mencari, lalu pilih)</span></label>
    <div class="suggest-wrap">
      <input
        type="text"
        id="lokasiInput"
        name="lokasi"
        class="input"
        autocomplete="off"
        placeholder="Buana Jaya, Tenggarong, Kutai Kartanegara, Kalimantan Timur"
        value="{{ old('lokasi', $gardu->lokasi ?? '') }}"
      >
      <div id="suggestBox" class="suggest-box" style="display:none;"></div>
    </div>
    {{-- hidden wilayah ids (diisi saat memilih saran) --}}
    <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id', $gardu->province_id ?? '') }}">
    <input type="hidden" name="regency_id" id="regency_id" value="{{ old('regency_id', $gardu->regency_id ?? '') }}">
    <input type="hidden" name="district_id" id="district_id" value="{{ old('district_id', $gardu->district_id ?? '') }}">
    <input type="hidden" name="village_id" id="village_id" value="{{ old('village_id', $gardu->village_id ?? '') }}">
  </div>
</div>

@push('scripts')
<script>
(function() {
  const elInput   = document.getElementById('lokasiInput');
  const elBox     = document.getElementById('suggestBox');
  const route     = "{{ route('admin.gardu.location.suggest') }}";

  const hid = {
    province: document.getElementById('province_id'),
    regency:  document.getElementById('regency_id'),
    district: document.getElementById('district_id'),
    village:  document.getElementById('village_id'),
  };

  let items = [];
  let activeIndex = -1;
  let lastQuery = '';

  const debounce = (fn, ms=250) => {
    let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); };
  };

  function clearIds() {
    hid.province.value = '';
    hid.regency.value  = '';
    hid.district.value = '';
    hid.village.value  = '';
  }

  function renderList() {
    if (!items.length) {
      elBox.style.display = 'none';
      elBox.innerHTML = '';
      return;
    }
    elBox.innerHTML = items.map((it, idx) => `
      <div class="suggest-item ${idx===activeIndex ? 'active':''}" data-idx="${idx}">
        ${it.label}
        <div class="muted">${it.type.toUpperCase()}</div>
      </div>
    `).join('');
    elBox.style.display = 'block';
    Array.from(elBox.querySelectorAll('.suggest-item')).forEach(el => {
      el.addEventListener('mousedown', (e) => { // mousedown agar sebelum blur
        e.preventDefault();
        const idx = Number(el.getAttribute('data-idx'));
        applyItem(items[idx]);
      });
    });
  }

  function applyItem(it) {
    if (!it) return;
    elInput.value = it.value || it.label;
    hid.province.value = it.ids?.province_id || '';
    hid.regency.value  = it.ids?.regency_id  || '';
    hid.district.value = it.ids?.district_id || '';
    hid.village.value  = it.ids?.village_id  || '';
    closeList();
  }

  function closeList() {
    items = [];
    activeIndex = -1;
    elBox.style.display = 'none';
    elBox.innerHTML = '';
  }

  async function fetchSuggest(q) {
    const url = route + '?q=' + encodeURIComponent(q);
    const res = await fetch(url, { headers: { 'Accept':'application/json' } });
    if (!res.ok) return [];
    return res.json();
  }

  const onType = debounce(async () => {
    const q = elInput.value.trim();
    if (q.length < 2) { closeList(); clearIds(); return; }
    // Jika user mengubah ketikan setelah memilih, hapus id terseleksi agar tidak “nyangkut”
    if (q !== lastQuery) clearIds();
    lastQuery = q;

    items = await fetchSuggest(q);
    activeIndex = -1;
    renderList();
  }, 250);

  elInput.addEventListener('input', onType);

  elInput.addEventListener('keydown', (e) => {
    if (!['ArrowDown','ArrowUp','Enter','Escape'].includes(e.key)) return;
    if (e.key === 'Escape') { closeList(); return; }
    if (!items.length) return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = (activeIndex + 1) % items.length;
      renderList();
      return;
    }
    if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = (activeIndex - 1 + items.length) % items.length;
      renderList();
      return;
    }
    if (e.key === 'Enter') {
      e.preventDefault();
      if (activeIndex >= 0) applyItem(items[activeIndex]);
      else closeList();
    }
  });

  // Tutup ketika blur (beri sedikit delay agar klik ter-register)
  elInput.addEventListener('blur', () => setTimeout(closeList, 120));
})();
</script>
@endpush
