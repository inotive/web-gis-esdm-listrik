<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PT_Trafo_Gardu_Distribusi_PPU;

class PtTrafoGarduDistribusiPpuController extends Controller
{
    /**
     * Return Trafo Gardu Distribusi PPU sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Trafo_Gardu_Distribusi_PPU::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Trafo_Gardu_Distribusi_PPU $row) {
            $geom = $row->geometry;

            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            if (!$geom || !isset($geom['type'])) {
                return null;
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'         => $row->id,
                    'OID_'       => $row->oid_,
                    'Name'       => $row->name,
                    'FolderPath' => $row->folderpath,
                    'SymbolID'   => $row->symbolid,
                    'AltMode'    => $row->altmode,
                    'Base'       => $row->base,
                    'TimeSpan'   => $row->timespan,
                    'TimeStamp'  => $row->timestamp,
                    'BeginTime'  => $row->begintime,
                    'EndTime'    => $row->endtime,
                    'Snippet'    => $row->snippet,
                    'PopupInfo'  => $row->popupinfo,
                    'HasLabel'   => $row->haslabel,
                    'LabelID'    => $row->labelid,
                    'Nama'       => $row->nama,
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