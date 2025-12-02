<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Search
        if ($request->filled('q')) {
            $query->where('tipe_pelanggan', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan tipe atau daya
        if ($request->filled('by') && $request->filled('val')) {
            if ($request->by === 'tipe') {
                $query->where('tipe_pelanggan', 'like', '%' . $request->val . '%');
            }
        }

        $perPage = $request->get('per_page', 10);
        $pelanggans = $query->paginate($perPage)->withQueryString();

        $view = [
            'title' => "Manajemen Data Pelanggan",
            'pelanggans' => $pelanggans,
        ];

        return view('admin.data_pelanggan.index', $view);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'daya_val' => 'required|numeric|min:0',
            'daya_unit' => 'required|string|in:VA,kVA,MVA',
            'ket' => 'nullable|string',
        ]);

        Pelanggan::create([
            'tipe_pelanggan' => $validated['tipe'],
            'jumlah' => $validated['jumlah'],
            'daya_tersambung' => $validated['daya_val'] . ' ' . $validated['daya_unit'],
            'keterangan' => $validated['ket'],
        ]);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'tipe' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'daya_val' => 'required|numeric|min:0',
            'daya_unit' => 'required|string|in:VA,kVA,MVA',
            'ket' => 'nullable|string',
        ]);

        $pelanggan->update([
            'tipe_pelanggan' => $validated['tipe'],
            'jumlah' => $validated['jumlah'],
            'daya_tersambung' => $validated['daya_val'] . ' ' . $validated['daya_unit'],
            'keterangan' => $validated['ket'],
        ]);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil dihapus!');
    }
}
