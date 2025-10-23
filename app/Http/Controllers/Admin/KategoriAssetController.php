<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriAssetController extends Controller
{
    /**
     * Tampilkan daftar kategori asset.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $search = $request->input('search');

        $kategoriAssets = KategoriAsset::query()
            ->withCount('assets')
            ->when($search, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $like = '%' . $keyword . '%';
                    $q->where('name', 'like', $like)
                        ->orWhere('kode', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.kategori_asset.index', [
            'kategoriAssets' => $kategoriAssets,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Form tambah kategori asset.
     */
    public function create(): View
    {
        return view('admin.kategori_asset.create');
    }

    /**
     * Simpan kategori asset baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        KategoriAsset::create($data);

        return redirect()
            ->route('admin.kategori-asset.index')
            ->with('success', 'Kategori asset berhasil ditambahkan.');
    }

    /**
     * Form edit kategori asset.
     */
    public function edit(KategoriAsset $kategoriAsset): View
    {
        return view('admin.kategori_asset.edit', compact('kategoriAsset'));
    }

    /**
     * Perbarui kategori asset.
     */
    public function update(Request $request, KategoriAsset $kategoriAsset): RedirectResponse
    {
        $data = $this->validatedData($request, $kategoriAsset);

        $kategoriAsset->update($data);

        return redirect()
            ->route('admin.kategori-asset.index')
            ->with('success', 'Kategori asset berhasil diperbarui.');
    }

    /**
     * Hapus kategori asset jika tidak memiliki relasi asset.
     */
    public function destroy(KategoriAsset $kategoriAsset): RedirectResponse
    {
        if ($kategoriAsset->assets()->exists()) {
            return redirect()
                ->route('admin.kategori-asset.index')
                ->with('error', 'Kategori asset tidak dapat dihapus karena masih digunakan oleh asset.');
        }

        $kategoriAsset->delete();

        return redirect()
            ->route('admin.kategori-asset.index')
            ->with('success', 'Kategori asset berhasil dihapus.');
    }

    /**
     * Validasi data kategori asset.
     */
    private function validatedData(Request $request, ?KategoriAsset $kategoriAsset = null): array
    {
        $kodeRule = Rule::unique('kategori_asset', 'kode');

        if ($kategoriAsset) {
            $kodeRule = $kodeRule->ignore($kategoriAsset->id);
        }

        return $request->validate([
            'kode' => ['required', 'string', 'max:50', $kodeRule],
            'name' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
