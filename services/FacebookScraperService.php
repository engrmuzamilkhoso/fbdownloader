<?php

declare(strict_types=1);

/**
 * Pure-PHP fallback resolver for hosts where yt-dlp can't run (shared
 * hosting that disables proc_open). Fetches the public video page with curl
 * and extracts the same progressive MP4 URLs Facebook's own player uses.
 *
 * Less robust than yt-dlp — Facebook changes its markup and may show a
 * login wall to some server IPs — so VideoResolver only uses it when yt-dlp
 * isn't usable (or when VIDEO_ENGINE=scraper forces it).
 */

const SCRAPER_PAGE_TIMEOUT_SECONDS = 15;
const SCRAPER_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36';

/**
 * @return array{ok: true, info: array{title: string, thumbnail: ?string, durationSeconds: ?int, sources: array<string, string>}}
 *       | array{ok: false, error: array{code: string, message: string}}
 */
function scraperFetchVideoInfo(string $url): array
{
    $attempts = [$url];
    // The mobile site sometimes exposes the video when the desktop page doesn't.
    $mobile = preg_replace('#^https://(www\.)?facebook\.com/#i', 'https://m.facebook.com/', $url);
    if ($mobile !== null && $mobile !== $url) {
        $attempts[] = $mobile;
    }

    $lastError = ['code' => 'NOT_FOUND', 'message' => "No downloadable video was found at this link. Make sure it's a public video post."];

    foreach ($attempts as $attemptUrl) {
        $page = scraperHttpGet($attemptUrl);
        if ($page['body'] === null) {
            $lastError = $page['timedOut']
                ? ['code' => 'TIMEOUT', 'message' => 'The request to Facebook timed out. Please try again.']
                : ['code' => 'UPSTREAM_ERROR', 'message' => 'Facebook returned an unexpected response. Please try again later.'];
            continue;
        }
        if ($page['status'] === 404) {
            return ['ok' => false, 'error' => ['code' => 'NOT_FOUND', 'message' => 'That video could not be found. It may have been deleted or the link is incorrect.']];
        }
        if ($page['status'] === 429) {
            return ['ok' => false, 'error' => ['code' => 'RATE_LIMITED', 'message' => 'Too many requests right now. Please wait a moment and try again.']];
        }

        $html = $page['body'];
        $sources = scraperExtractSources($html);
        if ($sources !== []) {
            return ['ok' => true, 'info' => [
                'title' => scraperExtractTitle($html),
                'thumbnail' => scraperExtractThumbnail($html),
                'durationSeconds' => scraperExtractDuration($html),
                'sources' => $sources,
            ]];
        }

        if (preg_match('/id="login_form"|name="login"|You must log in|log in to continue/i', $html) === 1) {
            $lastError = ['code' => 'PRIVATE_OR_LOGIN_REQUIRED', 'message' => "This video is private or requires login, so it can't be downloaded."];
        }
    }

    return ['ok' => false, 'error' => $lastError];
}

/**
 * @return array{status: int, body: ?string, timedOut: bool}
 */
function scraperHttpGet(string $url): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => SCRAPER_PAGE_TIMEOUT_SECONDS,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => SCRAPER_USER_AGENT,
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-Dest: document',
            'Upgrade-Insecure-Requests: 1',
        ],
    ]);

    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $timedOut = curl_errno($ch) === CURLE_OPERATION_TIMEDOUT;
    curl_close($ch);

    return ['status' => $status, 'body' => is_string($body) ? $body : null, 'timedOut' => $timedOut];
}

/**
 * Finds the HD/SD progressive MP4 URLs in the page's embedded JSON.
 *
 * @return array<string, string> e.g. ['hd' => 'https://...fbcdn.net/...', 'sd' => '...']
 */
function scraperExtractSources(string $html): array
{
    $patterns = [
        'hd' => ['browser_native_hd_url', 'playable_url_quality_hd', 'hd_src_no_ratelimit', 'hd_src'],
        'sd' => ['browser_native_sd_url', 'playable_url', 'sd_src_no_ratelimit', 'sd_src'],
    ];

    $sources = [];
    foreach ($patterns as $quality => $keys) {
        foreach ($keys as $key) {
            if (preg_match('/"?' . preg_quote($key, '/') . '"?\s*:\s*"((?:[^"\\\\]|\\\\.)+)"/', $html, $m) !== 1) {
                continue;
            }
            $decoded = json_decode('"' . $m[1] . '"');
            if (is_string($decoded) && scraperIsAllowedMediaUrl($decoded)) {
                $sources[$quality] = $decoded;
                break;
            }
        }
    }

    if (isset($sources['hd'], $sources['sd']) && $sources['hd'] === $sources['sd']) {
        unset($sources['sd']);
    }

    return $sources;
}

/** Only ever stream from Facebook's own video CDN (prevents SSRF via crafted pages). */
function scraperIsAllowedMediaUrl(string $url): bool
{
    $parts = parse_url($url);
    $host = strtolower($parts['host'] ?? '');

    return ($parts['scheme'] ?? '') === 'https'
        && ($host === 'fbcdn.net' || str_ends_with($host, '.fbcdn.net'));
}

function scraperExtractTitle(string $html): string
{
    foreach (['/<meta[^>]+property="og:title"[^>]+content="([^"]*)"/i', '/<title[^>]*>(.*?)<\/title>/is'] as $pattern) {
        if (preg_match($pattern, $html, $m) === 1) {
            $title = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $title = preg_replace('/\s*\|\s*Facebook\s*$/i', '', $title) ?? $title;
            // Drop the "2.8M views · 1.2K reactions | " prefix Facebook adds.
            $title = preg_replace('/^[\d.,]+\s*[KMB]?\s+(?:views?|plays?)\b[^|]*\|\s*/iu', '', $title) ?? $title;
            if ($title !== '' && strcasecmp($title, 'Facebook') !== 0) {
                return mb_substr($title, 0, 200);
            }
        }
    }

    return 'Facebook video';
}

function scraperExtractThumbnail(string $html): ?string
{
    if (preg_match('/<meta[^>]+property="og:image"[^>]+content="([^"]+)"/i', $html, $m) === 1) {
        $url = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (scraperIsAllowedMediaUrl($url)) {
            return $url;
        }
    }

    // Embedded JSON: video poster frames live under the t15.5256-10 CDN path,
    // which keeps us from picking up an unrelated avatar or page image.
    if (preg_match('/"uri"\s*:\s*"((?:[^"\\\\]|\\\\.)*?t15\.5256-10(?:[^"\\\\]|\\\\.)*)"/', $html, $m) === 1) {
        $url = json_decode('"' . $m[1] . '"');
        if (is_string($url) && scraperIsAllowedMediaUrl($url)) {
            return $url;
        }
    }

    return null;
}

function scraperExtractDuration(string $html): ?int
{
    if (preg_match('/"playable_duration_in_ms"\s*:\s*(\d+)/', $html, $m) === 1) {
        return (int) round(((int) $m[1]) / 1000);
    }
    if (preg_match('/"length_in_second"\s*:\s*([\d.]+)/', $html, $m) === 1) {
        return (int) round((float) $m[1]);
    }

    return null;
}

/**
 * Streams the chosen CDN file straight to the browser. Returns false (and
 * sends nothing) if the CDN refuses the request before any bytes arrive, so
 * the caller can still send a JSON error.
 */
function scraperStreamMedia(string $mediaUrl, string $filename): bool
{
    if (!scraperIsAllowedMediaUrl($mediaUrl)) {
        return false;
    }

    @set_time_limit(0);
    $headersSent = false;
    $status = 0;
    $contentLength = null;

    $ch = curl_init($mediaUrl);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_LOW_SPEED_LIMIT => 1024,
        CURLOPT_LOW_SPEED_TIME => 30,
        CURLOPT_USERAGENT => SCRAPER_USER_AGENT,
        CURLOPT_HEADERFUNCTION => static function ($ch, string $line) use (&$status, &$contentLength): int {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $m) === 1) {
                $status = (int) $m[1];
                $contentLength = null;
            } elseif (preg_match('/^content-length:\s*(\d+)/i', $line, $m) === 1) {
                $contentLength = (int) $m[1];
            }
            return strlen($line);
        },
        CURLOPT_WRITEFUNCTION => static function ($ch, string $chunk) use (&$headersSent, &$status, &$contentLength, $filename): int {
            if (!$headersSent) {
                if ($status < 200 || $status >= 300) {
                    return 0; // abort: let the caller report an error instead
                }
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }
                header('Content-Type: video/mp4');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: no-store');
                header('X-Accel-Buffering: no');
                if ($contentLength !== null) {
                    header('Content-Length: ' . $contentLength);
                }
                $headersSent = true;
            }
            echo $chunk;
            flush();
            return connection_aborted() ? 0 : strlen($chunk);
        },
    ]);

    curl_exec($ch);
    curl_close($ch);

    return $headersSent;
}
