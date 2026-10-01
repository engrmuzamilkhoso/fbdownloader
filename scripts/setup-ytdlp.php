<?php

declare(strict_types=1);

/**
 * Downloads the correct standalone yt-dlp binary for this OS into /bin.
 * Run manually with `php scripts/setup-ytdlp.php` (no system Python required —
 * the standalone release bundles its own interpreter).
 */

$binDir = __DIR__ . '/../bin';
$releaseApiUrl = 'https://api.github.com/repos/yt-dlp/yt-dlp/releases/latest';

function assetForPlatform(): array
{
    if (stripos(PHP_OS_FAMILY, 'Windows') === 0) {
        return ['asset' => 'yt-dlp.exe', 'localName' => 'yt-dlp.exe'];
    }
    if (PHP_OS_FAMILY === 'Darwin') {
        return ['asset' => 'yt-dlp_macos', 'localName' => 'yt-dlp'];
    }
    return ['asset' => 'yt-dlp_linux', 'localName' => 'yt-dlp'];
}

function httpGet(string $url): string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_USERAGENT => 'fbvideo-downloader-setup-script',
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        throw new RuntimeException("Request to {$url} failed: {$error}");
    }
    if ($status < 200 || $status >= 300) {
        throw new RuntimeException("Request to {$url} returned HTTP {$status}");
    }
    return $body;
}

function downloadFile(string $url, string $destination): void
{
    $fp = fopen($destination . '.download', 'w');
    if ($fp === false) {
        throw new RuntimeException("Could not open {$destination}.download for writing");
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_USERAGENT => 'fbvideo-downloader-setup-script',
    ]);
    $ok = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    fclose($fp);

    if (!$ok || $status < 200 || $status >= 300) {
        @unlink($destination . '.download');
        throw new RuntimeException("Download failed (HTTP {$status}): {$error}");
    }

    rename($destination . '.download', $destination);
    if (PHP_OS_FAMILY !== 'Windows') {
        chmod($destination, 0755);
    }
}

function binaryWorks(string $path): bool
{
    if (!is_file($path)) {
        return false;
    }
    $output = [];
    $exitCode = 0;
    exec(escapeshellarg($path) . ' --version 2>&1', $output, $exitCode);
    return $exitCode === 0;
}

['asset' => $asset, 'localName' => $localName] = assetForPlatform();
if (!is_dir($binDir)) {
    mkdir($binDir, 0755, true);
}
$destination = $binDir . '/' . $localName;

if (binaryWorks($destination)) {
    echo "yt-dlp already present at {$destination}, skipping download.\n";
    exit(0);
}

try {
    $release = json_decode(httpGet($releaseApiUrl), true);
    $match = null;
    foreach (($release['assets'] ?? []) as $entry) {
        if (($entry['name'] ?? '') === $asset) {
            $match = $entry;
            break;
        }
    }
    if ($match === null) {
        throw new RuntimeException("Could not find release asset \"{$asset}\" in yt-dlp {$release['tag_name']}.");
    }

    downloadFile($match['browser_download_url'], $destination);

    if (!binaryWorks($destination)) {
        throw new RuntimeException('Downloaded binary did not run successfully.');
    }

    echo "yt-dlp {$release['tag_name']} installed at {$destination}\n";
} catch (Throwable $e) {
    fwrite(STDERR, "\n[setup-ytdlp] Could not auto-install yt-dlp: {$e->getMessage()}\n");
    fwrite(STDERR, "[setup-ytdlp] Video resolution will not work until a yt-dlp binary is placed at:\n");
    fwrite(STDERR, "[setup-ytdlp]   {$destination}\n");
    fwrite(STDERR, "[setup-ytdlp] Download manually from https://github.com/yt-dlp/yt-dlp/releases/latest\n\n");
    exit(1);
}
