@php
    $editing = old('form_mode') === 'update' && (int) old('form_asset_id') === $asset->id;
    $value = fn (string $key, $default = null) => $editing ? old($key, $default) : ($asset->{$key} ?? $default);
@endphp

<div class="modal fade" id="kt_modal_edit_asset_{{ $asset->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_edit_asset_header_{{ $asset->id }}">
                <h2 class="fw-bold">Edit Data asset</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body px-5 py-7">
                <form id="kt_modal_edit_asset_form_{{ $asset->id }}" class="form" action="{{ route('admin.asset.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_mode" value="update">
                    <input type="hidden" name="form_asset_id" value="{{ $asset->id }}">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="required fw-semibold fs-7 mb-2" for="edit_kode_asset_{{ $asset->id }}">Kode Asset</label>
                            <input type="text" id="edit_kode_asset_{{ $asset->id }}" name="kode_asset" class="form-control form-control-solid" value="{{ $value('kode_asset') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-semibold fs-7 mb-2" for="edit_nama_asset_{{ $asset->id }}">Nama Asset</label>
                            <input type="text" id="edit_nama_asset_{{ $asset->id }}" name="nama_asset" class="form-control form-control-solid" value="{{ $value('nama_asset') }}" required>
                        </div>

                        @php
                            $selectedKategori = $editing ? old('kategori_id') : $asset->kategori_id;
                            $selectedUnit = $editing ? old('unit_kerja_id') : $asset->unit_kerja_id;
                            $selectedStatus = $editing ? old('status_hukum_id') : $asset->status_hukum_id;
                            $selectedProvinsi = $editing ? old('provinsi_id') : $asset->provinsi_id;
                            $selectedKabupaten = $editing ? old('kabupaten_id') : $asset->kabupaten_id;
                            $selectedKecamatan = $editing ? old('kecamatan_id') : $asset->kecamatan_id;
                            $selectedKelurahan = $editing ? old('kelurahan_id') : $asset->kelurahan_id;
                        @endphp

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_kategori_id_{{ $asset->id }}">Kategori</label>
                            <select id="edit_kategori_id_{{ $asset->id }}" name="kategori_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}" @selected($selectedKategori == $kategori->id)>{{ $kategori->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_unit_kerja_id_{{ $asset->id }}">Unit Kerja</label>
                            <select id="edit_unit_kerja_id_{{ $asset->id }}" name="unit_kerja_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih unit kerja</option>
                                @foreach ($unitKerjaList as $unit)
                                    <option value="{{ $unit->id }}" @selected($selectedUnit == $unit->id)>{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_status_hukum_id_{{ $asset->id }}">Status Hukum</label>
                            <select id="edit_status_hukum_id_{{ $asset->id }}" name="status_hukum_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih status hukum</option>
                                @foreach ($statusList as $status)
                                    <option value="{{ $status->id }}" @selected($selectedStatus == $status->id)>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_no_register_{{ $asset->id }}">No. Register</label>
                            <input type="text" id="edit_no_register_{{ $asset->id }}" name="no_register" class="form-control form-control-solid" value="{{ $value('no_register') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_nomor_hak_{{ $asset->id }}">Nomor Hak</label>
                            <input type="text" id="edit_nomor_hak_{{ $asset->id }}" name="nomor_hak" class="form-control form-control-solid" value="{{ $value('nomor_hak') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_penggunaan_spma_{{ $asset->id }}">Penggunaan SPMA</label>
                            <input type="text" id="edit_penggunaan_spma_{{ $asset->id }}" name="penggunaan_spma" class="form-control form-control-solid" value="{{ $value('penggunaan_spma') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_jenis_hak_{{ $asset->id }}">Jenis Hak</label>
                            <input type="text" id="edit_jenis_hak_{{ $asset->id }}" name="jenis_hak" class="form-control form-control-solid" value="{{ $value('jenis_hak') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_kode_{{ $asset->id }}">Kode Internal</label>
                            <input type="text" id="edit_kode_{{ $asset->id }}" name="kode" class="form-control form-control-solid" value="{{ $value('kode') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_nui_{{ $asset->id }}">NUI</label>
                            <input type="text" id="edit_nui_{{ $asset->id }}" name="nui" class="form-control form-control-solid" value="{{ $value('nui') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_nib_{{ $asset->id }}">NIB</label>
                            <input type="text" id="edit_nib_{{ $asset->id }}" name="nib" class="form-control form-control-solid" value="{{ $value('nib') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_luas_m2_{{ $asset->id }}">Luas (m²)</label>
                            <input type="number" step="0.01" id="edit_luas_m2_{{ $asset->id }}" name="luas_m2" class="form-control form-control-solid" value="{{ $value('luas_m2') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_panjang_m_{{ $asset->id }}">Panjang (m)</label>
                            <input type="number" step="0.01" id="edit_panjang_m_{{ $asset->id }}" name="panjang_m" class="form-control form-control-solid" value="{{ $value('panjang_m') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_lebar_m_{{ $asset->id }}">Lebar (m)</label>
                            <input type="number" step="0.01" id="edit_lebar_m_{{ $asset->id }}" name="lebar_m" class="form-control form-control-solid" value="{{ $value('lebar_m') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_path_shp_{{ $asset->id }}">Path SHP</label>
                            <input type="text" id="edit_path_shp_{{ $asset->id }}" name="path_shp" class="form-control form-control-solid" value="{{ $value('path_shp') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_provinsi_id_{{ $asset->id }}">Provinsi</label>
                            <select id="edit_provinsi_id_{{ $asset->id }}" name="provinsi_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih provinsi</option>
                                @foreach ($provinsiList as $provinsi)
                                    <option value="{{ $provinsi->id }}" @selected($selectedProvinsi == $provinsi->id)>{{ $provinsi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_kabupaten_id_{{ $asset->id }}">Kabupaten/Kota</label>
                            <select id="edit_kabupaten_id_{{ $asset->id }}" name="kabupaten_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih kabupaten/kota</option>
                                @foreach ($kabupatenList as $kabupaten)
                                    <option value="{{ $kabupaten->id }}" @selected($selectedKabupaten == $kabupaten->id) data-provinsi="{{ $kabupaten->province_id }}">{{ $kabupaten->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_kecamatan_id_{{ $asset->id }}">Kecamatan</label>
                            <select id="edit_kecamatan_id_{{ $asset->id }}" name="kecamatan_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih kecamatan</option>
                                @foreach ($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" @selected($selectedKecamatan == $kecamatan->id) data-kabupaten="{{ $kecamatan->regency_id }}">{{ $kecamatan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-2" for="edit_kelurahan_id_{{ $asset->id }}">Kelurahan</label>
                            <select id="edit_kelurahan_id_{{ $asset->id }}" name="kelurahan_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_edit_asset_{{ $asset->id }}">
                                <option value="">Pilih kelurahan</option>
                                @foreach ($kelurahanList as $kelurahan)
                                    <option value="{{ $kelurahan->id }}" @selected($selectedKelurahan == $kelurahan->id) data-kecamatan="{{ $kelurahan->district_id }}">{{ $kelurahan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
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
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
