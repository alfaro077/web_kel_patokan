<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Prepare writable /tmp storage directories for Vercel Serverless environment
$tmpStorage = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($tmpStorage . $dir)) {
        @mkdir($tmpStorage . $dir, 0755, true);
    }
}

putenv('APP_STORAGE=' . $tmpStorage);
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=' . $tmpStorage . '/framework/views');

$_ENV['APP_STORAGE'] = $tmpStorage;
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['VIEW_COMPILED_PATH'] = $tmpStorage . '/framework/views';

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

// Register Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($tmpStorage);

// Handle Request
$app->handleRequest(Request::capture());
