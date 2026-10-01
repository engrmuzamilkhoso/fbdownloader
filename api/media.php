<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../services/YtDlpService.php';
require_once __DIR__ . '/../helpers/status_codes.php';

const MEDIA_FIRST_BYTE_TIMEOUT_SECONDS = 25;

if (!requestMethodIs('GET')) {
    jsonResponse(['error' => 'Method not allowed.'], 405);
}

$clientIp = getClientIp();
$rateLimit = checkRateLimit('media:' . $clientIp, MEDIA_RATE_LIMIT, RATE_LIMIT_WINDOW_SECONDS);
if (!$rateLimit['allowed']) {
    jsonResponse(['error' => 'Too many requests. Please wait a moment and try again.'], 429, ['Retry-After' => (string) $rateLimit['retryAfter']]);
}

if (!csrfIsValid(csrfTokenFromRequest())) {
    jsonResponse(['error' => 'Invalid or expired session. Please refresh the page and try again.'], 403);
}

$rawUrl = trim((string) ($_GET['url'] ?? ''));
$formatId = trim((string) ($_GET['format'] ?? ''));
$title = trim((string) ($_GET['title'] ?? 'facebook-video'));

if (!isValidFacebookUrl($rawUrl)) {
    jsonResponse(['error' => 'Invalid or missing video link.'], 400);
}
if (!isValidFormatId($formatId)) {
    jsonResponse(['error' => 'Invalid quality selection.'], 400);
}

$normalizedUrl = normalizeFacebookUrl($rawUrl);
$handle = ytdlpSpawnDownload($normalizedUrl, $formatId);

if ($handle === null) {
    jsonResponse(['error' => 'The video service is temporarily unavailable. Please try again shortly.'], 502);
}

[$process, $pipes] = [$handle['process'], $handle['pipes']];
stream_set_blocking($pipes[1], false);
stream_set_blocking($pipes[2], false);

$stderrBuffer = '';
$firstChunk = null;
$start = microtime(true);

while ($firstChunk === null) {
    $status = proc_get_status($process);

    $chunk = fread($pipes[1], 65536);
    if ($chunk !== false && $chunk !== '') {
        $firstChunk = $chunk;
        break;
    }

    $errChunk = fread($pipes[2], 65536);
    if ($errChunk !== false && $errChunk !== '' && strlen($stderrBuffer) < YTDLP_MAX_STDERR_BUFFER) {
        $stderrBuffer .= $errChunk;
    }

    if (!$status['running']) {
        break;
    }

    if ((microtime(true) - $start) > MEDIA_FIRST_BYTE_TIMEOUT_SECONDS) {
        proc_terminate($process, 9);
        break;
    }

    usleep(20_000);
}

if ($firstChunk === null) {
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);
    $failure = ytdlpClassifyFailure($stderrBuffer, $exitCode);
    jsonResponse(['error' => $failure['message']], httpStatusForErrorCode($failure['code']));
}

while (ob_get_level() > 0) {
    ob_end_clean();
}

$filename = sanitizeFilenamePart($title) . '-' . $formatId . '.mp4';
header('Content-Type: video/mp4');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store');
header('X-Accel-Buffering: no');

echo $firstChunk;
flush();

while (true) {
    $status = proc_get_status($process);

    $chunk = fread($pipes[1], 65536);
    if ($chunk !== false && $chunk !== '') {
        echo $chunk;
        flush();
    }

    if (connection_aborted()) {
        proc_terminate($process, 9);
        break;
    }

    if (($chunk === false || $chunk === '') && !$status['running']) {
        break;
    }

    if ($chunk === '' || $chunk === false) {
        usleep(10_000);
    }
}

fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);
