{{-- resources/views/admin/perusahaan/create.blade.php --}}
<div id="modalCreatePerusahaan" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateTitle">
    <div class="modal-header">
      <h3 id="modalCreateTitle">Tambah Data Perusahaan</h3>
      <button type="button" class="btn-x" id="btnCloseCreate" aria-label="Tutup">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formCreatePerusahaan" method="POST" action="{{ route('admin.perusahaan.store') }}">
      @csrf

      <div class="modal-body">
        {{-- Kabupaten/Kota --}}
        <div class="form-group">
          <label class="label">Kabupaten/Kota</label>
          <div class="control">
            <select name="regency_id" id="cRegency" class="input" data-control="select2" data-placeholder="Pilih Kabupaten/Kota" required></select>
          </div>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <div class="control">
            <select name="district_id" id="cDistrict" class="input" data-control="select2" data-placeholder="Pilih Kecamatan" required></select>
          </div>
        </div>

        {{-- Desa/Kelurahan --}}
        <div class="form-group">
          <label class="label">Desa/Kelurahan</label>
          <div class="control">
            <select name="village_id" id="cVillage" class="input" data-control="select2" data-placeholder="Pilih Desa/Kelurahan" required></select>
          </div>
        </div>

        {{-- Nama Perusahaan --}}
        <div class="form-group">
          <label class="label">Nama Perusahaan</label>
          <div class="control">
            <input type="text" name="nama" id="cNama" class="input" placeholder="Masukkan nama perusahaan" required>
          </div>
        </div>

        {{-- Alamat --}}
        <div class="form-group">
          <label class="label">Alamat</label>
          <div class="control">
            <textarea name="alamat" id="cAlamat" class="input" rows="3" placeholder="Masukkan alamat perusahaan"></textarea>
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
    (function () {
      // Wait for jQuery to be available
      function waitForJQuery(callback) {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
          callback();
        } else {
          setTimeout(() => waitForJQuery(callback), 100);
        }
      }

      const overlay = document.getElementById('modalCreatePerusahaan');
      const btnClose = document.getElementById('btnCloseCreate');
      const btnAdd = document.querySelector('.btn-add');

      // selects
      const selReg = document.getElementById('cRegency');
      const selDis = document.getElementById('cDistrict');
      const selVil = document.getElementById('cVillage');

      function option(el, value, label) {
        const o = document.createElement('option');
        o.value = value; o.textContent = label;
        el.appendChild(o);
      }

      async function loadRegencies() {
        selReg.innerHTML = '';
        option(selReg, '', 'Pilih Kabupaten/Kota');
        const res = await fetch(`{{ route('admin.perusahaan.options.regencies') }}`);
        const rows = await res.json();
        rows.forEach(r => option(selReg, r.id, r.name));

        // Initialize Select2 for regency
        if (jQuery && jQuery.fn.select2) {
          // Destroy if already initialized
          if (jQuery(selReg).hasClass('select2-hidden-accessible')) {
            jQuery(selReg).select2('destroy');
          }

          jQuery(selReg).select2({
            placeholder: 'Pilih Kabupaten/Kota',
            width: '100%',
            language: {
              noResults: function() { return "Tidak ada hasil"; },
              searching: function() { return "Mencari..."; }
            }
          });

          // Attach event listener after Select2 initialization
          // Use setTimeout to ensure Select2 is fully initialized
          setTimeout(function() {
            jQuery(selReg).off('change');
            jQuery(selReg).on('change', function() {
              const regencyId = jQuery(this).val();
              // Ensure Select2 displays the selected value
              if (regencyId) {
                jQuery(this).trigger('change.select2');
                loadDistricts(regencyId);
              }
            });
          }, 100);
        }

        selDis.innerHTML = ''; option(selDis, '', 'Pilih Kecamatan');
        selVil.innerHTML = ''; option(selVil, '', 'Pilih Desa/Kelurahan');

        // Destroy Select2 for district and village
        if (jQuery && jQuery.fn.select2) {
          if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
            jQuery(selDis).select2('destroy');
          }
          if (jQuery(selVil).hasClass('select2-hidden-accessible')) {
            jQuery(selVil).select2('destroy');
          }
        }
      }

      async function loadDistricts(regencyId) {
        selDis.innerHTML = ''; option(selDis, '', 'Pilih Kecamatan');
        selVil.innerHTML = ''; option(selVil, '', 'Pilih Desa/Kelurahan');

        // Destroy Select2 for district and village
        if (jQuery && jQuery.fn.select2) {
          if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
            jQuery(selDis).select2('destroy');
          }
          if (jQuery(selVil).hasClass('select2-hidden-accessible')) {
            jQuery(selVil).select2('destroy');
          }
        }

        if (!regencyId) return;
        const res = await fetch(`{{ route('admin.perusahaan.options.districts') }}?regency_id=${encodeURIComponent(regencyId)}`);
        const rows = await res.json();
        rows.forEach(r => option(selDis, r.id, r.name));

        // Reinitialize Select2 for district
        if (jQuery && jQuery.fn.select2) {
          jQuery(selDis).select2({
            placeholder: 'Pilih Kecamatan',
            width: '100%',
            language: {
              noResults: function() { return "Tidak ada hasil"; },
              searching: function() { return "Mencari..."; }
            }
          });

          // Reattach event listener after Select2 initialization
          setTimeout(function() {
            jQuery(selDis).off('change');
            jQuery(selDis).on('change', function() {
              const districtId = jQuery(this).val();
              // Ensure Select2 displays the selected value
              if (districtId) {
                jQuery(this).trigger('change.select2');
                loadVillages(districtId);
              }
            });
          }, 100);
        }
      }

      async function loadVillages(districtId) {
        selVil.innerHTML = ''; option(selVil, '', 'Pilih Desa/Kelurahan');

        // Destroy Select2 for village
        if (jQuery && jQuery.fn.select2) {
          if (jQuery(selVil).hasClass('select2-hidden-accessible')) {
            jQuery(selVil).select2('destroy');
          }
        }

        if (!districtId) return;
        const res = await fetch(`{{ route('admin.perusahaan.options.villages') }}?district_id=${encodeURIComponent(districtId)}`);
        const rows = await res.json();
        rows.forEach(r => option(selVil, r.id, r.name));

        // Reinitialize Select2 for village
        if (jQuery && jQuery.fn.select2) {
          jQuery(selVil).select2({
            placeholder: 'Pilih Desa/Kelurahan',
            width: '100%',
            language: {
              noResults: function() { return "Tidak ada hasil"; },
              searching: function() { return "Mencari..."; }
            }
          });
        }
      }

      // Event listeners will be attached after Select2 initialization in loadRegencies()

      function openModal() {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
      }
      function closeModal() {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
        // Reset form
        document.getElementById('formCreatePerusahaan').reset();

        // Destroy Select2 before clearing
        if (jQuery && jQuery.fn.select2) {
          if (jQuery(selReg).hasClass('select2-hidden-accessible')) {
            jQuery(selReg).select2('destroy');
          }
          if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
            jQuery(selDis).select2('destroy');
          }
          if (jQuery(selVil).hasClass('select2-hidden-accessible')) {
            jQuery(selVil).select2('destroy');
          }
        }

        selReg.innerHTML = '';
        selDis.innerHTML = '';
        selVil.innerHTML = '';
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
