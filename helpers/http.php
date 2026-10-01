<?php

declare(strict_types=1);

function jsonResponse(array $data, int $status = 200, array $headers = []): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    foreach ($headers as $name => $value) {
        header("{$name}: {$value}");
    }
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
    exit;
}

function readJsonBody(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function requestMethodIs(string $method): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === strtoupper($method);
}
