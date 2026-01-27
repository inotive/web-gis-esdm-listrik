@extends('admin.layouts.app')

@section('title', 'Perizinan dan Permohonan')

@section('content')
<div class="page-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <div class="page-meta" style="font-size: 14px; color: #6B7280;">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title" style="font-size: 24px; font-weight: 700; color: #111827;">Perizinan</div>
  </div>
  <div class="page-actions">
        <a href="{{ route('admin.perizinan.import') }}" class="btn btn-primary" style="background:var(--accent-1, #059669); border-color:var(--accent-1, #059669); margin-right:8px;">
            <i class="ri-file-excel-2-line"></i> Import Data
        </a>
        <a href="{{ route('admin.perizinan.create') }}" class="btn btn-primary" style="background: var(--accent-2, #2563EB); border-color: var(--accent-2, #2563EB);">
            <i class="ri-add-line"></i> Tambah Data
        </a>
  </div>
</div>

<div class="card" style="border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
    <div class="card-header bg-white py-3" style="border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
        <h4 class="card-title mb-0" style="font-size: 18px; font-weight: 700; color: #111827;">Data Perizinan</h4>
        
        <form action="{{ route('admin.perizinan.index') }}" method="GET" style="width: 300px;">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari perizinan..." value="{{ request('q') }}" style="border-radius: 8px 0 0 8px;">
                <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0;">
                    <i class="ri-search-line"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted font-weight-bold">Nama Perizinan</th>
                        <th class="px-4 py-3 text-muted font-weight-bold">Perusahaan</th>
                        <th class="px-4 py-3 text-muted font-weight-bold">Jenis</th>
                        <th class="px-4 py-3 text-muted font-weight-bold">Status Listrik</th>
                        <th class="px-4 py-3 text-muted font-weight-bold">Lokasi</th>
                        <th class="px-4 py-3 text-muted font-weight-bold text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perizinans as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="fw-bold text-dark">{{ $item->nama }}</div>
                            <small class="text-muted">{{ $item->no_pengajuan ?? '-' }}</small>
                        </td>
                        <td class="px-4 py-3">
                            @if($item->perusahaan)
                                <div>{{ $item->perusahaan->nama }}</div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $item->jenis }}</td>
                        <td class="px-4 py-3">
                            @if($item->status_kelistrikan == 'berlistrik_pln')
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Berlistrik PLN</span>
                            @elseif($item->status_kelistrikan == 'berlistrik_non_pln')
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">Non PLN</span>
                            @elseif($item->status_kelistrikan == 'tidak_berlistrik')
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">Tidak Berlistrik</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                         <td class="px-4 py-3">
                            <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $item->lokasi ?? '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.perizinan.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.perizinan.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.perizinan.destroy', $item->id) }}" method="POST" class="form-delete-perizinan" data-name="{{ $item->nama }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-trigger" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada data perizinan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-top py-3">
        {{ $perizinans->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== Delete Confirmation ==========
        document.querySelectorAll('.btn-delete-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-delete-perizinan');
                const name = form.dataset.name;

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin ingin menghapus data perizinan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary me-3'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // ========== SweetAlert Notifications (From Controller) ==========
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
                customClass: {
                    popup: 'swal-custom-toast'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endpush