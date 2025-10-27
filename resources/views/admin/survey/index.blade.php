@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Hasil Survei Lapangan')

@push('styles')
<style>
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

  /* Modal */
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
  .textarea { min-height:90px; resize:vertical; }
  .map-shell { height:300px; border:1px solid #E2E8F0; border-radius:12px; overflow:hidden; }

  @media (max-width:780px){ .grid-2 { grid-template-columns:1fr; } }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-o9N1j7kGStb0v7cG3G3bZ6bSDo5Cw3tC1u1b2H0wM0A=" crossorigin="anonymous" />
@endpush

@section('content')
  <!-- Header -->
  <div class="page-head">
    <div>
      <div class="page-meta">Selasa, 22 September 2025</div>
      <div class="page-title">Hasil Survei Lapangan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>September 2025</span></div>

      {{-- Modal Create --}}
      @include('admin.survey.create')

      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Hasil Survei
      </button>
    </div>
  </div>

  <!-- Kartu -->
  <section class="card" style="margin-top:18px;">
    <div class="card-header">
     

      <form id="surveyFilterForm" class="toolbar" method="GET" action="#">
        <!-- Search -->
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari lokasi / desa..." aria-label="Cari lokasi">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnSurveyClear" title="Bersihkan">
              <i class="ri-close-line"></i>
            </button>
          @endif
        </div>

        <!-- Filter tanggal -->
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-calendar-line"></i></span>
          <input class="form-control auto-submit" type="date" name="tgl" value="{{ request('tgl') }}" aria-label="Tanggal">
        </div>

        <!-- Petugas -->
        <div class="input-group w-filter">
          <span class="input-group-text"><i class="ri-user-line"></i></span>
          <select class="form-select auto-submit" name="petugas">
            <option value="">Semua Petugas</option>
            <option value="eko" {{ request('petugas')==='eko' ? 'selected':'' }}>Eko</option>
            <option value="sari" {{ request('petugas')==='sari' ? 'selected':'' }}>Sari</option>
            <option value="budi" {{ request('petugas')==='budi' ? 'selected':'' }}>Budi</option>
          </select>
        </div>

        <button type="button" class="btn-ghost" id="btnSurveyReset" title="Reset filter">
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
              <th>Tanggal</th>
              <th>Lokasi/Desa</th>
              <th>Koordinat</th>
              <th>Petugas</th>
              <th>Temuan</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $rows = [
              ['2025-09-02','Muara Lesan','-0.5042, 117.1501','Eko','Calon pelanggan 42 KK, akses sulit.'],
              ['2025-09-04','Long Duhung','-0.5111, 117.1462','Sari','Butuh trafo 160 kVA.'],
              ['2025-09-07','Sinduung Indah','-0.4980, 117.1609','Budi','JTR perlu peremajaan.'],
              ['2025-09-10','Long Pelay','-0.5150, 117.1401','Eko','Gardu eksisting rusak ringan.'],
            ]; @endphp

            @foreach ($rows as $i => $r)
              <tr>
                <td class="col-no">{{ $i+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($r[0])->format('d M Y') }}</td>
                <td><strong>{{ $r[1] }}</strong></td>
                <td>{{ $r[2] }}</td>
                <td>{{ $r[3] }}</td>
                <td>{{ $r[4] }}</td>
                <td class="col-aksi">
                  <a href="#" class="btn-ico" title="Detail"><i class="ri-eye-line"></i></a>
                  <button type="button" class="btn-ico danger" title="Hapus"><i class="ri-delete-bin-6-line"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan <strong>1–4</strong> dari <strong>4</strong> survei</div>

          <div class="show-wrap">
            <span>Show</span>
            <form id="surveyPerPageForm" method="GET" action="#">
              <input type="hidden" name="q" value="{{ request('q') }}">
              <input type="hidden" name="tgl" value="{{ request('tgl') }}">
              <input type="hidden" name="petugas" value="{{ request('petugas') }}">
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-o9N1j7kGStb0v7cG3G3bZ6bSDo5Cw3tC1u1b2H0wM0A=" crossorigin="anonymous"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const f = document.getElementById('surveyFilterForm');
    const per = document.getElementById('surveyPerPageForm');

    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => { if (per && per.contains(el)) per.submit(); else if (f) f.submit(); });
    });

    const btnClear = document.getElementById('btnSurveyClear');
    if (btnClear && f) {
      btnClear.addEventListener('click', () => {
        const input = f.querySelector('input[name="q"]');
        if (input) input.value = '';
        f.submit();
      });
    }

    const btnReset = document.getElementById('btnSurveyReset');
    if (btnReset && f) {
      btnReset.addEventListener('click', () => {
        f.reset();
        const q = f.querySelector('input[name="q"]');
        if (q) q.value = '';
        f.submit();
      });
    }

    // Modal open/close
    const modal = document.getElementById('modalSurvey');
    const btnOpen = document.querySelector('.btn-add');
    const overlay = modal?.querySelector('.modal-backdrop');
    const btnClose = modal?.querySelectorAll('[data-close]');

    function openModal(){ modal?.classList.add('show'); setTimeout(initSurveyMap, 50); }
    function closeModal(){ modal?.classList.remove('show'); }

    btnOpen?.addEventListener('click', openModal);
    overlay?.addEventListener('click', closeModal);
    btnClose?.forEach(b => b.addEventListener('click', closeModal));

    // Leaflet Map
    let _surveyMap;
    function initSurveyMap(){
      if (_surveyMap) return;
      const el = document.getElementById('surveyMap');
      if (!el) return;
      _surveyMap = L.map(el).setView([-0.5042, 117.1501], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, attribution: '&copy; OpenStreetMap'
      }).addTo(_surveyMap);

      const latInput = document.getElementById('surveyLat');
      const lngInput = document.getElementById('surveyLng');
      const marker = L.marker(_surveyMap.getCenter(), { draggable:true }).addTo(_surveyMap);

      function syncInputs(latlng){
        latInput.value = latlng.lat.toFixed(6);
        lngInput.value = latlng.lng.toFixed(6);
      }
      syncInputs(marker.getLatLng());
      marker.on('dragend', () => syncInputs(marker.getLatLng()));
      _surveyMap.on('click', (e) => { marker.setLatLng(e.latlng); syncInputs(e.latlng); });
    }
  });
</script>
@endpush
