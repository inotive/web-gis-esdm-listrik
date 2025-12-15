@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Permohonan Saya')

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

  .input-group .form-control {
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
    width: 150px;
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

  .btn-ico.approve svg path {
    stroke: #10B981;
  }

  .btn-ico.progress svg path {
    stroke: #3B82F6;
  }

  .badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-pending {
    background: #FEF3C7;
    color: #92400E;
  }

  .badge-proses {
    background: #DBEAFE;
    color: #1E40AF;
  }

  .badge-selesai {
    background: #D1FAE5;
    color: #065F46;
  }

  .badge-ditolak {
    background: #FEE2E2;
    color: #991B1B;
  }

  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid #DBDFE9;
    background: #fff;
    color: #64748B;
    text-decoration: none;
    font-size: 13px;
    height: 32px;
  }

  .btn-secondary:hover {
    background: #F9FAFB;
    border-color: #CBD5E1;
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

  /* Modal Styling */
  .modal {
    display: none !important;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
  }

  .modal.show {
    display: flex !important;
    align-items: center;
    justify-content: center;
  }


  .modal.fade {
    opacity: 0;
    transition: opacity 0.15s linear;
  }

  .modal.fade.show {
    opacity: 1;
  }

  .modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: rgba(15, 23, 42, 0.45);
    display: none;
  }

  .modal-backdrop.show {
    display: block;
  }

  .modal-dialog {
    position: relative;
    width: auto;
    margin: 1.75rem auto;
    max-width: 500px;
    pointer-events: none;
    z-index: 1051;
  }

  .modal-dialog.modal-lg {
    max-width: 800px;
  }

  .modal.show .modal-dialog {
    pointer-events: auto;
  }

  .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    background-color: #fff !important;
    background-clip: padding-box;
    border: 1px solid #F1F1F4;
    border-radius: 16px;
    box-shadow: 0 12px 32px rgba(2, 6, 23, 0.12);
    outline: 0;
    opacity: 1 !important;
  }

  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid #F1F1F4;
    background: #fff !important;
    border-radius: 16px 16px 0 0;
  }

  .modal-header .modal-title {
    font-weight: 800;
    font-size: 20px;
    color: #111827;
    margin: 0;
  }

  .modal-header .close {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E2E8F0;
    background: #fff !important;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.18s ease;
    padding: 0;
    margin: 0;
    opacity: 1 !important;
  }

  .modal-header .close:hover {
    background: #F8FAFC;
  }

  .modal-header .close span {
    font-size: 24px;
    color: #64748B;
    line-height: 1;
  }

  .modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 18px 20px;
    background: #fff !important;
  }

  .modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 14px 20px 18px;
    border-top: 1px solid #F1F1F4;
    background: #fff !important;
    border-radius: 0 0 16px 16px;
  }

  /* Modal Form Labels */
  .modal .form-label {
    font-weight: 600;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
    display: block;
  }

  .modal .form-label.small {
    font-size: 12px;
    font-weight: 500;
    color: #64748B;
    margin-bottom: 6px;
  }

  /* Modal Form Controls */
  .modal .form-control {
    width: 100%;
    height: 44px;
    padding: 0 12px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #FCFCFD !important;
    font-size: 14px;
    color: #0f172a !important;
    transition: all 0.18s ease;
  }

  .modal .form-control:focus {
    outline: none;
    border-color: #CBD5E1;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    background: #fff !important;
  }

  .modal .form-control-sm {
    height: 38px;
    padding: 0 10px;
    font-size: 13px;
  }

  .modal textarea.form-control {
    height: auto;
    min-height: 100px;
    padding: 10px 12px;
    resize: vertical;
  }

  .modal .document-item {
    background: #FCFCFD !important;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 12px;
  }

  .modal .document-item:last-child {
    margin-bottom: 0;
  }

  .modal #addDocumentBtn {
    height: 38px;
    padding: 0 14px;
    border: 1px solid #E2E8F0;
    background: #fff;
    color: #64748B;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.18s ease;
  }

  .modal #addDocumentBtn:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
  }

  .modal .remove-document {
    height: 38px;
    padding: 0;
    border: 1px solid #FEE2E2;
    background: #FEF2F2;
    color: #DC2626;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.18s ease;
  }

  .modal .remove-document:hover {
    background: #FEE2E2;
    border-color: #FCA5A5;
  }

  .modal-footer .btn-success {
    height: 44px;
    padding: 0 20px;
    background: #10B981;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.18s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.22);
  }

  .modal-footer .btn-success:hover {
    background: #059669;
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
  }

  .modal-footer .btn-danger {
    height: 44px;
    padding: 0 20px;
    background: #EF4444;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.18s ease;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.22);
  }

  .modal-footer .btn-danger:hover {
    background: #DC2626;
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
  }

  .modal-footer .btn-primary {
    height: 44px;
    padding: 0 20px;
    background: #3B82F6;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.18s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.22);
  }

  .modal-footer .btn-primary:hover {
    background: #2563EB;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
  }

  .modal-footer .btn-secondary {
    height: 44px;
    padding: 0 20px;
    background: #fff;
    color: #64748B;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.18s ease;
  }

  .modal-footer .btn-secondary:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
  }

  .modal .text-muted {
    color: #94A3B8;
    font-weight: 400;
  }

  .modal .text-danger {
    color: #EF4444;
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">
        Permohonan Saya
        <span style="font-size: 14px; font-weight: normal; color: #64748B;"> - {{ $permohonan->nama }}</span>
      </div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>{{ now()->translatedFormat('F Y') }}</span>
      </div>

      <a href="{{ route('admin.permohonan-user.create', $permohonanId) }}" class="btn btn-primary">
        <i class="ri-add-line"></i>
        Buat Permohonan Baru
      </a>
    </div>
  </div>

  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.permohonan-user.index', $permohonanId) }}">
        <div class="input-group w-search">
          <span class="input-group-text"><i class="ri-search-line"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                 placeholder="Cari Nama atau Status" autocomplete="off">
        </div>
      </form>
    </div>

    <div class="card-body" style="padding:0;">
      <div class="table-responsive table-shell">
        <table class="table-permohonan">
          <thead>
            <tr>
              <th class="col-no">No</th>
              <th>Nama Permohonan</th>
              <th>Status</th>
              <th>Tanggal Dibuat</th>
              <th>Keterangan</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($permohonanUsers as $i => $permohonanUser)
              <tr>
                <td class="col-no">{{ $permohonanUsers->firstItem() + $i }}</td>
                <td><strong>{{ $permohonanUser->permohonan->nama }}</strong></td>
                <td>
                  <span class="badge badge-{{ $permohonanUser->status }}">
                    @if($permohonanUser->status === 'pending')
                      Pending
                    @elseif($permohonanUser->status === 'proses')
                      Proses
                    @elseif($permohonanUser->status === 'selesai')
                      Selesai
                    @elseif($permohonanUser->status === 'ditolak')
                      Ditolak
                    @else
                      {{ $permohonanUser->status }}
                    @endif
                  </span>
                </td>
                <td>{{ $permohonanUser->created_at->translatedFormat('d F Y') }}</td>
                <td>{{ $permohonanUser->keterangan ?? '-' }}</td>
                <td class="col-aksi">
                  @php
                    $userRole = auth()->user()->roles()->first()->name ?? null;
                    $isAdmin = in_array($userRole, ['admin', 'superadmin']);
                    $isOwner = $permohonanUser->user_id === auth()->id();
                  @endphp

                  @if($isAdmin)
                    {{-- Admin: Bisa update status reject, progress, approve --}}
                    @if($permohonanUser->status === 'pending')
                      {{-- Pending: bisa progress atau reject --}}
                      <button type="button" class="btn-ico progress btn-progress-permohonan-user"
                              data-id="{{ $permohonanUser->id }}"
                              data-name="{{ $permohonanUser->permohonan->nama }}"
                              title="Proses">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M2 17L12 22L22 17" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M2 12L12 17L22 12" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                      <button type="button" class="btn-ico danger btn-reject-permohonan-user"
                              data-id="{{ $permohonanUser->id }}"
                              data-name="{{ $permohonanUser->permohonan->nama }}"
                              title="Tolak">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M18 6L6 18M6 6L18 18" stroke="#F8285A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                    @elseif($permohonanUser->status === 'proses')
                      {{-- Proses: bisa approve atau reject --}}
                      <button type="button" class="btn-ico approve btn-approve-permohonan-user"
                              data-id="{{ $permohonanUser->id }}"
                              data-name="{{ $permohonanUser->permohonan->nama }}"
                              title="Setujui">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M20 6L9 17L4 12" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                      <button type="button" class="btn-ico danger btn-reject-permohonan-user"
                              data-id="{{ $permohonanUser->id }}"
                              data-name="{{ $permohonanUser->permohonan->nama }}"
                              title="Tolak">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M18 6L6 18M6 6L18 18" stroke="#F8285A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                    @endif
                  @elseif($isOwner)
                    {{-- User: Bisa cancel jika status pending --}}
                    @if($permohonanUser->status === 'pending')
                      <form action="{{ route('admin.permohonan-user.cancel', [$permohonanId, $permohonanUser]) }}" method="POST" style="display:inline-block;margin:0;" class="form-cancel-permohonan-user" data-name="{{ $permohonanUser->permohonan->nama }}">
                        @csrf @method('POST')
                        <button type="button" class="btn-ico danger btn-cancel-permohonan-user" title="Batalkan">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="#F8285A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </button>
                      </form>
                    @endif
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center" style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <div class="summary">Menampilkan
            <strong>{{ $permohonanUsers->firstItem() ?? 0 }}–{{ $permohonanUsers->lastItem() ?? 0 }}</strong> dari
            <strong>{{ $permohonanUsers->total() }}</strong> data</div>
          <nav aria-label="Pagination">
            {{ $permohonanUsers->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Approve -->
  <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="approveModalLabel">Setujui Permohonan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="approveForm" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="keterangan_approve" class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
              <textarea class="form-control" id="keterangan_approve" name="keterangan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Dokumen <span class="text-muted">(Opsional)</span></label>
              <div id="documentsContainer">
                <div class="document-item">
                  <div class="row g-3">
                    <div class="col-md-5">
                      <label class="form-label small">Nama Dokumen</label>
                      <input type="text" class="form-control form-control-sm" name="documents[0][nama]" placeholder="Nama dokumen">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small">File</label>
                      <input type="file" class="form-control form-control-sm" name="documents[0][file]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label small">Masa Berlaku</label>
                      <input type="date" class="form-control form-control-sm" name="documents[0][masa_berlaku]">
                    </div>
                  </div>
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-secondary" id="addDocumentBtn" style="margin-top: 12px;">
                <i class="ri-add-line"></i> Tambah Dokumen
              </button>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">
              <i class="ri-check-line"></i> Setujui
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Progress -->
  <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="progressModalLabel">Update Status ke Proses</h5>
          <button type="button" class="close" onclick="closeModal('progressModal')" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="progressForm" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="keterangan_progress" class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
              <textarea class="form-control" id="keterangan_progress" name="keterangan" rows="4" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">
              <i class="ri-play-line"></i> Update ke Proses
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Reject -->
  <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Tolak Permohonan</h5>
          <button type="button" class="close" onclick="closeModal('rejectModal')" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="rejectForm" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="keterangan_reject" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
              <textarea class="form-control" id="keterangan_reject" name="keterangan" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">
              <i class="ri-close-line"></i> Tolak
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Function to close modal
  function closeModal(modalId) {
    const modalEl = document.getElementById(modalId);
    const backdropId = modalId + 'Backdrop';
    const backdrop = document.getElementById(backdropId);

    if (modalEl) {
      modalEl.classList.remove('show');
    }

    if (backdrop) {
      backdrop.classList.remove('show');
      setTimeout(() => {
        if (backdrop.parentNode) {
          backdrop.parentNode.removeChild(backdrop);
        }
      }, 150);
    }
  }

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
    document.querySelectorAll('.btn-delete-permohonan-user').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.form-delete-permohonan-user');
        const name = form.dataset.name;

        Swal.fire({
          title: 'Konfirmasi Hapus',
          html: `Apakah Anda yakin ingin menghapus permohonan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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

    // Approve modal
    let documentIndex = 1;

    document.querySelectorAll('.btn-approve-permohonan-user').forEach(btn => {
      btn.addEventListener('click', function() {
        const permohonanUserId = this.dataset.id;
        const permohonanName = this.dataset.name;
        const form = document.getElementById('approveForm');
        form.action = `{{ route('admin.permohonan-user.index', $permohonanId) }}/${permohonanUserId}/approve`;
        document.getElementById('approveModalLabel').textContent = `Setujui Permohonan: ${permohonanName}`;
        document.getElementById('keterangan_approve').value = '';
        document.getElementById('documentsContainer').innerHTML = `
          <div class="document-item">
            <div class="row g-3">
              <div class="col-md-5">
                <label class="form-label small">Nama Dokumen</label>
                <input type="text" class="form-control form-control-sm" name="documents[0][nama]" placeholder="Nama dokumen">
              </div>
              <div class="col-md-4">
                <label class="form-label small">File</label>
                <input type="file" class="form-control form-control-sm" name="documents[0][file]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
              </div>
              <div class="col-md-3">
                <label class="form-label small">Masa Berlaku</label>
                <input type="date" class="form-control form-control-sm" name="documents[0][masa_berlaku]">
              </div>
            </div>
          </div>
        `;
        documentIndex = 1;

        // Show modal and backdrop
        const approveModalEl = document.getElementById('approveModal');
        const modalDialog = approveModalEl.querySelector('.modal-dialog');

        // Remove existing backdrop if any
        const existingBackdrop = document.getElementById('approveModalBackdrop');
        if (existingBackdrop) {
          existingBackdrop.remove();
        }

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        backdrop.id = 'approveModalBackdrop';
        document.body.appendChild(backdrop);

        approveModalEl.classList.add('show');
        backdrop.classList.add('show');

        // Close on backdrop click
        backdrop.addEventListener('click', function() {
          closeModal('approveModal');
        });

        // Prevent modal from closing when clicking inside modal content
        modalDialog.addEventListener('click', function(e) {
          e.stopPropagation();
        });

        // Close when clicking on modal container itself (outside dialog)
        approveModalEl.addEventListener('click', function(e) {
          if (e.target === approveModalEl) {
            closeModal('approveModal');
          }
        });
      });
    });

    // Progress modal
    document.querySelectorAll('.btn-progress-permohonan-user').forEach(btn => {
      btn.addEventListener('click', function() {
        const permohonanUserId = this.dataset.id;
        const permohonanName = this.dataset.name;
        const form = document.getElementById('progressForm');
        form.action = `{{ route('admin.permohonan-user.index', $permohonanId) }}/${permohonanUserId}/progress`;
        document.getElementById('progressModalLabel').textContent = `Update Status ke Proses: ${permohonanName}`;
        document.getElementById('keterangan_progress').value = '';

        // Show modal and backdrop
        const progressModalEl = document.getElementById('progressModal');
        const modalDialog = progressModalEl.querySelector('.modal-dialog');

        // Remove existing backdrop if any
        const existingBackdrop = document.getElementById('progressModalBackdrop');
        if (existingBackdrop) {
          existingBackdrop.remove();
        }

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        backdrop.id = 'progressModalBackdrop';
        document.body.appendChild(backdrop);

        progressModalEl.classList.add('show');
        backdrop.classList.add('show');

        // Close on backdrop click
        backdrop.addEventListener('click', function() {
          closeModal('progressModal');
        });

        // Prevent modal from closing when clicking inside modal content
        modalDialog.addEventListener('click', function(e) {
          e.stopPropagation();
        });

        // Close when clicking on modal container itself (outside dialog)
        progressModalEl.addEventListener('click', function(e) {
          if (e.target === progressModalEl) {
            closeModal('progressModal');
          }
        });
      });
    });

    // Reject modal
    document.querySelectorAll('.btn-reject-permohonan-user').forEach(btn => {
      btn.addEventListener('click', function() {
        const permohonanUserId = this.dataset.id;
        const permohonanName = this.dataset.name;
        const form = document.getElementById('rejectForm');
        form.action = `{{ route('admin.permohonan-user.index', $permohonanId) }}/${permohonanUserId}/reject`;
        document.getElementById('rejectModalLabel').textContent = `Tolak Permohonan: ${permohonanName}`;
        document.getElementById('keterangan_reject').value = '';

        // Show modal and backdrop
        const rejectModalEl = document.getElementById('rejectModal');
        const modalDialog = rejectModalEl.querySelector('.modal-dialog');

        // Remove existing backdrop if any
        const existingBackdrop = document.getElementById('rejectModalBackdrop');
        if (existingBackdrop) {
          existingBackdrop.remove();
        }

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        backdrop.id = 'rejectModalBackdrop';
        document.body.appendChild(backdrop);

        rejectModalEl.classList.add('show');
        backdrop.classList.add('show');

        // Close on backdrop click
        backdrop.addEventListener('click', function() {
          closeModal('rejectModal');
        });

        // Prevent modal from closing when clicking inside modal content
        modalDialog.addEventListener('click', function(e) {
          e.stopPropagation();
        });

        // Close when clicking on modal container itself (outside dialog)
        rejectModalEl.addEventListener('click', function(e) {
          if (e.target === rejectModalEl) {
            closeModal('rejectModal');
          }
        });
      });
    });

    // Add document row
    document.getElementById('addDocumentBtn')?.addEventListener('click', function() {
      const container = document.getElementById('documentsContainer');
      const newItem = document.createElement('div');
      newItem.className = 'document-item mb-3 p-3 border rounded';
      newItem.innerHTML = `
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label small">Nama Dokumen</label>
            <input type="text" class="form-control form-control-sm" name="documents[${documentIndex}][nama]" placeholder="Nama dokumen">
          </div>
          <div class="col-md-4">
            <label class="form-label small">File</label>
            <input type="file" class="form-control form-control-sm" name="documents[${documentIndex}][file]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Masa Berlaku</label>
            <input type="date" class="form-control form-control-sm" name="documents[${documentIndex}][masa_berlaku]">
          </div>
          <div class="col-md-1">
            <label class="form-label small">&nbsp;</label>
            <button type="button" class="btn btn-sm btn-danger w-100 remove-document" title="Hapus" style="height: 38px; padding: 0;">
              <i class="ri-delete-bin-line"></i>
            </button>
          </div>
        </div>
      `;
      container.appendChild(newItem);
      documentIndex++;

      // Add remove functionality
      newItem.querySelector('.remove-document').addEventListener('click', function() {
        newItem.remove();
      });
    });

    // Remove document functionality for existing items
    document.addEventListener('click', function(e) {
      if (e.target.closest('.remove-document')) {
        e.target.closest('.document-item').remove();
      }
    });

    // Cancel confirmation
    document.querySelectorAll('.btn-cancel-permohonan-user').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.form-cancel-permohonan-user');
        const name = form.dataset.name;

        Swal.fire({
          title: 'Konfirmasi Pembatalan',
          html: `Apakah Anda yakin ingin membatalkan permohonan <strong>${name}</strong>?<br><small class="text-muted">Permohonan yang dibatalkan tidak dapat dikembalikan.</small>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#94a3b8',
          confirmButtonText: '<i class="ri-close-line"></i> Ya, Batalkan!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // Make document fields optional - validate only if at least one document field is filled
    document.getElementById('approveForm')?.addEventListener('submit', function(e) {
      const documentItems = this.querySelectorAll('.document-item');
      let hasDocument = false;

      documentItems.forEach(item => {
        const nama = item.querySelector('input[name*="[nama]"]');
        const file = item.querySelector('input[name*="[file]"]');
        if (nama && nama.value.trim() && file && file.files.length > 0) {
          hasDocument = true;
        }
      });

      // If no documents are provided, that's fine (optional)
      // If documents are provided, both nama and file are required
      if (!hasDocument) {
        // Remove all required attributes
        this.querySelectorAll('input[name*="[nama]"], input[name*="[file]"]').forEach(input => {
          input.removeAttribute('required');
        });
      }
    });
  });
</script>
@endpush
