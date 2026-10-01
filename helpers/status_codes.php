<?php

declare(strict_types=1);

function httpStatusForErrorCode(string $code): int
{
    return match ($code) {
        'INVALID_URL' => 400,
        'NOT_FOUND' => 404,
        'PRIVATE_OR_LOGIN_REQUIRED' => 422,
        'RATE_LIMITED' => 429,
        'TIMEOUT' => 504,
        'UPSTREAM_ERROR' => 502,
        default => 500,
    };
}
