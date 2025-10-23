<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusHukumAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StatusHukumAssetController extends Controller
{
    /**
     * Tampilkan daftar status hukum asset.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $search = $request->input('search');

        $statusList = StatusHukumAsset::query()
            ->withCount('assets')
            ->when($search, function ($query, $keyword) {
                $like = '%' . $keyword . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.status_hukum_asset.index', [
            'statusList' => $statusList,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Form tambah status hukum asset.
     */
    public function create(): View
    {
        return view('admin.status_hukum_asset.create');
    }

    /**
     * Simpan status hukum asset baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        StatusHukumAsset::create($data);

        return redirect()
            ->route('admin.status-hukum-asset.index')
            ->with('success', 'Status hukum asset berhasil ditambahkan.');
    }

    /**
     * Form edit status hukum asset.
     */
    public function edit(StatusHukumAsset $statusHukumAsset): View
    {
        return view('admin.status_hukum_asset.edit', compact('statusHukumAsset'));
    }

    /**
     * Perbarui status hukum asset.
     */
    public function update(Request $request, StatusHukumAsset $statusHukumAsset): RedirectResponse
    {
        $data = $this->validatedData($request, $statusHukumAsset);

        $statusHukumAsset->update($data);

        return redirect()
            ->route('admin.status-hukum-asset.index')
            ->with('success', 'Status hukum asset berhasil diperbarui.');
    }

    /**
     * Hapus status hukum asset jika tidak memiliki relasi asset.
     */
    public function destroy(StatusHukumAsset $statusHukumAsset): RedirectResponse
    {
        if ($statusHukumAsset->assets()->exists()) {
            return redirect()
                ->route('admin.status-hukum-asset.index')
                ->with('error', 'Status hukum asset tidak dapat dihapus karena masih digunakan oleh asset.');
        }

        $statusHukumAsset->delete();

        return redirect()
            ->route('admin.status-hukum-asset.index')
            ->with('success', 'Status hukum asset berhasil dihapus.');
    }

    /**
     * Validasi data status hukum asset.
     */
    private function validatedData(Request $request, ?StatusHukumAsset $statusHukumAsset = null): array
    {
        $nameRule = Rule::unique('status_hukum_asset', 'name');

        if ($statusHukumAsset) {
            $nameRule = $nameRule->ignore($statusHukumAsset->id);
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:150', $nameRule],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
