<?php

// Vercel serverless entry point for Laravel

define('LARAVEL_START', microtime(true));

// Set the public path to the public directory
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../public';

// Change to the public directory
chdir(__DIR__ . '/../public');

// Require the autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
