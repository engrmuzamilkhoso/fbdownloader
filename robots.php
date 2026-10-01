<?php

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

header('Content-Type: text/plain; charset=utf-8');
?>
User-agent: *
Allow: /
Disallow: /api/
Disallow: /cache/

Sitemap: <?= SITE_URL ?>/sitemap.xml
