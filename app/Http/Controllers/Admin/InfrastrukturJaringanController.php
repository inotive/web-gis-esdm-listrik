<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfrastrukturJaringan;
use Illuminate\Http\Request;

class InfrastrukturJaringanController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q'));
        $jaringan = $request->get('jaringan'); // 'distribusi' | 'transmisi' | null
        $perPage  = (int) ($request->get('per_page') ?: 10);

        $items = InfrastrukturJaringan::query()
            ->when($q, function ($s) use ($q) {
                $s->where('jenis', 'like', "%{$q}%")
                  ->orWhere('panjang_jaringan', 'like', "%{$q}%");
            })
            ->when($jaringan, fn($s) => $s->where('jaringan', $jaringan))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // opsi dropdown jaringan & jenis (jenis bebas, ini hanya contoh UI)
        $jaringanOptions = ['distribusi' => 'Distribusi', 'transmisi' => 'Transmisi'];
        $jenisOptions    = ['JTM', 'JTR', 'Gardu', 'Trafo'];

        return view('admin.infrastruktur.index', compact('items', 'q', 'jaringan', 'perPage', 'jaringanOptions', 'jenisOptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jaringan'          => ['required', 'in:distribusi,transmisi'],
            'jenis'             => ['required', 'string', 'max:100'],
            'panjang_jaringan'  => ['required', 'numeric', 'min:0'],
        ]);

        InfrastrukturJaringan::create($data);

        return back()->with('success', 'Infrastruktur berhasil ditambahkan.');
    }

    public function update(Request $request, InfrastrukturJaringan $infrastruktur)
    {
        $data = $request->validate([
            'jaringan'          => ['required', 'in:distribusi,transmisi'],
            'jenis'             => ['required', 'string', 'max:100'],
            'panjang_jaringan'  => ['required', 'numeric', 'min:0'],
        ]);

        $infrastruktur->update($data);

        return back()->with('success', 'Infrastruktur berhasil diperbarui.');
    }

    public function destroy(InfrastrukturJaringan $infrastruktur)
    {
        $infrastruktur->delete();

        return back()->with('success', 'Infrastruktur berhasil dihapus.');
    }
}
