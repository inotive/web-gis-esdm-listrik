<div id="modalCreateSkoring" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateSkoringTitle">
    <div class="modal-header">
      <h3 id="modalCreateSkoringTitle">Tambah Variabel Skoring</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalCreateSkoring')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <form id="formCreateSkoring" method="POST" action="{{ route('admin.skoring.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Variabel <span style="color:#DC2626">*</span></label>
          <input class="input" name="nama" placeholder="Contoh: Jumlah KK" required>
        </div>
        <div class="form-group">
          <label>Bobot (1 - 100%) <span style="color:#DC2626">*</span></label>
          <input class="input" name="bobot" type="number" step="1" min="1" max="100" placeholder="25" required>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <textarea class="textarea" name="keterangan" placeholder="Penjelasan variabel"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="__closeModal('modalCreateSkoring')">Batal</button>
        <button class="btn-save" type="submit">
          <i class="ri-save-3-line"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<div id="modalEditSkoring" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditSkoringTitle">
    <div class="modal-header">
      <h3 id="modalEditSkoringTitle">Edit Variabel Skoring</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalEditSkoring')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <form id="formEditSkoring" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Variabel <span style="color:#DC2626">*</span></label>
          <input class="input" name="nama" id="edit_nama" placeholder="Contoh: Jumlah KK" required>
        </div>
        <div class="form-group">
          <label>Bobot (1 - 100%) <span style="color:#DC2626">*</span></label>
          <input class="input" name="bobot" id="edit_bobot" type="number" step="1" min="1" max="100" placeholder="25" required>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <textarea class="textarea" name="keterangan" id="edit_keterangan" placeholder="Penjelasan variabel"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="__closeModal('modalEditSkoring')">Batal</button>
        <button class="btn-save" type="submit">
          <i class="ri-save-3-line"></i> Update
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  window.__openModal  = id => {
    // Close any open modals first
    document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
    const o = document.getElementById(id);
    if(o){
      o.classList.add('show');
      document.body.style.overflow='hidden';
    }
  };
  window.__closeModal = id => {
    const o = document.getElementById(id);
    if(o){
      o.classList.remove('show');
      document.body.style.overflow='';
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    // Open create modal
    const btn = document.querySelector('.page-actions .btn-add');
    if(btn) btn.addEventListener('click', (e)=> {
      e.preventDefault();
      __openModal('modalCreateSkoring');
    });

    // Close modal on backdrop click
    document.getElementById('modalCreateSkoring')?.addEventListener('click', e=>{
      if(e.target.id==='modalCreateSkoring') __closeModal('modalCreateSkoring');
    });
    document.getElementById('modalEditSkoring')?.addEventListener('click', e=>{
      if(e.target.id==='modalEditSkoring') __closeModal('modalEditSkoring');
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
      if(e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.show').forEach(m => {
          m.classList.remove('show');
          document.body.style.overflow='';
        });
      }
    });

    // Edit button handlers
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const nama = this.getAttribute('data-nama');
        const bobot = this.getAttribute('data-bobot');
        const keterangan = this.getAttribute('data-keterangan') || '';

        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_bobot').value = bobot;
        document.getElementById('edit_keterangan').value = keterangan;
        document.getElementById('formEditSkoring').action = '{{ route("admin.skoring.update", ":id") }}'.replace(':id', id);

        __openModal('modalEditSkoring');
      });
    });

    // Delete button handlers
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const nama = this.getAttribute('data-nama');

        Swal.fire({
          title: 'Konfirmasi Hapus',
          html: `Apakah Anda yakin ingin menghapus variabel <strong>${nama}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#94a3b8',
          confirmButtonText: '<i class="ri-delete-bin-line"></i> Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            confirmButton: 'btn-swal-confirm',
            cancelButton: 'btn-swal-cancel'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.skoring.destroy", ":id") }}'.replace(':id', id);
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
