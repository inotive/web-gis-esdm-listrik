<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitKerjaController extends Controller
{
    /**
     * Tampilkan daftar unit kerja.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $search = $request->input('search');

        $unitKerjaList = UnitKerja::query()
            ->withCount('assets')
            ->when($search, function ($query, $keyword) {
                $like = '%' . $keyword . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('nama_unit', 'like', $like)
                        ->orWhere('kode_unit', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                });
            })
            ->orderBy('nama_unit')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.unit_kerja.index', [
            'unitKerjaList' => $unitKerjaList,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Form tambah unit kerja.
     */
    public function create(): View
    {
        return view('admin.unit_kerja.create');
    }

    /**
     * Simpan unit kerja baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        UnitKerja::create($data);

        return redirect()
            ->route('admin.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Form edit unit kerja.
     */
    public function edit(UnitKerja $unitKerja): View
    {
        return view('admin.unit_kerja.edit', compact('unitKerja'));
    }

    /**
     * Perbarui unit kerja.
     */
    public function update(Request $request, UnitKerja $unitKerja): RedirectResponse
    {
        $data = $this->validatedData($request, $unitKerja);

        $unitKerja->update($data);

        return redirect()
            ->route('admin.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    /**
     * Hapus unit kerja jika tidak memiliki relasi asset.
     */
    public function destroy(UnitKerja $unitKerja): RedirectResponse
    {
        if ($unitKerja->assets()->exists()) {
            return redirect()
                ->route('admin.unit-kerja.index')
                ->with('error', 'Unit kerja tidak dapat dihapus karena masih digunakan oleh asset.');
        }

        $unitKerja->delete();

        return redirect()
            ->route('admin.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil dihapus.');
    }

    /**
     * Validasi data unit kerja.
     */
    private function validatedData(Request $request, ?UnitKerja $unitKerja = null): array
    {
        $kodeRule = Rule::unique('unit_kerja', 'kode_unit');

        if ($unitKerja) {
            $kodeRule = $kodeRule->ignore($unitKerja->id);
        }

        return $request->validate([
            'kode_unit' => ['required', 'string', 'max:50', $kodeRule],
            'nama_unit' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
