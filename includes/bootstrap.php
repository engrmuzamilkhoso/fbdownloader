<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';
require_once __DIR__ . '/../helpers/http.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/rate_limit.php';
require_once __DIR__ . '/../helpers/assets.php';
require_once __DIR__ . '/../helpers/icons.php';
require_once __DIR__ . '/../helpers/toolzen_stats.php';
// Header/footer render navLinks() on every page, so shared content must load
// globally — not just from HomeController — or non-home pages fatal mid-render.
require_once __DIR__ . '/site_content.php';
require_once __DIR__ . '/guides_content.php';

startSecureSession();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
