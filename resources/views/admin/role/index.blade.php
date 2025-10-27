{{-- resources/views/admin/role/index.blade.php --}}
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
  /* ===== Toolbar & inputs (mengikuti gaya Data Gardu) ===== */
  .toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px}
  .toolbar .w-search{width:clamp(230px,38vw,340px)}
  .toolbar .w-filter{width:clamp(180px,26vw,230px)}
  .input-group{display:flex;align-items:center;background:#FCFCFD;border:1px solid var(--line);border-radius:10px;overflow:hidden;height:36px}
  .input-group:focus-within{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .input-group-text{display:grid;place-items:center;width:36px;height:100%;color:#94A3B8;background:#F8FAFC;border-right:1px solid var(--line)}
  .form-control,.form-select{height:36px;border:none;background:transparent;padding:0 10px;font:inherit;color:var(--text);outline:none;width:100%}
  .btn-ghost{height:32px;padding:0 10px;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ghost:hover{background:#F8FAFC}

  /* ===== Table shell (mengikuti Data Gardu) ===== */
  .table-shell{border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow-1)}
  table.data{width:100%;border-collapse:separate;border-spacing:0}
  table.data thead th{background:#FCFCFD;color:#64748B;font-weight:700;padding:12px 18px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap}
  table.data tbody td{padding:16px 18px;border-bottom:1px solid var(--line);color:#252F4A;vertical-align:middle}
  table.data tbody tr:hover{background:#FAFAFA}

  .col-no{width:70px;text-align:center}
  .col-aksi{width:180px;text-align:center}

  .btn-ico{--size:32px;width:var(--size);height:var(--size);display:inline-grid;place-items:center;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer}
  .btn-ico:hover{background:#F8FAFC}
  .btn-ico.danger{border-color:#FEE2E2;color:#DC2626}
  .btn-ico.danger:hover{background:#FFF5F5}

  /* ===== Footer tabel (info, per page, pagination) ===== */
  .table-footer{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:14px 18px;border-top:1px solid var(--line);background:#fff;border-bottom-left-radius:16px;border-bottom-right-radius:16px}
  .summary{color:var(--text-dim)}
  .show-wrap{display:inline-flex;align-items:center;gap:8px;color:var(--text-dim)}
  .show-wrap .form-select{width:92px}
  .pagination{display:flex;gap:6px;list-style:none;padding:0;margin:0}
  .page-link{min-width:34px;height:34px;padding:0 10px;display:flex;align-items:center;justify-content:center;border:1px solid var(--line);background:#fff;border-radius:8px;text-decoration:none;color:var(--text)}
  .page-link:hover{background:#F8FAFC}
  .page-item.active .page-link{background:var(--active-soft);color:#0F5132;border-color:#B7F7CF;font-weight:700}
  .page-item.disabled .page-link{opacity:.5;pointer-events:none}

  /* ===== Modal ringan (agar tidak mengganggu layout tabel) ===== */
  .modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:1050}
  .modal.show{display:flex}
  .modal::before{content:"";position:absolute;inset:0;background:rgba(15,23,42,.45)}
  .modal .modal-dialog{position:relative;z-index:1;margin:0;width:min(96vw,640px)}
  .modal .modal-content{border-radius:16px;border:1px solid var(--line);overflow:hidden;background:#fff;box-shadow:0 20px 60px rgba(2,6,23,.18)}
</style>
@endpush

@section('content')
  <!-- Header halaman (mirip Data Gardu) -->
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Daftar Role &amp; Permission</div>
    </div>
    <div class="page-actions">
      <div class="date-pill"><i class="ri-calendar-line"></i><span>{{ now()->translatedFormat('F Y') }}</span></div>

      {{-- Modal Tambah Role --}}
      @can('role.create')
        @include('admin.role.component.modal-tambah', ['id' => 'kt_modal_tambah'])
        <button class="btn btn-primary btn-add" data-modal-target="#kt_modal_tambah">
          <i class="ri-add-line"></i> Tambah Role
        </button>
      @endcan
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <div class="card-title">Tabel Daftar Role</div>

      <!-- Toolbar (search saja, fungsi tetap ke DataTables) -->
      <form id="filterForm" class="toolbar" method="GET" action="#">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" id="roleSearch" value="{{ request('q') }}" class="form-control" placeholder="Cari Nama Role...">
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
              <th>Nama Role</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $value)
              <tr>
                <td class="col-no">{{ $loop->iteration }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="symbol symbol-45px me-3">
                      <div class="symbol-label bg-light-primary">
                        <i class="fas fa-shield-alt fa-icon text-primary fs-2x"></i>
                      </div>
                    </div>
                    <div class="d-flex justify-content-start flex-column">
                      <strong class="text-gray-900">{{ $value->name }}</strong>
                      <span class="text-muted fw-semibold d-block fs-7">{{ $value->permissions_count ?? 0 }} permissions</span>
                    </div>
                  </div>
                </td>
                <td class="col-aksi">
                  @can('role.permission')
                    <a href="{{ route('admin.hak-akses.role.permissions', $value->id) }}"
                       class="btn-ico" title="Kelola Permission">
                      <i class="fas fa-key fa-icon"></i>
                    </a>
                  @endcan

                  @can('role.edit')
                    <button class="btn-ico" title="Edit Role" data-modal-target="#kt_modal_{{ $value->id }}">
                      <i class="fas fa-edit fa-icon"></i>
                    </button>
                  @endcan

                  @can('role.delete')
                    <button data-route="{{ route('admin.hak-akses.role.destroy', $value->id) }}"
                            class="btn-ico danger" title="Hapus" onclick="destroyItem(this)">
                      <i class="fas fa-trash fa-icon"></i>
                    </button>
                  @endcan
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Footer: info + per page + pagination (render via JS dari DataTables) -->
        <div class="table-footer">
          <div class="summary" id="dt-info-area">Menampilkan 0–0 dari 0 data</div>

          <div class="show-wrap">
            <span>Show</span>
            <select class="form-select" id="perPageSelect" aria-label="Jumlah baris per halaman">
              @foreach([10,25,50,100] as $pp)
                <option value="{{ $pp }}" {{ (int)request('per_page', 25)===$pp ? 'selected' : '' }}>{{ $pp }}</option>
              @endforeach
            </select>
            <span>per page</span>
          </div>

          <nav aria-label="Pagination">
            <ul class="pagination" id="dt-paging-area"></ul>
          </nav>
        </div>
      </div>
    </div>
  </section>

  {{-- Modals Edit (diposisikan DI LUAR tabel) --}}
  @foreach ($data as $value)
    @include('admin.role.component.modal', ['value' => $value]) {{-- pastikan wrapper modal punya id="kt_modal_{{ $value->id }}" --}}
  @endforeach
@endsection

@push('scripts')
<script>
/* Helper form POST delete */
if (typeof window.FormElementHelper === 'undefined') {
  class FormElementHelper {
    constructor(){ this.form=document.createElement('form'); }
    createAttribute(type,name,value){ const i=document.createElement('input'); i.type=type; i.name=name; i.value=value; this.form.appendChild(i); return this; }
    post(action){ this.form.method='POST'; this.form.action=action; this.form.style.display='none'; document.body.appendChild(this.form); this.form.submit(); }
  }
  window.FormElementHelper = FormElementHelper;
}
</script>

<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
  // Inisialisasi DataTables tanpa header bawaan (dom: 't'), kita render footer manual
  const dt = $("#kt_datatable_dom_positioning").DataTable({
    language:{
      lengthMenu:"Show _MENU_",
      info:"_START_ - _END_ dari _TOTAL_ data",
      infoEmpty:"Tidak ada data",
      zeroRecords:"Tidak ada data yang cocok",
      paginate:{previous:"‹", next:"›"}
    },
    dom:"t",
    ordering:false,
    autoWidth:false,
    pageLength: parseInt(document.getElementById('perPageSelect').value || 25, 10),
    columnDefs:[
      {targets:0, width:'70px', className:'text-center'},
      {targets:1, width:'auto'},
      {targets:2, width:'180px', className:'text-center'}
    ]
  });

  // Render info + pagination kustom agar mirip Data Gardu
  function renderDtFooter(){
    const info = dt.page.info();
    const infoText = info.recordsTotal
      ? `Menampilkan <strong>${info.start + 1}–${info.end}</strong> dari <strong>${info.recordsDisplay}</strong> data`
      : 'Tidak ada data';
    document.getElementById('dt-info-area').innerHTML = infoText;

    const paging = document.getElementById('dt-paging-area');
    const totalPages = info.pages;
    const current = info.page + 1;

    let html = '';
    html += `<li class="page-item ${current===1?'disabled':''}">
               <a class="page-link" href="#" data-page="${current-2}" aria-label="Sebelumnya"><i class="ri-arrow-left-s-line"></i></a>
             </li>`;

    const start = Math.max(1, current - 2);
    const end   = Math.min(totalPages, start + 4);
    for(let p=start; p<=end; p++){
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
        if(!isNaN(target) && target>=0 && target<totalPages){ dt.page(target).draw('page'); }
      });
    });
  }
  dt.on('draw', renderDtFooter);
  renderDtFooter();

  // Search (toolbar)
  let searchTimeout;
  const searchEl = document.getElementById('roleSearch');
  searchEl?.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(()=> dt.search(searchEl.value).draw(), 300);
  });
  document.getElementById('btnClearSearch')?.addEventListener('click', ()=>{
    if(searchEl){ searchEl.value=''; dt.search('').draw(); }
  });
  document.getElementById('btnReset')?.addEventListener('click', ()=>{
    if(searchEl){ searchEl.value=''; dt.search('').draw(); }
  });

  // Per page selector (footer)
  document.getElementById('perPageSelect')?.addEventListener('change', (e)=>{
    dt.page.len(parseInt(e.target.value || 25, 10)).draw();
  });

  // Modal toggler ringan (tanpa Bootstrap JS) — tidak mengubah fungsi existing
  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('[data-modal-target]');
    if(!btn) return;
    e.preventDefault();
    const sel = btn.getAttribute('data-modal-target');
    document.querySelector(sel)?.classList.add('show');
  });
  document.querySelectorAll('.modal [data-bs-dismiss="modal"], .modal [data-dismiss="modal"], .modal [data-close]')
    .forEach(el=> el.addEventListener('click', ()=> el.closest('.modal')?.classList.remove('show')));
  document.addEventListener('click', (e)=>{ if(e.target.classList.contains('modal')) e.target.classList.remove('show'); });

  // Hapus item (fungsi tetap)
  window.destroyItem = (e)=>{
    let target = $(e); callSwal(target.data('route'));
  }
  function callSwal(route){
    Swal.fire({
      title:"Apakah Anda Yakin?",
      html:"<p style='center'>Setelah Data Dihapus maka Anda Tidak Akan Bisa Mengembalikan Data Kembali!</p>",
      icon:"warning", showCancelButton:true, reverseButtons:true,
      confirmButtonColor:'#d33', cancelButtonColor:'#3085d6',
      confirmButtonText:'Hapus!', cancelButtonText:'Batalkan!'
    }).then((res)=>{
      if(res.isConfirmed){
        (new FormElementHelper)
          .createAttribute('hidden','_token','{{ csrf_token() }}')
          .createAttribute('hidden','_method','DELETE')
          .post(route);
      }else{
        Swal.fire({title:"Aksi Dibatalkan :)", icon:"info"})
      }
    })
  }

  @if (Session::has('pesan'))
    toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}")
  @endif
</script>
@endpush
