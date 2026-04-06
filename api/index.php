<?php

// Konfigurasi path untuk folder writable di Vercel (/tmp)
$storagePath = '/tmp/storage';
$paths = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
];

foreach ($paths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

// Override environment variables Laravel secara runtime
putenv("LOG_CHANNEL=stderr");
putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");
putenv("FRAMEWORK_CACHE_PATH=$storagePath/framework/cache");
putenv("SESSION_DRIVER=cookie");

// Jalanin aplikasi Laravel
require __DIR__ . '/../public/index.php';