<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;

class PublicRegionController extends Controller
{
    public function regencies(Request $request)
    {
        $q = $request->get('q');
        $items = RegRegency::when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->limit(100)
            ->get(['id', 'name']);
            
        return response()->json($items);
    }

    public function districts(Request $request)
    {
        $regencyId = $request->get('regency_id');
        $q = $request->get('q');

        $items = RegDistrict::when($regencyId, fn($query) => $query->where('regency_id', $regencyId))
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->limit(100)
            ->get(['id', 'name']);

        return response()->json($items);
    }

    public function villages(Request $request)
    {
        $districtId = $request->get('district_id');
        $q = $request->get('q');

        $items = RegVillage::when($districtId, fn($query) => $query->where('district_id', $districtId))
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->limit(100)
            ->get(['id', 'name']);

        return response()->json($items);
    }
}
