<?php

declare(strict_types=1);

require_once __DIR__ . '/YtDlpService.php';

/**
 * @return array{ok: true, data: array}|array{ok: false, error: string, code: string}
 */
function resolveFacebookVideo(string $sourceUrl): array
{
    $result = ytdlpFetchVideoInfo($sourceUrl);

    if (!$result['ok']) {
        return ['ok' => false, 'error' => $result['error']['message'], 'code' => $result['error']['code']];
    }

    $info = $result['info'];
    $links = ytdlpSelectDownloadFormats($info);

    if (count($links) === 0) {
        return [
            'ok' => false,
            'error' => "No downloadable video was found at this link. Make sure it's a public video post.",
            'code' => 'NOT_FOUND',
        ];
    }

    return [
        'ok' => true,
        'data' => [
            'title' => ytdlpExtractTitle($info),
            'thumbnail' => ytdlpExtractThumbnail($info),
            'durationSeconds' => ytdlpExtractDurationSeconds($info),
            'links' => $links,
            'sourceUrl' => $sourceUrl,
        ],
    ];
}
