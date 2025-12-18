<?php

/**
 * Script untuk mengkonversi koordinat GeoJSON dari Web Mercator ke WGS84
 * Menggunakan nilai longitudex dan latitudey dari properties
 */

$files = [
    'public/assets/infrastruktur/PT_Gardu_Berau.json',
    'public/assets/infrastruktur/PT_Trafo_Berau.json',
    'public/assets/Jaringan-Listrik/LN_SUTM_PPU.json',
    'public/assets/Jaringan-Listrik/LN_SUTM_Berau.json',
    'public/assets/Jaringan-Listrik/LN_SUTR_Kutim.json',
    'public/assets/infrastruktur/PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang.json',
    'public/assets/Jaringan-Listrik/LN_Rencana_Jaringan_Listrik_Bontang.json'
];

/**
 * Transformasi koordinat dari Web Mercator (EPSG:3857) ke WGS84 (EPSG:4326)
 */
function webMercatorToWGS84($x, $y) {
    $lon = $x / 20037508.34 * 180;
    $lat = atan(sinh($y / 20037508.34 * pi())) * 180 / pi();

    return [$lon, $lat];
}

/**
 * Cek apakah koordinat dalam format Web Mercator
 */
function isWebMercator($coord) {
    if (!is_array($coord) || count($coord) < 2) {
        return false;
    }
    $x = $coord[0];
    $y = $coord[1];
    // Web Mercator memiliki range yang jauh lebih besar dari -180 to 180
    return (abs($x) > 180 || abs($y) > 90);
}

/**
 * Transformasi array koordinat
 */
function transformCoordinateArray($coords, $depth = 1) {
    if ($depth == 0) {
        // Base case: ini adalah koordinat tunggal [x, y]
        if (isWebMercator($coords)) {
            return webMercatorToWGS84($coords[0], $coords[1]);
        }
        return $coords;
    }

    // Recursive case: array of coordinates
    $result = [];
    foreach ($coords as $coord) {
        $result[] = transformCoordinateArray($coord, $depth - 1);
    }
    return $result;
}

function convertCoordinates($geometry, $properties) {
    // Konversi berdasarkan tipe geometri
    switch ($geometry['type']) {
        case 'Point':
            // Prioritas: gunakan longitudex/latitudey jika ada
            if (isset($properties['longitudex']) && isset($properties['latitudey'])) {
                $lon = (float) $properties['longitudex'];
                $lat = (float) $properties['latitudey'];
                $geometry['coordinates'] = [$lon, $lat];
            } else if (isWebMercator($geometry['coordinates'])) {
                // Fallback: transformasi dari Web Mercator
                $geometry['coordinates'] = webMercatorToWGS84(
                    $geometry['coordinates'][0],
                    $geometry['coordinates'][1]
                );
            }
            break;

        case 'LineString':
        case 'MultiPoint':
            // Array of coordinates
            $geometry['coordinates'] = transformCoordinateArray($geometry['coordinates'], 1);
            break;

        case 'Polygon':
        case 'MultiLineString':
            // Array of array of coordinates
            $geometry['coordinates'] = transformCoordinateArray($geometry['coordinates'], 2);
            break;

        case 'MultiPolygon':
            // Array of array of array of coordinates
            $geometry['coordinates'] = transformCoordinateArray($geometry['coordinates'], 3);
            break;
    }

    return $geometry;
}

function convertFile($filePath) {
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "Processing: $filePath\n";
    echo str_repeat("=", 70) . "\n";

    if (!file_exists($filePath)) {
        echo "❌ File tidak ditemukan!\n";
        return false;
    }

    // Backup file asli
    $backupPath = $filePath . '.backup.' . date('YmdHis');
    copy($filePath, $backupPath);
    echo "✅ Backup dibuat: $backupPath\n";

    // Load JSON
    $jsonContent = file_get_contents($filePath);
    $data = json_decode($jsonContent, true);

    if ($data === null) {
        echo "❌ Error parsing JSON!\n";
        return false;
    }

    if (!isset($data['features']) || !is_array($data['features'])) {
        echo "❌ Format GeoJSON tidak valid!\n";
        return false;
    }

    $totalFeatures = count($data['features']);
    $converted = 0;
    $skipped = 0;

    echo "📊 Total features: $totalFeatures\n";

    // Proses setiap feature
    foreach ($data['features'] as &$feature) {
        if (!isset($feature['geometry']) || !isset($feature['properties'])) {
            $skipped++;
            continue;
        }

        $oldGeometry = $feature['geometry'];
        $newGeometry = convertCoordinates($feature['geometry'], $feature['properties']);

        // Cek apakah ada perubahan
        if (json_encode($oldGeometry) !== json_encode($newGeometry)) {
            $feature['geometry'] = $newGeometry;
            $converted++;
        } else {
            $skipped++;
        }
    }
    unset($feature); // Break reference

    echo "✅ Converted: $converted features\n";
    echo "⚠️  Skipped: $skipped features\n";

    // Simpan hasil
    $newJsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($filePath, $newJsonContent);

    echo "✅ File berhasil diupdate!\n";

    // Tampilkan contoh koordinat sebelum dan sesudah
    if ($converted > 0) {
        echo "\n📍 Sample koordinat:\n";
        $sample = $data['features'][0];
        if ($sample['geometry']['type'] === 'Point') {
            echo "   New: [" . $sample['geometry']['coordinates'][0] . ", " . $sample['geometry']['coordinates'][1] . "]\n";
        }
    }

    return true;
}

// Main execution
echo "\n";
echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║        KONVERSI KOORDINAT GEOJSON: Web Mercator → WGS84           ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n";

$success = 0;
$failed = 0;

foreach ($files as $file) {
    if (convertFile($file)) {
        $success++;
    } else {
        $failed++;
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "SUMMARY\n";
echo str_repeat("=", 70) . "\n";
echo "✅ Sukses: $success files\n";
echo "❌ Gagal: $failed files\n";
echo "\n💡 Backup files telah dibuat dengan extensi .backup.[timestamp]\n";
echo "   Jika ada masalah, restore dengan: cp file.json.backup.* file.json\n";
echo "\n";
