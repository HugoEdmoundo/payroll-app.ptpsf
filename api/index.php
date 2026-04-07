<?php

// Buat folder wajib di /tmp
$baseTmp = '/tmp/bootstrap/cache';
if (!is_dir($baseTmp)) {
    mkdir($baseTmp, 0777, true);
}

// Buat file pancingan biar Laravel nggak nyari folder aslinya
file_put_contents($baseTmp . '/services.php', '<?php return [];');
file_put_contents($baseTmp . '/packages.php', '<?php return [];');

// Set Environment secara runtime
putenv("LOG_CHANNEL=stderr");
putenv("APP_CONFIG_CACHE=/tmp/config.php");
putenv("APP_ROUTES_CACHE=/tmp/routes.php");
putenv("APP_SERVICES_CACHE=$baseTmp/services.php");
putenv("APP_PACKAGES_CACHE=$baseTmp/packages.php");

require __DIR__ . '/../public/index.php';