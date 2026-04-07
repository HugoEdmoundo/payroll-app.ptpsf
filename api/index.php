<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// On Vercel, /tmp is the only writable directory
if (getenv('VERCEL')) {
    $tmpStorage = '/tmp/laravel/storage';
    $dirs = [
        $tmpStorage . '/framework/sessions',
        $tmpStorage . '/framework/views',
        $tmpStorage . '/framework/cache/data',
        $tmpStorage . '/logs',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    // Point storage to /tmp
    putenv('APP_STORAGE_PATH=' . $tmpStorage);
}

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

if (getenv('VERCEL')) {
    $app->useStoragePath('/tmp/laravel/storage');
}

$app->handleRequest(Request::capture());
