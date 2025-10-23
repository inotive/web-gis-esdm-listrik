{{-- modal --}}
<div class="modal fade" tabindex="-1" id="kt_modal_tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Data Permission</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('admin.hak-akses.permission.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Nama Group</label>
                            </div>
                            <div class="col-xl-12">
                                <input type="text" class="form-control" id="inputgroup" name="group"
                                    value="{{ old('group') }}" required placeholder="Masukkan Nama Group"
                                    aria-label="Group" aria-describedby="basic-addon1" required/>
                            </div>
                        </div>
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Dispaly Name</label>
                            </div>
                            <div class="col-xl-12">
                                <input type="text" class="form-control" id="inputDisplayName" name="display_name"
                                    value="{{ old('display_name') }}" required placeholder="Masukkan Display Name"
                                    aria-label="Display Name" aria-describedby="basic-addon1" required/>
                            </div>
                        </div>
                        <div class="input-group mb-5">
                            <div class="col-xl-12 mb-2">
                                <label for="">Permission</label>
                            </div>
                            <div class="col-xl-12">
                                <input type="text" class="form-control" id="inputNama" name="name"
                                    value="{{ old('name')}}" required placeholder="Masukkan Nama Permission"
                                    aria-label="Username" aria-describedby="basic-addon1" required/>
                            </div>
                        </div>
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
