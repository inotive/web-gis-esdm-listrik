<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\JaringanListrikBalikpapan;

class JaringanlistrikbalikpapanController extends Controller
{
    /**
     * Return data jaringan listrik Balikpapan sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = JaringanListrikBalikpapan::whereNotNull('geometry')->get();

        $features = $rows->map(function (JaringanListrikBalikpapan $row) {
            $geom = $row->geometry;

            // Jika geometry tersimpan sebagai string JSON, decode dulu
            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            if (!$geom || !isset($geom['type'])) {
                return null; // skip jika geometry invalid
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'         => $row->id,
                    'OBJECTID'   => $row->objectid,
                    'NAMOBJ'     => $row->namobj,
                    'ORDE01'     => $row->orde01,
                    'ORDE02'     => $row->orde02,
                    'ORDE03'     => $row->orde03,
                    'ORDE04'     => $row->orde04,
                    'JNSRSR'     => $row->jnsrsr,
                    'STSJRN'     => $row->stsjrn,
                    'WADMPR'     => $row->wadmpr,
                    'WADMKK'     => $row->wadmkk,
                    'REMARK'     => $row->remark,
                    'SBDATA'     => $row->sbdata,
                    'Length'     => $row->length,
                    'SHAPE_Leng' => $row->shape_leng,
                ],
                'geometry'   => $geom,
            ];
        })
        ->filter()   // buang null
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
