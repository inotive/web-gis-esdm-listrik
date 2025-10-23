<!--begin::Modal - Tambah Jabatan-->
<div class="modal fade" id="kt_modal_tambah_jabatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_tambah_jabatan_header">
                <h2 class="fw-bold">Tambah Jabatan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body px-5 py-7">
                <form id="kt_modal_tambah_jabatan_form" class="form" action="{{ route('admin.jabatan.store') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column gap-7">
                        <div class="fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                            <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan') }}" class="form-control form-control-solid" placeholder="Masukkan nama jabatan" required />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" class="form-control form-control-solid" placeholder="Masukkan NIP" />
                        </div>

                        <div class="fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="form-control form-control-solid" placeholder="Masukkan jabatan" required />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control form-control-solid" placeholder="Masukkan nomor telepon" />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-solid" placeholder="Masukkan email" />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Alamat Kantor</label>
                            <textarea name="alamat_kantor" class="form-control form-control-solid" rows="3" placeholder="Masukkan alamat kantor">{{ old('alamat_kantor') }}</textarea>
                        </div>

                        <div class="fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Status</label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="">Pilih status</option>
                                @foreach(($statusOptions ?? ['aktif', 'nonaktif']) as $status)
                                    <option value="{{ $status }}" {{ old('status', 'aktif') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

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
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Modal - Tambah Jabatan-->
