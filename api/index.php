<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup temporary writable directory for Vercel Serverless
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath, 0777, true);
    @mkdir($storagePath . '/framework/views', 0777, true);
    @mkdir($storagePath . '/framework/sessions', 0777, true);
    @mkdir($storagePath . '/framework/cache', 0777, true);
    @mkdir($storagePath . '/logs', 0777, true);
}

// Ensure session & cache paths exist
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");
putenv("CACHE_STORE=array");

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
