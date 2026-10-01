<?php

declare(strict_types=1);

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfIsValid(?string $token): bool
{
    if (empty($_SESSION['csrf_token']) || $token === null || $token === '') {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Reads the CSRF token from the X-CSRF-Token header, which our own JS sets on
 * every fetch() call. A cross-site form post can't set custom headers without
 * triggering a CORS preflight, so this alone is a solid baseline defense for
 * our JSON API endpoints (paired with the SameSite=Lax session cookie).
 */
function csrfTokenFromRequest(): ?string
{
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    foreach ($headers as $name => $value) {
        if (strcasecmp($name, 'X-CSRF-Token') === 0) {
            return $value;
        }
    }
    return $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
}
