<?php

declare(strict_types=1);

function guidesIndex(): array
{
    return [
        [
            'slug' => 'how-to-download-facebook-videos-on-iphone',
            'short' => 'On iPhone & iPad',
            'icon' => 'smartphone',
            'title' => 'How to Download Facebook Videos on iPhone',
            'description' => 'A step-by-step walkthrough for saving public Facebook videos to your iPhone or iPad camera roll, with fixes for common Safari issues.',
            'updated' => '2026-06-01',
        ],
        [
            'slug' => 'how-to-download-facebook-videos-on-android',
            'short' => 'On Android',
            'icon' => 'smartphone',
            'title' => 'How to Download Facebook Videos on Android',
            'description' => 'How to save Facebook videos to your Android phone from the Facebook app or Chrome, including where downloaded files end up.',
            'updated' => '2026-06-01',
        ],
        [
            'slug' => 'facebook-reels-downloader-guide',
            'short' => 'Downloading Reels',
            'icon' => 'clapperboard',
            'title' => 'How to Download Facebook Reels (HD, No Watermark)',
            'description' => "Reels use a different share flow than regular videos — here's how to grab the link correctly and download the original quality.",
            'updated' => '2026-06-01',
        ],
        [
            'slug' => 'fix-facebook-video-download-errors',
            'short' => 'Fixing download errors',
            'icon' => 'bug',
            'title' => "Facebook Video Won't Download? Here's How to Fix It",
            'description' => 'The most common reasons a Facebook video link fails to resolve, and exactly how to tell which one applies to you.',
            'updated' => '2026-06-01',
        ],
    ];
}

function findGuideBySlug(string $slug): ?array
{
    foreach (guidesIndex() as $guide) {
        if ($guide['slug'] === $slug) {
            return $guide;
        }
    }
    return null;
}

function guideReadingTime(string $slug): int
{
    $template = ROOT_PATH . '/templates/pages/guides/' . basename($slug) . '.php';
    if (!is_file($template)) {
        return 1;
    }
    $words = str_word_count(strip_tags((string) file_get_contents($template)));
    return max(1, (int) round($words / 220));
}
