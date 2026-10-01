<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

define('ROOT_PATH', dirname(__DIR__));

loadEnvFile(ROOT_PATH . '/config/.env');

define('APP_ENV', env('APP_ENV', 'production'));
define('APP_DEBUG', envBool('APP_DEBUG', false));

define('SITE_NAME', env('SITE_NAME', 'FBVideo Downloader'));
define('SITE_TAGLINE', 'Facebook video downloader');
define('SITE_DESCRIPTION', 'Download public Facebook videos in HD or SD in seconds. No login, no software install, no watermark — just paste a link and go.');
define('SITE_URL', rtrim(env('SITE_URL', 'https://fbvideodownloader.example.com'), '/'));

define('CONTACT_EMAIL', env('CONTACT_EMAIL', 'support@fbvideodownloader.example.com'));
define('PRIVACY_EMAIL', env('PRIVACY_EMAIL', 'privacy@fbvideodownloader.example.com'));

define('ADSENSE_ENABLED', envBool('ADSENSE_ENABLED', false));
define('ADSENSE_CLIENT_ID', env('ADSENSE_CLIENT_ID', ''));

// Search Console / Bing "HTML tag" verification codes (just the content value).
define('GOOGLE_SITE_VERIFICATION', env('GOOGLE_SITE_VERIFICATION', '') ?? '');
define('BING_SITE_VERIFICATION', env('BING_SITE_VERIFICATION', '') ?? '');

// Video backend: auto (yt-dlp if it can run here, else the pure-PHP
// scraper), or force "ytdlp" / "scraper". See services/VideoResolver.php.
define('VIDEO_ENGINE', strtolower((string) env('VIDEO_ENGINE', 'auto')));

define('YTDLP_BINARY_PATH', env('YTDLP_BINARY_PATH', ROOT_PATH . '/bin/' . (stripos(PHP_OS_FAMILY, 'Windows') === 0 ? 'yt-dlp.exe' : 'yt-dlp')));

// Optional: report page views + downloads to the Toolzen tools site's admin
// dashboard (see helpers/toolzen_stats.php). Leave blank to disable.
define('TOOLZEN_EVENTS_URL', env('TOOLZEN_EVENTS_URL', '') ?? '');
define('TOOLZEN_CONNECT_TO', env('TOOLZEN_CONNECT_TO', '') ?? '');

define('RATE_LIMIT_DIR', ROOT_PATH . '/cache/rate-limit');
define('DOWNLOAD_RATE_LIMIT', (int) env('DOWNLOAD_RATE_LIMIT', '12'));
define('MEDIA_RATE_LIMIT', (int) env('MEDIA_RATE_LIMIT', '20'));
define('RATE_LIMIT_WINDOW_SECONDS', (int) env('RATE_LIMIT_WINDOW_SECONDS', '60'));

define('IS_HTTPS', (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off')
    || ($_SERVER['SERVER_PORT'] ?? '') === '443'
    || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
}
