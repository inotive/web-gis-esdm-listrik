<!--begin::Modal - Edit Jabatan-->
<div class="modal fade" id="kt_modal_edit_jabatan_{{ $jabatan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_edit_jabatan_header_{{ $jabatan->id }}">
                <h2 class="fw-bold">Edit Jabatan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body px-5 py-7">
                <form id="kt_modal_edit_jabatan_form_{{ $jabatan->id }}" class="form" action="{{ route('admin.jabatan.update', $jabatan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="d-flex flex-column gap-7">
                        <div class="fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                            <input type="text" name="nama_jabatan" value="{{ $jabatan->nama_jabatan }}" class="form-control form-control-solid" placeholder="Masukkan nama jabatan" required />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">NIP</label>
                            <input type="text" name="nip" value="{{ $jabatan->nip }}" class="form-control form-control-solid" placeholder="Masukkan NIP" />
                        </div>

                        <div class="fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ $jabatan->jabatan }}" class="form-control form-control-solid" placeholder="Masukkan jabatan" required />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ $jabatan->no_telp }}" class="form-control form-control-solid" placeholder="Masukkan nomor telepon" />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $jabatan->email }}" class="form-control form-control-solid" placeholder="Masukkan email" />
                        </div>

                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Alamat Kantor</label>
                            <textarea name="alamat_kantor" class="form-control form-control-solid" rows="3" placeholder="Masukkan alamat kantor">{{ $jabatan->alamat_kantor }}</textarea>
                        </div>

                        <div class="fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Status</label>
                            <select name="status" class="form-select form-select-solid" required>
                                @foreach(($statusOptions ?? ['aktif', 'nonaktif']) as $status)
                                    <option value="{{ $status }}" {{ $jabatan->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
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
                        <button type="submit" class="btn btn-warning">
                            <i class="ki-duotone ki-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="indicator-label">Simpan Perubahan</span>
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
<!--end::Modal - Edit Jabatan-->
