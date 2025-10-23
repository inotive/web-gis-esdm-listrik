<!--begin::Modal - Edit Data status_hukum_asset-->
<div class="modal fade" id="kt_modal_edit_status_hukum_asset_{{ $id ?? '1' }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_edit_status_hukum_asset_header_{{ $id ?? '1' }}">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Edit Data status_hukum_asset</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 py-7">
                <!--begin::Form-->
                <form id="kt_modal_edit_status_hukum_asset_form_{{ $id ?? '1' }}" class="form" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_edit_status_hukum_asset_scroll_{{ $id ?? '1' }}" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_edit_status_hukum_asset_header_{{ $id ?? '1' }}" data-kt-scroll-wrappers="#kt_modal_edit_status_hukum_asset_scroll_{{ $id ?? '1' }}" data-kt-scroll-offset="300px">
                        
                        <!--begin::Input group - Nama status_hukum_asset-->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Nama status_hukum_asset</label>
                            <input type="text" name="nama_status_hukum_asset" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Masukkan nama status_hukum_asset" value="{{ $nama_desa ?? 'status_hukum_asset Induk Samarinda' }}" required />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Lokasi-->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Lokasi</label>
                            <textarea name="lokasi" class="form-control form-control-solid" rows="2" placeholder="Masukkan lokasi lengkap status_hukum_asset" required>{{ $kecamatan ?? 'Jl. Pramuka No. 45, Samarinda Kota' }}</textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Jenis status_hukum_asset Distribusi-->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Jenis status_hukum_asset Distribusi</label>
                            <select name="jenis_status_hukum_asset" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Jenis status_hukum_asset" data-dropdown-parent="#kt_modal_edit_status_hukum_asset_{{ $id ?? '1' }}" required>
                                <option value="">Pilih Jenis status_hukum_asset</option>
                                <option value="status_hukum_asset Induk (GI)" {{ ($kabupaten ?? 'status_hukum_asset Induk (GI)') == 'status_hukum_asset Induk (GI)' ? 'selected' : '' }}>status_hukum_asset Induk (GI)</option>
                                <option value="status_hukum_asset Distribusi (GD)" {{ ($kabupaten ?? '') == 'status_hukum_asset Distribusi (GD)' ? 'selected' : '' }}>status_hukum_asset Distribusi (GD)</option>
                                <option value="status_hukum_asset Hubung (GH)" {{ ($kabupaten ?? '') == 'status_hukum_asset Hubung (GH)' ? 'selected' : '' }}>status_hukum_asset Hubung (GH)</option>
                                <option value="status_hukum_asset Tiang" {{ ($kabupaten ?? '') == 'status_hukum_asset Tiang' ? 'selected' : '' }}>status_hukum_asset Tiang</option>
                                <option value="status_hukum_asset Beton" {{ ($kabupaten ?? '') == 'status_hukum_asset Beton' ? 'selected' : '' }}>status_hukum_asset Beton</option>
                                <option value="status_hukum_asset Portal" {{ ($kabupaten ?? '') == 'status_hukum_asset Portal' ? 'selected' : '' }}>status_hukum_asset Portal</option>
                            </select>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Koordinat-->
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Koordinat Bujur</label>
                                <input type="text" name="koordinat_bujur" class="form-control form-control-solid" placeholder='Contoh: 117°08\'45"E' value="{{ $koordinat_bujur ?? '117°08\'45\"E' }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Koordinat Lintang</label>
                                <input type="text" name="koordinat_lintang" class="form-control form-control-solid" placeholder='Contoh: 0°30\'15"S' value="{{ $koordinat_lintang ?? '0°30\'15\"S' }}" required />
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Kapasitas Daya-->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Kapasitas Daya (MVA)</label>
                            <input type="number" step="0.01" name="kapasitas_daya" class="form-control form-control-solid" placeholder="Masukkan kapasitas daya" value="{{ $jumlah_penduduk ?? '150' }}" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Luas Area-->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Luas Area (m²)</label>
                            <input type="number" step="0.01" name="luas_area" class="form-control form-control-solid" placeholder="Masukkan luas area status_hukum_asset" value="{{ $luas_status_hukum_asset ?? '2500' }}" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Keterangan-->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Keterangan</label>
                            <textarea name="keterangan" class="form-control form-control-solid" rows="3" placeholder="Masukkan keterangan (opsional)">{{ $keterangan ?? '' }}</textarea>
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ki-duotone ki-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="indicator-label">Update Data</span>
                            <span class="indicator-progress">Menyimpan...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Edit Data status_hukum_asset-->
