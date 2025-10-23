<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetDokumen;
use App\Models\KategoriAsset;
use App\Models\UnitKerja;
use App\Models\StatusHukumAsset;
use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapitulasiAssetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;



class AssetController extends Controller
{
    /**
     * Tampilkan daftar asset dengan pencarian dan filter.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $filters = [
            'search' => $request->input('search'),
            'filter' => $request->input('filter'),
        ];

        $filterKey = null;
        $filterValue = null;

        if ($filters['filter']) {
            [$filterKey, $filterValue] = array_pad(explode(':', $filters['filter'], 2), 2, null);

            if (!in_array($filterKey, ['kategori', 'status', 'unit', 'provinsi'], true) || !is_numeric($filterValue)) {
                $filterKey = null;
                $filterValue = null;
                $filters['filter'] = null;
            } else {
                $filterValue = (int) $filterValue;
            }
        }

        $assets = Asset::query()
            ->with(['kategori', 'unitKerja', 'statusHukum', 'province', 'regency', 'district', 'village', 'dokumenUtama'])
            ->when($filters['search'], function ($query, $search) {
                $searchTerm = '%' . $search . '%';

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nama_asset', 'like', $searchTerm)
                        ->orWhere('kode_asset', 'like', $searchTerm)
                        ->orWhere('no_register', 'like', $searchTerm);
                });
            })
            ->when($filterKey === 'kategori' && $filterValue !== null, fn($query) => $query->where('kategori_id', $filterValue))
            ->when($filterKey === 'status' && $filterValue !== null, fn($query) => $query->where('status_hukum_id', $filterValue))
            ->when($filterKey === 'unit' && $filterValue !== null, fn($query) => $query->where('unit_kerja_id', $filterValue))
            ->when($filterKey === 'provinsi' && $filterValue !== null, fn($query) => $query->where('reg_provinces_id', $filterValue))
            ->orderBy('nama_asset')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.asset.index', [
            'assets' => $assets,
            'filters' => $filters,
            'perPage' => $perPage,
            'kategoriList' => KategoriAsset::orderBy('name')->get(),
            'unitKerjaList' => UnitKerja::orderBy('nama_unit')->get(),
            'statusList' => StatusHukumAsset::orderBy('name')->get(),
            'provinsiList' => RegProvince::orderBy('name')->get(),
        ]);
    }

    /**
     * Form tambah asset.
     */
    public function create(): View
    {
        return view('admin.asset.create', [
            'kategoriList' => KategoriAsset::orderBy('name')->get(),
            'unitKerjaList' => UnitKerja::orderBy('nama_unit')->get(),
            'statusList' => StatusHukumAsset::orderBy('name')->get(),
            'provinsiList' => RegProvince::orderBy('name')->get(),
            'kabupatenList' => RegRegency::orderBy('name')->get(),
            'kecamatanList' => RegDistrict::orderBy('name')->get(),
            'kelurahanList' => RegVillage::orderBy('name')->get(),
        ]);
    }

    /**
     * Simpan asset baru.
     */
    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Validasi data asset
            $assetData = $this->validatedAssetData($request);
            
            // Create asset
            $asset = Asset::create($assetData);

            // Handle dokumen sertifikat
            $this->handleDokumenSertifikat($request, $asset);

            DB::commit();

            return redirect()
                ->route('admin.asset.index')
                ->with('success', 'Asset berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing asset', ['error' => $e->getMessage()]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit asset.
     */
    public function edit(Asset $asset): View
    {
        // Load relasi dokumen
        $asset->load('dokumenUtama');
        
        return view('admin.asset.edit', [
            'asset' => $asset,
            'kategoriList' => KategoriAsset::orderBy('name')->get(),
            'unitKerjaList' => UnitKerja::orderBy('nama_unit')->get(),
            'statusList' => StatusHukumAsset::orderBy('name')->get(),
            'provinsiList' => RegProvince::orderBy('name')->get(),
            'kabupatenList' => RegRegency::orderBy('name')->get(),
            'kecamatanList' => RegDistrict::orderBy('name')->get(),
            'kelurahanList' => RegVillage::orderBy('name')->get(),
        ]);
    }

    /**
     * Perbarui asset yang ada.
     */
    public function update(Request $request, Asset $asset): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Validasi data asset
            $assetData = $this->validatedAssetData($request, $asset);
            
            // Update asset
            $asset->update($assetData);

            // Handle dokumen sertifikat
            $this->handleDokumenSertifikat($request, $asset);

            DB::commit();

            return redirect()
                ->route('admin.asset.index')
                ->with('success', 'Asset berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating asset', ['error' => $e->getMessage()]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus asset.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Delete associated file if exists
            if ($asset->dokumenUtama && $asset->dokumenUtama->file_sertif) {
                Storage::disk('public')->delete($asset->dokumenUtama->file_sertif);
            }

            // Delete will cascade to dokumenUtama thanks to foreign key constraint
            $asset->delete();

            DB::commit();

            return redirect()
                ->route('admin.asset.index')
                ->with('success', 'Asset berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting asset', ['error' => $e->getMessage()]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus asset: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Handle dokumen sertifikat (create/update)
     */
    private function handleDokumenSertifikat(Request $request, Asset $asset): void
    {
        // Validasi dokumen data
        $dokumenData = $this->validatedDokumenData($request);
        
        // Skip jika tidak ada data dokumen
        if (empty(array_filter($dokumenData))) {
            return;
        }

        // Handle file upload
        if ($request->hasFile('file_sertif') && $request->input('sertifikat_type') === 'file') {
            // Delete old file if exists
            if ($asset->dokumenUtama && $asset->dokumenUtama->file_sertif) {
                Storage::disk('public')->delete($asset->dokumenUtama->file_sertif);
            }
            
            $file = $request->file('file_sertif');
            $filename = time() . '_' . $file->getClientOriginalName();
            $dokumenData['file_sertif'] = $file->storeAs('sertifikat', $filename, 'public');
            $dokumenData['link_sertif'] = null;
            
            Log::info('File sertifikat uploaded', ['path' => $dokumenData['file_sertif']]);
        } 
        // Handle file removal
        elseif ($request->input('remove_file_sertif') == '1') {
            if ($asset->dokumenUtama && $asset->dokumenUtama->file_sertif) {
                Storage::disk('public')->delete($asset->dokumenUtama->file_sertif);
            }
            $dokumenData['file_sertif'] = null;
        }
        // Switch to link
        elseif ($request->input('sertifikat_type') === 'link') {
            if ($asset->dokumenUtama && $asset->dokumenUtama->file_sertif) {
                Storage::disk('public')->delete($asset->dokumenUtama->file_sertif);
            }
            $dokumenData['file_sertif'] = null;
        }

        // Create or update dokumen
        if ($asset->dokumenUtama) {
            $asset->dokumenUtama->update($dokumenData);
        } else {
            $asset->dokumenUtama()->create($dokumenData);
        }
    }

    /**
     * Validasi data asset.
     */
    private function validatedAssetData(Request $request, ?Asset $asset = null): array
    {
        $kodeAssetRule = Rule::unique('asset', 'kode_asset');

        if ($asset) {
            $kodeAssetRule = $kodeAssetRule->ignore($asset->id);
        }

        return $request->validate([
            // Field dasar asset
            'kode_asset' => ['required', 'string', 'max:100', $kodeAssetRule],
            'nama_asset' => ['required', 'string', 'max:255'],
            'no_register' => ['nullable', 'string', 'max:100'],
            'nomor_hak' => ['nullable', 'string', 'max:100'],
            'penggunaan_spma' => ['nullable', 'string', 'max:255'],
            'jenis_hak' => ['nullable', 'string', 'max:150'],
            'asal' => ['nullable', 'string', 'max:150'],
            'kat_tanah' => ['nullable', 'string', 'max:100'],
            'kode' => ['nullable', 'string', 'max:100'],
            'nui' => ['nullable', 'string', 'max:100'],
            'nib' => ['nullable', 'string', 'max:100'],
            'luas_m2' => ['nullable', 'numeric', 'min:0'],
            'panjang_m' => ['nullable', 'numeric', 'min:0'],
            'lebar_m' => ['nullable', 'numeric', 'min:0'],
            'alamat' => ['nullable', 'string', 'max:500'],

            // Field wilayah
            'reg_provinces_id' => ['nullable', 'exists:reg_provinces,id'],
            'reg_regencies_id' => ['nullable', 'exists:reg_regencies,id'],
            'reg_districts_id' => ['nullable', 'exists:reg_districts,id'],
            'reg_villages_id' => ['nullable', 'exists:reg_villages,id'],

            // Field relasi
            'kategori_id' => ['nullable', 'exists:kategori_asset,id'],
            'unit_kerja_id' => ['nullable', 'exists:unit_kerja,id'],
            'status_hukum_id' => ['nullable', 'exists:status_hukum_asset,id'],

            // Field koordinat dan GeoJSON
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'geojson' => ['nullable', 'string'],
        ], [
            'kode_asset.required' => 'Kode asset wajib diisi.',
            'kode_asset.unique' => 'Kode asset sudah digunakan.',
            'nama_asset.required' => 'Nama asset wajib diisi.',
            'latitude.between' => 'Latitude harus antara -90 dan 90.',
            'longitude.between' => 'Longitude harus antara -180 dan 180.',
        ]);
    }

    /**
     * ✅ Validasi data dokumen sertifikat
     */
    private function validatedDokumenData(Request $request): array
    {
        $rules = [
            'sertifikat_type' => ['nullable', 'in:file,link'],
            'link_sertif' => ['nullable', 'url', 'max:500'],
            'no_sertif' => ['nullable', 'string', 'max:100'],
            'tgl_sertif' => ['nullable', 'date'],
            'nama_sertifikat' => ['nullable', 'string', 'max:255'],
            'sts_sertif' => ['nullable', 'string', 'max:255'],
            'ket_sertif' => ['nullable', 'string', 'max:1000'],
            // ✅ VALIDASI KOLOM BARU
            'no_dokumen' => ['nullable', 'string', 'max:100'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'tanggal_oleh' => ['nullable', 'date'],
            'tanggal_buku' => ['nullable', 'date'],
            'has_konfir' => ['nullable', 'boolean'],
        ];

        // Add file validation only if file is uploaded
        if ($request->hasFile('file_sertif')) {
            $rules['file_sertif'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        return $request->validate($rules, [
            'file_sertif.file' => 'File sertifikat harus berupa file yang valid.',
            'file_sertif.mimes' => 'File sertifikat harus berformat PDF, JPG, JPEG, atau PNG.',
            'file_sertif.max' => 'Ukuran file sertifikat maksimal 5MB.',
            'link_sertif.url' => 'Link sertifikat harus berupa URL yang valid.',
            'tanggal_dokumen.date' => 'Format tanggal dokumen tidak valid.',
            'tanggal_oleh.date' => 'Format tanggal oleh tidak valid.',
            'tanggal_buku.date' => 'Format tanggal buku tidak valid.',
        ]);
    }

    /**
     * Tampilkan peta persebaran tanah
     */
    public function petaPersebaran(): View
    {
        return view('admin.asset.peta-persebaran', [
            'title' => 'Peta Persebaran Tanah'
        ]);
    }

    /**
     * Tampilkan rekapitulasi asset tanah
     */
     public function rekapitulasi(Request $request)
    {
        $activeTab = $request->input('tab', 'rekap');

        // Allowed per page dan ambil dari query
        $allowedPerPage = [10, 25, 50, 100, 200];
        $perPage = (int) $request->input('per_page', 25);
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        $base = Asset::with(['province','regency','district','village','unitKerja','kategori']);

        if ($request->filled('search')) {
            $base->where('nama_asset', 'like', "%{$request->search}%");
        }
        if ($request->filled('unit_kerja_id')) {
            $base->where('unit_kerja_id', $request->unit_kerja_id);
        }
        if ($request->filled('regency_id')) {
            $base->where('reg_regencies_id', $request->regency_id);
        }
        if ($request->filled('district_id')) {
            $base->where('reg_districts_id', $request->district_id);
        }

        // (Opsional, tidak dipakai di Blade Anda)
        $assets = (clone $base)->orderBy('nama_asset','asc')->paginate($perPage)->withQueryString();

        // ===== Rekap per Nama Aset (Collection -> paginate manual) =====
        $rekapCollection = (clone $base)->get()
            ->groupBy('nama_asset')
            ->map(function ($items, $nama) {
                $first = $items->first();
                return (object) [
                    'nama_asset'     => $nama,
                    'jumlah_titik'   => $items->count(),
                    'kecamatan'      => $first->district->name ?? '-',
                    'kecamatan_code' => $first->reg_districts_id ?? '',
                    'kabupaten'      => $first->regency->name ?? '-',
                    'kabupaten_code' => $first->reg_regencies_id ?? '',
                    'total_luas'     => $items->sum('luas_m2'),
                ];
            })->values();

        $pageRekap = (int) $request->input('page_rekap', 1);
        $rekap = new LengthAwarePaginator(
            $rekapCollection->slice(($pageRekap - 1) * $perPage, $perPage)->values(),
            $rekapCollection->count(),
            $perPage,
            $pageRekap,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page_rekap']
        );

        // ===== Wilayah (Kabupaten x Kecamatan) + rincian per kategori =====
        $wilayahBase = (clone $base)
            ->leftJoin('reg_regencies as kab', 'asset.reg_regencies_id', '=', 'kab.id')
            ->leftJoin('reg_districts as kec', 'asset.reg_districts_id', '=', 'kec.id')
            ->selectRaw('asset.reg_regencies_id, COALESCE(kab.name,"-") as kabupaten')
            ->selectRaw('asset.reg_districts_id, COALESCE(kec.name,"-") as kecamatan')
            ->selectRaw('COUNT(*) as total_asset')
            ->groupBy('asset.reg_regencies_id','kab.name','asset.reg_districts_id','kec.name')
            ->get();

        $perKategori = (clone $base)
            ->selectRaw('reg_regencies_id, reg_districts_id, kategori_id, COUNT(*) as total')
            ->groupBy('reg_regencies_id','reg_districts_id','kategori_id')
            ->get()
            ->groupBy(fn($r) => ($r->reg_regencies_id ?? 0).'-'.($r->reg_districts_id ?? 0));

        $wilayahCollection = $wilayahBase->map(function($row) use ($perKategori) {
            $key = ($row->reg_regencies_id ?? 0).'-'.($row->reg_districts_id ?? 0);
            $jenis = ($perKategori[$key] ?? collect())
                ->mapWithKeys(fn($r) => [(int)$r->kategori_id => (int)$r->total]);

            return (object)[
                'kabupaten'       => $row->kabupaten,
                'kabupaten_code'  => $row->reg_regencies_id,
                'kecamatan'       => $row->kecamatan,
                'kecamatan_code'  => $row->reg_districts_id,
                'total_asset'     => (int)$row->total_asset,
                'per_jenis'       => $jenis,
            ];
        })->values();

        $pageWilayah = (int) $request->input('page_wilayah', 1);
        $wilayahSummary = new LengthAwarePaginator(
            $wilayahCollection->slice(($pageWilayah - 1) * $perPage, $perPage)->values(),
            $wilayahCollection->count(),
            $perPage,
            $pageWilayah,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page_wilayah']
        );

        // Dropdown & statistik
        $unitKerjas    = UnitKerja::orderBy('nama_unit')->get();
        $regencies     = RegRegency::orderBy('name')->get();
        $districts     = RegDistrict::orderBy('name')->get();
        $kategoriList  = KategoriAsset::orderBy('name')->get();
        $statistics = [
            'total_asset'      => Asset::count(),
            'total_luas'       => Asset::sum('luas_m2'),
            'total_unit_kerja' => UnitKerja::count(),
        ];

        return view('admin.asset.rekapitulasi', compact(
            'rekap', 'wilayahSummary', 'assets',
            'unitKerjas','regencies','districts','statistics',
            'kategoriList','activeTab','perPage'
        ));
    }

    public function printRekapitulasi(Request $request)
    {
        // Base + filter sama seperti rekapitulasi()
        $base = Asset::with(['kategori','unitKerja','province','regency','district','village']);

        if ($request->filled('search')) {
            $base->where('nama_asset', 'like', "%{$request->search}%");
        }
        if ($request->filled('unit_kerja_id')) {
            $base->where('unit_kerja_id', $request->unit_kerja_id);
        }
        if ($request->filled('regency_id')) {
            $base->where('reg_regencies_id', $request->regency_id);
        }
        if ($request->filled('district_id')) {
            $base->where('reg_districts_id', $request->district_id);
        }

        // Ringkasan angka kunci
        $summary = [
            'total_asset'      => (clone $base)->count(),
            'total_luas'       => (clone $base)->sum('luas_m2'),
            'total_kabupaten'  => (clone $base)->select('reg_regencies_id')->distinct()->count('reg_regencies_id'),
            'total_kecamatan'  => (clone $base)->select('reg_districts_id')->distinct()->count('reg_districts_id'),
            'total_unit_kerja' => (clone $base)->select('unit_kerja_id')->distinct()->count('unit_kerja_id'),
        ];

        // Agregat per kategori
        $perKategori = (clone $base)
            ->leftJoin('kategori_asset as ka', 'asset.kategori_id', '=', 'ka.id')
            ->selectRaw('COALESCE(ka.name,"Tanpa Kategori") as kategori')
            ->selectRaw('COUNT(*) as total, COALESCE(SUM(asset.luas_m2),0) as luas')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        // Agregat per kabupaten
        $perKabupaten = (clone $base)
            ->leftJoin('reg_regencies as kab', 'asset.reg_regencies_id', '=', 'kab.id')
            ->selectRaw('COALESCE(kab.name,"-") as kabupaten')
            ->selectRaw('COUNT(*) as total, COALESCE(SUM(asset.luas_m2),0) as luas')
            ->groupBy('kabupaten')
            ->orderBy('kabupaten')
            ->get();

        // Agregat per unit kerja
        $perUnitKerja = (clone $base)
            ->leftJoin('unit_kerja as uk', 'asset.unit_kerja_id', '=', 'uk.id')
            ->selectRaw('COALESCE(uk.nama_unit,"-") as unit_kerja')
            ->selectRaw('COUNT(*) as total, COALESCE(SUM(asset.luas_m2),0) as luas')
            ->groupBy('unit_kerja')
            ->orderBy('unit_kerja')
            ->get();

        // 10 aset terluas (opsional, sering berguna di ringkasan)
        $topAset = (clone $base)
            ->selectRaw('nama_asset, COUNT(*) as titik, COALESCE(SUM(luas_m2),0) as luas')
            ->groupBy('nama_asset')
            ->orderByDesc('luas')
            ->limit(10)
            ->get();

        // Nama filter untuk ditampilkan di header print
        $filters = [
            'search'     => $request->input('search'),
            'unit_kerja' => optional(UnitKerja::find($request->unit_kerja_id))->nama_unit,
            'kabupaten'  => optional(RegRegency::find($request->regency_id))->name,
            'kecamatan'  => optional(RegDistrict::find($request->district_id))->name,
        ];

        $printedAt = now();

        return view('admin.asset.rekapitulasi-print', compact(
            'summary', 'perKategori', 'perKabupaten', 'perUnitKerja', 'topAset', 'filters', 'printedAt'
        ));
    }




    /**
     * Export Excel
     */
    public function exportRekapitulasi(Request $request)
    {
        // Ambil filter dari request
        $filters = [
            'search' => $request->input('search'),
            'unit_kerja_id' => $request->input('unit_kerja_id'),
            'reg_regencies_id' => $request->input('regency_id'),
            'reg_districts_id' => $request->input('district_id'),
        ];

        // Generate filename dengan timestamp
        $filename = 'Rekapitulasi_Asset_' . date('Y-m-d_His') . '.xlsx';

        // Download file Excel
        return Excel::download(
            new RekapitulasiAssetExport($filters), 
            $filename
        );
    }

    /**
     * Get Statistics (untuk AJAX)
     */
    public function getStatistics()
    {
        $data = [
            'total_asset' => Asset::count(),
            'total_luas' => number_format(Asset::sum('luas_m2'), 2, ',', '.'),
            'total_unit_kerja' => UnitKerja::count(),
            'asset_by_kabupaten' => Asset::with('regency')
                ->selectRaw('reg_regencies_id, COUNT(*) as total')
                ->groupBy('reg_regencies_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'kabupaten' => $item->regency->name ?? 'Tidak Ada',
                        'total' => $item->total
                    ];
                }),
        ];

        return response()->json($data);
    }

    /**
     * Tampilkan dokumen asset
     */
    public function dokumen(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 20);
        $perPage = in_array($perPage, [12, 20, 40, 60], true) ? $perPage : 20;

        $search = $request->input('search');
        $filterStatus = $request->input('filter_status');
        $filterUnit = $request->input('filter_unit');

        $assets = Asset::with(['kategori', 'unitKerja', 'dokumenUtama'])
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_asset', 'like', "%{$search}%")
                      ->orWhere('kode_asset', 'like', "%{$search}%")
                      ->orWhereHas('dokumenUtama', function($subQ) use ($search) {
                          $subQ->where('no_sertif', 'like', "%{$search}%");
                      });
                });
            })
            ->when($filterStatus, function($query, $status) {
                if ($status === 'digitalized') {
                    $query->whereHas('dokumenUtama', function($q) {
                        $q->whereNotNull('file_sertif');
                    });
                } elseif ($status === 'link') {
                    $query->whereHas('dokumenUtama', function($q) {
                        $q->whereNotNull('link_sertif');
                    });
                } elseif ($status === 'none') {
                    $query->doesntHave('dokumenUtama');
                }
            })
            ->when($filterUnit, function($query, $unit) {
                $query->where('unit_kerja_id', $unit);
            })
            ->orderBy('nama_asset')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.asset.dokumen', [
            'title' => 'Dokumen Asset',
            'assets' => $assets,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Download file sertifikat
     */
    public function downloadSertifikat(Asset $asset)
    {
        if (!$asset->dokumenUtama || !$asset->dokumenUtama->file_sertif) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $asset->dokumenUtama->file_sertif);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($filePath, basename($asset->dokumenUtama->file_sertif));
    }

    /**
     * View file sertifikat (inline)
     */
    public function viewSertifikat(Asset $asset)
    {
        if (!$asset->dokumenUtama || !$asset->dokumenUtama->file_sertif) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $asset->dokumenUtama->file_sertif);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->file($filePath);
    }

    /**
     * Delete sertifikat file only
     */
    public function deleteSertifikat(Request $request, Asset $asset): RedirectResponse
    {
        if ($asset->dokumenUtama && $asset->dokumenUtama->file_sertif) {
            Storage::disk('public')->delete($asset->dokumenUtama->file_sertif);
            $asset->dokumenUtama->update(['file_sertif' => null]);
            
            return redirect()->back()->with('success', 'File sertifikat berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada file sertifikat untuk dihapus.');
    }
}