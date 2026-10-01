<?php

declare(strict_types=1);

const YTDLP_INFO_TIMEOUT_SECONDS = 20;
const YTDLP_MAX_STDERR_BUFFER = 4000;
const YTDLP_MAX_QUALITY_OPTIONS = 3;
const YTDLP_AVATAR_AREA_THRESHOLD = 200 * 200;

function ytdlpIsAvailable(): bool
{
    return is_file(YTDLP_BINARY_PATH);
}

/**
 * @return array{code: string, message: string}
 */
function ytdlpClassifyFailure(string $stderr, ?int $exitCode): array
{
    $normalized = strtolower($stderr);

    if (preg_match('/log in|login required|log into facebook|private/i', $normalized) === 1) {
        return ['code' => 'PRIVATE_OR_LOGIN_REQUIRED', 'message' => "This video is private or requires login, so it can't be downloaded."];
    }
    if (preg_match('/http error 429|too many requests|rate.?limit/i', $normalized) === 1) {
        return ['code' => 'RATE_LIMITED', 'message' => 'Too many requests right now. Please wait a moment and try again.'];
    }
    if (preg_match("/unsupported url|unable to extract|cannot parse data|no video formats found|not found|content isn'?t available|video unavailable/i", $normalized) === 1) {
        return ['code' => 'NOT_FOUND', 'message' => 'That video could not be found. It may have been deleted or the link is incorrect.'];
    }
    if ($exitCode === null) {
        return ['code' => 'TIMEOUT', 'message' => 'The request to Facebook timed out. Please try again.'];
    }
    return ['code' => 'UPSTREAM_ERROR', 'message' => 'Facebook returned an unexpected response. Please try again later.'];
}

/**
 * @param string[] $args
 * @return array{stdout: string, stderr: string, exitCode: ?int}
 */
function ytdlpRunCollecting(array $args, int $timeoutSeconds): array
{
    $command = array_merge([YTDLP_BINARY_PATH], $args);
    $descriptorSpec = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];

    $process = proc_open($command, $descriptorSpec, $pipes, null, null, ['bypass_shell' => true]);
    if (!is_resource($process)) {
        return ['stdout' => '', 'stderr' => 'spawn failed', 'exitCode' => null];
    }

    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $stdout = '';
    $stderr = '';
    $start = microtime(true);
    $stdoutOpen = true;
    $stderrOpen = true;

    while ($stdoutOpen || $stderrOpen) {
        if ((microtime(true) - $start) > $timeoutSeconds) {
            proc_terminate($process, 9);
            usleep(100_000);
            proc_close($process);
            return ['stdout' => $stdout, 'stderr' => $stderr, 'exitCode' => null];
        }

        if ($stdoutOpen) {
            $chunk = fread($pipes[1], 65536);
            if ($chunk === false || feof($pipes[1])) {
                $stdoutOpen = false;
            } elseif ($chunk !== '') {
                $stdout .= $chunk;
            }
        }
        if ($stderrOpen) {
            $chunk = fread($pipes[2], 65536);
            if ($chunk === false || feof($pipes[2])) {
                $stderrOpen = false;
            } elseif ($chunk !== '' && strlen($stderr) < YTDLP_MAX_STDERR_BUFFER) {
                $stderr .= $chunk;
            }
        }

        if ($stdoutOpen || $stderrOpen) {
            usleep(20_000);
        }
    }

    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    return ['stdout' => $stdout, 'stderr' => $stderr, 'exitCode' => $exitCode];
}

/**
 * @return array{ok: true, info: array}|array{ok: false, error: array{code: string, message: string}}
 */
function ytdlpFetchVideoInfo(string $url): array
{
    if (!ytdlpIsAvailable()) {
        return ['ok' => false, 'error' => ['code' => 'UPSTREAM_ERROR', 'message' => 'The video service is temporarily unavailable. Please try again shortly.']];
    }

    $result = ytdlpRunCollecting(
        ['-J', '--no-warnings', '--no-playlist', '--socket-timeout', '15', '--retries', '2', $url],
        YTDLP_INFO_TIMEOUT_SECONDS,
    );

    if ($result['exitCode'] !== 0 || trim($result['stdout']) === '') {
        return ['ok' => false, 'error' => ytdlpClassifyFailure($result['stderr'], $result['exitCode'])];
    }

    $info = json_decode($result['stdout'], true);
    if (!is_array($info)) {
        return ['ok' => false, 'error' => ['code' => 'UPSTREAM_ERROR', 'message' => 'Facebook returned an unreadable response. Please try again.']];
    }
    if (empty($info['formats']) || !is_array($info['formats'])) {
        return ['ok' => false, 'error' => ['code' => 'NOT_FOUND', 'message' => 'No downloadable video was found at this link.']];
    }

    return ['ok' => true, 'info' => $info];
}

function ytdlpIsAudioOnlyOrVideoOnly(array $format): bool
{
    return ($format['vcodec'] ?? null) === 'none' || ($format['acodec'] ?? null) === 'none';
}

function ytdlpLabelForFormat(array $format, int $index, int $total): string
{
    if (!empty($format['height'])) {
        return $format['height'] . 'p';
    }
    if (!empty($format['format_note'])) {
        return strtoupper((string) $format['format_note']);
    }
    if (($format['format_id'] ?? null) === 'hd') {
        return 'HD';
    }
    if (($format['format_id'] ?? null) === 'sd') {
        return 'SD';
    }
    if ($index === 0) {
        return 'Best quality';
    }
    return $total === 2 ? 'Lower quality' : ('Quality ' . ($index + 1));
}

/**
 * @return array<int, array{formatId: string, label: string, isBest: bool, sizeBytes: ?int}>
 */
function ytdlpSelectDownloadFormats(array $info): array
{
    $formats = $info['formats'] ?? [];
    $candidates = array_values(array_filter($formats, function ($format) {
        return !empty($format['format_id']) && ($format['ext'] ?? null) === 'mp4' && !ytdlpIsAudioOnlyOrVideoOnly($format);
    }));

    $deduped = [];
    foreach ($candidates as $format) {
        $key = !empty($format['height']) ? (string) $format['height'] : (string) $format['format_id'];
        $existing = $deduped[$key] ?? null;
        if ($existing === null || ($format['filesize'] ?? 0) > ($existing['filesize'] ?? 0)) {
            $deduped[$key] = $format;
        }
    }

    $sorted = array_values($deduped);
    usort($sorted, function ($a, $b) {
        if (!empty($a['height']) && !empty($b['height'])) {
            return $b['height'] <=> $a['height'];
        }
        if (($a['format_id'] ?? null) === 'hd') {
            return -1;
        }
        if (($b['format_id'] ?? null) === 'hd') {
            return 1;
        }
        return 0;
    });

    $limited = array_slice($sorted, 0, YTDLP_MAX_QUALITY_OPTIONS);
    $total = count($limited);

    return array_map(function ($format, $index) use ($total) {
        return [
            'formatId' => (string) $format['format_id'],
            'label' => ytdlpLabelForFormat($format, $index, $total),
            'isBest' => $index === 0,
            'sizeBytes' => $format['filesize'] ?? $format['filesize_approx'] ?? null,
        ];
    }, $limited, array_keys($limited));
}

function ytdlpExtractTitle(array $info): string
{
    $title = trim((string) ($info['title'] ?? ''));
    return $title === '' ? 'Facebook video' : $title;
}

function ytdlpExtractThumbnail(array $info): ?string
{
    $candidates = array_values(array_filter($info['thumbnails'] ?? [], fn ($t) => !empty($t['url'])));

    if (count($candidates) > 0) {
        usort($candidates, function ($a, $b) {
            $areaA = ($a['width'] ?? 0) * ($a['height'] ?? 0);
            $areaB = ($b['width'] ?? 0) * ($b['height'] ?? 0);
            return $areaB <=> $areaA;
        });

        $largest = $candidates[0];
        $largestArea = ($largest['width'] ?? 0) * ($largest['height'] ?? 0);

        if ($largestArea > YTDLP_AVATAR_AREA_THRESHOLD) {
            return $largest['url'];
        }

        foreach ($candidates as $candidate) {
            $area = ($candidate['width'] ?? 0) * ($candidate['height'] ?? 0);
            if ($area > YTDLP_AVATAR_AREA_THRESHOLD) {
                return $candidate['url'];
            }
        }

        return $largest['url'];
    }

    return $info['thumbnail'] ?? null;
}

function ytdlpExtractDurationSeconds(array $info): ?int
{
    return isset($info['duration']) && is_numeric($info['duration']) ? (int) round((float) $info['duration']) : null;
}

/**
 * Starts a streaming download and returns the process handle plus its pipes.
 * Caller is responsible for reading $pipes[1] and calling proc_close($process).
 *
 * @return array{process: resource, pipes: array}|null
 */
function ytdlpSpawnDownload(string $url, string $formatId): ?array
{
    $command = [
        YTDLP_BINARY_PATH, '-f', $formatId, '-o', '-',
        '--no-warnings', '--no-playlist', '--no-part', '--retries', '2', $url,
    ];
    $descriptorSpec = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];

    $process = proc_open($command, $descriptorSpec, $pipes, null, null, ['bypass_shell' => true]);
    if (!is_resource($process)) {
        return null;
    }

    fclose($pipes[0]);
    return ['process' => $process, 'pipes' => $pipes];
}
