<?php

declare(strict_types=1);

/**
 * Reports this site's page views and successful downloads to the Toolzen
 * tools site, where FBVideo Downloader is listed as an external tool and its
 * views vs. usage appear in the admin dashboard (see the tools project's
 * ExternalToolController). Disabled unless TOOLZEN_EVENTS_URL and
 * TOOLZEN_INGEST_KEY are both set.
 *
 * Events are sent after the response has been flushed, with short timeouts,
 * and any failure is ignored — stats must never slow down or break a page
 * or a download.
 */

const STATS_VISITOR_COOKIE = 'fbv_vid';

function statsEnabled(): bool
{
    return TOOLZEN_EVENTS_URL !== '' && TOOLZEN_INGEST_KEY !== '';
}

/**
 * Anonymous, long-lived visitor id so the tools dashboard can de-duplicate
 * refreshes. Must be called before any output (it may set a cookie).
 */
function statsVisitorId(): string
{
    $existing = $_COOKIE[STATS_VISITOR_COOKIE] ?? null;
    if (is_string($existing) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $existing) === 1) {
        return $existing;
    }

    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    $id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));

    if (!headers_sent()) {
        setcookie(STATS_VISITOR_COOKIE, $id, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => IS_HTTPS,
        ]);
        $_COOKIE[STATS_VISITOR_COOKIE] = $id;
    }

    return $id;
}

function statsIsBot(string $userAgent): bool
{
    return $userAgent === '' || preg_match('/bot|crawl|spider|slurp|curl|wget|python|headless|preview|monitor/i', $userAgent) === 1;
}

/** Records one HTML page view. Call from the page header, before output. */
function trackPageView(): void
{
    if (!statsEnabled() || !requestMethodIs('GET')) {
        return;
    }

    $userAgent = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (statsIsBot($userAgent)) {
        return;
    }

    $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $payload = [
        'event' => 'view',
        'path' => $path,
        'referrer' => (string) ($_SERVER['HTTP_REFERER'] ?? ''),
        'visitor_id' => statsVisitorId(),
        'ip' => getClientIp(),
        'user_agent' => $userAgent,
    ];

    // Decide at the very end so 404 pages (mostly scanners) aren't counted.
    register_shutdown_function(static function () use ($payload): void {
        if (http_response_code() !== 404) {
            sendStatsEvent($payload);
        }
    });
}

/** Records one successful tool run (a video link that resolved). */
function trackToolUsage(): void
{
    if (!statsEnabled()) {
        return;
    }

    $payload = [
        'event' => 'usage',
        'token' => bin2hex(random_bytes(16)),
        'visitor_id' => statsVisitorId(),
        'ip' => getClientIp(),
        'user_agent' => (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''),
    ];

    register_shutdown_function(static fn () => sendStatsEvent($payload));
}

function sendStatsEvent(array $payload): void
{
    // Let the visitor's response finish before we make our own request.
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    } else {
        @ob_end_flush();
        @flush();
    }

    $ch = curl_init(TOOLZEN_EVENTS_URL);
    if ($ch === false) {
        return;
    }

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Ingest-Key: ' . TOOLZEN_INGEST_KEY,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT_MS => 800,
        CURLOPT_TIMEOUT_MS => 2000,
    ]);
    if (TOOLZEN_CONNECT_TO !== '') {
        // Dev only: reach the tools site under its own hostname while
        // connecting elsewhere, e.g. "localhost:8080:host.docker.internal:8080".
        curl_setopt($ch, CURLOPT_CONNECT_TO, [TOOLZEN_CONNECT_TO]);
    }

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($response === false || $status >= 300) {
        error_log('Toolzen stats event failed (HTTP ' . $status . '): ' . substr((string) $response, 0, 200));
    }
}
