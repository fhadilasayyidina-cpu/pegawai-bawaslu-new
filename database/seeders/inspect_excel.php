<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Rap2hpoutre\FastExcel\FastExcel;

$excelPath = __DIR__ . '/../../storage/app/private/livewire-tmp/BNbIeaKLJpYw9t1i9XGMhP0F1a916e-metaU1VMQVdFU0kgU0VMQVRBTiAtIENsb3NpbmcgSnVuaSAyMDI2IC0gSW5wdXQgQXBsaWthc2kueGxzeA==-.xlsx';

if (!file_exists($excelPath)) {
    $files = glob(__DIR__ . '/../../storage/app/private/livewire-tmp/*-meta*.xlsx');
    if (!empty($files)) {
        $excelPath = $files[0];
    }
}

$rows = (new FastExcel)->import($excelPath);

$nips = [];
foreach ($rows as $row) {
    if (!empty($row['NIP_BARU'])) {
        $nips[] = $row['NIP_BARU'];
    }
}

$uniqueNips = array_unique($nips);
echo "Total rows in Excel: " . count($rows) . "\n";
echo "Total unique NIPs: " . count($uniqueNips) . "\n";

// Print first 5 unique names and their gelar_blk
$printed = 0;
foreach ($rows as $row) {
    if ($printed >= 10) break;
    echo "NIP: {$row['NIP_BARU']} - Name: {$row['NAMA']} - Gelar Blk: {$row['GELAR_BLK']}\n";
    $printed++;
}
