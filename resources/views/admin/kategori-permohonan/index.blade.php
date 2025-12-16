@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Manajemen Kategori Permohonan')

@push('styles')
<style>
  .card-header {
    background: white;
    border-bottom: 1px solid #F1F1F4;
    padding: 8px 20px;
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .w-search {
    width: 250px;
  }

  .input-group {
    display: flex;
    align-items: center;
    background: #FCFCFC;
    border: 1px solid #DBDFE9;
    border-radius: 6px;
    overflow: hidden;
    height: 32px;
  }

  .input-group-text {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 100%;
    color: #99A1B7;
    background: transparent;
    border: none;
    padding: 0;
  }

  .form-control {
    height: 100%;
    border: none;
    background: transparent;
    padding: 0 10px;
    font-size: 11px;
    color: #78829D;
    outline: none;
    width: 100%;
  }

  .table-permohonan {
    width: 100%;
    border-collapse: collapse;
  }

  .table-permohonan thead {
    background: #FCFCFC;
  }

  .table-permohonan thead th {
    background: #FCFCFC;
    color: #4B5675;
    font-weight: 400;
    font-size: 13px;
    padding: 12px 20px;
    text-align: left;
    border-bottom: 1px solid #F1F1F4;
  }

  .table-permohonan tbody td {
    padding: 23px 20px;
    border-bottom: 1px solid #F1F1F4;
    color: #252F4A;
    font-size: 14px;
    vertical-align: middle;
  }

  .table-permohonan tbody tr:hover {
    background: #FCFCFC;
  }

  .col-aksi {
    width: 120px;
    text-align: center;
  }

  .btn-ico {
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    cursor: pointer;
    margin: 0 6px;
  }

  .btn-ico.edit svg path {
    stroke: #DFA000;
  }

  .btn-ico.edit svg circle {
    fill: #DFA000;
  }

  .btn-ico.danger svg path {
    stroke: #F8285A;
  }

  .table-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 20px;
    border-top: 1px solid #F1F1F4;
    background: #fff;
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Manajemen Kategori Permohonan</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>{{ now()->translatedFormat('F Y') }}</span>
      </div>

      <a href="{{ route('admin.kategori-permohonan.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i>
        Tambah Kategori Permohonan
      </a>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.kategori-permohonan.index') }}">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari Nama atau Jenis Kategori Permohonan" autocomplete="off">
        </div>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-permohonan">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Kategori Permohonan</th>
              <th>Jenis Kategori Permohonan</th>
              <th>Jumlah Pertanyaan</th>
              <th>Keterangan</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($permohonans as $i => $permohonan)
              <tr>
                <td class="col-no">{{ $permohonans->firstItem() + $i }}</td>
                <td><strong>{{ $permohonan->nama }}</strong></td>
                <td>{{ $permohonan->jenis_permohonan }}</td>
                <td>{{ $permohonan->questions_count ?? 0 }}</td>
                <td>{{ $permohonan->keterangan ?? '-' }}</td>
                <td class="col-aksi">
                  <a href="{{ route('admin.kategori-permohonan.edit', $permohonan) }}" class="btn-ico edit" title="Edit">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="12" cy="12" r="2" fill="#DFA000"/>
                      <path d="M12 5L9 8M12 5L15 8M12 5V3M12 19L9 16M12 19L15 16M12 19V21M19 12L16 9M19 12L16 15M19 12H21M5 12L8 9M5 12L8 15M5 12H3" stroke="#DFA000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </a>
                  <form action="{{ route('admin.kategori-permohonan.destroy', $permohonan) }}" method="POST" style="display:inline-block;margin:0;" class="form-delete-permohonan" data-name="{{ $permohonan->nama }}">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-ico danger btn-delete-permohonan" title="Hapus">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 20H15M10 4H14M7 7H17L16 20H8L7 7Z" stroke="#F8285A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center" style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan
            <strong>{{ $permohonans->firstItem() ?? 0 }}–{{ $permohonans->lastItem() ?? 0 }}</strong> dari
            <strong>{{ $permohonans->total() }}</strong> data</div>
          <nav aria-label="Pagination">
            {{ $permohonans->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
      });
    @endif

    // Delete confirmation
    document.querySelectorAll('.btn-delete-permohonan').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.form-delete-permohonan');
        const name = form.dataset.name;

        Swal.fire({
          title: 'Konfirmasi Hapus',
          html: `Apakah Anda yakin ingin menghapus kategori permohonan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#94a3b8',
          confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // Auto submit on search
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
      let timeout;
      searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          document.getElementById('filterForm').submit();
        }, 500);
      });
    }
  });
</script>
@endpush
