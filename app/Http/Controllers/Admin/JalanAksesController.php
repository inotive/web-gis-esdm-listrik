<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataJalanNasional;
use Illuminate\Http\Request;

class JalanAksesController extends Controller
{
    public function index(Request $request)
    {
        $query = DataJalanNasional::query();

        // Search by nama jalan
        if ($request->filled('q')) {
            $query->where('nama_jln', 'like', '%' . $request->q . '%');
        }

        // Filter by kabupaten/kota
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Filter by fungsi jalan
        if ($request->filled('fungsi')) {
            $query->where('fungsi_jal', $request->fungsi);
        }

        // Filter by sumber
        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        // Order: prioritize data with kabupaten_kota first
        $query->orderByRaw('CASE WHEN kabupaten_kota IS NOT NULL THEN 0 ELSE 1 END')
              ->orderBy('id', 'asc');

        // Pagination
        $perPage = $request->input('per_page', 10);
        $jalan = $query->paginate($perPage)->withQueryString();

        // Get unique values for filters
        $kabupatenList = DataJalanNasional::select('kabupaten_kota')
            ->distinct()
            ->whereNotNull('kabupaten_kota')
            ->orderBy('kabupaten_kota')
            ->pluck('kabupaten_kota');

        $kecamatanList = DataJalanNasional::select('kecamatan')
            ->distinct()
            ->whereNotNull('kecamatan')
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $fungsiFungsi = DataJalanNasional::select('fungsi_jal')
            ->distinct()
            ->whereNotNull('fungsi_jal')
            ->orderBy('fungsi_jal')
            ->pluck('fungsi_jal');

        $sumberList = DataJalanNasional::select('sumber')
            ->distinct()
            ->whereNotNull('sumber')
            ->orderBy('sumber')
            ->pluck('sumber');

        return view('admin.jalan_akses.index', compact('jalan', 'kabupatenList', 'kecamatanList', 'fungsiFungsi', 'sumberList'));
    }
}
