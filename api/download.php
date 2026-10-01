<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../services/VideoResolver.php';
require_once __DIR__ . '/../helpers/status_codes.php';

if (!requestMethodIs('POST')) {
    jsonResponse(['ok' => false, 'error' => 'Method not allowed.', 'code' => 'INVALID_URL'], 405);
}

$clientIp = getClientIp();
$rateLimit = checkRateLimit('download:' . $clientIp, DOWNLOAD_RATE_LIMIT, RATE_LIMIT_WINDOW_SECONDS);
if (!$rateLimit['allowed']) {
    jsonResponse(
        ['ok' => false, 'error' => 'Too many requests. Please wait a moment and try again.', 'code' => 'RATE_LIMITED'],
        429,
        ['Retry-After' => (string) $rateLimit['retryAfter']],
    );
}

if (!csrfIsValid(csrfTokenFromRequest())) {
    jsonResponse(['ok' => false, 'error' => 'Invalid or expired session. Please refresh the page and try again.', 'code' => 'INVALID_URL'], 403);
}

$body = readJsonBody();
$url = trim((string) ($body['url'] ?? ''));

if (!isValidFacebookUrl($url)) {
    jsonResponse(['ok' => false, 'error' => 'Enter a valid facebook.com or fb.watch video link.', 'code' => 'INVALID_URL'], 400);
}

$normalizedUrl = normalizeFacebookUrl($url);
$result = resolveFacebookVideo($normalizedUrl);

if (!$result['ok']) {
    jsonResponse($result, httpStatusForErrorCode($result['code']));
}

trackToolUsage();
jsonResponse($result, 200);
