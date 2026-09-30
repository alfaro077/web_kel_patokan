<?php

// Prepare writable /tmp directories for Vercel Serverless environment
$tmpStorage = '/tmp/storage';
if (!is_dir($tmpStorage . '/framework/views')) {
    @mkdir($tmpStorage . '/framework/views', 0755, true);
}
if (!is_dir($tmpStorage . '/framework/cache')) {
    @mkdir($tmpStorage . '/framework/cache', 0755, true);
}
if (!is_dir($tmpStorage . '/framework/sessions')) {
    @mkdir($tmpStorage . '/framework/sessions', 0755, true);
}

putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// If SQLite is used or database is missing, prepare /tmp/database.sqlite
$dbConn = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');
if ($dbConn === 'sqlite') {
    $dbFile = __DIR__ . '/../database/database.sqlite';
    $tmpDbFile = '/tmp/database.sqlite';
    if (!file_exists($tmpDbFile)) {
        if (file_exists($dbFile)) {
            @copy($dbFile, $tmpDbFile);
        } else {
            @touch($tmpDbFile);
        }
    }
    putenv("DB_DATABASE={$tmpDbFile}");
    $_ENV['DB_DATABASE'] = $tmpDbFile;
}

require __DIR__ . '/../public/index.php';
