<?php

declare(strict_types=1);

require_once __DIR__ . '/YtDlpService.php';
require_once __DIR__ . '/FacebookScraperService.php';

/**
 * Which backend resolves videos: yt-dlp when it can actually run here,
 * otherwise the pure-PHP scraper (shared hosts that disable proc_open).
 * VIDEO_ENGINE=ytdlp|scraper in .env forces one; "auto" (default) decides.
 */
function videoEngine(): string
{
    if (VIDEO_ENGINE === 'ytdlp' || VIDEO_ENGINE === 'scraper') {
        return VIDEO_ENGINE;
    }

    return function_exists('proc_open') && ytdlpIsAvailable() ? 'ytdlp' : 'scraper';
}

/**
 * @return array{ok: true, data: array}|array{ok: false, error: string, code: string}
 */
function resolveFacebookVideo(string $sourceUrl): array
{
    return videoEngine() === 'scraper'
        ? resolveWithScraper($sourceUrl)
        : resolveWithYtDlp($sourceUrl);
}

function resolveWithYtDlp(string $sourceUrl): array
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

function resolveWithScraper(string $sourceUrl): array
{
    $result = scraperFetchVideoInfo($sourceUrl);

    if (!$result['ok']) {
        return ['ok' => false, 'error' => $result['error']['message'], 'code' => $result['error']['code']];
    }

    $info = $result['info'];
    $links = [];
    foreach (array_keys($info['sources']) as $i => $quality) {
        $links[] = [
            'formatId' => $quality,
            'label' => strtoupper($quality),
            'isBest' => $i === 0,
            'sizeBytes' => null,
        ];
    }

    return [
        'ok' => true,
        'data' => [
            'title' => $info['title'],
            'thumbnail' => $info['thumbnail'],
            'durationSeconds' => $info['durationSeconds'],
            'links' => $links,
            'sourceUrl' => $sourceUrl,
        ],
    ];
}
