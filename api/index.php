<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure /tmp storage paths exist for Vercel
$tmpStorage = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($tmpStorage . $dir)) {
        @mkdir($tmpStorage . $dir, 0755, true);
    }
}

// Fallback SQLite database if DB_CONNECTION is sqlite
$dbConn = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');
if ($dbConn === 'sqlite') {
    $tmpDbFile = '/tmp/database.sqlite';
    if (!file_exists($tmpDbFile)) {
        $origDb = __DIR__ . '/../database/database.sqlite';
        if (file_exists($origDb)) {
            @copy($origDb, $tmpDbFile);
        } else {
            @touch($tmpDbFile);
        }
    }
    putenv("DB_DATABASE={$tmpDbFile}");
    $_ENV['DB_DATABASE'] = $tmpDbFile;
}

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($tmpStorage);

// Handle HTTP Request
$app->handleRequest(Request::capture());
