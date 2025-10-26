<div class="modal fade" id="kt_modal_tambah_asset" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_tambah_asset_header">
                <h2 class="fw-bold">Tambah Data asset</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body px-5 py-7">
                <form id="kt_modal_tambah_asset_form" class="form" action="{{ route('admin.asset.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_mode" value="create">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="required fw-semibold fs-7 mb-2" for="create_kode_asset">Kode Asset</label>
                            <input type="text" id="create_kode_asset" name="kode_asset" class="form-control form-control-solid" placeholder="Masukkan kode asset" value="{{ old('kode_asset') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-semibold fs-7 mb-2" for="create_nama_asset">Nama Asset</label>
                            <input type="text" id="create_nama_asset" name="nama_asset" class="form-control form-control-solid" placeholder="Masukkan nama asset" value="{{ old('nama_asset') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_kategori_id">Kategori</label>
                            <select id="create_kategori_id" name="kategori_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_unit_kerja_id">Unit Kerja</label>
                            <select id="create_unit_kerja_id" name="unit_kerja_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih unit kerja</option>
                                @foreach ($unitKerjaList as $unit)
                                    <option value="{{ $unit->id }}" @selected(old('unit_kerja_id') == $unit->id)>{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_status_hukum_id">Status Hukum</label>
                            <select id="create_status_hukum_id" name="status_hukum_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih status hukum</option>
                                @foreach ($statusList as $status)
                                    <option value="{{ $status->id }}" @selected(old('status_hukum_id') == $status->id)>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_no_register">No. Register</label>
                            <input type="text" id="create_no_register" name="no_register" class="form-control form-control-solid" value="{{ old('no_register') }}" placeholder="Masukkan nomor register">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_nomor_hak">Nomor Hak</label>
                            <input type="text" id="create_nomor_hak" name="nomor_hak" class="form-control form-control-solid" value="{{ old('nomor_hak') }}" placeholder="Masukkan nomor hak">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_penggunaan_spma">Penggunaan SPMA</label>
                            <input type="text" id="create_penggunaan_spma" name="penggunaan_spma" class="form-control form-control-solid" value="{{ old('penggunaan_spma') }}" placeholder="Masukkan penggunaan">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_jenis_hak">Jenis Hak</label>
                            <input type="text" id="create_jenis_hak" name="jenis_hak" class="form-control form-control-solid" value="{{ old('jenis_hak') }}" placeholder="Masukkan jenis hak">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_kode">Kode Internal</label>
                            <input type="text" id="create_kode" name="kode" class="form-control form-control-solid" value="{{ old('kode') }}" placeholder="Masukkan kode internal">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_nui">NUI</label>
                            <input type="text" id="create_nui" name="nui" class="form-control form-control-solid" value="{{ old('nui') }}" placeholder="Nomor unik inventaris">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_nib">NIB</label>
                            <input type="text" id="create_nib" name="nib" class="form-control form-control-solid" value="{{ old('nib') }}" placeholder="Nomor identifikasi bidang">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_luas_m2">Luas (m²)</label>
                            <input type="number" step="0.01" id="create_luas_m2" name="luas_m2" class="form-control form-control-solid" value="{{ old('luas_m2') }}" placeholder="Contoh: 1000">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_panjang_m">Panjang (m)</label>
                            <input type="number" step="0.01" id="create_panjang_m" name="panjang_m" class="form-control form-control-solid" value="{{ old('panjang_m') }}" placeholder="Contoh: 50">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_lebar_m">Lebar (m)</label>
                            <input type="number" step="0.01" id="create_lebar_m" name="lebar_m" class="form-control form-control-solid" value="{{ old('lebar_m') }}" placeholder="Contoh: 20">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_path_shp">Path SHP</label>
                            <input type="text" id="create_path_shp" name="path_shp" class="form-control form-control-solid" value="{{ old('path_shp') }}" placeholder="contoh: /storage/maps/asset.shp">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_provinsi_id">Provinsi</label>
                            <select id="create_provinsi_id" name="provinsi_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih provinsi</option>
                                @foreach ($provinsiList as $provinsi)
                                    <option value="{{ $provinsi->id }}" @selected(old('provinsi_id') == $provinsi->id)>{{ $provinsi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_kabupaten_id">Kabupaten/Kota</label>
                            <select id="create_kabupaten_id" name="kabupaten_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih kabupaten/kota</option>
                                @foreach ($kabupatenList as $kabupaten)
                                    <option value="{{ $kabupaten->id }}" @selected(old('kabupaten_id') == $kabupaten->id) data-provinsi="{{ $kabupaten->province_id }}">{{ $kabupaten->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_kecamatan_id">Kecamatan</label>
                            <select id="create_kecamatan_id" name="kecamatan_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih kecamatan</option>
                                @foreach ($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" @selected(old('kecamatan_id') == $kecamatan->id) data-kabupaten="{{ $kecamatan->regency_id }}">{{ $kecamatan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="create_kelurahan_id">Kelurahan</label>
                            <select id="create_kelurahan_id" name="kelurahan_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_tambah_asset">
                                <option value="">Pilih kelurahan</option>
                                @foreach ($kelurahanList as $kelurahan)
                                    <option value="{{ $kelurahan->id }}" @selected(old('kelurahan_id') == $kelurahan->id) data-kecamatan="{{ $kelurahan->district_id }}">{{ $kelurahan->name }}</option>
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
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
