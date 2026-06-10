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
                        <input type="text" name="name" id="cName" class="input"
                            placeholder="Masukkan nama desa" required>
                    </div>
                </div>

                {{-- Status Listrik --}}
                <div class="form-group">
                    <label class="label">Status Listrik</label>
                    <div class="control">
                        <select name="status_berlistrik" id="cStatusBerlistrik" class="input" required>
                            <option value="">Pilih Status Listrik</option>
                            <option value="Terlayani Listrik">Terlayani Listrik</option>
                            <option value="Desa Berlistrik NonPLN">Desa Berlistrik NonPLN</option>
                            <option value="Belum Terlayani Listrik">Belum Terlayani Listrik</option>
                        </select>
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
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            z-index: 9999;
            padding: 18px;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .modal-overlay.show {
            display: flex !important;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-overlay .modal {
            max-width: 520px;
            width: calc(100% - 36px);
            margin: auto;
            background: #ffffff !important;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
            z-index: 10000;
            flex-shrink: 0;
            min-height: 100px;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
            pointer-events: auto;
            height: auto;
            animation: slideDown 0.3s ease;
        }

        .modal-overlay.show .modal {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
        }

        .modal-header h3 {
            margin: 0;
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -.2px;
        }

        .btn-x {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border: 1px solid #E2E8F0;
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
        }

        .btn-x:hover {
            background: #F8FAFC;
        }

        .modal-body {
            padding: 18px 20px 6px;
        }

        .modal-footer {
            padding: 14px 20px 18px;
        }

        .btn-save {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            background: var(--accent-2);
            box-shadow: 0 10px 22px rgba(34, 197, 94, .22);
            cursor: pointer;
        }

        .btn-save:hover {
            filter: brightness(.95);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .label {
            display: block;
            font-size: 14px;
            color: #374151;
            margin: 6px 0 8px;
            font-weight: 600;
        }

        .control {
            position: relative;
        }

        .input {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            background: #FCFCFD;
            outline: none;
            font: inherit;
            color: #111827;
        }

        .input::placeholder {
            color: #94A3B8;
        }

        .input:focus {
            border-color: #CBD5E1;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .12);
        }

        /* ========== Select2 Custom Styles untuk Modal (Mengikuti Perusahaan) ========== */
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
            padding-right: 40px !important;
            font-size: 14px !important;
            color: #111827 !important;
            display: block !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Ensure selected value is visible */
        .modal .select2-container--default .select2-selection--single .select2-selection__rendered[title],
        .modal .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827 !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* When a value is selected, ensure it's displayed */
        .modal .select2-container--default .select2-selection--single[aria-expanded="false"] .select2-selection__rendered,
        .modal .select2-container--default .select2-selection--single[aria-expanded="true"] .select2-selection__rendered {
            color: #111827 !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Ensure the selection container shows the selected value */
        .modal .select2-container--default .select2-selection--single {
            visibility: visible !important;
        }

        .modal .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 12px !important;
            top: 1px !important;
        }

        .modal .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #94A3B8 transparent transparent transparent !important;
            border-width: 6px 4px 0 4px !important;
            margin-top: -2px !important;
        }

        .modal .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #94A3B8 transparent !important;
            border-width: 0 4px 6px 4px !important;
        }

        .modal .select2-container--default .select2-selection--single:focus,
        .modal .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #CBD5E1 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .12) !important;
        }

        /* Select2 Dropdown */
        .modal .select2-dropdown {
            border: 1px solid #E2E8F0 !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            background: #fff !important;
            margin-top: 4px !important;
            z-index: 10001 !important;
        }

        /* Ensure Select2 container appears above modal */
        .modal .select2-container {
            z-index: 10002 !important;
        }

        .modal .select2-container--open {
            z-index: 10002 !important;
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
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .12) !important;
        }

        .modal .select2-results {
            padding: 4px 0 !important;
        }

        .modal .select2-results__option {
            padding: 10px 12px !important;
            font-size: 14px !important;
            color: #111827 !important;
            cursor: pointer !important;
        }

        .modal .select2-results__option--highlighted {
            background: #F0FDF4 !important;
            color: #047857 !important;
        }

        .modal .select2-results__option[aria-selected="true"] {
            background: #17C653 !important;
            color: #fff !important;
        }

        .modal .select2-results__option[aria-selected="true"]:hover {
            background: #22C55E !important;
        }

        .modal .select2-results__option--loading {
            padding: 10px 12px !important;
            color: #6b7280 !important;
            font-size: 14px !important;
        }

        .modal .select2-results__message {
            padding: 10px 12px !important;
            color: #6b7280 !important;
            font-size: 14px !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        (function() {
            const overlay = document.getElementById('modalCreateDesa');
            const btnClose = document.getElementById('btnCloseCreate');
            const btnAdd = document.querySelector('.btn-add');

            // selects
            const selReg = document.getElementById('cRegency');
            const selDis = document.getElementById('cDistrict');

            function option(el, value, label) {
                const o = document.createElement('option');
                o.value = value;
                o.textContent = label;
                el.appendChild(o);
            }

            async function loadRegencies() {
                selReg.innerHTML = '';
                // Add empty option for placeholder
                option(selReg, '', 'Pilih Kabupaten/Kota');

                try {
                    const res = await fetch(`{{ route('admin.desa.options.regencies') }}`);
                    const rows = await res.json();
                    rows.forEach(r => option(selReg, r.id, r.name));

                    // Initialize Select2 for regency AFTER options are loaded
                    if (jQuery && jQuery.fn.select2) {
                        // Destroy if already initialized
                        if (jQuery(selReg).hasClass('select2-hidden-accessible')) {
                            jQuery(selReg).select2('destroy');
                        }

                        // Get modal element for dropdownParent (use the .modal element, not the overlay)
                        const modalElement = document.querySelector('#modalCreateDesa .modal');

                        // Initialize Select2
                        jQuery(selReg).select2({
                            placeholder: 'Pilih Kabupaten/Kota',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: jQuery(modalElement ||
                            '#modalCreateDesa'), // Ensure dropdown is rendered inside modal
                            language: {
                                noResults: function() {
                                    return "Tidak ada hasil";
                                },
                                searching: function() {
                                    return "Mencari...";
                                }
                            }
                        });

                        // Ensure Select2 is ready and can display selected values
                        console.log('Select2 initialized for regency');

                        // Attach event listener after Select2 initialization
                        // Use setTimeout to ensure Select2 is fully initialized
                        setTimeout(function() {
                            // Remove all existing event listeners
                            jQuery(selReg).off('change select2:select select2:clear');

                            // Handle when user selects an option - this is the primary event
                            jQuery(selReg).on('select2:select', function(e) {
                                const regencyId = e.params.data.id;
                                const regencyName = e.params.data.text;
                                console.log('Regency selected:', regencyId, regencyName);

                                const $select = jQuery(this);

                                // Ensure the value is set in the select element
                                $select.val(regencyId);

                                // Immediately update the rendered text
                                const $container = $select.next('.select2-container');
                                const $rendered = $container.find('.select2-selection__rendered');

                                // Force update the displayed text
                                $rendered.text(regencyName);
                                $rendered.attr('title', regencyName);

                                // Ensure the option is selected in the DOM
                                $select.find('option').prop('selected', false);
                                $select.find('option[value="' + regencyId + '"]').prop('selected',
                                    true);

                                // Force Select2 to update by triggering change
                                $select.trigger('change');

                                // Double check after a short delay
                                setTimeout(function() {
                                    const checkText = $rendered.text().trim();
                                    console.log('Rendered text check:', checkText);
                                    if (checkText !== regencyName && checkText !== '') {
                                        console.log('Fixing rendered text again');
                                        $rendered.text(regencyName);
                                        $rendered.attr('title', regencyName);
                                    }
                                }, 100);

                                // Load districts
                                if (regencyId) {
                                    loadDistricts(regencyId);
                                }
                            });

                            // Handle change event as fallback (for programmatic changes)
                            jQuery(selReg).on('change', function() {
                                const regencyId = jQuery(this).val();
                                console.log('Regency changed via change event:', regencyId);
                                if (regencyId) {
                                    loadDistricts(regencyId);
                                } else {
                                    // Clear district if regency is cleared
                                    selDis.innerHTML = '';
                                    option(selDis, '', 'Pilih Kecamatan');
                                    if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
                                        jQuery(selDis).select2('destroy');
                                    }
                                }
                            });

                            // Handle clear event
                            jQuery(selReg).on('select2:clear', function() {
                                console.log('Regency cleared');
                                // Clear district if regency is cleared
                                selDis.innerHTML = '';
                                option(selDis, '', 'Pilih Kecamatan');
                                if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
                                    jQuery(selDis).select2('destroy');
                                }
                            });
                        }, 100);
                    }
                } catch (error) {
                    console.error('Error loading regencies:', error);
                }

                selDis.innerHTML = '';
                option(selDis, '', 'Pilih Kecamatan');

                // Destroy Select2 for district
                if (jQuery && jQuery.fn.select2) {
                    if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
                        jQuery(selDis).select2('destroy');
                    }
                }
            }

            async function loadDistricts(regencyId) {
                selDis.innerHTML = '';
                option(selDis, '', 'Pilih Kecamatan');

                // Destroy Select2 for district
                if (jQuery && jQuery.fn.select2) {
                    if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
                        jQuery(selDis).select2('destroy');
                    }
                }

                if (!regencyId) return;

                try {
                    const res = await fetch(
                        `{{ route('admin.desa.options.districts') }}?regency_id=${encodeURIComponent(regencyId)}`
                        );
                    const rows = await res.json();
                    rows.forEach(r => option(selDis, r.id, r.name));

                    // Reinitialize Select2 for district
                    if (jQuery && jQuery.fn.select2) {
                        // Get modal element for dropdownParent (use the .modal element, not the overlay)
                        const modalElement = document.querySelector('#modalCreateDesa .modal');

                        jQuery(selDis).select2({
                            placeholder: 'Pilih Kecamatan',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: jQuery(modalElement ||
                            '#modalCreateDesa'), // Ensure dropdown is rendered inside modal
                            language: {
                                noResults: function() {
                                    return "Tidak ada hasil";
                                },
                                searching: function() {
                                    return "Mencari...";
                                }
                            }
                        });
                    }
                } catch (error) {
                    console.error('Error loading districts:', error);
                }
            }

            function openModal() {
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
                // Wait a bit for modal to be fully rendered before initializing Select2
                setTimeout(() => {
                    loadRegencies();
                }, 50);
            }

            function closeModal() {
                overlay.classList.remove('show');
                document.body.style.overflow = '';
                // Reset form
                document.getElementById('formCreateDesa').reset();
                // Destroy Select2 instances
                if (jQuery && jQuery.fn.select2) {
                    if (jQuery(selReg).hasClass('select2-hidden-accessible')) {
                        jQuery(selReg).select2('destroy');
                    }
                    if (jQuery(selDis).hasClass('select2-hidden-accessible')) {
                        jQuery(selDis).select2('destroy');
                    }
                }
                selReg.innerHTML = '';
                selDis.innerHTML = '';
            }

            // Open/close
            btnAdd?.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
            btnClose?.addEventListener('click', closeModal);
            overlay?.addEventListener('click', (e) => {
                if (e.target === overlay) closeModal();
            });

            if (location.hash === '#create') {
                openModal();
            }
        })();
    </script>
@endpush
