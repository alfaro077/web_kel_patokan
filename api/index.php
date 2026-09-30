<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

try {
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

} catch (\Throwable $e) {
    http_response_code(500);
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; border-radius: 12px; margin: 20px;'>";
    echo "<h2 style='margin-top:0;'>Laravel Debug Error (Vercel Serverless)</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #fda4af; overflow: auto; max-height: 400px; color: #334155; font-size: 13px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
