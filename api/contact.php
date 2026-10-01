<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!requestMethodIs('POST')) {
    jsonResponse(['ok' => false, 'error' => 'Method not allowed.'], 405);
}

$clientIp = getClientIp();
$rateLimit = checkRateLimit('contact:' . $clientIp, 5, RATE_LIMIT_WINDOW_SECONDS);
if (!$rateLimit['allowed']) {
    jsonResponse(['ok' => false, 'error' => 'Too many requests. Please wait a moment and try again.'], 429, ['Retry-After' => (string) $rateLimit['retryAfter']]);
}

if (!csrfIsValid(csrfTokenFromRequest())) {
    jsonResponse(['ok' => false, 'error' => 'Invalid or expired session. Please refresh the page and try again.'], 403);
}

$body = readJsonBody();
$name = trim((string) ($body['name'] ?? ''));
$email = trim((string) ($body['email'] ?? ''));
$message = trim((string) ($body['message'] ?? ''));

if ($name === '' || mb_strlen($name) > 120) {
    jsonResponse(['ok' => false, 'error' => 'Please enter your name.'], 400);
}
if (!isValidEmail($email)) {
    jsonResponse(['ok' => false, 'error' => 'Enter a valid email address.'], 400);
}
if (mb_strlen($message) < 10 || mb_strlen($message) > 2000) {
    jsonResponse(['ok' => false, 'error' => 'Message should be at least 10 characters.'], 400);
}

jsonResponse(['ok' => true], 200);
