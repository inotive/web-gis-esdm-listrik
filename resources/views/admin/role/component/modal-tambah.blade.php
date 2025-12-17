{{-- modal --}}
<div class="modal fade" tabindex="-1" id="kt_modal_tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Data Role</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('admin.hak-akses.role.store') }}">
                    @csrf
                                        <div class="mb-4">
                                                <label for="inputNamaRole" class="form-label fw-semibold" style="font-size:14px;">Nama Role</label>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-lg"
                                                    id="inputNamaRole"
                                                    name="name"
                                                    value="{{ old('name') }}"
                                                    required
                                                    placeholder="Masukkan Nama Role"
                                                    autocomplete="off"
                                                />
                                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
{{-- end modal --}}
