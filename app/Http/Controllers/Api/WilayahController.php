<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Get all Kabupaten/Kota from GeoJSON
     */
    public function getRegencies(Request $request)
    {
        $path = public_path('assets/Administrasi/LN_BATAS_KABUPATENKOTA.json');

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $json = json_decode(file_get_contents($path), true);
        $regencies = [];

        foreach ($json['features'] as $feature) {
            $name = $feature['properties']['WADMKK'] ?? null;
            if ($name && !isset($regencies[$name])) {
                $regencies[$name] = [
                    'id' => $name,
                    'name' => $name
                ];
            }
        }

        return response()->json(array_values($regencies));
    }

    /**
     * Get Kecamatan by Kabupaten/Kota
     */
    public function getDistricts(Request $request)
    {
        $regencyName = $request->query('regency_id');
        $path = public_path('assets/Administrasi/LN_BATAS_KECAMATAN.json');

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $json = json_decode(file_get_contents($path), true);
        $districts = [];

        foreach ($json['features'] as $feature) {
            $kabupaten = $feature['properties']['WADMKK'] ?? null;
            $kecamatan = $feature['properties']['WADMKC'] ?? null;

            if ($kecamatan) {
                // Filter by regency if specified
                if ($regencyName && $kabupaten !== $regencyName) {
                    continue;
                }

                if (!isset($districts[$kecamatan])) {
                    $districts[$kecamatan] = [
                        'id' => $kecamatan,
                        'regency_id' => $kabupaten,
                        'name' => $kecamatan
                    ];
                }
            }
        }

        return response()->json(array_values($districts));
    }

    /**
     * Get Kelurahan/Desa by Kecamatan
     */
    public function getVillages(Request $request)
    {
        $districtName = $request->query('district_id');
        $path = public_path('assets/Administrasi/LN_BATAS_DESA.json');

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $json = json_decode(file_get_contents($path), true);
        $villages = [];

        foreach ($json['features'] as $feature) {
            $kecamatan = $feature['properties']['WADMKC'] ?? null;
            $desa = $feature['properties']['WADMKD'] ?? null;

            if ($desa) {
                // Filter by district if specified
                if ($districtName && $kecamatan !== $districtName) {
                    continue;
                }

                $key = $desa . '_' . $kecamatan; // Make unique key
                if (!isset($villages[$key])) {
                    $villages[$key] = [
                        'id' => $desa,
                        'district_id' => $kecamatan,
                        'name' => $desa
                    ];
                }
            }
        }

        return response()->json(array_values($villages));
    }
}
