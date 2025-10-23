<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Asset;

$asset = Asset::whereNotNull('geojson')->first();

if ($asset) {
    $geo = json_decode($asset->geojson, true);
    
    echo "Asset ID: " . $asset->id . PHP_EOL;
    echo "Nama: " . $asset->nama_asset . PHP_EOL;
    echo "Geometry Type: " . ($geo['type'] ?? 'null') . PHP_EOL;
    
    if (isset($geo['coordinates'][0])) {
        echo "First coordinate: " . json_encode($geo['coordinates'][0][0]) . PHP_EOL;
        echo "Total points: " . count($geo['coordinates'][0]) . PHP_EOL;
        
        // Check if coordinates are in projected system
        $firstPoint = $geo['coordinates'][0][0];
        if (abs($firstPoint[0]) > 180 || abs($firstPoint[1]) > 90) {
            echo "⚠️ WARNING: Coordinates appear to be in projected system (not lat/long)" . PHP_EOL;
            echo "   X: " . $firstPoint[0] . " Y: " . $firstPoint[1] . PHP_EOL;
        } else {
            echo "✓ Coordinates appear to be in geographic system (lat/long)" . PHP_EOL;
        }
    }
}
