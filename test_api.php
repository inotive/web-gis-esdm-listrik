<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Test API Controller
$controller = new App\Http\Controllers\Api\AssetController();
$response = $controller->index();
$data = json_decode($response->content(), true);

echo "Total Features: " . count($data['features']) . PHP_EOL;

if (count($data['features']) > 0) {
    $firstFeature = $data['features'][0];
    echo "\nFirst Feature:" . PHP_EOL;
    echo "- Nama: " . $firstFeature['properties']['nama_asset'] . PHP_EOL;
    echo "- Kode: " . $firstFeature['properties']['kode_asset'] . PHP_EOL;
    echo "- Luas: " . $firstFeature['properties']['luas_m2'] . " m²" . PHP_EOL;
    echo "- Geometry Type: " . $firstFeature['geometry']['type'] . PHP_EOL;
    
    // Check coordinate sample
    $coords = $firstFeature['geometry']['coordinates'][0][0];
    echo "- First Coordinate: [" . $coords[0] . ", " . $coords[1] . "]" . PHP_EOL;
    
    // Validate if coordinates are in geographic range
    if (abs($coords[0]) <= 180 && abs($coords[1]) <= 90) {
        echo "✓ Koordinat dalam format Geographic (lat/long)" . PHP_EOL;
    } else {
        echo "✗ Koordinat masih dalam format Projected" . PHP_EOL;
    }
}
