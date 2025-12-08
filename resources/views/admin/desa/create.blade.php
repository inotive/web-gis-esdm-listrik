{{-- resources/views/admin/desa/create.blade.php --}}
<div id="modalCreateDesa" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateTitle">
    <div class="modal-header">
      <h3 id="modalCreateTitle">Tambah Data Desa</h3>
      <button type="button" class="btn-x" id="btnCloseCreate" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreateDesa" method="POST" action="{{ route('admin.desa.store') }}">
      @csrf

      <div class="modal-body">
        {{-- Kabupaten/Kota --}}
        <div class="form-group">
          <label class="label">Kabupaten/Kota</label>
          <div class="control">
            <select name="regency_id" id="cRegency" class="input" required></select>
          </div>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <div class="control">
            <select name="district_id" id="cDistrict" class="input" required></select>
          </div>
        </div>

        {{-- Nama Desa --}}
        <div class="form-group">
          <label class="label">Nama Desa</label>
          <div class="control">
            <input type="text" name="name" id="cName" class="input" placeholder="Masukkan nama desa" required>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('styles')
  <style>
    .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;z-index:1000;padding:18px;overflow:auto;}
    .modal-overlay.show{display:block;}
    .modal{max-width:520px;margin:20px auto;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-2);overflow:hidden;}
    .modal-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid var(--line);}
    .modal-header h3{margin:0;font-weight:800;font-size:20px;letter-spacing:-.2px;}
    .btn-x{width:36px;height:36px;display:grid;place-items:center;border:1px solid #E2E8F0;background:#fff;border-radius:10px;cursor:pointer;}
    .btn-x:hover{background:#F8FAFC;}
    .modal-body{padding:18px 20px 6px;}
    .modal-footer{padding:14px 20px 18px;}
    .btn-save{width:100%;height:44px;border:none;border-radius:10px;font-weight:700;color:#fff;background:var(--accent-2);box-shadow:0 10px 22px rgba(34,197,94,.22);cursor:pointer;}
    .btn-save:hover{filter:brightness(.95);}
    .form-group{margin-bottom:16px;}
    .label{display:block;font-size:14px;color:#374151;margin:6px 0 8px;font-weight:600;}
    .control{position:relative;}
    .input{width:100%;height:44px;padding:0 12px;border:1px solid #E2E8F0;border-radius:10px;background:#FCFCFD;outline:none;font:inherit;color:#111827;}
    .input::placeholder{color:#94A3B8;}
    .input:focus{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
  </style>
@endpush

@push('scripts')
  <script>
    (function () {
      const overlay = document.getElementById('modalCreateDesa');
      const btnClose = document.getElementById('btnCloseCreate');
      const btnAdd = document.querySelector('.btn-add');

      // selects
      const selReg = document.getElementById('cRegency');
      const selDis = document.getElementById('cDistrict');

      function option(el, value, label) {
        const o = document.createElement('option');
        o.value = value; o.textContent = label;
        el.appendChild(o);
      }

      async function loadRegencies() {
        selReg.innerHTML = '';
        option(selReg, '', 'Pilih Kabupaten/Kota');
        const res = await fetch(`{{ route('admin.desa.options.regencies') }}`);
        const rows = await res.json();
        rows.forEach(r => option(selReg, r.id, r.name));
        selDis.innerHTML = ''; option(selDis, '', 'Pilih Kecamatan');
      }

      async function loadDistricts(regencyId) {
        selDis.innerHTML = ''; option(selDis, '', 'Pilih Kecamatan');
        if (!regencyId) return;
        const res = await fetch(`{{ route('admin.desa.options.districts') }}?regency_id=${encodeURIComponent(regencyId)}`);
        const rows = await res.json();
        rows.forEach(r => option(selDis, r.id, r.name));
      }

      selReg?.addEventListener('change', () => loadDistricts(selReg.value));

      function openModal() {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
      }
      function closeModal() {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
        // Reset form
        document.getElementById('formCreateDesa').reset();
        selReg.innerHTML = '';
        selDis.innerHTML = '';
      }

      // Open/close
      btnAdd?.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
        loadRegencies();
      });
      btnClose?.addEventListener('click', closeModal);
      overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

      if (location.hash === '#create') { openModal(); loadRegencies(); }
    })();
  </script>
@endpush
