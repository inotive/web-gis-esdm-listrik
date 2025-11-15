<?php
// app/Http/Controllers/Admin/PembangkitLokalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembangkitLokal;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class PembangkitLokalController extends Controller
{
    // Helper method untuk get wilayahs dropdown
    private function getWilayahsForDropdown()
    {
        return Wilayah::with(['village', 'district', 'regency.province'])
            ->orderBy('id')
            ->get()
            ->map(function($w) {
                $parts = array_filter([
                    optional($w->village)->name,
                    optional($w->district)->name,
                    optional($w->regency)->name,
                    optional(optional($w->regency)->province)->name,
                ]);
                
                return [
                    'id' => $w->id,
                    'label' => implode(', ', $parts)
                ];
            });
    }

    public function index(Request $request)
    {
        $q       = trim((string) $request->get('q'));
        $perPage = (int) $request->get('per_page', 10);

        $items = PembangkitLokal::query()
            ->with(['wilayah.village', 'wilayah.district', 'wilayah.regency.province'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('kapasitas_gardu', 'like', "%{$q}%")
                      ->orWhereHas('wilayah.village', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('wilayah.district', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('wilayah.regency', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('wilayah.regency.province', fn($qq) => $qq->where('name', 'like', "%{$q}%"));
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // Tambahkan wilayahs untuk dropdown di modal
        $wilayahs = $this->getWilayahsForDropdown();

        return view('admin.pembangkit_lokal.index', compact('items', 'q', 'perPage', 'wilayahs'));
    }

    public function create()
    {
        $wilayahs = $this->getWilayahsForDropdown();
        return view('admin.pembangkit_lokal.create', compact('wilayahs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kapasitas_gardu' => ['required','string','max:100'],
            'wilayah_id'      => ['required','exists:wilayah,id'],
        ]);

        // Ambil lokasi text dari wilayah
        $wilayah = Wilayah::with(['village', 'district', 'regency.province'])->find($data['wilayah_id']);
        $lokasi = $this->generateLokasiFromWilayah($wilayah);

        PembangkitLokal::create([
            'wilayah_id'      => $data['wilayah_id'],
            'kapasitas_gardu' => $data['kapasitas_gardu'],
            'lokasi'          => $lokasi, // Jika ada field lokasi di tabel
        ]);

        return redirect()->route('admin.pembangkit.index')->with('success', 'Pembangkit lokal berhasil ditambahkan.');
    }

    public function edit(PembangkitLokal $pembangkit)
    {
        $pembangkit->load(['wilayah.village', 'wilayah.district', 'wilayah.regency.province']);
        $wilayahs = $this->getWilayahsForDropdown();
        
        return view('admin.pembangkit_lokal.edit', compact('pembangkit', 'wilayahs'));
    }

    public function update(Request $request, PembangkitLokal $pembangkit)
    {
        $data = $request->validate([
            'kapasitas_gardu' => ['required','string','max:100'],
            'wilayah_id'      => ['required','exists:wilayah,id'],
        ]);

        // Ambil lokasi text dari wilayah
        $wilayah = Wilayah::with(['village', 'district', 'regency.province'])->find($data['wilayah_id']);
        $lokasi = $this->generateLokasiFromWilayah($wilayah);

        $pembangkit->update([
            'wilayah_id'      => $data['wilayah_id'],
            'kapasitas_gardu' => $data['kapasitas_gardu'],
            'lokasi'          => $lokasi, // Jika ada field lokasi di tabel
        ]);

        return redirect()->route('admin.pembangkit.index')->with('success', 'Pembangkit lokal berhasil diperbarui.');
    }

    public function destroy(PembangkitLokal $pembangkit)
    {
        $pembangkit->delete();
        return redirect()->route('admin.pembangkit.index')->with('success', 'Pembangkit lokal berhasil dihapus.');
    }

    // Helper untuk generate text lokasi dari wilayah
    private function generateLokasiFromWilayah($wilayah)
    {
        if (!$wilayah) return '';
        
        $parts = array_filter([
            optional($wilayah->village)->name,
            optional($wilayah->district)->name,
            optional($wilayah->regency)->name,
            optional(optional($wilayah->regency)->province)->name,
        ]);
        
        return implode(', ', $parts);
    }
}