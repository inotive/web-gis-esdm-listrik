@php
    $isEditing = filled($asset);
    
    // Helper function untuk get old/existing value dari asset
    $selected = static fn ($field, $fallback = null) => old($field, $isEditing ? ($asset->{$field} ?? $fallback) : $fallback);
    
    // ✅ Get dokumen from relation
    $dokumen = $isEditing && $asset ? $asset->dokumenUtama : null;
    
    // Helper function untuk get old/existing value dari dokumen
    $selectedDokumen = static fn ($field, $fallback = null) => old($field, $dokumen ? ($dokumen->{$field} ?? $fallback) : $fallback);
    
    // ✅ Determine sertifikat type
    $sertifikatType = 'file'; // default
    
    if ($dokumen) {
        if (!empty($dokumen->link_sertif)) {
            $sertifikatType = 'link';
        } elseif (!empty($dokumen->file_sertif)) {
            $sertifikatType = 'file';
        }
    }
    
    // Override with old value if exists (for validation errors)
    $sertifikatType = old('sertifikat_type', $sertifikatType);
@endphp

<div class="container-fluid">
    {{-- Baris 1: Kategori, Provinsi, Kabupaten/Kota, Kecamatan, Kelurahan --}}
    <div class="row mb-6">
        <div class="col-md-2">
            <label class="fw-semibold fs-7 mb-2" for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="form-select form-select-solid">
                <option value="">Pilih kategori</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}" @selected($selected('kategori_id') == $kategori->id)>
                        {{ $kategori->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="fw-semibold fs-7 mb-2" for="reg_provinces_id">Provinsi</label>
            <select id="reg_provinces_id" name="reg_provinces_id" class="form-select form-select-solid">
                <option value="">Pilih Provinsi</option>
                @foreach ($provinsiList as $prov)
                    <option value="{{ $prov->id }}" {{ $selected('reg_provinces_id') == $prov->id ? 'selected' : '' }}>
                        {{ $prov->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-semibold fs-7 mb-2" for="reg_regencies_id">Kabupaten / Kota</label>
            <select id="reg_regencies_id" name="reg_regencies_id" class="form-select form-select-solid">
                <option value="">Pilih kabupaten/kota</option>
                @foreach ($kabupatenList as $kabupaten)
                    <option value="{{ $kabupaten->id }}" data-provinsi="{{ $kabupaten->province_id }}" 
                        @selected($selected('reg_regencies_id') == $kabupaten->id)>
                        {{ $kabupaten->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="fw-semibold fs-7 mb-2" for="reg_districts_id">Kecamatan</label>
            <select id="reg_districts_id" name="reg_districts_id" class="form-select form-select-solid">
                <option value="">Pilih kecamatan</option>
                @foreach ($kecamatanList as $kecamatan)
                    <option value="{{ $kecamatan->id }}" data-kabupaten="{{ $kecamatan->regency_id }}" 
                        @selected($selected('reg_districts_id') == $kecamatan->id)>
                        {{ $kecamatan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="fw-semibold fs-7 mb-2" for="reg_villages_id">Kelurahan</label>
            <select id="reg_villages_id" name="reg_villages_id" class="form-select form-select-solid">
                <option value="">Pilih kelurahan</option>
                @foreach ($kelurahanList as $kelurahan)
                    <option value="{{ $kelurahan->id }}" data-kecamatan="{{ $kelurahan->district_id }}" 
                        @selected($selected('reg_villages_id') == $kelurahan->id)>
                        {{ $kelurahan->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ✅ MAP SECTION --}}
    <div class="row mb-6">
        <div class="col-12">
            <div class="card border border-gray-300">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="fa-solid fa-location-dot fs-2 text-primary me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Pilih Lokasi Aset
                            </h5>
                            <p class="text-muted small mb-0">Gunakan tools di kanan atas untuk menggambar <strong>Polygon</strong> atau <strong>Rectangle</strong> area aset</p>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge badge-light-info fs-7">
                                <i class="fa-solid fa-map fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <span id="existingAssetsCount">0</span> Asset Lain
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="toggleExistingBtn" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-eye fs-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Sembunyikan Asset Lain
                            </button>
                            <button type="button" id="zoomAllBtn" class="btn btn-sm btn-light-info">
                                <i class="fa-solid fa-maximize fs-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                                Zoom ke Semua
                            </button>
                            <button type="button" id="clearMapBtn" class="btn btn-sm btn-light-danger">
                                <i class="fa-solid fa-trash fs-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                Hapus Gambar
                            </button>
                        </div>
                        <div class="text-muted small">
                            <div class="d-flex gap-3 align-items-center">
                                <div>
                                    <span class="badge" style="background: #3b82f6; width: 16px; height: 16px; display: inline-block; border-radius: 3px;"></span>
                                    <span class="ms-1">Asset Baru</span>
                                </div>
                                <div>
                                    <span class="badge" style="background: #ef4444; width: 16px; height: 16px; display: inline-block; border-radius: 3px; border: 2px dashed #ef4444;"></span>
                                    <span class="ms-1">Asset Lain</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ KOORDINAT & GEOJSON --}}
    <div class="row mb-6">
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="latitude">
                Latitude 
                <span class="text-muted fs-8">(auto-set dari peta atau input manual)</span>
            </label>
            <input type="text" id="latitude" name="latitude" class="form-control form-control-solid bg-light-primary" 
                value="{{ $selected('latitude') }}" placeholder="Contoh: -1.2379">
            <small class="text-muted">Format: -90 sampai 90</small>
        </div>
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="longitude">
                Longitude 
                <span class="text-muted fs-8">(auto-set dari peta atau input manual)</span>
            </label>
            <input type="text" id="longitude" name="longitude" class="form-control form-control-solid bg-light-primary" 
                value="{{ $selected('longitude') }}" placeholder="Contoh: 116.8529">
            <small class="text-muted">Format: -180 sampai 180</small>
        </div>
        <input type="hidden" id="geojson" name="geojson" value="{{ $selected('geojson') }}">
    </div>

    {{-- Baris 2: Field dasar --}}
    <div class="row g-6 mb-6">
        <div class="col-md-6">
            <label class="required fw-semibold fs-7 mb-2" for="kode_asset">Kode Asset</label>
            <input type="text" id="kode_asset" name="kode_asset" class="form-control form-control-solid"
                value="{{ $selected('kode_asset') }}" required>
        </div>

        <div class="col-md-6">
            <label class="required fw-semibold fs-7 mb-2" for="nama_asset">Nama Asset</label>
            <input type="text" id="nama_asset" name="nama_asset" class="form-control form-control-solid"
                value="{{ $selected('nama_asset') }}" required>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="unit_kerja_id">Unit Kerja</label>
            <select id="unit_kerja_id" name="unit_kerja_id" class="form-select form-select-solid">
                <option value="">Pilih unit kerja</option>
                @foreach ($unitKerjaList as $unit)
                    <option value="{{ $unit->id }}" @selected($selected('unit_kerja_id') == $unit->id)>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="status_hukum_id">Status Hukum</label>
            <select id="status_hukum_id" name="status_hukum_id" class="form-select form-select-solid">
                <option value="">Pilih status hukum</option>
                @foreach ($statusList as $status)
                    <option value="{{ $status->id }}" @selected($selected('status_hukum_id') == $status->id)>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="no_register">No. Register</label>
            <input type="text" id="no_register" name="no_register" class="form-control form-control-solid"
                value="{{ $selected('no_register') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="nomor_hak">Nomor Hak</label>
            <input type="text" id="nomor_hak" name="nomor_hak" class="form-control form-control-solid"
                value="{{ $selected('nomor_hak') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="penggunaan_spma">Penggunaan SPMA</label>
            <input type="text" id="penggunaan_spma" name="penggunaan_spma" class="form-control form-control-solid"
                value="{{ $selected('penggunaan_spma') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="jenis_hak">Jenis Hak</label>
            <input type="text" id="jenis_hak" name="jenis_hak" class="form-control form-control-solid"
                value="{{ $selected('jenis_hak') }}">
        </div>

        {{-- ✅ FIELD BARU: Hak Tanah --}}
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="hak_tanah">
                Hak Tanah
                
            </label>
            <select id="hak_tanah" name="hak_tanah" class="form-select form-select-solid">
                <option value="">Pilih Hak Tanah</option>
                <option value="Hak Milik" {{ $selected('hak_tanah') === 'Hak Milik' ? 'selected' : '' }}>Hak Milik</option>
                <option value="Hak Guna Usaha" {{ $selected('hak_tanah') === 'Hak Guna Usaha' ? 'selected' : '' }}>Hak Guna Usaha</option>
                <option value="Hak Guna Bangunan" {{ $selected('hak_tanah') === 'Hak Guna Bangunan' ? 'selected' : '' }}>Hak Guna Bangunan</option>
                <option value="Hak Pakai" {{ $selected('hak_tanah') === 'Hak Pakai' ? 'selected' : '' }}>Hak Pakai</option>
                <option value="Hak Pengelolaan" {{ $selected('hak_tanah') === 'Hak Pengelolaan' ? 'selected' : '' }}>Hak Pengelolaan</option>
                <option value="Tanah Negara" {{ $selected('hak_tanah') === 'Tanah Negara' ? 'selected' : '' }}>Tanah Negara</option>
                <option value="Lainnya" {{ $selected('hak_tanah') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <small class="text-muted">Status kepemilikan hak atas tanah</small>
        </div>

        {{-- ✅ FIELD BARU: Asal --}}
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="asal">
                Asal Perolehan
                
            </label>
            <select id="asal" name="asal" class="form-select form-select-solid">
                <option value="">Pilih Asal Perolehan</option>
                <option value="Pembelian" {{ $selected('asal') === 'Pembelian' ? 'selected' : '' }}>Pembelian</option>
                <option value="Hibah" {{ $selected('asal') === 'Hibah' ? 'selected' : '' }}>Hibah</option>
                <option value="Tukar Menukar" {{ $selected('asal') === 'Tukar Menukar' ? 'selected' : '' }}>Tukar Menukar</option>
                <option value="Ganti Rugi" {{ $selected('asal') === 'Ganti Rugi' ? 'selected' : '' }}>Ganti Rugi</option>
                <option value="Sumbangan" {{ $selected('asal') === 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
                <option value="APBN" {{ $selected('asal') === 'APBN' ? 'selected' : '' }}>APBN</option>
                <option value="APBD" {{ $selected('asal') === 'APBD' ? 'selected' : '' }}>APBD</option>
                <option value="Lainnya" {{ $selected('asal') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <small class="text-muted">Sumber perolehan tanah</small>
        </div>

        {{-- ✅ FIELD BARU: Kategori Tanah --}}
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="kat_tanah">
                Kategori Tanah
                
            </label>
            <select id="kat_tanah" name="kat_tanah" class="form-select form-select-solid">
                <option value="">Pilih Kategori Tanah</option>
                <option value="Pertanian" {{ $selected('kat_tanah') === 'Pertanian' ? 'selected' : '' }}>Pertanian</option>
                <option value="Perkebunan" {{ $selected('kat_tanah') === 'Perkebunan' ? 'selected' : '' }}>Perkebunan</option>
                <option value="Permukiman" {{ $selected('kat_tanah') === 'Permukiman' ? 'selected' : '' }}>Permukiman</option>
                <option value="Industri" {{ $selected('kat_tanah') === 'Industri' ? 'selected' : '' }}>Industri</option>
                <option value="Perkantoran" {{ $selected('kat_tanah') === 'Perkantoran' ? 'selected' : '' }}>Perkantoran</option>
                <option value="Komersial" {{ $selected('kat_tanah') === 'Komersial' ? 'selected' : '' }}>Komersial</option>
                <option value="Fasilitas Umum" {{ $selected('kat_tanah') === 'Fasilitas Umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                <option value="Hutan" {{ $selected('kat_tanah') === 'Hutan' ? 'selected' : '' }}>Hutan</option>
                <option value="Pertambangan" {{ $selected('kat_tanah') === 'Pertambangan' ? 'selected' : '' }}>Pertambangan</option>
                <option value="Kosong" {{ $selected('kat_tanah') === 'Kosong' ? 'selected' : '' }}>Kosong</option>
                <option value="Lainnya" {{ $selected('kat_tanah') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <small class="text-muted">Klasifikasi penggunaan tanah</small>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="kode">Kode Internal</label>
            <input type="text" id="kode" name="kode" class="form-control form-control-solid"
                value="{{ $selected('kode') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="nui">NUI</label>
            <input type="text" id="nui" name="nui" class="form-control form-control-solid"
                value="{{ $selected('nui') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="nib">NIB</label>
            <input type="text" id="nib" name="nib" class="form-control form-control-solid"
                value="{{ $selected('nib') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="luas_m2">Luas (m²)</label>
            <input type="number" step="0.01" id="luas_m2" name="luas_m2" class="form-control form-control-solid"
                value="{{ $selected('luas_m2') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="panjang_m">Panjang (m)</label>
            <input type="number" step="0.01" id="panjang_m" name="panjang_m" class="form-control form-control-solid"
                value="{{ $selected('panjang_m') }}">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="lebar_m">Lebar (m)</label>
            <input type="number" step="0.01" id="lebar_m" name="lebar_m" class="form-control form-control-solid"
                value="{{ $selected('lebar_m') }}">
        </div>

        <div class="col-md-12">
            <label class="fw-semibold fs-7 mb-2" for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" class="form-control form-control-solid" rows="2">{{ $selected('alamat') }}</textarea>
        </div>
    </div>

    {{-- ✅ SECTION SERTIFIKAT --}}
    <div class="separator separator-dashed my-8"></div>
    
    <div class="row mb-6">
        <div class="col-12">
            <h4 class="fw-bold text-gray-800 mb-4">
                <i class="fa-solid fa-file-lines fs-2 text-primary me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Dokumen Sertifikat
            </h4>
        </div>

        {{-- Radio Button: Pilih Tipe Sertifikat --}}
        <div class="col-12 mb-4">
            <label class="fw-semibold fs-7 mb-3">Tipe Dokumen Sertifikat</label>
            <div class="d-flex gap-5">
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input" type="radio" name="sertifikat_type" 
                           id="sertifikat_file" value="file" 
                           {{ $sertifikatType === 'file' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-gray-700" for="sertifikat_file">
                        <i class="fa-solid fa-file-arrow-up fs-2 text-primary me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Upload File
                    </label>
                </div>
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input" type="radio" name="sertifikat_type" 
                           id="sertifikat_link" value="link"
                           {{ $sertifikatType === 'link' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-gray-700" for="sertifikat_link">
                        <i class="fa-solid fa-link fs-2 text-success me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Input Link
                    </label>
                </div>
            </div>
        </div>

        {{-- Field No Sertifikat & Tanggal --}}
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="no_sertif">No. Sertifikat</label>
            <input type="text" id="no_sertif" name="no_sertif" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('no_sertif') }}"
                   placeholder="Contoh: 123/SRT/2024">
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="tgl_sertif">Tanggal Sertifikat</label>
            <input type="date" id="tgl_sertif" name="tgl_sertif" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('tgl_sertif') ? (is_string($selectedDokumen('tgl_sertif')) ? $selectedDokumen('tgl_sertif') : $selectedDokumen('tgl_sertif')->format('Y-m-d')) : '' }}">
        </div>

        {{-- ✅ SECTION BARU: Informasi Dokumen Tambahan --}}
        <div class="col-12">
            <div class="separator separator-dashed my-4"></div>
            <h5 class="fw-bold text-gray-700 mb-3">
                <i class="fa-solid fa-file-lines fs-3 text-info me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Informasi Dokumen Tambahan
            </h5>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="no_dokumen">No. Dokumen</label>
            <input type="text" id="no_dokumen" name="no_dokumen" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('no_dokumen') }}"
                   placeholder="Contoh: DOK-001/2024">
            <small class="text-muted">Nomor referensi dokumen internal</small>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="tanggal_dokumen">Tanggal Dokumen</label>
            <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('tanggal_dokumen') ? (is_string($selectedDokumen('tanggal_dokumen')) ? $selectedDokumen('tanggal_dokumen') : $selectedDokumen('tanggal_dokumen')->format('Y-m-d')) : '' }}">
            <small class="text-muted">Tanggal penerbitan dokumen</small>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="tanggal_oleh">Tanggal Diolah</label>
            <input type="date" id="tanggal_oleh" name="tanggal_oleh" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('tanggal_oleh') ? (is_string($selectedDokumen('tanggal_oleh')) ? $selectedDokumen('tanggal_oleh') : $selectedDokumen('tanggal_oleh')->format('Y-m-d')) : '' }}">
            <small class="text-muted">Tanggal dokumen diproses</small>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="tanggal_buku">Tanggal Buku Tanah</label>
            <input type="date" id="tanggal_buku" name="tanggal_buku" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('tanggal_buku') ? (is_string($selectedDokumen('tanggal_buku')) ? $selectedDokumen('tanggal_buku') : $selectedDokumen('tanggal_buku')->format('Y-m-d')) : '' }}">
            <small class="text-muted">Tanggal pencatatan di buku tanah</small>
        </div>

        <div class="col-12">
            <div class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="has_konfir" 
                       id="has_konfir" value="1"
                       {{ $selectedDokumen('has_konfir') ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-700" for="has_konfir">
                   
                    Dokumen Sudah Dikonfirmasi
                </label>
            </div>
            <small class="text-muted ms-7">Centang jika dokumen telah diverifikasi dan dikonfirmasi</small>
        </div>

        <div class="col-12">
            <div class="separator separator-dashed my-4"></div>
        </div>

        {{-- Upload File Section --}}
        <div class="col-12" id="file_upload_section">
            <label class="fw-semibold fs-7 mb-2">Upload File Sertifikat</label>
            
            {{-- Preview existing file (for edit mode) --}}
            @if($dokumen && $dokumen->file_sertif)
                <div class="existing-file-preview mb-3" id="existing-file-container">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fa-solid fa-circle-check fs-2 text-success me-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="flex-grow-1">
                            <strong>File Saat Ini:</strong>
                            <a href="{{ asset('storage/' . $dokumen->file_sertif) }}" 
                               target="_blank" class="text-primary ms-2">
                                {{ basename($dokumen->file_sertif) }}
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm btn-light-danger" 
                                onclick="removeExistingFile()">
                            <i class="ki-duotone ki-trash fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            @endif
            
            <div class="dropzone-custom" id="sertifikat_dropzone">
                <div class="dz-message" id="dz-default-message">
                    <i class="fa-solid fa-file-arrow-up fs-3x text-primary mb-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <div class="fw-bold fs-5 text-gray-700 mb-2">
                        Drag & Drop file atau klik untuk upload
                    </div>
                    <div class="text-muted fs-7">
                        Format: PDF, JPG, PNG (Max: 5MB)
                    </div>
                </div>
            </div>
            
            <input type="file" name="file_sertif" id="file_sertif_input" 
                   class="d-none" accept=".pdf,.jpg,.jpeg,.png">
            
            <small class="text-muted">Upload file sertifikat dalam format PDF atau gambar</small>
        </div>

        {{-- Link Input Section --}}
        <div class="col-12" id="link_input_section" style="display: none;">
            <label class="fw-semibold fs-7 mb-2" for="link_sertif">Link Sertifikat</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-link fs-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <input type="url" id="link_sertif" name="link_sertif" 
                       class="form-control form-control-solid"
                       value="{{ $selectedDokumen('link_sertif') }}"
                       placeholder="https://drive.google.com/...">
            </div>
            <small class="text-muted">Masukkan URL lengkap dari Google Drive, Dropbox, atau layanan cloud lainnya</small>
        </div>

        {{-- Status & Keterangan Sertifikat --}}
        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="sts_sertif">Status Sertifikat</label>
            <select id="sts_sertif" name="sts_sertif" class="form-select form-select-solid">
                <option value="">Pilih Status</option>
                <option value="Sudah Tersertifikasi" {{ $selectedDokumen('sts_sertif') === 'Sudah Tersertifikasi' ? 'selected' : '' }}>
                    Sudah Tersertifikasi
                </option>
                <option value="Proses Sertifikasi" {{ $selectedDokumen('sts_sertif') === 'Proses Sertifikasi' ? 'selected' : '' }}>
                    Proses Sertifikasi
                </option>
                <option value="Belum Tersertifikasi" {{ $selectedDokumen('sts_sertif') === 'Belum Tersertifikasi' ? 'selected' : '' }}>
                    Belum Tersertifikasi
                </option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="fw-semibold fs-7 mb-2" for="nama_sertifikat">Nama Sertifikat</label>
            <input type="text" id="nama_sertifikat" name="nama_sertifikat" 
                   class="form-control form-control-solid"
                   value="{{ $selectedDokumen('nama_sertifikat') }}"
                   placeholder="Contoh: Sertifikat Hak Milik">
        </div>

        <div class="col-12">
            <label class="fw-semibold fs-7 mb-2" for="ket_sertif">Keterangan Sertifikat</label>
            <textarea id="ket_sertif" name="ket_sertif" 
                      class="form-control form-control-solid" 
                      rows="3"
                      placeholder="Catatan atau keterangan tambahan mengenai sertifikat">{{ $selectedDokumen('ket_sertif') }}</textarea>
        </div>
    </div>
</div>

@push('styles')
<style>
.dropzone-custom {
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.dropzone-custom:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.dropzone-custom.drag-over {
    border-color: #2563eb;
    background: #dbeafe;
    transform: scale(1.02);
}

.existing-file-preview {
    margin-bottom: 15px;
}

.file-preview-container {
    width: 100%;
}

.file-preview-container .alert {
    margin: 0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing sertifikat form...');
    
    // ========================================
    // 📄 DECLARE ALL VARIABLES FIRST
    // ========================================
    
    const fileRadio = document.getElementById('sertifikat_file');
    const linkRadio = document.getElementById('sertifikat_link');
    const fileSection = document.getElementById('file_upload_section');
    const linkSection = document.getElementById('link_input_section');
    const dropzone = document.getElementById('sertifikat_dropzone');
    const fileInput = document.getElementById('file_sertif_input');
    const defaultMessage = document.getElementById('dz-default-message');
    
    // ========================================
    // 📄 HELPER FUNCTIONS
    // ========================================
    
    function clearFilePreview() {
        const preview = dropzone.querySelector('.file-preview-container');
        if (preview) {
            preview.remove();
        }
        
        if (defaultMessage) {
            defaultMessage.style.display = 'block';
        }
    }
    
    function showFilePreview(file) {
        if (defaultMessage) {
            defaultMessage.style.display = 'none';
        }
        
        const existingPreview = dropzone.querySelector('.file-preview-container');
        if (existingPreview) {
            existingPreview.remove();
        }
        
        const preview = document.createElement('div');
        preview.className = 'file-preview-container';
        preview.innerHTML = `
            <div class="alert alert-success d-flex align-items-center mb-0">
                <i class="ki-duotone ki-check-circle fs-2 text-success me-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <div class="flex-grow-1">
                    <strong>File Dipilih:</strong> ${file.name}
                    <div class="text-muted fs-8">${(file.size / 1024).toFixed(2)} KB</div>
                </div>
                <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection()">
                    <i class="ki-duotone ki-trash fs-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                </button>
            </div>
        `;
        
        dropzone.appendChild(preview);
    }
    
    function handleFiles(files) {
        const file = files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        
        console.log('📁 File selected:', file.name, file.type, file.size);
        
        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            alert('❌ Format file tidak valid! Hanya PDF, JPG, atau PNG yang diperbolehkan.');
            fileInput.value = '';
            clearFilePreview();
            return;
        }
        
        // Validate file size
        if (file.size > maxSize) {
            alert('❌ Ukuran file terlalu besar! Maksimal 5MB.');
            fileInput.value = '';
            clearFilePreview();
            return;
        }
        
        console.log('✅ File validated successfully');
        showFilePreview(file);
    }
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // ========================================
    // 📄 SERTIFIKAT TYPE TOGGLE
    // ========================================
    
    function toggleSertifikatType() {
        if (fileRadio.checked) {
            fileSection.style.display = 'block';
            linkSection.style.display = 'none';
            
            // Clear link value
            const linkInput = document.getElementById('link_sertif');
            if (linkInput) {
                linkInput.value = '';
            }
        } else {
            fileSection.style.display = 'none';
            linkSection.style.display = 'block';
            
            // Clear file input
            if (fileInput) {
                fileInput.value = '';
                clearFilePreview();
            }
        }
    }
    
    // Add event listeners for radio buttons
    if (fileRadio) {
        fileRadio.addEventListener('change', toggleSertifikatType);
    }
    
    if (linkRadio) {
        linkRadio.addEventListener('change', toggleSertifikatType);
    }
    
    // Initialize on page load
    toggleSertifikatType();
    
    // ========================================
    // 📤 DRAG & DROP FILE UPLOAD
    // ========================================
    
    if (dropzone && fileInput) {
        // Click to browse
        dropzone.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-light-danger')) {
                fileInput.click();
            }
        });
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop zone
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, function() {
                dropzone.classList.add('drag-over');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, function() {
                dropzone.classList.remove('drag-over');
            }, false);
        });
        
        // Handle dropped files
        dropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                
                handleFiles(files);
            }
        });
        
        // Handle file input change
        fileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                handleFiles(this.files);
            }
        });
    }
    
    // ========================================
    // 🌐 GLOBAL FUNCTIONS
    // ========================================
    
    // Clear file selection
    window.clearFileSelection = function() {
        if (fileInput) {
            fileInput.value = '';
            clearFilePreview();
            console.log('🗑️ File selection cleared');
        }
    };
    
    // Remove existing file
    window.removeExistingFile = function() {
        if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            const existingContainer = document.getElementById('existing-file-container');
            if (existingContainer) {
                existingContainer.remove();
            }
            
            let removeFlag = document.querySelector('input[name="remove_file_sertif"]');
            if (!removeFlag) {
                removeFlag = document.createElement('input');
                removeFlag.type = 'hidden';
                removeFlag.name = 'remove_file_sertif';
                removeFlag.value = '1';
                const form = document.querySelector('form');
                if (form) {
                    form.appendChild(removeFlag);
                }
            }
            
            console.log('🗑️ Existing file marked for removal');
        }
    };
    
    console.log('✅ Sertifikat form initialized');
});
</script>
@endpush