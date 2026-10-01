<?php

declare(strict_types=1);

/**
 * Router for PHP's built-in dev server (`php -S`), which ignores .htaccess.
 * Mirrors the same directory protections and pretty-URL rewrites so local
 * testing matches production Apache behavior. Not used in real deployments.
 *
 * Usage: php -S localhost:8000 scripts/dev-server-router.php
 */

$protectedPrefixes = ['/config/', '/includes/', '/helpers/', '/services/', '/controllers/', '/templates/', '/bin/', '/cache/', '/scripts/'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

foreach ($protectedPrefixes as $prefix) {
    if (str_starts_with($uri, $prefix)) {
        http_response_code(403);
        echo 'Forbidden';
        return true;
    }
}

if ($uri === '/robots.txt') {
    require __DIR__ . '/../robots.php';
    return true;
}
if ($uri === '/sitemap.xml') {
    require __DIR__ . '/../sitemap.php';
    return true;
}
if ($uri === '/ads.txt') {
    require __DIR__ . '/../ads.php';
    return true;
}

if ($uri === '/') {
    require __DIR__ . '/../index.php';
    return true;
}

$filePath = __DIR__ . '/..' . $uri;
if (is_dir($filePath) && is_file(rtrim($filePath, '/') . '/index.php')) {
    require rtrim($filePath, '/') . '/index.php'; // Mirror Apache's DirectoryIndex (e.g. /guides/).
    return true;
}
if (file_exists($filePath) && !is_dir($filePath)) {
    return false; // Serve the static file or execute the .php script directly.
}

http_response_code(404);
require __DIR__ . '/../404.php';
return true;
