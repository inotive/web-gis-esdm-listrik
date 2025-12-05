<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VariabelKriteria;
use Illuminate\Http\Request;

class SkoringBobotController extends Controller
{
    public function index(Request $request)
    {
        $query = VariabelKriteria::query();

        // Search
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->q . '%');
        }

        // Filter by grup (jika ada di view, tapi tidak di database - kita skip atau bisa dihapus dari view)
        // Karena tidak ada field grup di database, kita skip filter ini

        $perPage = $request->get('per_page', 10);
        $items = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Get all data for progress bar (not paginated)
        $allItems = VariabelKriteria::orderBy('created_at', 'desc')->get();
        $totalBobot = $allItems->sum('bobot');

        return view('admin.skoring_bobot.index', compact('items', 'allItems', 'totalBobot'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:1|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $currentTotal = VariabelKriteria::sum('bobot');
        if (($currentTotal + $validated['bobot']) > 100) {
            return back()
                ->withInput()
                ->withErrors(['bobot' => 'Total bobot tidak boleh lebih dari 100%.']);
        }

        VariabelKriteria::create([
            'nama' => $validated['nama'],
            'bobot' => $validated['bobot'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('admin.skoring.index')->with('success', 'Variabel skoring berhasil ditambahkan');
    }

    public function update(Request $request, VariabelKriteria $variabel)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:1|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $currentTotal = VariabelKriteria::where('id', '!=', $variabel->id)->sum('bobot');
        if (($currentTotal + $validated['bobot']) > 100) {
            return back()
                ->withInput()
                ->withErrors(['bobot' => 'Total bobot tidak boleh lebih dari 100%.']);
        }

        $variabel->update([
            'nama' => $validated['nama'],
            'bobot' => $validated['bobot'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('admin.skoring.index')->with('success', 'Variabel skoring berhasil diperbarui');
    }

    public function destroy(VariabelKriteria $variabel)
    {
        $variabel->delete();

        return redirect()->route('admin.skoring.index')->with('success', 'Variabel skoring berhasil dihapus');
    }
}
