{{-- resources/views/admin/perusahaan/edit_modal.blade.php --}}
<div id="modalEditPerusahaan" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditPerusahaanTitle">
    <div class="modal-header">
      <h3 id="modalEditPerusahaanTitle">Edit Data Perusahaan</h3>
      <button type="button" class="btn-x" onclick="__closeModal('modalEditPerusahaan')" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formEditPerusahaan" method="POST">
      @csrf
      @method('PUT')

      <div class="modal-body">
        {{-- Kabupaten/Kota --}}
        <div class="form-group">
          <label class="label">Kabupaten/Kota</label>
          <div class="control">
            <select name="regency_id" id="edit_regency_id" class="input" required></select>
          </div>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <div class="control">
            <select name="district_id" id="edit_district_id" class="input" required></select>
          </div>
        </div>

        {{-- Desa/Kelurahan --}}
        <div class="form-group">
          <label class="label">Desa/Kelurahan</label>
          <div class="control">
            <select name="village_id" id="edit_village_id" class="input" required></select>
          </div>
        </div>

        {{-- Nama Perusahaan --}}
        <div class="form-group">
          <label class="label">Nama Perusahaan</label>
          <div class="control">
            <input type="text" name="nama" id="edit_nama" class="input" placeholder="Masukkan nama perusahaan" required>
          </div>
        </div>

        {{-- Alamat --}}
        <div class="form-group">
          <label class="label">Alamat</label>
          <div class="control">
            <textarea name="alamat" id="edit_alamat" class="input" rows="3" placeholder="Masukkan alamat perusahaan"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="__closeModal('modalEditPerusahaan')">Batal</button>
        <button class="btn-save" type="submit">
          <i class="ri-save-3-line"></i> Update
        </button>
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
    .modal-footer{padding:14px 20px 18px;display:flex;gap:10px;justify-content:flex-end;}
    .btn-cancel{height:44px;padding:0 20px;border:1px solid #E2E8F0;border-radius:10px;font-weight:600;color:#64748B;background:#fff;cursor:pointer;}
    .btn-cancel:hover{background:#F8FAFC;}
    .btn-save{height:44px;padding:0 20px;border:none;border-radius:10px;font-weight:700;color:#fff;background:var(--accent-2);box-shadow:0 10px 22px rgba(34,197,94,.22);cursor:pointer;display:inline-flex;align-items:center;gap:6px;}
    .btn-save:hover{filter:brightness(.95);}
    .form-group{margin-bottom:16px;}
    .label{display:block;font-size:14px;color:#374151;margin:6px 0 8px;font-weight:600;}
    .control{position:relative;}
    .input{width:100%;height:44px;padding:0 12px;border:1px solid #E2E8F0;border-radius:10px;background:#FCFCFD;outline:none;font:inherit;color:#111827;}
    .input::placeholder{color:#94A3B8;}
    .input:focus{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
    .control textarea{min-height:80px;padding:12px;resize:vertical;}
  </style>
@endpush

@push('scripts')
  <script>
    window.__openModal = id => {
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
      // Close modal on backdrop click
      document.getElementById('modalEditPerusahaan')?.addEventListener('click', e=>{
        if(e.target.id==='modalEditPerusahaan') __closeModal('modalEditPerusahaan');
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

      // Cascading dropdown handlers for edit modal
      const selRegEdit = document.getElementById('edit_regency_id');
      const selDisEdit = document.getElementById('edit_district_id');
      const selVilEdit = document.getElementById('edit_village_id');

      if (selRegEdit && selDisEdit) {
        selRegEdit.addEventListener('change', async function() {
          const rid = this.value;
          selDisEdit.innerHTML = '';
          selVilEdit.innerHTML = '';
          if (!rid) return;
          const res = await fetch('{{ route("admin.perusahaan.options.districts") }}?regency_id=' + encodeURIComponent(rid));
          const districts = await res.json();
          districts.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.name;
            selDisEdit.appendChild(opt);
          });
        });
      }

      if (selDisEdit && selVilEdit) {
        selDisEdit.addEventListener('change', async function() {
          const did = this.value;
          selVilEdit.innerHTML = '';
          if (!did) return;
          const res = await fetch('{{ route("admin.perusahaan.options.villages") }}?district_id=' + encodeURIComponent(did));
          const villages = await res.json();
          villages.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = v.name;
            selVilEdit.appendChild(opt);
          });
        });
      }

      // Edit button handlers
      document.querySelectorAll('.btn-edit-perusahaan').forEach(btn => {
        btn.addEventListener('click', async function() {
          const id = this.getAttribute('data-id');
          const nama = this.getAttribute('data-nama');
          const alamat = this.getAttribute('data-alamat') || '';
          const regencyId = this.getAttribute('data-regency-id');
          const districtId = this.getAttribute('data-district-id');
          const villageId = this.getAttribute('data-village-id');

          // Set form action
          document.getElementById('formEditPerusahaan').action = '{{ route("admin.perusahaan.update", ":id") }}'.replace(':id', id);

          // Set nama and alamat
          document.getElementById('edit_nama').value = nama;
          document.getElementById('edit_alamat').value = alamat;

          // Load regencies
          const selReg = document.getElementById('edit_regency_id');
          selReg.innerHTML = '';
          const resReg = await fetch('{{ route("admin.perusahaan.options.regencies") }}');
          const regencies = await resReg.json();
          regencies.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.name;
            opt.selected = r.id === regencyId;
            selReg.appendChild(opt);
          });

          // Load districts for selected regency
          const selDis = document.getElementById('edit_district_id');
          selDis.innerHTML = '';
          if (regencyId) {
            const resDis = await fetch('{{ route("admin.perusahaan.options.districts") }}?regency_id=' + encodeURIComponent(regencyId));
            const districts = await resDis.json();
            districts.forEach(d => {
              const opt = document.createElement('option');
              opt.value = d.id;
              opt.textContent = d.name;
              opt.selected = d.id === districtId;
              selDis.appendChild(opt);
            });

            // Load villages for selected district
            const selVil = document.getElementById('edit_village_id');
            selVil.innerHTML = '';
            if (districtId) {
              const resVil = await fetch('{{ route("admin.perusahaan.options.villages") }}?district_id=' + encodeURIComponent(districtId));
              const villages = await resVil.json();
              villages.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.id;
                opt.textContent = v.name;
                opt.selected = v.id === villageId;
                selVil.appendChild(opt);
              });
            }
          }

          __openModal('modalEditPerusahaan');
        });
      });
    });
  </script>
@endpush
