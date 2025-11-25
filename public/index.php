<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Quick debug: log every incoming request to storage/logs/request_debug.log
// This runs before Laravel routing so it helps detect requests that don't reach the framework.
try {
    $debugPath = __DIR__ . '/../storage/logs/request_debug.log';
    $line = date('Y-m-d H:i:s') . ' ' . ($_SERVER['REMOTE_ADDR'] ?? 'cli') . ' ' . ($_SERVER['REQUEST_METHOD'] ?? '') . ' ' . ($_SERVER['REQUEST_URI'] ?? '') . "\n";
    @file_put_contents($debugPath, $line, FILE_APPEND | LOCK_EX);
} catch (Throwable $e) {
    // ignore - debugging only
}
// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
