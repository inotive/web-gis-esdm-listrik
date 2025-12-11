<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\DataJalanNasional;

class DatajalannasionalController extends Controller
{
    /**
     * Return data jalan nasional sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = DataJalanNasional::whereNotNull('geometry')->get();

        $features = $rows->map(function (DataJalanNasional $row) {
            $geom = $row->geometry;

            // Jika geometry tersimpan sebagai string, decode dulu
            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            if (!$geom || !isset($geom['type'])) {
                return null; // skip jika invalid
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'         => $row->id,
                    'OBJECTID'   => $row->objectid,
                    'Fungsi_Jal' => $row->fungsi_jal,
                    'Nama_Jln'   => $row->nama_jln,
                    'Sumber'     => $row->sumber,
                    'Shape_Leng' => $row->shape_leng,
                ],
                'geometry'   => $geom,
            ];
        })
        ->filter()
        ->values()
        ->toArray();

        return response()->json(
            [
                'type'     => 'FeatureCollection',
                'features' => $features,
            ],
            200,
            [
                'Content-Type'                => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]
        );
    }
}
