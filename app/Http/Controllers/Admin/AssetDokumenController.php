<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetDokumen;
use App\Models\Asset;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\AssetDokumenExport;
use Maatwebsite\Excel\Facades\Excel;

class AssetDokumenController extends Controller
{
    /**
     * List dokumen + filter & pencarian.
     * - $dokumen : hasil paginated semua dokumen (untuk statistik/keperluan lain)
     * - $files   : paginated khusus dokumen yang punya file_sertif (untuk tab "File")
     * - $links   : paginated khusus dokumen yang punya link_sertif (untuk tab "Link")
     */
    public function index(Request $request)
    {
        // jumlah per halaman default 10, pilihan 10/20/40/60
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 40, 60], true) ? $perPage : 10;

        $filters = [
            'search'      => $request->input('search'),
            'status'      => $request->input('status'),       // digitalized|link|none|confirmed|unconfirmed
            'unit_kerja'  => $request->input('unit_kerja'),
            'tgl_from'    => $request->input('tgl_from'),
            'tgl_to'      => $request->input('tgl_to'),
        ];

        // === Query utama (semua dokumen) ===
        $dokumen = AssetDokumen::query()
            ->with(['asset.unitKerja'])
            ->when($filters['search'], function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('no_sertif', 'like', "%{$search}%")
                       ->orWhere('nama_sertifikat', 'like', "%{$search}%")
                       ->orWhere('no_dokumen', 'like', "%{$search}%")
                       ->orWhereHas('asset', function ($qa) use ($search) {
                           $qa->where('nama_asset', 'like', "%{$search}%")
                              ->orWhere('kode_asset', 'like', "%{$search}%");
                       });
                });
            })
            ->when($filters['status'], function ($q, $status) {
                if ($status === 'digitalized') {
                    $q->whereNotNull('file_sertif');
                } elseif ($status === 'link') {
                    $q->whereNotNull('link_sertif');
                } elseif ($status === 'none') {
                    $q->whereNull('file_sertif')->whereNull('link_sertif');
                } elseif ($status === 'confirmed') {
                    $q->where('has_konfir', true);
                } elseif ($status === 'unconfirmed') {
                    $q->where(function ($qq) {
                        $qq->whereNull('has_konfir')->orWhere('has_konfir', false);
                    });
                }
            })
            ->when($filters['unit_kerja'], function ($q, $unit) {
                $q->whereHas('asset', fn($qa) => $qa->where('unit_kerja_id', (int) $unit));
            })
            ->when($filters['tgl_from'], fn($q, $d) => $q->whereDate('tgl_sertif', '>=', $d))
            ->when($filters['tgl_to'], fn($q, $d) => $q->whereDate('tgl_sertif', '<=', $d))
            ->orderByDesc('tgl_sertif')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // === Paginator khusus FILE (tab "File") ===
        $files = AssetDokumen::query()
            ->with(['asset.unitKerja'])
            ->when($filters['search'], function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('no_sertif', 'like', "%{$search}%")
                       ->orWhere('nama_sertifikat', 'like', "%{$search}%")
                       ->orWhere('no_dokumen', 'like', "%{$search}%")
                       ->orWhereHas('asset', function ($qa) use ($search) {
                           $qa->where('nama_asset', 'like', "%{$search}%")
                              ->orWhere('kode_asset', 'like', "%{$search}%");
                       });
                });
            })
            ->whereNotNull('file_sertif')
            ->when($filters['unit_kerja'], fn($q, $unit) => $q->whereHas('asset', fn($qa) => $qa->where('unit_kerja_id', (int) $unit)))
            ->when($filters['tgl_from'], fn($q, $d) => $q->whereDate('tgl_sertif', '>=', $d))
            ->when($filters['tgl_to'], fn($q, $d) => $q->whereDate('tgl_sertif', '<=', $d))
            ->orderByDesc('tgl_sertif')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // === Paginator khusus LINK (tab "Link") ===
        $links = AssetDokumen::query()
            ->with(['asset.unitKerja'])
            ->when($filters['search'], function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('no_sertif', 'like', "%{$search}%")
                       ->orWhere('nama_sertifikat', 'like', "%{$search}%")
                       ->orWhere('no_dokumen', 'like', "%{$search}%")
                       ->orWhereHas('asset', function ($qa) use ($search) {
                           $qa->where('nama_asset', 'like', "%{$search}%")
                              ->orWhere('kode_asset', 'like', "%{$search}%");
                       });
                });
            })
            ->whereNull('file_sertif')
            ->whereNotNull('link_sertif')
            ->when($filters['unit_kerja'], fn($q, $unit) => $q->whereHas('asset', fn($qa) => $qa->where('unit_kerja_id', (int) $unit)))
            ->when($filters['tgl_from'], fn($q, $d) => $q->whereDate('tgl_sertif', '>=', $d))
            ->when($filters['tgl_to'], fn($q, $d) => $q->whereDate('tgl_sertif', '<=', $d))
            ->orderByDesc('tgl_sertif')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.asset_dokumen.index', [
            'dokumen'     => $dokumen,
            'files'       => $files,
            'links'       => $links,
            'filters'     => $filters,
            'perPage'     => $perPage,
            'unitKerjas'  => UnitKerja::orderBy('nama_unit')->get(),
        ]);
    }

    /**
     * Form create.
     */
    public function create()
    {
        return view('admin.asset_dokumen.create', [
            'assets'     => Asset::orderBy('nama_asset')->get(),
            'unitKerjas' => UnitKerja::orderBy('nama_unit')->get(),
        ]);
    }

    /**
     * Store dokumen.
     */
    public function store(Request $request)
    {
        $data = $this->validatedDokumenData($request, null);

        DB::beginTransaction();
        try {
            // File upload
            if ($request->hasFile('file_sertif') && ($data['sertifikat_type'] ?? null) === 'file') {
                $file = $request->file('file_sertif');
                $filename = time() . '_' . $file->getClientOriginalName();
                $data['file_sertif'] = $file->storeAs('sertifikat', $filename, 'public');
                $data['link_sertif'] = null;
            } else {
                // Jika tipe link, pastikan file null
                if (($data['sertifikat_type'] ?? null) === 'link') {
                    $data['file_sertif'] = null;
                }
            }

            unset($data['sertifikat_type'], $data['remove_file_sertif']);

            AssetDokumen::create($data);

            DB::commit();
            return redirect()->route('admin.dokumen-asset.index')
                ->with('success', 'Dokumen berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Create dokumen error', ['e' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal menyimpan dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Form edit.
     */
    public function edit(AssetDokumen $dokumen)
    {
        $dokumen->load('asset.unitKerja');

        return view('admin.asset_dokumen.edit', [
            'dokumen'    => $dokumen,
            'assets'     => Asset::orderBy('nama_asset')->get(),
            'unitKerjas' => UnitKerja::orderBy('nama_unit')->get(),
        ]);
    }

    /**
     * Update dokumen.
     */
    public function update(Request $request, AssetDokumen $dokumen)
    {
        $data = $this->validatedDokumenData($request, $dokumen);

        DB::beginTransaction();
        try {
            // Handle file
            if ($request->hasFile('file_sertif') && ($data['sertifikat_type'] ?? null) === 'file') {
                // hapus file lama
                if ($dokumen->file_sertif) {
                    Storage::disk('public')->delete($dokumen->file_sertif);
                }
                $file = $request->file('file_sertif');
                $filename = time() . '_' . $file->getClientOriginalName();
                $data['file_sertif'] = $file->storeAs('sertifikat', $filename, 'public');
                $data['link_sertif'] = null;
            } elseif (($data['sertifikat_type'] ?? null) === 'link') {
                // beralih ke link
                if ($dokumen->file_sertif) {
                    Storage::disk('public')->delete($dokumen->file_sertif);
                }
                $data['file_sertif'] = null;
            } elseif ($request->input('remove_file_sertif') == '1') {
                if ($dokumen->file_sertif) {
                    Storage::disk('public')->delete($dokumen->file_sertif);
                }
                $data['file_sertif'] = null;
            }

            unset($data['sertifikat_type'], $data['remove_file_sertif']);

            $dokumen->update($data);

            DB::commit();
            return redirect()->route('admin.dokumen-asset.index')
                ->with('success', 'Dokumen berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update dokumen error', ['e' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Hapus dokumen.
     */
    public function destroy(AssetDokumen $dokumen)
    {
        DB::beginTransaction();
        try {
            if ($dokumen->file_sertif) {
                Storage::disk('public')->delete($dokumen->file_sertif);
            }
            $dokumen->delete();

            DB::commit();
            return redirect()->route('admin.dokumen-asset.index')
                ->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delete dokumen error', ['e' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download file sertifikat per-dokumen.
     */
    public function download(AssetDokumen $dokumen)
    {
        if (!$dokumen->file_sertif) {
            return back()->with('error', 'File sertifikat tidak tersedia.');
        }

        $path = storage_path('app/public/' . $dokumen->file_sertif);
        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($path, basename($dokumen->file_sertif));
    }

    /**
     * View inline file sertifikat.
     */
    public function view(AssetDokumen $dokumen)
    {
        if (!$dokumen->file_sertif) {
            return back()->with('error', 'File sertifikat tidak tersedia.');
        }

        $path = storage_path('app/public/' . $dokumen->file_sertif);
        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->file($path);
    }

    /**
     * Hapus file saja.
     */
    public function deleteFile(AssetDokumen $dokumen)
    {
        if ($dokumen->file_sertif) {
            Storage::disk('public')->delete($dokumen->file_sertif);
            $dokumen->update(['file_sertif' => null]);
            return back()->with('success', 'File sertifikat dihapus.');
        }
        return back()->with('error', 'Tidak ada file untuk dihapus.');
    }

    /**
     * Export Excel (pakai filter yang sama dengan index).
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'search', 'status', 'unit_kerja', 'tgl_from', 'tgl_to'
        ]);

        $filename = 'Dokumen_Aset_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new AssetDokumenExport($filters), $filename);
    }

    /**
     * Validasi input dokumen.
     */
    private function validatedDokumenData(Request $request, ?AssetDokumen $dokumen): array
    {
        $rules = [
            'asset_id'        => ['required', 'exists:asset,id'],
            'sertifikat_type' => ['nullable', 'in:file,link'],
            'no_sertif'       => ['nullable', 'string', 'max:100'],
            'tgl_sertif'      => ['nullable', 'date'],
            'nama_sertifikat' => ['nullable', 'string', 'max:255'],
            'sts_sertif'      => ['nullable', 'string', 'max:255'],
            'ket_sertif'      => ['nullable', 'string', 'max:1000'],
            'no_dokumen'      => ['nullable', 'string', 'max:100'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'tanggal_oleh'    => ['nullable', 'date'],
            'tanggal_buku'    => ['nullable', 'date'],
            'has_konfir'      => ['nullable', 'boolean'],
            'link_sertif'     => ['nullable', 'url', 'max:500'],
            'remove_file_sertif' => ['nullable', 'in:0,1'],
        ];

        if ($request->hasFile('file_sertif')) {
            $rules['file_sertif'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        return $request->validate($rules, [
            'asset_id.required' => 'Pilih asset.',
            'asset_id.exists'   => 'Asset tidak valid.',
            'file_sertif.mimes' => 'File harus PDF/JPG/PNG.',
            'file_sertif.max'   => 'Ukuran file maksimal 5MB.',
            'link_sertif.url'   => 'Link harus URL valid.',
        ]);
    }

    /**
     * Halaman print (summary) memakai filter yang sama.
     */
    public function print(Request $request)
    {
        $filters = $request->only(['search','status','unit_kerja','tgl_from','tgl_to']);

        $items = \App\Models\AssetDokumen::query()
            ->with(['asset.unitKerja'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('no_sertif', 'like', "%{$search}%")
                       ->orWhere('nama_sertifikat', 'like', "%{$search}%")
                       ->orWhere('no_dokumen', 'like', "%{$search}%")
                       ->orWhereHas('asset', function ($qa) use ($search) {
                           $qa->where('nama_asset', 'like', "%{$search}%")
                              ->orWhere('kode_asset', 'like', "%{$search}%");
                       });
                });
            })
            ->when($filters['status'] ?? null, function ($q, $status) {
                if ($status === 'digitalized') {
                    $q->whereNotNull('file_sertif');
                } elseif ($status === 'link') {
                    $q->whereNotNull('link_sertif');
                } elseif ($status === 'none') {
                    $q->whereNull('file_sertif')->whereNull('link_sertif');
                } elseif ($status === 'confirmed') {
                    $q->where('has_konfir', true);
                } elseif ($status === 'unconfirmed') {
                    $q->where(function ($qq) {
                        $qq->whereNull('has_konfir')->orWhere('has_konfir', false);
                    });
                }
            })
            ->when($filters['unit_kerja'] ?? null, fn($q, $u) => $q->whereHas('asset', fn($qa) => $qa->where('unit_kerja_id', (int)$u)))
            ->when($filters['tgl_from'] ?? null, fn($q, $d) => $q->whereDate('tgl_sertif', '>=', $d))
            ->when($filters['tgl_to'] ?? null, fn($q, $d) => $q->whereDate('tgl_sertif', '<=', $d))
            ->orderBy('id')
            ->get();

        $stats = [
            'total'       => $items->count(),
            'file'        => $items->whereNotNull('file_sertif')->count(),
            'link'        => $items->whereNull('file_sertif')->whereNotNull('link_sertif')->count(),
            'none'        => $items->whereNull('file_sertif')->whereNull('link_sertif')->count(),
            'confirmed'   => $items->where('has_konfir', true)->count(),
            'unconfirmed' => $items->filter(fn($d) => !$d->has_konfir)->count(),
        ];

        return view('admin.asset_dokumen.print', [
            'items'     => $items,
            'filters'   => $filters,
            'stats'     => $stats,
            'printedAt' => now()->format('d M Y H:i')
        ]);
    }
}
