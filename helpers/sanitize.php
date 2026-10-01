<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sanitizeFilenamePart(string $value): string
{
    $trimmed = mb_substr(trim($value), 0, 80);
    $clean = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $trimmed) ?? '';
    $clean = preg_replace('/\s+/', '-', trim($clean)) ?? '';
    return $clean === '' ? 'facebook-video' : $clean;
}

function getClientIp(): string
{
    $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    if ($forwardedFor !== '') {
        $parts = explode(',', $forwardedFor);
        return trim($parts[0]);
    }
    return $_SERVER['HTTP_X_REAL_IP'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}
