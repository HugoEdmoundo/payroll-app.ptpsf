<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// On Vercel, use /tmp for writable storage
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
    
    // Create required directories in /tmp
    $dirs = [
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/logs',
        '/tmp/bootstrap/cache',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Maintenance mode check
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override storage path for Vercel
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
    $app->useBootstrapPath('/tmp/bootstrap');
}

$app->handleRequest(Request::capture());
