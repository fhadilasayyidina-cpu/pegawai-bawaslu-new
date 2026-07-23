<?php

$tmpDir = __DIR__ . '/../../storage/app/private/livewire-tmp';
$files = glob($tmpDir . '/*-meta*.xlsx');

if (empty($files)) {
    echo "No uploaded Excel files found in $tmpDir\n";
    exit(1);
}

// Find the newest file
usort($files, function ($a, $b) {
    return filemtime($b) - filemtime($a);
});

$newestFile = $files[0];
$destDir = __DIR__ . '/data';
if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

$destPath = $destDir . '/pegawai.xlsx';
copy($newestFile, $destPath);

echo "Successfully copied newest uploaded Excel file:\n";
echo "Source: $newestFile\n";
echo "Destination: $destPath\n";
echo "Size: " . filesize($destPath) . " bytes\n";
