<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImportedJsonFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportedFeatureController extends Controller
{
    /**
     * Get the hierarchical structure of available data for the frontend filter.
     */
    public function getStructure()
    {
        // Fetch all unique combinations with regency check in one go
        $rows = ImportedJsonFeature::select(
            'kategori',
            'sub_kategori',
            'sub_subkategori',
            \Illuminate\Support\Facades\DB::raw('MAX(CASE WHEN regency_id IS NOT NULL THEN 1 ELSE 0 END) as has_regency')
        )
            ->groupBy('kategori', 'sub_kategori', 'sub_subkategori')
            ->get();

        $structureMap = [];

        foreach ($rows as $row) {
            $kategori = $row->kategori;
            $sub = $row->sub_kategori;
            $subsub = $row->sub_subkategori;

            if (!isset($structureMap[$kategori])) {
                $structureMap[$kategori] = [
                    'slug' => $kategori,
                    'label' => Str::title(str_replace('-', ' ', $kategori)),
                    'sub_categories' => []
                ];
            }

            $path = $sub;
            if ($subsub) {
                $path .= '/' . $subsub;
            }

            $displayLabel = $subsub ? $subsub : $sub;

            $label = Str::title(str_replace('-', ' ', $displayLabel ?? 'Umum'));

            // Rename specific label as requested
            if ($label === 'Kondisi Titik Pemukiman Non Listrik Pln') {
                $label = 'Kondisi Titik Pemukiman Belum Berlistrik PLN 2025';
            }

            $structureMap[$kategori]['sub_categories'][] = [
                'slug' => $path,
                'label' => $label,
                'has_regency' => (bool)$row->has_regency
            ];
        }

        return response()->json(array_values($structureMap));
    }

    /**
     * Get GeoJSON data filtered by parameters.
     * Uses caching to handle large datasets efficiently.
     */
    public function getData(Request $request)
    {
        // Increase memory limit for this request
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 3600);

        // Generate Cache Key
        $params = $request->all();
        ksort($params); // Ensure consistent key order
        $cacheKey = 'geojson_data_v3_' . md5(json_encode($params));

        // Attempt to get from cache (Forever)
        $jsonContent = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 60 * 24, function () use ($request) {
            $query = ImportedJsonFeature::query();

            // Select only necessary columns
            $query->select([
                'imported_json_features.id',
                'imported_json_features.kategori',
                'imported_json_features.sub_kategori',
                'imported_json_features.sub_subkategori',
                'imported_json_features.regency_id',
                'imported_json_features.properties',
                'imported_json_features.geometry'
            ]);

            // Use Join for efficiency
            $query->leftJoin('json_videos', 'imported_json_features.id', '=', 'json_videos.imported_json_features_id');

            // Add video link to selection
            $query->addSelect('json_videos.link as video_link_joined');

            if ($request->has('kategori')) {
                $query->where('imported_json_features.kategori', $request->kategori);
            }

            if ($request->has('sub_kategori')) {
                $val = $request->sub_kategori;
                if ($val === 'null' || $val === '') {
                    $query->whereNull('imported_json_features.sub_kategori');
                } else {
                    if (str_contains($val, '/')) {
                        $parts = explode('/', $val, 2);
                        $sub = $parts[0];
                        $subsub = $parts[1];

                        $query->where('imported_json_features.sub_kategori', $sub)
                            ->where('imported_json_features.sub_subkategori', $subsub);
                    } else {
                        $query->where('imported_json_features.sub_kategori', $val)
                            ->whereNull('imported_json_features.sub_subkategori');
                    }
                }
            }

            if ($request->has('regency_id') && $request->regency_id) {
                $query->where('imported_json_features.regency_id', $request->regency_id);
            }

            // Ensure we only retrieve features with valid geometry
            $query->whereNotNull('imported_json_features.geometry')
                ->where('imported_json_features.geometry', '!=', '');

            $features = [];

            foreach ($query->cursor() as $item) {
                $properties = $item->properties ?: [];

                $geometry = $item->geometry ?: [];

                if ($item->sub_subkategori === 'Rencana Bantuan Lokasi Pemukiman') {
                    if (isset($properties['Prioritas'])) {
                        $prioritas = $properties['Prioritas'];
                        unset($properties['Prioritas']);
                        $properties = ['Prioritas' => $prioritas] + $properties;
                    }
                    if (isset($properties['Rencana Sumber Listrik'])) {
                        $geometry['color'] = 'red';
                        if (str_contains($properties['Rencana Sumber Listrik'], 'SUTM')) {
                            $geometry['color'] = 'yellow';
                        }
                    }
                }

                // Add video link from joined column
                if ($item->video_link_joined) {
                    $properties['video_360_link'] = $item->video_link_joined;
                }



                // Skip if geometry is invalid
                if (!$geometry) {
                    continue;
                }

                $features[] = [
                    'type' => 'Feature',
                    'properties' => $properties,
                    'geometry' => $geometry
                ];
            }

            return json_encode([
                'type' => 'FeatureCollection',
                'features' => $features
            ]);
        });

        return response($jsonContent, 200, [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
}
