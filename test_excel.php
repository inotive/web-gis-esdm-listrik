<?php

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/Rencana Pengembangan Bantuan Ketenagalistrikan.xlsx';

echo "Trying to load: $filePath\n";

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$reader->setReadDataOnly(true);
try {
    $spreadsheet = $reader->load($filePath);
    echo "Load success!\n";
    $count = $spreadsheet->getActiveSheet()->getHighestRow();
    echo "Rows: $count\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
