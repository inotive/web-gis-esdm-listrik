<!--begin::Modal - Tambah Data unit_kerja-->
<div class="modal fade" id="kt_modal_tambah_unit_kerja" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_tambah_unit_kerja_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Tambah Data unit_kerja</h2>
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
                <form id="kt_modal_tambah_unit_kerja_form" class="form" action="#" method="POST">
                    @csrf
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_tambah_unit_kerja_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_tambah_unit_kerja_header" data-kt-scroll-wrappers="#kt_modal_tambah_unit_kerja_scroll" data-kt-scroll-offset="300px">
                        
                        <!--begin::Input group - Nama unit_kerja-->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Nama unit_kerja</label>
                            <input type="text" name="nama_unit_kerja" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Masukkan nama unit_kerja" required />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Lokasi-->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Lokasi</label>
                            <textarea name="lokasi" class="form-control form-control-solid" rows="2" placeholder="Masukkan lokasi lengkap unit_kerja" required></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Jenis unit_kerja Distribusi-->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Jenis unit_kerja Distribusi</label>
                            <select name="jenis_unit_kerja" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Jenis unit_kerja" data-dropdown-parent="#kt_modal_tambah_unit_kerja" required>
                                <option value="">Pilih Jenis unit_kerja</option>
                                <option value="unit_kerja Induk (GI)">unit_kerja Induk (GI)</option>
                                <option value="unit_kerja Distribusi (GD)">unit_kerja Distribusi (GD)</option>
                                <option value="unit_kerja Hubung (GH)">unit_kerja Hubung (GH)</option>
                                <option value="unit_kerja Tiang">unit_kerja Tiang</option>
                                <option value="unit_kerja Beton">unit_kerja Beton</option>
                                <option value="unit_kerja Portal">unit_kerja Portal</option>
                            </select>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Koordinat-->
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Koordinat Bujur</label>
                                <input type="text" name="koordinat_bujur" class="form-control form-control-solid" placeholder='Contoh: 117°08\'45"E' required />
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-semibold fs-6 mb-2">Koordinat Lintang</label>
                                <input type="text" name="koordinat_lintang" class="form-control form-control-solid" placeholder='Contoh: 0°30\'15"S' required />
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Kapasitas Daya-->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Kapasitas Daya (MVA)</label>
                            <input type="number" step="0.01" name="kapasitas_daya" class="form-control form-control-solid" placeholder="Masukkan kapasitas daya" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Luas Area-->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Luas Area (m²)</label>
                            <input type="number" step="0.01" name="luas_area" class="form-control form-control-solid" placeholder="Masukkan luas area unit_kerja" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Keterangan-->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Keterangan</label>
                            <textarea name="keterangan" class="form-control form-control-solid" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
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
                        <button type="submit" class="btn btn-success">
                            <i class="ki-duotone ki-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="indicator-label">Simpan Data</span>
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
<!--end::Modal - Tambah Data unit_kerja-->
