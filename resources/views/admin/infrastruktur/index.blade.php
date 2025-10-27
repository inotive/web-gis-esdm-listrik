@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Infrastruktur Jaringan')

@push('styles')
<style>
  /* ===== Toolbar ===== */
  .toolbar { display:flex; flex-wrap:wrap; align-items:center; gap:8px 10px; }
  .toolbar .w-search { width: clamp(230px, 38vw, 360px); }
  .toolbar .w-filter { width: clamp(180px, 26vw, 240px); }

  .input-group {
    display:flex; align-items:center; background:#FCFCFD; border:1px solid var(--line);
    border-radius:10px; overflow:hidden; height:36px;
  }
  .input-group:focus-within { border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12); }
  .input-group-text {
    display:grid; place-items:center; width:36px; height:100%; color:#94A3B8; background:#F8FAFC; border-right:1px solid var(--line);
  }
  .form-control, .form-select {
    height:36px; border:none; background:transparent; padding:0 10px; font: inherit; color: var(--text);
    outline:none; width:100%;
  }
  .btn-ghost { height:32px; padding:0 10px; border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ghost:hover { background:#F8FAFC; }

  /* ===== Table shell ===== */
  .table-shell { border: 1px solid var(--line); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-1); }
  .table-esdm { width:100%; border-collapse:separate; border-spacing:0; }
  .table-esdm thead th {
    background:#FCFCFD; color:#64748B; font-weight:700; padding:12px 18px; text-align:left;
    border-bottom:1px solid var(--line); white-space:nowrap;
  }
  .table-esdm tbody td {
    padding:14px 18px; border-bottom:1px solid var(--line); color:#252F4A; vertical-align:middle;
  }
  .table-esdm tbody tr:hover { background:#FAFAFA; }

  .col-no{ width:70px; text-align:center; }
  .col-aksi{ width:130px; text-align:center; }
  .btn-ico { --size:32px; width:var(--size); height:var(--size); display:inline-grid; place-items:center;
    border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ico:hover{ background:#F8FAFC; }
  .btn-ico.danger { border-color:#FEE2E2; color:#DC2626; }
  .btn-ico.danger:hover { background:#FFF5F5; }

  .table-footer{
    display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px;
    padding:14px 18px; border-top:1px solid var(--line); background:#fff; border-bottom-left-radius:16px; border-bottom-right-radius:16px;
  }
  .summary{ color:var(--text-dim); }
  .show-wrap{ display:inline-flex; align-items:center; gap:8px; color:var(--text-dim); }
  .show-wrap .form-select { width:92px; }

  .pagination { display:flex; gap:6px; list-style:none; padding:0; margin:0; }
  .page-link { min-width:34px; height:34px; padding:0 10px; display:flex; align-items:center; justify-content:center;
    border:1px solid var(--line); background:#fff; border-radius:8px; text-decoration:none; color:var(--text); }
  .page-link:hover { background:#F8FAFC; }
  .page-item.active .page-link { background:var(--active-soft); color:#0F5132; border-color:#B7F7CF; font-weight:700; }

  /* ===== Modal ===== */
  .modal { position:fixed; inset:0; display:none; align-items:center; justify-content:center; z-index:100; }
  .modal.show { display:flex; }
  .modal-backdrop { position:absolute; inset:0; background:rgba(15,23,42,.4); }
  .modal-card {
    position:relative; width:min(920px, 96vw); background:#fff; border-radius:16px; border:1px solid var(--line);
    box-shadow:0 20px 60px rgba(2,6,23,.18); overflow:hidden;
  }
  .modal-head { padding:14px 16px; border-bottom:1px solid var(--line); display:flex; align-items:center; justify-content:space-between; }
  .modal-title { font-weight:700; }
  .modal-body { padding:16px; }
  .modal-actions { display:flex; justify-content:flex-end; gap:10px; padding:14px 16px; border-top:1px solid var(--line); }

  .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
  .form-row { display:flex; flex-direction:column; gap:6px; }
  .label { font-size:13px; color:#475569; }
  .input, .select, .textarea {
    width:100%; border:1px solid #E2E8F0; border-radius:10px; padding:10px 12px; background:#FCFCFD; outline:none;
  }
  .textarea { min-height:90px; resize: vertical; }
  .map-shell { height:300px; border:1px solid #E2E8F0; border-radius:12px; overflow:hidden; }

  @media (max-width:780px){ .grid-2 { grid-template-columns:1fr; } }
</style>

{{-- Leaflet CSS untuk peta modal --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-o9N1j7kGStb0v7cG3G3bZ6bSDo5Cw3tC1u1b2H0wM0A=" crossorigin="anonymous" />
@endpush

@section('content')
  <!-- Header halaman -->
  <div class="page-head">
    <div>
      <div class="page-meta">Selasa, 22 September 2025</div>
      <div class="page-title">Data Infrastruktur Jaringan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>September 2025</span></div>

      {{-- Modal Create --}}
      @include('admin.infrastruktur.create')

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Infrastruktur
      </button>
    </div>
  </div>

  <!-- Kartu: Tabel -->
  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      

      <!-- Toolbar -->
      <form id="infraFilterForm" class="toolbar" method="GET" action="#">
        <!-- Search -->
        <div class="input-group w-search">
          <span class="input-group-text" id="search-addon"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari Nama/Kode Infrastruktur..." aria-label="Cari" aria-describedby="search-addon">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnInfraClear" title="Bersihkan">
              <i class="ri-close-line"></i>
            </button>
          @endif
        </div>

        <!-- Filter: Jenis -->
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-filter-3-line"></i></span>
          <select class="form-select auto-submit" name="jenis">
            <option value="">Semua Jenis</option>
            <option value="jtm" {{ request('jenis')==='jtm' ? 'selected':'' }}>JTM</option>
            <option value="jtr" {{ request('jenis')==='jtr' ? 'selected':'' }}>JTR</option>
            <option value="gardu" {{ request('jenis')==='gardu' ? 'selected':'' }}>Gardu</option>
            <option value="trafo" {{ request('jenis')==='trafo' ? 'selected':'' }}>Trafo</option>
          </select>
        </div>

        <!-- Filter: Kondisi -->
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-tools-line"></i></span>
          <select class="form-select auto-submit" name="kondisi">
            <option value="">Semua Kondisi</option>
            <option value="baik" {{ request('kondisi')==='baik' ? 'selected':'' }}>Baik</option>
            <option value="sedang" {{ request('kondisi')==='sedang' ? 'selected':'' }}>Sedang</option>
            <option value="rusak" {{ request('kondisi')==='rusak' ? 'selected':'' }}>Rusak</option>
          </select>
        </div>

        <button type="button" class="btn-ghost" id="btnInfraReset" title="Reset filter">
          <i class="ri-refresh-line"></i><span class="d-none d-sm-inline"> Reset</span>
        </button>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-esdm">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Kode Aset</th>
              <th>Nama Aset</th>
              <th>Jenis</th>
              <th>Koordinat</th>
              <th>Kondisi</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $rows = [
              ['AS-001','JTM 20kV Segmen A','JTM','-0.5021, 117.1532','Baik'],
              ['AS-002','JTR 380V RT 03','JTR','-0.5039, 117.1510','Sedang'],
              ['AS-003','Gardu Trafo #12','Gardu','-0.5054, 117.1498','Baik'],
              ['AS-004','Trafo Distribusi 250 kVA','Trafo','-0.5092, 117.1481','Rusak'],
              ['AS-005','JTR 380V RT 05','JTR','-0.5110, 117.1520','Baik'],
              ['AS-006','JTM 20kV Segmen B','JTM','-0.5162, 117.1545','Sedang'],
            ]; @endphp

            @foreach ($rows as $i => $r)
              <tr>
                <td class="col-no">{{ $i+1 }}</td>
                <td><strong>{{ $r[0] }}</strong></td>
                <td>{{ $r[1] }}</td>
                <td>{{ $r[2] }}</td>
                <td>{{ $r[3] }}</td>
                <td>{{ $r[4] }}</td>
                <td class="col-aksi">
                  <a href="#" class="btn-ico" title="Pengaturan"><i class="ri-settings-3-line"></i></a>
                  <button type="button" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Footer table -->
        <div class="table-footer">
          <div class="summary">Menampilkan <strong>1–6</strong> dari <strong>6</strong> infrastruktur</div>

          <div class="show-wrap">
            <span>Show</span>
            <form id="infraPerPageForm" method="GET" action="#">
              <input type="hidden" name="q" value="{{ request('q') }}">
              <input type="hidden" name="jenis" value="{{ request('jenis') }}">
              <input type="hidden" name="kondisi" value="{{ request('kondisi') }}">
              <select class="form-select auto-submit" name="per_page" aria-label="Jumlah baris per halaman">
                @foreach([5,10,25,50,100] as $pp)
                  <option value="{{ $pp }}" {{ (string)request('per_page','10')===(string)$pp ? 'selected':'' }}>{{ $pp }}</option>
                @endforeach
              </select>
            </form>
            <span>per page</span>
          </div>

          <nav aria-label="Pagination">
            <ul class="pagination">
              <li class="page-item disabled"><span class="page-link" aria-label="Sebelumnya"><i class="ri-arrow-left-s-line"></i></span></li>
              <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#" aria-label="Berikutnya"><i class="ri-arrow-right-s-line"></i></a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
{{-- Leaflet JS untuk peta modal --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-o9N1j7kGStb0v7cG3G3bZ6bSDo5Cw3tC1u1b2H0wM0A=" crossorigin="anonymous"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const f = document.getElementById('infraFilterForm');
    const per = document.getElementById('infraPerPageForm');

    // auto-submit untuk select
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => {
        if (per && per.contains(el)) per.submit(); else if (f) f.submit();
      });
    });

    // clear search
    const btnClear = document.getElementById('btnInfraClear');
    if (btnClear && f) {
      btnClear.addEventListener('click', () => {
        const input = f.querySelector('input[name="q"]');
        if (input) input.value = '';
        f.submit();
      });
    }

    // reset filter
    const btnReset = document.getElementById('btnInfraReset');
    if (btnReset && f) {
      btnReset.addEventListener('click', () => {
        f.reset();
        const q = f.querySelector('input[name="q"]');
        if (q) q.value = '';
        f.submit();
      });
    }

    // Modal open/close
    const modal = document.getElementById('modalInfra');
    const btnOpen = document.querySelector('.btn-add');
    const btnClose = modal?.querySelectorAll('[data-close]');
    const overlay = modal?.querySelector('.modal-backdrop');

    function openModal(){ modal?.classList.add('show'); setTimeout(initInfraMap, 50); }
    function closeModal(){ modal?.classList.remove('show'); }

    btnOpen?.addEventListener('click', openModal);
    overlay?.addEventListener('click', closeModal);
    btnClose?.forEach(b => b.addEventListener('click', closeModal));

    // Leaflet Map init
    let _infraMap;
    function initInfraMap(){
      if (_infraMap) return;
      const el = document.getElementById('infraMap');
      if (!el) return;
      _infraMap = L.map(el).setView([-0.5021, 117.1532], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, attribution: '&copy; OpenStreetMap'
      }).addTo(_infraMap);

      // marker draggable
      const latInput = document.getElementById('infraLat');
      const lngInput = document.getElementById('infraLng');
      const marker = L.marker(_infraMap.getCenter(), { draggable:true }).addTo(_infraMap);

      function syncInputs(latlng){
        latInput.value = latlng.lat.toFixed(6);
        lngInput.value = latlng.lng.toFixed(6);
      }
      syncInputs(marker.getLatLng());

      marker.on('dragend', () => syncInputs(marker.getLatLng()));
      _infraMap.on('click', (e) => { marker.setLatLng(e.latlng); syncInputs(e.latlng); });
    }
  });
</script>
@endpush
