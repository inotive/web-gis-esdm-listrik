@extends('admin.layouts.app')

@section('title', 'Detail Perizinan')

@push('styles')
<style>
  .card {
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: var(--shadow-1);
    padding: 24px;
    margin-top: 18px;
  }

  .detail-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #F1F1F4;
  }

  .detail-row:last-child {
    border-bottom: none;
  }

  .detail-label {
    font-weight: 600;
    color: #4B5675;
    font-size: 14px;
  }

  .detail-value {
    color: #252F4A;
    font-size: 14px;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid var(--line);
    cursor: pointer;
    background: #fff;
    text-decoration: none;
    font-size: 14px;
  }

  .btn-primary {
    background: var(--accent-2);
    color: #fff;
    border: none;
  }

  .btn-secondary {
    background: #6b7280;
    color: #fff;
    border: none;
  }

  .section-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #E5E7EB;
  }

  .documents-list {
    margin-top: 16px;
  }

  .document-item {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
  }

  .document-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
  }

  .document-name {
    font-weight: 600;
    color: #111827;
  }

  .document-meta {
    font-size: 12px;
    color: #6B7280;
    margin-top: 8px;
  }

  .expiry-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 8px;
  }

  .expiry-badge.expired {
    background: #FEE2E2;
    color: #991B1B;
  }

  .expiry-badge.warning {
    background: #FEF3C7;
    color: #92400E;
  }

  .expiry-badge.safe {
    background: #D1FAE5;
    color: #065F46;
  }

  .document-actions {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .btn-icon {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.2s;
  }

  .btn-icon:hover {
    background: #F3F4F6;
  }

  .btn-icon.download svg path {
    stroke: #3B82F6;
  }

  .btn-icon.delete svg path {
    stroke: #EF4444;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #E5E7EB;
  }

  .btn-add-document {
    height: 36px;
    padding: 0 16px;
    background: var(--accent-2);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-add-document:hover {
    opacity: 0.9;
  } 

  .badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .badge-pln { background: #D1FAE5; color: #065F46; } /* Hijau - PLN */
  .badge-non-pln { background: #FEF3C7; color: #92400E; } /* Kuning - Non-PLN */
  .badge-none { background: #FEE2E2; color: #991B1B; } /* Merah - Tidak Berlistrik */

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
    max-width: 600px;
    pointer-events: none;
    z-index: 1051;
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
    gap: 12px;
  }

  .modal .form-label {
    font-weight: 600;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
    display: block;
  }

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

  .modal textarea.form-control {
    height: auto;
    min-height: 100px;
    padding: 10px 12px;
    resize: vertical;
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

  .mb-3 {
    margin-bottom: 16px;
  }
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">Detail Perizinan</div>
  </div>
  <div class="page-actions">
    <a href="{{ route('admin.perizinan.index') }}" class="btn btn-secondary">
      <i class="ri-arrow-left-line"></i>
      Kembali
    </a>
    @php
      $user = auth()->user();
      $isOwner = ($user->perusahaan_id && $user->perusahaan_id == $perizinan->perusahaan_id) || ($perizinan->created_by == $user->id);
      $canEdit = $user->can('perizinan.edit') || $isOwner;
    @endphp
    @if($canEdit)
    <a href="{{ route('admin.perizinan.edit', $perizinan->id) }}" class="btn btn-primary">
      <i class="ri-edit-line"></i>
      Edit
    </a>
    @endif
  </div>
</div>

<section class="card">
  <h3 class="section-title">Informasi Umum</h3>



  <div class="detail-row">
    <div class="detail-label">Nama Perusahaan</div>
    <div class="detail-value">{{ $perizinan->perusahaan->nama ?? $perizinan->nama_perusahaan ?? $perizinan->nama ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Kontak</div>
    <div class="detail-value">{{ $perizinan->kontak ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Jenis</div>
    <div class="detail-value">{{ $perizinan->jenis ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">No. Pengajuan</div>
    <div class="detail-value">{{ $perizinan->no_pengajuan ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">No. Surat Keluar</div>
    <div class="detail-value">{{ $perizinan->no_surat_keluar ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Tanggal</div>
    <div class="detail-value">{{ $perizinan->tanggal ? $perizinan->tanggal->translatedFormat('d F Y') : '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Lokasi</div>
    <div class="detail-value">{{ $perizinan->lokasi ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Titik Koordinat</div>
    <div class="detail-value">{{ $perizinan->titik_koordinat ?? '-' }}</div>
  </div>



  <div class="detail-row">
    <div class="detail-label">Jumlah Unit</div>
    <div class="detail-value">{{ $perizinan->jumlah ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Kapasitas</div>
    <div class="detail-value">{{ $perizinan->kapasitas ? number_format($perizinan->kapasitas, 2) : '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Total Kapasitas (kVA)</div>
    <div class="detail-value">{{ $perizinan->total_kapasitas_kva ? number_format($perizinan->total_kapasitas_kva, 2) : '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Jenis Pembangkit</div>
    <div class="detail-value">{{ $perizinan->jenis_penggunaan ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Sifat Penggunaan</div>
    <div class="detail-value">{{ $perizinan->sifat_penggunaan ?? '-' }}</div>
  </div>

  <div class="detail-row">
    <div class="detail-label">Catatan</div>
    <div class="detail-value">{{ $perizinan->catatan ?? '-' }}</div>
  </div>
</section>

<section class="card" style="margin-top: 24px;">
  <div class="section-header">
    <h3 class="section-title" style="margin: 0; border: none; padding: 0;">Dokumen Perizinan</h3>
    @if($canEdit)
    <button type="button" class="btn-add-document" onclick="openAddDocumentModal()">
      <i class="ri-add-line"></i>
      Tambah Dokumen
    </button>
    @endif
  </div>

  @if($perizinan->documents->count() > 0)
    <div class="documents-list">
      @foreach($perizinan->documents as $document)
        <div class="document-item">
          <div class="document-item-header">
            <div class="document-name">{{ $document->nama }}</div>
            <div class="document-actions">
              @if($document->dokumen)
                <a href="{{ route('admin.dokumen.download', $document->dokumen) }}" class="btn-icon download" title="Download">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 10L12 15L17 10" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 15V3" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </a>
              @endif
              @if($canEdit)
              <form action="{{ route('admin.perizinan.document.delete', [$perizinan->id, $document->id]) }}" method="POST" style="display:inline;" class="form-delete-document" data-name="{{ $document->nama }}">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-icon delete btn-delete-document" title="Hapus">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 6H5H21" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 11V17" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 11V17" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </form>
              @endif
            </div>
          </div>
          <div class="document-meta">
            <div>No. Surat Izin Terbit: {{ $document->no_surat_izin_terbit ?? '-' }}</div>
            <div>Tanggal Terbit: {{ $document->tanggal_terbit ? $document->tanggal_terbit->translatedFormat('d F Y') : '-' }}</div>
            <div>Tanggal Akhir: {{ $document->tanggal_akhir ? $document->tanggal_akhir->translatedFormat('d F Y') : '-' }}</div>
            @if($document->tanggal_akhir)
              @php
                $now = now();
                $tanggalAkhir = \Carbon\Carbon::parse($document->tanggal_akhir);
                $diffDays = $now->diffInDays($tanggalAkhir, false);
                $isExpired = $tanggalAkhir->isPast();
                $isWarning = !$isExpired && $diffDays <= 30;
              @endphp
              @if($isExpired)
                <div class="expiry-badge expired">
                  <i class="ri-error-warning-line"></i> Kadaluarsa {{ abs($diffDays) }} hari yang lalu
                </div>
              @elseif($isWarning)
                <div class="expiry-badge warning">
                  <i class="ri-alert-line"></i> Tersisa {{ $diffDays }} hari
                </div>
              @else
                <div class="expiry-badge safe">
                  <i class="ri-checkbox-circle-line"></i> Tersisa {{ $diffDays }} hari
                </div>
              @endif
            @endif
            @if($document->dokumen)
              <div style="margin-top: 8px;">File: {{ $document->dokumen->nama }} ({{ $document->dokumen->formatted_size }})</div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div style="text-align: center; padding: 40px; color: #64748B;">
      <i class="ri-file-line" style="font-size: 48px; margin-bottom: 12px; display: block;"></i>
      <p>Belum ada dokumen</p>
      <p style="font-size: 12px; margin-top: 8px;">Klik tombol "Tambah Dokumen" untuk menambahkan dokumen perizinan</p>
    </div>
  @endif
</section>

<!-- Modal Add Document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDocumentModalLabel">Tambah Dokumen Perizinan</h5>
        <button type="button" class="close" onclick="closeModal('addDocumentModal')" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addDocumentForm" method="POST" action="{{ route('admin.perizinan.document.add', $perizinan->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Dokumen <span style="color:#DC2626">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama dokumen">
          </div>

          <div class="mb-3">
            <label for="file" class="form-label">File <span style="color:#DC2626">*</span></label>
            <input type="file" class="form-control" id="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            <small style="color: #64748B; font-size: 12px; margin-top: 4px; display: block;">Format yang diperbolehkan: PDF, DOC, DOCX, JPG, JPEG, PNG (Maks. 10MB)</small>
          </div>

          <div class="mb-3">
            <label for="no_surat_izin_terbit" class="form-label">No. Surat Izin Terbit</label>
            <input type="text" class="form-control" id="no_surat_izin_terbit" name="no_surat_izin_terbit" placeholder="Masukkan nomor surat izin terbit">
          </div>

          <div class="mb-3">
            <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
            <input type="date" class="form-control" id="tanggal_terbit" name="tanggal_terbit">
          </div>

          <div class="mb-3">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" onclick="closeModal('addDocumentModal')">Batal</button>
          <button type="submit" class="btn-success">
            <i class="ri-save-line"></i>
            Simpan
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

    // Reset form
    if (modalId === 'addDocumentModal') {
      document.getElementById('addDocumentForm').reset();
    }
  }

  function openAddDocumentModal() {
    const modalEl = document.getElementById('addDocumentModal');
    const modalDialog = modalEl.querySelector('.modal-dialog');

    // Remove existing backdrop if any
    const existingBackdrop = document.getElementById('addDocumentModalBackdrop');
    if (existingBackdrop) {
      existingBackdrop.remove();
    }

    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop';
    backdrop.id = 'addDocumentModalBackdrop';
    document.body.appendChild(backdrop);

    modalEl.classList.add('show');
    backdrop.classList.add('show');

    // Close on backdrop click
    backdrop.addEventListener('click', function() {
      closeModal('addDocumentModal');
    });

    // Prevent modal from closing when clicking inside modal content
    modalDialog.addEventListener('click', function(e) {
      e.stopPropagation();
    });

    // Close when clicking on modal container itself (outside dialog)
    modalEl.addEventListener('click', function(e) {
      if (e.target === modalEl) {
        closeModal('addDocumentModal');
      }
    });
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
    document.querySelectorAll('.btn-delete-document').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.form-delete-document');
        const name = form.dataset.name;

        Swal.fire({
          title: 'Konfirmasi Hapus',
          html: `Apakah Anda yakin ingin menghapus dokumen <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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
  });
</script>
@endpush

