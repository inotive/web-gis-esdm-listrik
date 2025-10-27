{{-- resources/views/admin/user/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', $title . ' - BPKAD')
@section('page-title', $title)

@section('breadcrumb')
<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">Hak Akses</li>
<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{$title}}</li>
@endsection

@push('styles')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  /* ====== Gaya “mirip Data Gardu” ====== */
  .toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px}
  .toolbar .w-search{width:clamp(230px,38vw,340px)}
  .input-group{display:flex;align-items:center;background:#FCFCFD;border:1px solid var(--line);border-radius:10px;overflow:hidden;height:36px}
  .input-group:focus-within{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .input-group-text{display:grid;place-items:center;width:36px;height:100%;color:#94A3B8;background:#F8FAFC;border-right:1px solid var(--line)}
  .form-control,.form-select{height:36px;border:none;background:transparent;padding:0 10px;font:inherit;color:var(--text);outline:none;width:100%}
  .btn-ghost{height:32px;padding:0 10px;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ghost:hover{background:#F8FAFC}

  .table-shell{border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow-1)}
  table.data{width:100%;border-collapse:separate;border-spacing:0}
  table.data thead th{background:#FCFCFD;color:#64748B;font-weight:700;padding:12px 18px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap}
  table.data tbody td{padding:16px 18px;border-bottom:1px solid var(--line);color:#252F4A;vertical-align:middle}
  table.data tbody tr:hover{background:#FAFAFA}

  .col-no{width:70px;text-align:center}
  .col-user{min-width:260px}
  .col-aksi{width:130px;text-align:center}

  .btn-ico{--size:32px;width:var(--size);height:var(--size);display:inline-grid;place-items:center;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ico:hover{background:#F8FAFC}
  .btn-ico.danger{border-color:#FEE2E2;color:#DC2626}
  .btn-ico.danger:hover{background:#FFF5F5}

  .table-footer{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:14px 18px;border-top:1px solid var(--line);background:#fff;border-bottom-left-radius:16px;border-bottom-right-radius:16px}
  .summary{color:var(--text-dim)}
  .show-wrap{display:inline-flex;align-items:center;gap:8px;color:var(--text-dim)}
  .show-wrap .form-select{width:92px}
  .pagination{display:flex;gap:6px;list-style:none;padding:0;margin:0}
  .page-link{min-width:34px;height:34px;padding:0 10px;display:flex;align-items:center;justify-content:center;border:1px solid var(--line);background:#fff;border-radius:8px;text-decoration:none;color:var(--text)}
  .page-link:hover{background:#F8FAFC}
  .page-item.active .page-link{background:var(--active-soft);color:#0F5132;border-color:#B7F7CF;font-weight:700}
  .page-item.disabled .page-link{opacity:.5;pointer-events:none}
</style>
@endpush

@section('content')
  <!-- Header -->
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Data Pengguna</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>{{ now()->translatedFormat('F Y') }}</span></div>
      <button class="btn btn-primary btn-add" data-open="#modalCreateUser"><i class="ri-add-line"></i> Tambah Pengguna</button>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <div class="card-title">Daftar User Login</div>

      <!-- Toolbar: Search -->
      <form id="filterForm" class="toolbar" method="GET" action="#">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" id="userSearch" value="{{ request('q') }}" class="form-control" placeholder="Cari nama / email / username / role..." aria-label="Cari user">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan"><i class="ri-close-line"></i></button>
          @endif
        </div>
        <button type="button" class="btn-ghost" id="btnReset" title="Reset">
          <i class="ri-refresh-line"></i><span class="d-none d-sm-inline"> Reset</span>
        </button>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table id="kt_datatable_dom_positioning" class="data">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th class="col-user">User</th>
              <th>Username</th>
              <th>Hak Akses</th>
              <th>Tanggal</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $value)
              <tr>
                <td class="col-no">{{ $loop->iteration }}</td>
                <td class="col-user">
                  <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-3">
                      @if ($value->image)
                        <img src="{{ asset('storage/profile/' . $value->image) }}" alt="" class="rounded">
                      @else
                        <div class="symbol-label fs-2 fw-bold bg-light-primary text-primary">
                          {{ strtoupper(substr($value->name, 0, 1)) }}
                        </div>
                      @endif
                    </div>
                    <div class="d-flex justify-content-start flex-column">
                      <strong class="text-gray-900">{{ $value->name }}</strong>
                      <span class="text-muted fw-semibold d-block fs-7">{{ $value->email }}</span>
                    </div>
                  </div>
                </td>
                <td><strong>{{ $value->username }}</strong></td>
                <td>
                  @php $roleNames = $value->getRoleNames(); @endphp
                  @if($roleNames && count($roleNames))
                    @foreach ($roleNames as $roleName)
                      <span class="btn-ghost" style="border-radius:999px;padding:4px 10px;border-color:#D7E3FF;color:#2563eb;background:#F5F9FF">
                        <i class="fas fa-shield-alt me-1"></i>{{ $roleName }}
                      </span>
                    @endforeach
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M Y') }}</td>
                <td class="col-aksi">
                  <a href="#" class="btn-ico" title="Edit" data-bs-toggle="modal" data-bs-target="#kt_modal_{{ $value->id }}"><i class="ri-edit-2-line"></i></a>
                  <button data-route="{{ route('admin.hak-akses.user.destroy', $value->id) }}" class="btn-ico danger" title="Hapus" onclick="destroyItem(this)"><i class="ri-delete-bin-6-line"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Footer: info + per-page + pagination -->
        <div class="table-footer">
          <div class="summary" id="dt-info-area">Menampilkan 0–0 dari 0 data</div>

          <div class="show-wrap">
            <span>Show</span>
            <select class="form-select" id="perPageSelect" aria-label="Jumlah baris per halaman">
              @foreach([10,25,50,100,200] as $n)
                <option value="{{ $n }}" {{ (int)request('per_page', 25) === $n ? 'selected' : '' }}>{{ $n }}</option>
              @endforeach
            </select>
            <span>per page</span>
          </div>

          <nav aria-label="Pagination">
            <ul class="pagination" id="dt-paging-area"><!-- render via JS --></ul>
          </nav>
        </div>
      </div>
    </div>
  </section>

  {{-- MODAL CREATE --}}
  @include('admin.user.create')

  {{-- MODALS EDIT (render di luar tabel) --}}
  @foreach ($data as $value)
    @include('admin.user.component.modal', ['value' => $value])
  @endforeach
@endsection

@push('scripts')
<!-- 1) jQuery duluan (tanpa integrity agar tidak diblok) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- 2) DataTables setelah jQuery -->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

<script>
  // ===== Helper form delete =====
  if (typeof window.FormElementHelper === 'undefined') {
    class FormElementHelper {
      constructor(){ this.form=document.createElement('form'); }
      createAttribute(type,name,value){ const i=document.createElement('input'); i.type=type;i.name=name;i.value=value; this.form.appendChild(i); return this; }
      post(action){ this.form.method='POST'; this.form.action=action; this.form.style.display='none'; document.body.appendChild(this.form); this.form.submit(); }
    }
    window.FormElementHelper = FormElementHelper;
  }

  // ===== Modal toggler (open/close) =====
  (function(){
    const openModal = sel => document.querySelector(sel)?.classList.add('show');
    const closeModal = m => m?.classList.remove('show');
    document.addEventListener('click', e=>{
      const opener = e.target.closest('[data-open]');
      if(opener){ e.preventDefault(); openModal(opener.getAttribute('data-open')); }
      if(e.target.hasAttribute('data-close') || e.target.classList.contains('modal-backdrop')){
        closeModal(e.target.closest('.modal') || document.querySelector('.modal.show'));
      }
    });
    document.addEventListener('keydown', e=>{ if(e.key==='Escape') closeModal(document.querySelector('.modal.show')); });
  })();

  // ===== DataTables init =====
  jQuery(function($){
    const dt = $("#kt_datatable_dom_positioning").DataTable({
      language:{
        lengthMenu:"Show _MENU_",
        info:"_START_ - _END_ dari _TOTAL_ data",
        infoEmpty:"Tidak ada data",
        zeroRecords:"Tidak ada data yang cocok",
        paginate:{ previous:"‹", next:"›" }
      },
      dom:"t",
      ordering:false,
      autoWidth:false,
      pageLength: parseInt(document.getElementById('perPageSelect')?.value || 25, 10),
      columnDefs:[
        {targets:0, width:'70px', className:'text-center'},
        {targets:1, width:'auto'},
        {targets:2, width:'150px'},
        {targets:3, width:'220px'},
        {targets:4, width:'125px'},
        {targets:5, width:'130px', className:'text-center'}
      ]
    });

    function renderDtFooter(){
      const info = dt.page.info();
      const infoText = info.recordsTotal
        ? `Menampilkan <strong>${info.start+1}–${info.end}</strong> dari <strong>${info.recordsDisplay}</strong> data`
        : 'Tidak ada data';
      document.getElementById('dt-info-area').innerHTML = infoText;

      const paging = document.getElementById('dt-paging-area');
      const totalPages = info.pages, current = info.page + 1;
      let html = '';

      html += `<li class="page-item ${current===1?'disabled':''}">
                 <a class="page-link" href="#" data-page="${current-2}" aria-label="Sebelumnya"><i class="ri-arrow-left-s-line"></i></a>
               </li>`;

      const start = Math.max(1, current-2);
      const end = Math.min(totalPages, start+4);
      for (let p = start; p <= end; p++) {
        html += `<li class="page-item ${p===current?'active':''}">
                   <a class="page-link" href="#" data-page="${p-1}">${p}</a>
                 </li>`;
      }

      html += `<li class="page-item ${current===totalPages?'disabled':''}">
                 <a class="page-link" href="#" data-page="${current}" aria-label="Berikutnya"><i class="ri-arrow-right-s-line"></i></a>
               </li>`;
      paging.innerHTML = html;

      paging.querySelectorAll('a.page-link').forEach(a=>{
        a.addEventListener('click', e=>{
          e.preventDefault();
          const target = parseInt(a.dataset.page, 10);
          if (!isNaN(target) && target>=0 && target<totalPages) dt.page(target).draw('page');
        });
      });
    }
    dt.on('draw', renderDtFooter);
    renderDtFooter();

    // search
    let t; const searchEl = document.getElementById('userSearch');
    searchEl?.addEventListener('input', ()=>{ clearTimeout(t); t=setTimeout(()=> dt.search(searchEl.value).draw(), 300); });
    document.getElementById('btnClearSearch')?.addEventListener('click', ()=>{ if(searchEl){ searchEl.value=''; dt.search('').draw(); } });
    document.getElementById('btnReset')?.addEventListener('click', ()=>{ if(searchEl){ searchEl.value=''; dt.search('').draw(); } });

    // per page
    document.getElementById('perPageSelect')?.addEventListener('change', e=>{
      dt.page.len(parseInt(e.target.value||25,10)).draw();
    });
  });

  // Delete confirm
  window.destroyItem = (e) => {
    const route = e.getAttribute('data-route');
    Swal.fire({
      title:"Apakah Anda Yakin?",
      html:"<p>Setelah Data Dihapus maka Anda Tidak Akan Bisa Mengembalikan Data Kembali!</p>",
      icon:"warning",
      showCancelButton:true,
      reverseButtons:true,
      confirmButtonColor:'#d33',
      cancelButtonColor:'#3085d6',
      confirmButtonText:'Hapus!',
      cancelButtonText:'Batalkan!'
    }).then((res)=>{
      if(res.isConfirmed){
        (new FormElementHelper)
          .createAttribute('hidden','_token','{{ csrf_token() }}')
          .createAttribute('hidden','_method','DELETE')
          .post(route);
      } else {
        Swal.fire({title:"Aksi Dibatalkan :)", icon:"info"});
      }
    });
  };

  @if (Session::has('pesan'))
    toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}")
  @endif
</script>
@endpush
