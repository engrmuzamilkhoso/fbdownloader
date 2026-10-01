<?php

declare(strict_types=1);

/**
 * One-time asset generator for the Apple touch icon and Open Graph image.
 * Run manually with `php scripts/generate-images.php` whenever the brand
 * mark changes. Falls back to GD's built-in bitmap font if no TTF is found.
 */

$outputDir = __DIR__ . '/../assets/images';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

function findFont(): ?string
{
    $candidates = [
        'C:\\Windows\\Fonts\\segoeui.ttf',
        'C:\\Windows\\Fonts\\arial.ttf',
        '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        '/System/Library/Fonts/Helvetica.ttc',
    ];
    foreach ($candidates as $candidate) {
        if (is_file($candidate)) {
            return $candidate;
        }
    }
    return null;
}

function hexToRgb(string $hex): array
{
    $hex = ltrim($hex, '#');
    return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
}

function applyGradient($image, int $width, int $height, string $fromHex, string $toHex): void
{
    [$r1, $g1, $b1] = hexToRgb($fromHex);
    [$r2, $g2, $b2] = hexToRgb($toHex);
    for ($x = 0; $x < $width; $x++) {
        $t = $x / max(1, $width - 1);
        $r = (int) round($r1 + ($r2 - $r1) * $t);
        $g = (int) round($g1 + ($g2 - $g1) * $t);
        $b = (int) round($b1 + ($b2 - $b1) * $t);
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, $x, 0, $x, $height, $color);
    }
}

function drawArrowIcon($image, int $centerX, int $centerY, int $size, $color, int $thickness): void
{
    $half = (int) ($size / 2);
    imagesetthickness($image, $thickness);
    imageline($image, $centerX, $centerY - $half, $centerX, $centerY + (int) ($half * 0.3), $color);
    imageline($image, $centerX, $centerY + (int) ($half * 0.3), $centerX - (int) ($half * 0.6), $centerY - (int) ($half * 0.35), $color);
    imageline($image, $centerX, $centerY + (int) ($half * 0.3), $centerX + (int) ($half * 0.6), $centerY - (int) ($half * 0.35), $color);
    imageline($image, $centerX - $half, $centerY + $half, $centerX + $half, $centerY + $half, $color);
}

/* ---------- Apple touch icon (180x180) + standard favicon PNG fallbacks ---------- */
$icon = imagecreatetruecolor(180, 180);
applyGradient($icon, 180, 180, '#a3e635', '#10b981');
$white = imagecolorallocate($icon, 5, 46, 28);
drawArrowIcon($icon, 90, 92, 80, $white, 9);
imagepng($icon, $outputDir . '/apple-touch-icon.png');
imagedestroy($icon);

foreach ([32, 16] as $size) {
    $favicon = imagecreatetruecolor($size, $size);
    applyGradient($favicon, $size, $size, '#a3e635', '#10b981');
    $favWhite = imagecolorallocate($favicon, 5, 46, 28);
    drawArrowIcon($favicon, (int) ($size / 2), (int) ($size / 2) + 1, (int) ($size * 0.6), $favWhite, max(1, (int) ($size / 9)));
    imagepng($favicon, $outputDir . "/favicon-{$size}x{$size}.png");
    imagedestroy($favicon);
}

/* ---------- Open Graph image (1200x630) ---------- */
$og = imagecreatetruecolor(1200, 630);
applyGradient($og, 1200, 630, '#062a1e', '#0b3d2c');
$white = imagecolorallocate($og, 255, 255, 255);
$muted = imagecolorallocate($og, 167, 214, 190);

$badge = imagecreatetruecolor(56, 56);
applyGradient($badge, 56, 56, '#a3e635', '#10b981');
$badgeWhite = imagecolorallocate($badge, 5, 46, 28);
drawArrowIcon($badge, 28, 29, 26, $badgeWhite, 4);
imagecopy($og, $badge, 96, 96, 0, 0, 56, 56);
imagedestroy($badge);

$font = findFont();

if ($font !== null) {
    imagettftext($og, 22, 0, 168, 132, $white, $font, 'FBVideo Downloader');
    imagettftext($og, 46, 0, 96, 260, $white, $font, 'Save any Facebook video');
    imagettftext($og, 46, 0, 96, 320, $white, $font, 'in one paste.');
    imagettftext($og, 18, 0, 96, 380, $muted, $font, 'Free, fast, and completely private. No login, no watermark.');
} else {
    imagestring($og, 5, 168, 110, 'FBVideo Downloader', $white);
    imagestring($og, 5, 96, 240, 'Save any Facebook video in one paste', $white);
    imagestring($og, 3, 96, 280, 'Free, fast, and completely private.', $muted);
}

imagepng($og, $outputDir . '/og-image.png');
imagedestroy($og);

echo "Generated apple-touch-icon.png, favicon-32x32.png, favicon-16x16.png, and og-image.png in {$outputDir}\n";
