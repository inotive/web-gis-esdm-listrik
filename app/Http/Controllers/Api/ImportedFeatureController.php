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
        $categories = ImportedJsonFeature::select('kategori')->distinct()->pluck('kategori');

        $structure = [];

        foreach ($categories as $kategori) {
            // Fetch unique combinations of sub_kategori and sub_subkategori
            $rows = ImportedJsonFeature::where('kategori', $kategori)
                ->select('sub_kategori', 'sub_subkategori')
                ->distinct()
                ->get();

            $subs = [];
            foreach ($rows as $row) {
                // Construct the full "slug" which acts as the path
                // Logic: sub_kategori + '/' + sub_subkategori (if exists)
                $path = $row->sub_kategori;
                if ($row->sub_subkategori) {
                    $path .= '/' . $row->sub_subkategori;
                }

                // Check has_regency (optimization: simplified check)
                // We assume if regency_id is set on ANY matching row, it's true.
                // But rows are distinct (sub, subsub).
                // Let's check existence just to be safe or skip it if performant enough.
                // Re-querying per row might be slow.
                // Alternatively, just return generic true/false or fetch regency_id presence in initial query?
                // Let's stick to simple exists() check for now.

                $hasRegency = ImportedJsonFeature::where('kategori', $kategori)
                    ->where('sub_kategori', $row->sub_kategori)
                    ->where('sub_subkategori', $row->sub_subkategori)
                    ->whereNotNull('regency_id')
                    ->exists();

                $displayLabel = $row->sub_subkategori ? $row->sub_subkategori : $row->sub_kategori;

                $subs[] = [
                    'slug' => $path, // This path is sent to FE and returned in getData
                    'label' => Str::title(str_replace('-', ' ', $displayLabel ?? 'Umum')), // Label is leaf name
                    'has_regency' => $hasRegency
                ];
            }

            $structure[] = [
                'slug' => $kategori,
                'label' => Str::title(str_replace('-', ' ', $kategori)),
                'sub_categories' => $subs
            ];
        }

        return response()->json($structure);
    }

    /**
     * Get GeoJSON data filtered by parameters.
     * Uses streaming to handle large datasets.
     */
    public function getData(Request $request)
    {
        // Increase memory limit for this request
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        return response()->stream(function () use ($request) {
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

            // $query->with('videos'); // Eager load doesn't work well with cursor

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

            echo '{"type": "FeatureCollection", "features": [';

            $first = true;

            // Use cursor for memory efficient iteration
            foreach ($query->cursor() as $item) {
                if (!$first) {
                    echo ',';
                }
                $first = false;

                $properties = $item->properties ? json_decode($item->properties, true) : [];
                $properties['db_id'] = $item->id;
                $properties['kategori'] = $item->kategori;
                $properties['sub_kategori'] = $item->sub_kategori;
                $properties['sub_subkategori'] = $item->sub_subkategori;
                $properties['regency_id'] = $item->regency_id;

                // Add video link from joined column
                if ($item->video_link_joined) {
                    $properties['video_360_link'] = $item->video_link_joined;
                }

                // Build feature valid JSON string manually to avoid array overhead
                $geometry = $item->geometry;

                $feature = [
                    'type' => 'Feature',
                    'properties' => $properties,
                ];

                // Encode properties part filter
                $featureJson = json_encode($feature);
                // remove last brace
                echo substr($featureJson, 0, -1);

                echo ',"geometry":';
                echo $item->geometry ?: 'null';
                echo '}';
            }

            echo ']}';
        }, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
