<?php
// app/Http/Controllers/Admin/DataGarduController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gardu;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class DataGarduController extends Controller
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
        $jenis   = (string) $request->get('jenis');
        $perPage = (int) $request->get('per_page', 10);

        $items = Gardu::query()
            ->with(['wilayah.village', 'wilayah.district', 'wilayah.regency.province'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('lokasi', 'like', "%{$q}%")
                        ->orWhereHas('wilayah.village', function ($qq) use ($q) {
                            $qq->where('name', 'like', "%{$q}%");
                        })
                        ->orWhereHas('wilayah.district', function ($qq) use ($q) {
                            $qq->where('name', 'like', "%{$q}%");
                        })
                        ->orWhereHas('wilayah.regency', function ($qq) use ($q) {
                            $qq->where('name', 'like', "%{$q}%");
                        });
                });
            })
            ->when($jenis !== '', function ($query) use ($jenis) {
                $query->where('jenis_gardu_distribusi', $jenis);
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $jenisOptions = [
            'Gardu Tiang',
            'Gardu Portal',
            'Gardu Beton',
            'Gardu Compact',
            'Lainnya',
        ];

        // Tambahkan wilayahs untuk dropdown di modal
        $wilayahs = $this->getWilayahsForDropdown();

        return view('admin.data_gardu.index', compact('items', 'jenisOptions', 'q', 'jenis', 'perPage', 'wilayahs'));
    }

    public function create()
    {
        $jenisOptions = ['Gardu Tiang','Gardu Portal','Gardu Beton','Gardu Compact','Lainnya'];
        $wilayahs = $this->getWilayahsForDropdown();
        
        return view('admin.data_gardu.create', compact('jenisOptions', 'wilayahs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'                   => ['required','string','max:150'],
            'jenis_gardu_distribusi' => ['required','string','max:100'],
            'wilayah_id'             => ['required','exists:wilayah,id'],
        ]);

        // Ambil lokasi text dari wilayah
        $wilayah = Wilayah::with(['village', 'district', 'regency.province'])->find($data['wilayah_id']);
        $lokasi = $this->generateLokasiFromWilayah($wilayah);

        Gardu::create([
            'nama'                   => $data['nama'],
            'jenis_gardu_distribusi' => $data['jenis_gardu_distribusi'],
            'wilayah_id'             => $data['wilayah_id'],
            'lokasi'                 => $lokasi,
        ]);

        return redirect()->route('admin.gardu.index')->with('success', 'Data gardu berhasil ditambahkan.');
    }

    public function edit(Gardu $gardu)
    {
        $gardu->load('wilayah');
        $jenisOptions = ['Gardu Tiang','Gardu Portal','Gardu Beton','Gardu Compact','Lainnya'];
        $wilayahs = $this->getWilayahsForDropdown();
        
        return view('admin.data_gardu.edit', compact('gardu', 'jenisOptions', 'wilayahs'));
    }

    public function update(Request $request, Gardu $gardu)
    {
        $data = $request->validate([
            'nama'                   => ['required','string','max:150'],
            'jenis_gardu_distribusi' => ['required','string','max:100'],
            'wilayah_id'             => ['required','exists:wilayah,id'],
        ]);

        // Ambil lokasi text dari wilayah
        $wilayah = Wilayah::with(['village', 'district', 'regency.province'])->find($data['wilayah_id']);
        $lokasi = $this->generateLokasiFromWilayah($wilayah);

        $gardu->update([
            'nama'                   => $data['nama'],
            'jenis_gardu_distribusi' => $data['jenis_gardu_distribusi'],
            'wilayah_id'             => $data['wilayah_id'],
            'lokasi'                 => $lokasi,
        ]);

        return redirect()->route('admin.gardu.index')->with('success', 'Data gardu berhasil diperbarui.');
    }

    public function destroy(Gardu $gardu)
    {
        $gardu->delete();
        return redirect()->route('admin.gardu.index')->with('success', 'Data gardu berhasil dihapus.');
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