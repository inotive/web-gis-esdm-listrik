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
            <select name="regency_id" id="edit_regency_id" class="input" data-control="select2" data-placeholder="Pilih Kabupaten/Kota" required></select>
          </div>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <div class="control">
            <select name="district_id" id="edit_district_id" class="input" data-control="select2" data-placeholder="Pilih Kecamatan" required></select>
          </div>
        </div>

        {{-- Desa/Kelurahan --}}
        <div class="form-group">
          <label class="label">Desa/Kelurahan</label>
          <div class="control">
            <select name="village_id" id="edit_village_id" class="input" data-control="select2" data-placeholder="Pilih Desa/Kelurahan" required></select>
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

    /* Select2 Styling for Modal */
    .modal .select2-container {
      width: 100% !important;
    }

    .modal .select2-container--default .select2-selection--single {
      height: 44px !important;
      border: 1px solid #E2E8F0 !important;
      border-radius: 10px !important;
      background: #FCFCFD !important;
      display: flex !important;
      align-items: center !important;
    }

    .modal .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 44px !important;
      padding-left: 12px !important;
      padding-right: 28px !important;
      font-size: 14px !important;
      color: #111827 !important;
    }

    .modal .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 42px !important;
      right: 12px !important;
      top: 1px !important;
    }

    .modal .select2-container--default .select2-selection--single:focus,
    .modal .select2-container--default.select2-container--focus .select2-selection--single {
      border-color: #CBD5E1 !important;
      box-shadow: 0 0 0 3px rgba(16,185,129,.12) !important;
    }

    .modal .select2-dropdown {
      border: 1px solid #E2E8F0 !important;
      border-radius: 10px !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
      margin-top: 4px !important;
      z-index: 10001 !important;
    }

    .modal .select2-search--dropdown {
      padding: 8px !important;
      border-bottom: 1px solid #F1F1F4 !important;
    }

    .modal .select2-search--dropdown .select2-search__field {
      border: 1px solid #E2E8F0 !important;
      border-radius: 6px !important;
      padding: 6px 10px !important;
      font-size: 14px !important;
      color: #111827 !important;
      outline: none !important;
    }

    .modal .select2-search--dropdown .select2-search__field:focus {
      border-color: #CBD5E1 !important;
      box-shadow: 0 0 0 3px rgba(16,185,129,.12) !important;
    }

    .modal .select2-results__option {
      padding: 10px 12px !important;
      font-size: 14px !important;
      color: #111827 !important;
    }

    .modal .select2-results__option--highlighted {
      background: #F0FDF4 !important;
      color: #047857 !important;
    }

    .modal .select2-results__option[aria-selected="true"] {
      background: #17C653 !important;
      color: #fff !important;
    }
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

        // Destroy Select2 instances in modal
        if (jQuery && jQuery.fn.select2) {
          const modal = o.querySelector('.modal');
          if (modal) {
            modal.querySelectorAll('select[data-control="select2"]').forEach(sel => {
              if (jQuery(sel).hasClass('select2-hidden-accessible')) {
                jQuery(sel).select2('destroy');
              }
            });
          }
        }
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

      // Function to initialize Select2 for edit modal
      function initSelect2Edit(element, placeholder) {
        if (jQuery && jQuery.fn.select2) {
          if (jQuery(element).hasClass('select2-hidden-accessible')) {
            jQuery(element).select2('destroy');
          }
          jQuery(element).select2({
            placeholder: placeholder,
            width: '100%',
            language: {
              noResults: function() { return "Tidak ada hasil"; },
              searching: function() { return "Mencari..."; }
            }
          });
        }
      }

      if (selRegEdit && selDisEdit) {
        const regChangeHandler = async function() {
          const rid = this.value;
          selDisEdit.innerHTML = '';
          selVilEdit.innerHTML = '';

          // Destroy Select2
          if (jQuery && jQuery.fn.select2) {
            if (jQuery(selDisEdit).hasClass('select2-hidden-accessible')) {
              jQuery(selDisEdit).select2('destroy');
            }
            if (jQuery(selVilEdit).hasClass('select2-hidden-accessible')) {
              jQuery(selVilEdit).select2('destroy');
            }
          }

          if (!rid) return;
          const res = await fetch('{{ route("admin.perusahaan.options.districts") }}?regency_id=' + encodeURIComponent(rid));
          const districts = await res.json();
          districts.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.name;
            selDisEdit.appendChild(opt);
          });

          // Reinitialize Select2 for district
          initSelect2Edit(selDisEdit, 'Pilih Kecamatan');
        };

        if (jQuery && jQuery.fn.select2) {
          jQuery(selRegEdit).on('change', regChangeHandler);
        } else {
          selRegEdit.addEventListener('change', regChangeHandler);
        }
      }

      if (selDisEdit && selVilEdit) {
        const disChangeHandler = async function() {
          const did = this.value;
          selVilEdit.innerHTML = '';

          // Destroy Select2
          if (jQuery && jQuery.fn.select2) {
            if (jQuery(selVilEdit).hasClass('select2-hidden-accessible')) {
              jQuery(selVilEdit).select2('destroy');
            }
          }

          if (!did) return;
          const res = await fetch('{{ route("admin.perusahaan.options.villages") }}?district_id=' + encodeURIComponent(did));
          const villages = await res.json();
          villages.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = v.name;
            selVilEdit.appendChild(opt);
          });

          // Reinitialize Select2 for village
          initSelect2Edit(selVilEdit, 'Pilih Desa/Kelurahan');
        };

        if (jQuery && jQuery.fn.select2) {
          jQuery(selDisEdit).on('change', disChangeHandler);
        } else {
          selDisEdit.addEventListener('change', disChangeHandler);
        }
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

          // Destroy Select2 if initialized
          if (jQuery && jQuery.fn.select2 && jQuery(selReg).hasClass('select2-hidden-accessible')) {
            jQuery(selReg).select2('destroy');
          }

          const resReg = await fetch('{{ route("admin.perusahaan.options.regencies") }}');
          const regencies = await resReg.json();
          regencies.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.name;
            opt.selected = r.id === regencyId;
            selReg.appendChild(opt);
          });

          // Initialize Select2 for regency
          initSelect2Edit(selReg, 'Pilih Kabupaten/Kota');
          if (regencyId && jQuery && jQuery.fn.select2) {
            jQuery(selReg).val(regencyId).trigger('change');
          }

          // Load districts for selected regency
          const selDis = document.getElementById('edit_district_id');
          selDis.innerHTML = '';

          // Destroy Select2 if initialized
          if (jQuery && jQuery.fn.select2 && jQuery(selDis).hasClass('select2-hidden-accessible')) {
            jQuery(selDis).select2('destroy');
          }

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

            // Initialize Select2 for district
            initSelect2Edit(selDis, 'Pilih Kecamatan');
            if (districtId && jQuery && jQuery.fn.select2) {
              jQuery(selDis).val(districtId).trigger('change');
            }

            // Load villages for selected district
            const selVil = document.getElementById('edit_village_id');
            selVil.innerHTML = '';

            // Destroy Select2 if initialized
            if (jQuery && jQuery.fn.select2 && jQuery(selVil).hasClass('select2-hidden-accessible')) {
              jQuery(selVil).select2('destroy');
            }

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

              // Initialize Select2 for village
              initSelect2Edit(selVil, 'Pilih Desa/Kelurahan');
              if (villageId && jQuery && jQuery.fn.select2) {
                jQuery(selVil).val(villageId).trigger('change');
              }
            }
          }

          __openModal('modalEditPerusahaan');
        });
      });
    });
  </script>
@endpush
