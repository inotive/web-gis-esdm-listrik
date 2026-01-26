{{-- resources/views/admin/desa/edit_modal.blade.php --}}
<div id="modalEditDesa" class="modal-overlay" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEditDesaTitle">
        <div class="modal-header">
            <h3 id="modalEditDesaTitle">Edit Data Desa</h3>
            <button type="button" class="btn-x" onclick="__closeModal('modalEditDesa')" aria-label="Tutup">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <form id="formEditDesa" method="POST">
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

                {{-- Nama Desa --}}
                <div class="form-group">
                    <label class="label">Nama Desa</label>
                    <div class="control">
                        <input type="text" name="name" id="edit_name" class="input"
                            placeholder="Masukkan nama desa" required>
                    </div>
                </div>

                {{-- Status Berlistrik --}}
                <div class="form-group">
                    <label class="label">Status Berlistrik</label>
                    <div class="control">
                        <select name="status_berlistrik" id="edit_status_berlistrik" class="input">
                            <option value="">-- Pilih Status --</option>
                            <option value="Terlayani Listrik">Terlayani Listrik</option>
                            <option value="Belum Terlayani Listrik">Belum Terlayani Listrik</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="__closeModal('modalEditDesa')">Batal</button>
                <button class="btn-save" type="submit">
                    <i class="ri-save-3-line"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        /* ... existing styles ... */
        .modal-overlay {
            /* ... existing styles ... */

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
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-cancel {
            height: 44px;
            padding: 0 20px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-weight: 600;
            color: #64748B;
            background: #fff;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #F8FAFC;
        }

        .btn-save {
            height: 44px;
            padding: 0 20px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            background: var(--accent-2);
            box-shadow: 0 10px 22px rgba(34, 197, 94, .22);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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

        /* ========== Select2 Custom Styles untuk Modal ========== */
        .modal .select2-container--bootstrap-5 {
            width: 100% !important;
        }

        /* Selection Box */
        .modal .select2-container--bootstrap-5 .select2-selection {
            min-height: 44px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            background: #FCFCFD;
            transition: all 0.2s ease;
        }

        .modal .select2-container--bootstrap-5 .select2-selection:hover {
            border-color: #CBD5E1;
        }

        .modal .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .modal .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #CBD5E1;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }

        /* Single Selection */
        .modal .select2-container--bootstrap-5 .select2-selection--single {
            height: 44px;
        }

        .modal .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
            padding-right: 40px;
            font-size: 14px;
            color: #111827;
            line-height: 42px;
        }

        /* Arrow Icon */
        .modal .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 42px;
            right: 12px;
            width: 24px;
        }

        .modal .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow b {
            border-color: #94A3B8 transparent transparent transparent;
            border-width: 6px 6px 0 6px;
            margin-top: -3px;
            transition: transform 0.2s ease;
        }

        .modal .select2-container--bootstrap-5.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #94A3B8 transparent;
            border-width: 0 6px 6px 6px;
            margin-top: -4px;
        }

        /* Clear Button */
        .modal .select2-container--bootstrap-5 .select2-selection__clear {
            margin-right: 30px;
            color: #94A3B8;
            font-size: 18px;
            line-height: 42px;
        }

        .modal .select2-container--bootstrap-5 .select2-selection__clear:hover {
            color: #64748B;
        }

        /* Dropdown */
        .modal .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
            overflow: hidden;
        }

        /* Search Field */
        .modal .select2-container--bootstrap-5 .select2-search--dropdown {
            padding: 8px;
        }

        .modal .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: all 0.2s ease;
        }

        .modal .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
            border-color: #CBD5E1;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }

        /* Results */
        .modal .select2-container--bootstrap-5 .select2-results {
            max-height: 200px;
        }

        .modal .select2-container--bootstrap-5 .select2-results__option {
            padding: 10px 12px;
            font-size: 14px;
            color: #111827;
            transition: all 0.15s ease;
        }

        .modal .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #F1F5F9;
            color: #111827;
        }

        .modal .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
            background-color: #E8FFF4;
            color: #10B981;
            font-weight: 500;
        }

        .modal .select2-container--bootstrap-5 .select2-results__option[aria-disabled=true] {
            color: #94A3B8;
            cursor: not-allowed;
        }

        /* No Results */
        .modal .select2-container--bootstrap-5 .select2-results__message {
            padding: 12px;
            color: #64748B;
            font-size: 14px;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.__openModal = id => {
            // Close any open modals first
            document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
            const o = document.getElementById(id);
            if (o) {
                o.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        };
        window.__closeModal = id => {
            const o = document.getElementById(id);
            if (o) {
                o.classList.remove('show');
                document.body.style.overflow = '';
            }
        };

        // Initialize Select2 for edit modal
        function initSelect2Edit() {
            const selRegEdit = $('#edit_regency_id');
            const selDisEdit = $('#edit_district_id');

            // Destroy existing instances if any
            if (selRegEdit.hasClass('select2-hidden-accessible')) {
                selRegEdit.select2('destroy');
            }
            if (selDisEdit.hasClass('select2-hidden-accessible')) {
                selDisEdit.select2('destroy');
            }

            // Initialize Regency Select2
            selRegEdit.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Kabupaten/Kota',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Tidak ada hasil";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            // Initialize District Select2
            selDisEdit.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Kecamatan',
                allowClear: true,
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

        $(document).ready(function() {
            // Close modal on backdrop click
            document.getElementById('modalEditDesa')?.addEventListener('click', e => {
                if (e.target.id === 'modalEditDesa') __closeModal('modalEditDesa');
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-overlay.show').forEach(m => {
                        m.classList.remove('show');
                        document.body.style.overflow = '';
                    });
                }
            });

            // Cascading dropdown handler for edit modal
            const selRegEdit = $('#edit_regency_id');
            const selDisEdit = $('#edit_district_id');

            selRegEdit.on('change', async function() {
                const rid = $(this).val();

                // Destroy existing instance
                if (selDisEdit.hasClass('select2-hidden-accessible')) {
                    selDisEdit.select2('destroy');
                }

                selDisEdit.empty().append('<option value="">Pilih Kecamatan</option>');

                if (!rid) {
                    initSelect2Edit();
                    return;
                }

                const res = await fetch('{{ route('admin.desa.options.districts') }}?regency_id=' +
                    encodeURIComponent(rid));
                const districts = await res.json();

                districts.forEach(d => {
                    selDisEdit.append(new Option(d.name, d.id, false, false));
                });

                // Re-initialize Select2
                initSelect2Edit();
            });

            // Edit button handlers
            document.querySelectorAll('.btn-edit-desa').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const regencyId = this.getAttribute('data-regency-id');
                    const districtId = this.getAttribute('data-district-id');
                    const status = this.getAttribute('data-status');

                    // Set form action
                    document.getElementById('formEditDesa').action =
                        '{{ route('admin.desa.update', ':id') }}'.replace(':id', id);

                    // Set name
                    document.getElementById('edit_name').value = name;

                    // Set status
                    const statusSelect = document.getElementById('edit_status_berlistrik');
                    if (statusSelect) {
                        statusSelect.value = status || '';
                    }

                    // Load regencies
                    const selReg = $('#edit_regency_id');
                    selReg.empty();
                    const resReg = await fetch('{{ route('admin.desa.options.regencies') }}');
                    const regencies = await resReg.json();
                    regencies.forEach(r => {
                        selReg.append(new Option(r.name, r.id, r.id === regencyId, r
                            .id === regencyId));
                    });

                    // Load districts for selected regency
                    const selDis = $('#edit_district_id');
                    selDis.empty();
                    if (regencyId) {
                        const resDis = await fetch(
                            '{{ route('admin.desa.options.districts') }}?regency_id=' +
                            encodeURIComponent(regencyId));
                        const districts = await resDis.json();
                        districts.forEach(d => {
                            selDis.append(new Option(d.name, d.id, d.id === districtId,
                                d.id === districtId));
                        });
                    }

                    // Initialize Select2 after loading data
                    initSelect2Edit();

                    __openModal('modalEditDesa');
                });
            });
        });
    </script>
@endpush
