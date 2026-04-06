<?php

// 1. Buat folder temporary di /tmp (satu-satunya tempat yang bisa ditulis di Vercel)
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

$dirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
    $cachePath
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 2. Paksa Laravel pake folder /tmp buat bootstrap cache
putenv("APP_STORAGE=$storagePath");
putenv("BOOTSTRAP_CACHE=$cachePath");

// 3. Tambahkan file dummy services.php & packages.php biar Laravel gak komplain
if (!file_exists($cachePath . '/services.php')) file_put_contents($cachePath . '/services.php', '<?php return [];');
if (!file_exists($cachePath . '/packages.php')) file_put_contents($cachePath . '/packages.php', '<?php return [];');

// 4. Load aplikasi
require __DIR__ . '/../public/index.php';