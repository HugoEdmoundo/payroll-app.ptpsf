<?php

// Pindahkan folder cache ke /tmp biar nggak Read-only
$cachePath = '/tmp/storage/framework/cache';
$viewsPath = '/tmp/storage/framework/views';

if (!is_dir($cachePath)) mkdir($cachePath, 0777, true);
if (!is_dir($viewsPath)) mkdir($viewsPath, 0777, true);

// Set environment variable buat folder-folder ini
putenv("XDG_CONFIG_HOME=/tmp");
putenv("APP_CONFIG_CACHE=/tmp/config.php");
putenv("APP_ROUTES_CACHE=/tmp/routes.php");
putenv("APP_SERVICES_CACHE=/tmp/services.php");
putenv("APP_PACKAGES_CACHE=/tmp/packages.php");

require __DIR__ . '/../public/index.php';