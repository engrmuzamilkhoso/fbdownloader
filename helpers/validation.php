<?php

declare(strict_types=1);

function isValidFacebookUrl(string $url): bool
{
    $url = trim($url);
    if ($url === '' || mb_strlen($url) > 2048) {
        return false;
    }

    $parts = parse_url($url);
    if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
        return false;
    }

    if (!in_array($parts['scheme'], ['http', 'https'], true)) {
        return false;
    }

    $host = strtolower($parts['host']);
    return (bool) preg_match('/(^|\.)facebook\.com$|(^|\.)fb\.watch$|(^|\.)fb\.com$/', $host);
}

function normalizeFacebookUrl(string $url): string
{
    $parts = parse_url(trim($url));
    $host = strtolower($parts['host'] ?? '');
    $host = preg_replace('/^m\./', 'www.', $host) ?? $host;
    $host = preg_replace('/^mbasic\./', 'www.', $host) ?? $host;

    $normalized = 'https://' . $host . ($parts['path'] ?? '');
    if (!empty($parts['query'])) {
        $normalized .= '?' . $parts['query'];
    }
    return $normalized;
}

function isValidEmail(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

function isValidFormatId(string $formatId): bool
{
    return (bool) preg_match('/^[\w.+-]{1,32}$/', $formatId);
}
