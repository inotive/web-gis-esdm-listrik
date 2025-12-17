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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
  /* Header / Filter */
  .card-header{ background:white; border-bottom:1px solid #F1F1F4; padding:8px 20px; }
  .toolbar{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
  .w-search{ width:250px; }
  .input-group{ display:flex; align-items:center; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; overflow:hidden; height:32px; }
  .input-group-text{ display:flex; align-items:center; justify-content:center; width:32px; height:100%; color:#99A1B7; background:transparent; border:none; padding:0; }
  .input-group .form-control{ height:100%; border:none; background:transparent; padding:0 10px; font-size:11px; color:#78829D; outline:none; width:100%; }
  .btn-ghost{ height:32px; padding:0 10px; border:1px solid #F1F1F4; background:#fff; border-radius:6px; cursor:pointer; display:inline-flex; align-items:center; gap:4px; font-size:13px; color:#4B5675; transition:.2s; }
  .btn-ghost:hover{ background:#F8FAFC; }

  /* Table */
  .table-shell{ background:white; overflow:hidden; }
  table.data{ width:100%; border-collapse:collapse; }
  table.data thead{ background:#FCFCFC; }
  table.data thead th{ background:#FCFCFC; color:#4B5675; font-weight:400; font-size:13px; padding:12px 20px; text-align:left; border-bottom:1px solid #F1F1F4; white-space:nowrap; }
  table.data tbody td{ padding:23px 20px; border-bottom:1px solid #F1F1F4; color:#252F4A; font-size:14px; vertical-align:middle; }
  table.data tbody tr:last-child td{ border-bottom:none; }
  table.data tbody tr:hover{ background:#FCFCFC; }

  .col-no{ width:48px; text-align:center; color:#071437; }
  .col-aksi{ width:180px; text-align:center; vertical-align:middle; }

  .btn-ico{ width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; border:none; background:transparent; cursor:pointer; transition:transform .2s; padding:0; margin:0 6px; vertical-align:middle; }
  .btn-ico:hover{ transform:scale(1.1); }

  .table-footer{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; padding:14px 20px; border-top:1px solid #F1F1F4; background:#fff; }
  .footer-actions{ display:flex; align-items:center; flex-wrap:wrap; gap:12px; justify-content:flex-end; }
  .summary{ color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap{ display:inline-flex; align-items:center; gap:10px; color:#4B5675; font-size:13px; white-space:nowrap; }
  .show-wrap .form-select{
    width:70px; height:30px; background:#FCFCFC; border:1px solid #DBDFE9; border-radius:6px; padding:4px 8px; font-size:11px; color:#252F4A; cursor:pointer; text-align:center;
    appearance:none; background-image:url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 8px center; background-size:14px; padding-right:30px;
  }

  .pagination{ display:flex; align-items:center; gap:2px; }
  .pagination .page-item{ list-style:none; }
  .pagination .page-link{ width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:14px; color:#4B5675; text-decoration:none; transition:.2s; border:none; background:transparent; }
  .pagination .page-link:hover{ background:#F5F5F5; }
  .pagination .page-item.active .page-link{ background:#F1F1F4; color:#252F4A; font-weight:500; }
  .pagination .page-item.disabled .page-link{ opacity:.5; cursor:not-allowed; }

  /* Modal ringan (tanpa mengubah fungsi) */
  .modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:1050}
  .modal.show{display:flex}
  .modal::before{content:"";position:absolute;inset:0;background:rgba(15,23,42,.45)}
  .modal .modal-dialog{position:relative;z-index:1;margin:0;width:min(96vw,640px)}
  .modal .modal-content{border-radius:16px;border:1px solid #F1F1F4;overflow:hidden;background:#fff;box-shadow:0 20px 60px rgba(2,6,23,.18)}
  .modal .form-label{font-weight:600;font-size:14px;color:#334155;margin-bottom:8px}
  .modal .form-control{
    height:44px;
    border:1px solid #e2e8f0;
    background:#f8fafc;
    border-radius:10px;
    padding:0 12px;
    font-size:14px;
    color:#1e293b;
    box-shadow:none;
  }
  .modal .form-control:focus{border-color:#94a3b8;box-shadow:0 0 0 3px rgba(16,185,129,.12);background:#fff}

  @media (max-width:768px){
    .toolbar{ flex-direction:column; align-items:stretch; gap:12px; }
    .w-search{ width:100%; }
    table.data{ font-size:13px; }
    table.data thead th, table.data tbody td{ padding:12px 10px; }
    .col-no{ width:40px; }
    .table-footer{ flex-direction:column; align-items:flex-start; }
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Daftar Role &amp; Permission</div>
    </div>
    <div class="page-actions">

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

      <form id="filterForm" class="toolbar" method="GET" action="#" onsubmit="return false;">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="search" id="roleSearch" value="{{ request('q') }}" class="form-control" placeholder="Cari Nama Role..." autocomplete="off" spellcheck="false">
          @if(request('q'))
            <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan"><i class="ri-close-line"></i><span class="d-none d-sm-inline"> Clear</span></button>
          @endif
        </div>

        <!-- <button type="button" class="btn-ghost" id="btnReset" title="Reset">
          <i class="ri-refresh-line"></i><span class="d-none d-sm-inline"> Reset</span>
        </button> -->
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table id="kt_datatable_dom_positioning" class="data">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Role</th>
              <th style="width:150px;">Jumlah Hak Akses</th>
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
                    </div>
                  </div>
                </td>
                <td>
                  @php $permCount = $value->permissions_count ?? ($value->permissions ? $value->permissions->count() : 0); @endphp
                  <span class="badge badge-light-primary fs-7 fw-bold">{{ $permCount }}</span>
                </td>
                <td class="col-aksi">
                  @can('role.permission')
                    <a href="{{ route('admin.hak-akses.role.permissions', $value->id) }}" class="btn-ico" title="Kelola Hak Akses" data-bs-toggle="tooltip" data-bs-placement="top">
                      <i class="fas fa-key fa-icon"></i>
                    </a>
                  @endcan

                  @can('role.edit')
                    <button class="btn-ico" title="Edit Role" data-modal-target="#kt_modal_{{ $value->id }}">
                      <i class="fas fa-edit fa-icon"></i>
                    </button>
                  @endcan

                  @can('role.delete')
                    <button data-route="{{ route('admin.hak-akses.role.destroy', $value->id) }}" class="btn-ico" title="Hapus" onclick="destroyItem(this)">
                      <i class="fas fa-trash fa-icon"></i>
                    </button>
                  @endcan
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="table-footer">

          <div class="show-wrap">
            <span>Show</span>
            <select class="form-select" id="perPageSelect" aria-label="Jumlah baris per halaman">
              @foreach([10,25,50,100] as $pp)
                <option value="{{ $pp }}" {{ (int)request('per_page', 10)===$pp ? 'selected' : '' }}>{{ $pp }}</option>
              @endforeach
            </select>
            <span>per page</span>
          </div>

          <div class="footer-actions">
            <div class="summary" id="dt-info-area">Menampilkan 0–0 dari 0 data</div>
            <nav aria-label="Pagination">
              <ul class="pagination" id="dt-paging-area"></ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </section>

  @foreach ($data as $value)
    @include('admin.role.component.modal', ['value' => $value])
  @endforeach
@endsection

@push('scripts')
<script>
if (typeof window.FormElementHelper === 'undefined') {
  class FormElementHelper {
    constructor(){ this.form=document.createElement('form'); }
    createAttribute(type,name,value){ const i=document.createElement('input'); i.type=type; i.name=name; i.value=value; this.form.appendChild(i); return this; }
    post(action){ this.form.method='POST'; this.form.action=action; this.form.style.display='none'; document.body.appendChild(this.form); this.form.submit(); }
  }
  window.FormElementHelper = FormElementHelper;
}
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
  jQuery(function($){
    const dt = $("#kt_datatable_dom_positioning").DataTable({
      language:{ lengthMenu:"Show _MENU_", info:"_START_ - _END_ dari _TOTAL_ data", infoEmpty:"Tidak ada data", zeroRecords:"Tidak ada data yang cocok", paginate:{previous:"‹", next:"›"} },
      dom:"t", ordering:false, autoWidth:false,
      pageLength: parseInt(document.getElementById('perPageSelect').value || 10, 10),
      columnDefs:[
        {targets:0, width:'48px', className:'text-center'},
        {targets:1, width:'auto'},
        {targets:2, width:'150px', className:'text-center'},
        {targets:3, width:'180px', className:'text-center'}
      ]
    });

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

    // Search
    let searchTimeout;
    const searchEl = document.getElementById('roleSearch');
    searchEl?.addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(()=> dt.search(searchEl.value).draw(), 250);
    });
    document.getElementById('btnClearSearch')?.addEventListener('click', ()=>{ if(searchEl){ searchEl.value=''; dt.search('').draw(); } });
    document.getElementById('btnReset')?.addEventListener('click', ()=>{ if(searchEl){ searchEl.value=''; dt.search('').draw(); } });

    // Per page - Go to first page when changing per page
    document.getElementById('perPageSelect')?.addEventListener('change', (e)=>{
      dt.page.len(parseInt(e.target.value || 10, 10)).page(0).draw();
    });

    // Update row numbers after pagination/draw
    dt.on('draw', function(){
      const info = dt.page.info();
      dt.column(0, {page:'current'}).nodes().each(function(cell, i){
        cell.innerHTML = info.start + i + 1;
      });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Modal toggler ringan + reset form on close
    function closeModal(modal){
      if(!modal) return;
      modal.classList.remove('show');
      const form = modal.querySelector('form');
      if(form) form.reset();
    }

    document.addEventListener('click', (e)=>{
      const btn = e.target.closest('[data-modal-target]');
      if(!btn) return;
      e.preventDefault();
      const sel = btn.getAttribute('data-modal-target');
      document.querySelector(sel)?.classList.add('show');
    });
    document.querySelectorAll('.modal [data-bs-dismiss="modal"], .modal [data-dismiss="modal"], .modal [data-close]')
      .forEach(el=> el.addEventListener('click', ()=> closeModal(el.closest('.modal'))));
    document.addEventListener('click', (e)=>{ if(e.target.classList.contains('modal')) closeModal(e.target); });

    // Delete
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
      @if (Session::get('alert') === 'success')
        toastr.success("{{ Session::get('pesan') }}", "Berhasil!", {
          closeButton: true,
          progressBar: true,
          positionClass: "toast-top-right",
          timeOut: 3000
        });
      @elseif (Session::get('alert') === 'error')
        toastr.error("{{ Session::get('pesan') }}", "Gagal!", {
          closeButton: true,
          progressBar: true,
          positionClass: "toast-top-right",
          timeOut: 3000
        });
      @else
        toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}", "", {
          closeButton: true,
          progressBar: true,
          positionClass: "toast-top-right",
          timeOut: 3000
        });
      @endif
    @endif

    @if ($errors->any())
      toastr.error("{{ $errors->first() }}", "Error!", {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 4000
      });
    @endif
  });
</script>
@endpush
