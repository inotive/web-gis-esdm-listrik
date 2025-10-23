@extends('admin.layouts.app')

@section('title', $title . ' - BPKAD')
@section('page-title', $title)

@section('breadcrumb')
<!--begin::Item-->
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item text-muted">Hak Akses</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<!--end::Item-->
<!--begin::Item-->
<li class="breadcrumb-item text-muted">{{$title}}</li>
<!--end::Item-->
@endsection

@push('styles')
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!--end::Vendor Stylesheets-->

<style>
/* =========================================================================
   NAMESPACE: semua style dibatasi pada halaman ini agar tak bentrok tema
   ========================================================================= */
.asset-rekap-page{
  --surface: #ffffff;
  --surface-alt: #f6f8ff;
  --surface-muted: rgba(15,23,42,.04);
  --border: rgba(148,163,184,.32);
  --border-strong: rgba(99,102,241,.25);
  --text: #0f172a;
  --text-soft: #475569;
  --text-muted: #64748b;
  --accent: #3b82f6;
  --accent-strong: #2563eb;
  --green: #10b981;
  --amber: #f59e0b;
  --shadow: 0 18px 40px -20px rgba(15,23,42,.45);
  --shadow-soft: 0 12px 30px rgba(15,23,42,.08);
  position: relative;
  max-width: 1160px;
  margin-inline: auto;
  padding: 0 clamp(16px, 4vw, 32px) 56px;
}
.asset-rekap-page .card{ border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-soft); background: var(--surface); }

/* tombol & utilities */
.asset-rekap-page .btn{ border-radius: 12px; font-weight: 600; transition: box-shadow .2s ease, transform .2s ease; }
.asset-rekap-page .btn:hover{ transform: translateY(-1px); box-shadow: 0 12px 24px -12px rgba(15,23,42,.35); }
.asset-rekap-page .btn-primary-modern{ background: var(--accent-strong) !important; color:#fff !important; border:none !important; padding:12px 18px !important; display:inline-flex; align-items:center; gap:10px; box-shadow:0 20px 32px -18px rgba(37,99,235,.65); }

/* tabsbar */
.asset-rekap-page .tabsbar{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin:32px 0 10px; }
.asset-rekap-page .tabs{ display:flex; gap:8px; margin:0; background: rgba(255,255,255,.88); padding:6px; border-radius:16px; border:1px solid rgba(148,163,184,.24); box-shadow: var(--shadow-soft); width:fit-content; }
.asset-rekap-page .tab-actions{ display:flex; gap:10px; }
.asset-rekap-page .tab-link{ display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:12px; font-weight:600; color: var(--text-muted); text-decoration:none; transition: all .2s ease; }
.asset-rekap-page .tab-link.active{ background: var(--accent-strong); color:#fff; box-shadow: 0 16px 30px -18px rgba(37,99,235,.65); }

/* toolbar */
.asset-rekap-page .toolbar{ margin-top:24px; background: rgba(255,255,255,.88); border:1px solid rgba(148,163,184,.25); border-radius:18px; box-shadow: var(--shadow-soft); padding: clamp(18px, 4vw, 26px); display:grid; grid-template-columns:1fr auto; gap:18px; align-items:center; }
.asset-rekap-page .toolbar-left{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; }
.asset-rekap-page .toolbar-right{ display:flex; justify-content:flex-end; align-items:center; }
.asset-rekap-page .search{ display:flex; align-items:center; gap:10px; background: rgba(248,250,255,1); border:1px solid rgba(148,163,184,.25); border-radius:14px; padding:10px 14px; min-width: clamp(200px, 32vw, 280px); }
.asset-rekap-page .search input{ border:none; outline:none; background:transparent; font-size:.92rem; color: var(--text); width:100%; }
.asset-rekap-page .perpage{ display:inline-flex; align-items:center; gap:10px; font-size:.9rem; color: var(--text-muted); }
.asset-rekap-page .perpage select{ border:1px solid rgba(148,163,184,.35); border-radius:12px; padding:10px 12px; font-size:.9rem; background: rgba(248,250,255,.8); color: var(--text); }

/* table - PERBAIKAN UTAMA */
.asset-rekap-page .panel{ background: rgba(255,255,255,.92); border:1px solid rgba(148,163,184,.24); border-radius:18px; box-shadow: var(--shadow-soft); overflow:hidden; }
.asset-rekap-page .table-container{ overflow-x:auto; }
.asset-rekap-page table{ 
    width:100%; 
    border-collapse: separate; 
    border-spacing: 0;
    table-layout: fixed;
}
.asset-rekap-page thead th{ 
    background: linear-gradient(180deg, rgba(248,250,255,1) 0%, rgba(240,244,255,1) 100%); 
    border-bottom:1px solid rgba(148,163,184,.3); 
    font-size:.72rem; 
    font-weight:700; 
    letter-spacing:.08em; 
    color: var(--text-muted); 
    text-transform: uppercase; 
    padding:14px 18px; 
    white-space:nowrap; 
    position:sticky; 
    top:0; 
    z-index:2;
    vertical-align: middle;
}
.asset-rekap-page tbody td{ 
    border-bottom:1px solid rgba(226,232,240,.7); 
    padding:12px 18px; 
    font-size:.94rem; 
    color: var(--text); 
    vertical-align: middle;
}
.asset-rekap-page tbody tr:nth-child(even){ background: rgba(248,250,255,.7); }
.asset-rekap-page tbody tr:hover{ background: rgba(219,234,254,.55); }

/* PERBAIKAN: Definisikan lebar kolom yang konsisten */
.asset-rekap-page thead th.col-num,
.asset-rekap-page tbody td.col-num {
    width: 80px;
    min-width: 80px;
    max-width: 80px;
    text-align: center;
    box-sizing: border-box;
}

.asset-rekap-page thead th.text-right,
.asset-rekap-page tbody td.text-right {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
    text-align: right;
    box-sizing: border-box;
}

/* Kolom nama role akan mengambil sisa lebar */
.asset-rekap-page thead th:nth-child(2),
.asset-rekap-page tbody td:nth-child(2) {
    width: auto;
    min-width: 200px;
}

/* Pastikan konsistensi alignment */
.asset-rekap-page .text-right{
    text-align: right;
}

.asset-rekap-page .text-center{
    text-align: center;
}

.asset-rekap-page .chip{ 
    display:inline-flex; 
    align-items:center; 
    gap:6px; 
    padding:6px 12px; 
    border-radius:999px; 
    background: rgba(59,130,246,.12); 
    border:1px solid rgba(59,130,246,.2); 
    font-size:.82rem; 
    color: var(--accent-strong); 
}

/* Style untuk Font Awesome icons */
.asset-rekap-page .fa-icon {
    font-size: inherit;
}

.asset-rekap-page .btn-icon .fa-icon {
    width: 1em;
    height: 1em;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* datatables footer area wrapper */
.asset-rekap-page .dt-footer{ 
    display:flex; 
    align-items:center; 
    justify-content:space-between; 
    gap:12px; 
    padding:14px 18px; 
    background: var(--surface); 
}

/* responsive */
@media (max-width: 992px){ 
    .asset-rekap-page .toolbar{ grid-template-columns:1fr; } 
}
@media (max-width: 640px){
    .asset-rekap-page{ padding-inline:16px; }
    .asset-rekap-page .tabsbar{ flex-direction:column; align-items:stretch; gap:8px; }
    .asset-rekap-page .tabs{ width:100%; overflow-x:auto; }
    .asset-rekap-page .tab-actions{ justify-content:flex-end; }
    .asset-rekap-page .search{ min-width:100%; }
    
    /* Responsive untuk kolom */
    .asset-rekap-page thead th.col-num,
    .asset-rekap-page tbody td.col-num {
        width: 60px;
        min-width: 60px;
        max-width: 60px;
    }
    
    .asset-rekap-page thead th.text-right,
    .asset-rekap-page tbody td.text-right {
        width: 120px;
        min-width: 120px;
        max-width: 120px;
    }
}
</style>
@endpush

@section('content')
<div class="asset-rekap-page">
    {{-- Header --}}
    <div class="mb-5">
        <div class="d-flex flex-column">
            <span class="fw-bold fs-2 mb-1">Daftar Role</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Per {{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- Tabs + Actions --}}
    <div class="tabsbar" role="toolbar" aria-label="Tab & Aksi">
        <nav class="tabs" aria-label="Tabel">
            <a href="#" class="tab-link active" data-tab="role">Tabel Daftar Role</a>
        </nav>

        <div class="tab-actions">
            <a href="#" class="btn btn-sm btn-primary-modern" data-bs-toggle="modal" data-bs-target="#kt_modal_tambah">
                <i class="fas fa-plus fa-icon me-1"></i>Tambah Data
            </a>
        </div>
    </div>

    {{-- Toolbar (Search + Per Page) --}}
    <div class="toolbar">
        <div class="toolbar-left">
            <label class="search" aria-label="Cari Nama Role">
                <i class="fas fa-search fa-icon text-muted"></i>
                <input type="text" id="roleSearch" placeholder="Cari Nama Role">
            </label>
        </div>
        <div class="toolbar-right">
            <label class="perpage" aria-label="Tampilkan jumlah baris per halaman">
                <span>Show per page</span>
                <select id="perPageSelect">
                    @foreach([10,25,50,100,200] as $n)
                        <option value="{{ $n }}" {{ (int)request('per_page', 25) === $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    {{-- Panel + Table --}}
    <div class="panel mt-3">
        <div class="table-container">
            <table id="kt_datatable_dom_positioning">
                <thead>
                    <tr>
                        <th class="col-num text-center">No</th>
                        <th>Nama Role</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $value)
                        <tr>
                            <td class="col-num text-center">
                                <span class="text-gray-800 fw-bold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="fas fa-shield-alt fa-icon text-primary fs-2x"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <span class="text-gray-900 fw-bold fs-6">{{ $value->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="#"
                                   class="btn btn-icon btn-light-primary btn-active-color-primary btn-sm me-1"
                                   data-bs-toggle="modal" data-bs-target="#kt_modal_{{ $value->id }}"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role">
                                    <i class="fas fa-edit fa-icon"></i>
                                </a>
                                @include('admin.role.component.modal', ['value' => $value])

                                <button data-route="{{ route('admin.hak-akses.role.destroy', $value->id) }}"
                                    class="btn btn-icon btn-light-danger btn-active-color-danger btn-sm"
                                    onclick="destroyItem(this)"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Role">
                                    <i class="fas fa-trash fa-icon"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- DataTables footer (info + pagination) --}}
        <div class="dt-footer">
            <div id="dt-info-area" class="text-muted small"></div>
            <div id="dt-paging-area"></div>
        </div>
    </div>

    @include('admin.role.component.modal-tambah')
</div>
@endsection

@push('scripts')
<!-- Polyfill FormElementHelper agar hapus via POST + _method=DELETE tidak error -->
<script>
if (typeof window.FormElementHelper === 'undefined') {
  class FormElementHelper {
    constructor() {
      this.form = document.createElement('form');
    }
    createAttribute(type, name, value) {
      const input = document.createElement('input');
      input.type = type;
      input.name = name;
      input.value = value;
      this.form.appendChild(input);
      return this;
    }
    post(action) {
      this.form.method = 'POST';
      this.form.action = action;
      this.form.style.display = 'none';
      document.body.appendChild(this.form);
      this.form.submit();
    }
  }
  window.FormElementHelper = FormElementHelper;
}
</script>

<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Vendors Javascript-->
<script>
    // Init DataTable dengan konfigurasi kolom yang diperbaiki
    const dt = $("#kt_datatable_dom_positioning").DataTable({
        language: {
            lengthMenu: "Show _MENU_",
            info: "_START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { previous: "‹", next: "›" }
        },
        dom: "t",
        pageLength: parseInt(document.getElementById('perPageSelect').value || 25),
        ordering: false,
        autoWidth: false,
        columnDefs: [
            { 
                targets: 0,
                width: '80px',
                className: 'col-num text-center'
            },
            { 
                targets: 1,
                width: 'auto',
                className: ''
            },
            { 
                targets: 2,
                width: '150px',
                className: 'text-right'
            }
        ]
    });

    // Render info & pagination manual
    function renderDtFooter() {
        const info = dt.page.info();
        const infoText = info.recordsTotal
            ? `${info.start + 1} - ${info.end} dari ${info.recordsDisplay} data`
            : 'Tidak ada data';
        document.getElementById('dt-info-area').textContent = infoText;

        // Buat pagination sederhana
        const paging = document.getElementById('dt-paging-area');
        const totalPages = info.pages;
        const current = info.page + 1;

        let html = `<nav aria-label="Navigasi halaman"><ul class="pagination mb-0">`;
        
        // Previous button
        html += `<li class="page-item ${current === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${current - 2}" aria-label="Previous">
                        <i class="fas fa-chevron-left fa-icon"></i>
                    </a>
                 </li>`;
        
        // Page numbers
        const start = Math.max(1, current - 2);
        const end = Math.min(totalPages, start + 4);
        for (let p = start; p <= end; p++) {
            html += `<li class="page-item ${p === current ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${p - 1}">${p}</a>
                     </li>`;
        }
        
        // Next button
        html += `<li class="page-item ${current === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${current}" aria-label="Next">
                        <i class="fas fa-chevron-right fa-icon"></i>
                    </a>
                 </li>`;
        html += `</ul></nav>`;
        
        paging.innerHTML = html;

        // Binding click event untuk pagination
        paging.querySelectorAll('a.page-link').forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const targetPage = parseInt(a.dataset.page, 10);
                if (!isNaN(targetPage) && targetPage >= 0 && targetPage < totalPages) {
                    dt.page(targetPage).draw('page');
                }
            });
        });
    }

    // Initial render
    dt.on('draw', renderDtFooter);
    renderDtFooter();

    // Search dengan debounce
    let searchTimeout;
    const searchEl = document.getElementById('roleSearch');
    if (searchEl) {
        searchEl.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                dt.search(searchEl.value).draw();
            }, 350);
        });
    }

    // Per page change
    const perSel = document.getElementById('perPageSelect');
    perSel.addEventListener('change', () => {
        dt.page.len(parseInt(perSel.value || 25, 10)).draw();
    });

    // === Fungsi asli: jangan diubah namanya ===
    const destroyItem = (e) => {
        let target = $(e);
        callSwal(target.data('route'))
    }

    const callSwal = (route) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            html: "<p style='center'>Setelah Data Dihapus maka Anda Tidak Akan Bisa Mengembalikan Data Kembali!</p>",
            icon: "warning",
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batalkan!'
        })
        .then((willDelete) => {
            if (willDelete.isConfirmed) {
                (new FormElementHelper)
                    .createAttribute('hidden', '_token', '{{ csrf_token() }}')
                    .createAttribute('hidden', '_method', 'DELETE')
                    .post(route);
            } else {
                Swal.fire({
                    title: "Aksi Dibatalkan :)",
                    icon: "info",
                })
            }
        })
    }

    @if (Session::has('pesan'))
        toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}")
    @endif
</script>
@endpush
