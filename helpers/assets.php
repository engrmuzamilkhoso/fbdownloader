<?php

declare(strict_types=1);

/**
 * Appends a cache-busting ?v=<mtime> query string so the long-lived,
 * immutable Cache-Control on static assets (see .htaccess) doesn't serve
 * stale CSS/JS after a deploy.
 */
function assetUrl(string $path): string
{
    $fsPath = ROOT_PATH . $path;
    $version = is_file($fsPath) ? filemtime($fsPath) : time();
    return $path . '?v=' . $version;
}
