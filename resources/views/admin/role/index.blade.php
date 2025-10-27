{{-- resources/views/admin/role/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', ($title ?? 'Role & Permission') . ' - BPKAD')

@push('styles')
<style>
  /* ===== Toolbar & Form Mini ===== */
  .toolbar { display:flex; flex-wrap:wrap; align-items:center; gap:8px 10px; }
  .toolbar .w-search { width: clamp(230px, 38vw, 360px); }

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

  /* ===== Table ===== */
  .table-shell { border:1px solid var(--line); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-1); }
  .table-esdm { width:100%; border-collapse:separate; border-spacing:0; }
  .table-esdm thead th {
    background:#FCFCFD; color:#64748B; font-weight:700; padding:12px 18px; text-align:left;
    border-bottom:1px solid var(--line); white-space:nowrap;
  }
  .table-esdm tbody td {
    padding:14px 18px; border-bottom:1px solid var(--line); color:#252F4A; vertical-align:middle;
  }
  .table-esdm tbody tr:hover{ background:#FAFAFA; }

  .col-no{ width:70px; text-align:center; }
  .col-perm{ width:160px; text-align:right; }
  .col-aksi{ width:160px; text-align:center; }

  .btn-ico { --size:32px; width:var(--size); height:var(--size); display:inline-grid; place-items:center;
    border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
  .btn-ico:hover{ background:#F8FAFC; }
  .btn-ico.danger { border-color:#FEE2E2; color:#DC2626; }
  .btn-ico.danger:hover { background:#FFF5F5; }

  /* ===== Footer ===== */
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

  /* Header kecil */
  .page-head{ display:flex; justify-content:space-between; align-items:center; padding:6px 4px 2px 4px; margin-bottom:8px; }
  .page-meta{ color:#6B7280; font-size:13px; }
  .page-title{ font-size:28px; font-weight:800; margin-top:6px; }
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">{{ $title ?? 'Role & Permission' }}</div>
  </div>
  <div class="page-actions">
    <div class="date-pill">
      <i class="ri-calendar-line"></i>
      <span>{{ now()->translatedFormat('F Y') }}</span>
    </div>

    @can('role.create')
      {{-- tombol tetap pakai Bootstrap modal yang sudah ada --}}
      <a href="#" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#kt_modal_tambah">
        <i class="ri-add-line"></i> Tambah Data
      </a>
    @endcan
  </div>
</div>

<section class="card" style="margin-top:18px;">
  <div class="card-header">
    <div class="card-title">Daftar Role & Permission</div>

    <!-- Toolbar -->
    <div class="toolbar" id="roleToolbar">
      <div class="input-group w-search">
        <span class="input-group-text"><i class="ri-search-line"></i></span>
        <input id="roleSearch" type="text" class="form-control" placeholder="Cari Nama Role..." aria-label="Cari Nama Role">
        <button type="button" class="btn-ghost" id="btnClearSearch" title="Bersihkan">
          <i class="ri-close-line"></i>
        </button>
      </div>

      <div class="show-wrap" style="margin-left:auto;">
        <span>Show</span>
        <select id="perPageSelect" class="form-select" aria-label="Jumlah baris per halaman">
          @foreach([10,25,50,100] as $n)
            <option value="{{ $n }}" {{ (int)request('per_page', 25) === $n ? 'selected' : '' }}>{{ $n }}</option>
          @endforeach
        </select>
        <span>per page</span>
      </div>
    </div>
  </div>

  <div class="card-body" style="padding:0;">
    <div class="table-responsive table-shell">
      <table class="table-esdm" id="roleTable">
        <thead>
          <tr>
            <th class="col-no">No</th>
            <th>Nama Role</th>
            <th class="col-perm">Permissions</th>
            <th class="col-aksi">Aksi</th>
          </tr>
        </thead>
        <tbody id="roleTbody">
          @foreach ($data as $value)
            <tr class="role-row" data-name="{{ Str::lower($value->name) }}">
              <td class="col-no">0</td>
              <td>
                <strong>{{ $value->name }}</strong>
                <div class="text-muted" style="font-size:.82rem;">ID: {{ $value->id }}</div>
              </td>
              <td class="col-perm">
                <span>{{ $value->permissions_count ?? 0 }}</span>
              </td>
              <td class="col-aksi">
                @can('role.permission')
                  <a href="{{ route('admin.hak-akses.role.permissions', $value->id) }}"
                     class="btn-ico" title="Kelola Permission">
                    <i class="ri-key-2-line"></i>
                  </a>
                @endcan

                @can('role.edit')
                  <a href="#" class="btn-ico" title="Edit Role"
                     data-bs-toggle="modal" data-bs-target="#kt_modal_{{ $value->id }}">
                    <i class="ri-edit-line"></i>
                  </a>
                  @include('admin.role.component.modal', ['value' => $value])
                @endcan

                @can('role.delete')
                  <button type="button" class="btn-ico danger" title="Hapus Role"
                          onclick="destroyItem(this)" data-route="{{ route('admin.hak-akses.role.destroy', $value->id) }}">
                    <i class="ri-delete-bin-6-line"></i>
                  </button>
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Footer -->
      <div class="table-footer">
        <div class="summary" id="dt-info-area">—</div>

        <nav aria-label="Pagination">
          <ul class="pagination" id="dt-paging-area">
            <!-- diisi via JS -->
          </ul>
        </nav>
      </div>
    </div>
  </div>
</section>

@can('role.create')
  @include('admin.role.component.modal-tambah')
@endcan
@endsection

@push('scripts')
<script>
  // Helper untuk form POST (hapus)
  if (typeof window.FormElementHelper === 'undefined') {
    class FormElementHelper {
      constructor(){ this.form = document.createElement('form'); }
      createAttribute(type, name, value){
        const input = document.createElement('input');
        input.type = type; input.name = name; input.value = value;
        this.form.appendChild(input); return this;
      }
      post(action){
        this.form.method = 'POST'; this.form.action = action; this.form.style.display='none';
        document.body.appendChild(this.form); this.form.submit();
      }
    }
    window.FormElementHelper = FormElementHelper;
  }

  // SweetAlert konfirmasi hapus
  const destroyItem = (el) => {
    const route = el.getAttribute('data-route');
    if (!route) return;
    if (typeof Swal === 'undefined') {
      // fallback konfirmasi sederhana
      if (confirm('Hapus data ini? Tindakan tidak dapat dibatalkan.')) {
        (new FormElementHelper)
          .createAttribute('hidden', '_token', '{{ csrf_token() }}')
          .createAttribute('hidden', '_method', 'DELETE')
          .post(route);
      }
      return;
    }
    Swal.fire({
      title: "Apakah Anda Yakin?",
      html: "<p>Setelah data dihapus Anda tidak dapat mengembalikannya.</p>",
      icon: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Hapus!',
      cancelButtonText: 'Batalkan!'
    }).then((res) => {
      if (res.isConfirmed) {
        (new FormElementHelper)
          .createAttribute('hidden', '_token', '{{ csrf_token() }}')
          .createAttribute('hidden', '_method', 'DELETE')
          .post(route);
      }
    });
  };

  // ======= Client-side Search + Pagination =======
  document.addEventListener('DOMContentLoaded', function(){
    const tbody = document.getElementById('roleTbody');
    const rows = Array.from(tbody.querySelectorAll('tr.role-row'));
    const searchInput = document.getElementById('roleSearch');
    const clearBtn = document.getElementById('btnClearSearch');
    const perPageSel = document.getElementById('perPageSelect');
    const infoArea = document.getElementById('dt-info-area');
    const pagingArea = document.getElementById('dt-paging-area');

    let filtered = rows.slice();
    let currentPage = 1;

    function applySearch(){
      const q = (searchInput.value || '').toLowerCase().trim();
      filtered = q
        ? rows.filter(r => (r.dataset.name || '').includes(q))
        : rows.slice();
      currentPage = 1;
      render();
    }

    function render(){
      // setup paging
      const per = parseInt(perPageSel.value || '25', 10);
      const total = filtered.length;
      const pages = Math.max(1, Math.ceil(total / per));
      if (currentPage > pages) currentPage = pages;

      // hide all
      rows.forEach(r => r.style.display = 'none');

      // show slice
      const startIdx = (currentPage - 1) * per;
      const endIdx = Math.min(startIdx + per, total);

      for (let i = startIdx; i < endIdx; i++){
        const row = filtered[i];
        // nomor urut tampil
        const noCell = row.querySelector('.col-no');
        if (noCell) noCell.textContent = (i + 1).toString();
        row.style.display = '';
      }

      // info text
      const infoText = total
        ? `${startIdx + 1} - ${endIdx} dari ${total} data`
        : 'Tidak ada data';
      infoArea.textContent = infoText;

      // pagination
      pagingArea.innerHTML = '';
      const addPageItem = (label, page, disabled = false, active = false, aria = '') => {
        const li = document.createElement('li');
        li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = label;
        if (aria) a.setAttribute('aria-label', aria);
        a.addEventListener('click', (e) => {
          e.preventDefault();
          if (!disabled && page !== currentPage) {
            currentPage = page;
            render();
          }
        });
        li.appendChild(a);
        pagingArea.appendChild(li);
      };

      // prev
      addPageItem('‹', Math.max(1, currentPage - 1), currentPage === 1, false, 'Sebelumnya');

      // window pages (maks 5)
      const win = 2;
      let start = Math.max(1, currentPage - win);
      let end = Math.min(pages, currentPage + win);
      if (end - start < 4){
        if (start === 1) end = Math.min(pages, start + 4);
        else if (end === pages) start = Math.max(1, end - 4);
      }
      for (let p = start; p <= end; p++){
        addPageItem(String(p), p, false, p === currentPage);
      }

      // next
      addPageItem('›', Math.min(pages, currentPage + 1), currentPage === pages, false, 'Berikutnya');
    }

    // events
    let t; 
    searchInput.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(applySearch, 250);
    });
    clearBtn.addEventListener('click', () => {
      searchInput.value = '';
      applySearch();
    });
    perPageSel.addEventListener('change', render);

    // init
    render();
  });

  @if (Session::has('pesan'))
    if (typeof toastr !== 'undefined') {
      toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}");
    }
  @endif
</script>
@endpush
