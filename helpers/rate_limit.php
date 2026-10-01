<?php

declare(strict_types=1);

/**
 * @return array{allowed: bool, retryAfter: int}
 */
function checkRateLimit(string $key, int $limit, int $windowSeconds): array
{
    if (!is_dir(RATE_LIMIT_DIR)) {
        mkdir(RATE_LIMIT_DIR, 0755, true);
    }

    $file = RATE_LIMIT_DIR . '/' . hash('sha256', $key) . '.json';
    $handle = fopen($file, 'c+');
    if ($handle === false) {
        // If we can't open the bucket file, fail open rather than blocking real users.
        return ['allowed' => true, 'retryAfter' => 0];
    }

    flock($handle, LOCK_EX);

    $contents = stream_get_contents($handle);
    $bucket = $contents !== false && $contents !== '' ? json_decode($contents, true) : null;

    $now = time();
    if (!is_array($bucket) || !isset($bucket['windowStartedAt']) || ($now - $bucket['windowStartedAt']) > $windowSeconds) {
        $bucket = ['count' => 1, 'windowStartedAt' => $now];
        $result = ['allowed' => true, 'retryAfter' => 0];
    } elseif ($bucket['count'] >= $limit) {
        $retryAfter = max(1, $bucket['windowStartedAt'] + $windowSeconds - $now);
        $result = ['allowed' => false, 'retryAfter' => $retryAfter];
    } else {
        $bucket['count']++;
        $result = ['allowed' => true, 'retryAfter' => 0];
    }

    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($bucket));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    return $result;
}
