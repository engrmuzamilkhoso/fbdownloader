<?php

declare(strict_types=1);

function navLinks(): array
{
    return [
        ['href' => '/#platforms', 'label' => 'Formats'],
        ['href' => '/#features', 'label' => 'Features'],
        ['href' => '/#how-it-works', 'label' => 'How it works'],
        ['href' => '/guides/', 'label' => 'Guides'],
        ['href' => '/#faq', 'label' => 'FAQ'],
    ];
}

function footerCompanyLinks(): array
{
    return [
        ['href' => '/about.php', 'label' => 'About'],
        ['href' => '/#contact', 'label' => 'Contact'],
        ['href' => '/privacy-policy.php', 'label' => 'Privacy Policy'],
        ['href' => '/terms.php', 'label' => 'Terms of Use'],
    ];
}

function trustHighlights(): array
{
    return [
        ['stat' => '0', 'statSuffix' => '', 'title' => 'No login required', 'description' => 'Paste a public video link and download — we never ask for your Facebook credentials.', 'icon' => 'shield-check'],
        ['stat' => '0', 'statSuffix' => '', 'title' => 'Nothing stored', 'description' => 'Links are resolved on the fly and never saved to a database or logged against you.', 'icon' => 'eye-off'],
        ['stat' => '~2', 'statSuffix' => 's', 'title' => 'Built for speed', 'description' => 'Optimized requests mean most links resolve in under two seconds.', 'icon' => 'zap'],
        ['stat' => '$0', 'statSuffix' => '', 'title' => 'Always free', 'description' => 'No subscriptions, no download caps, no hidden fees — ever.', 'icon' => 'gift'],
    ];
}

function supportedPlatforms(): array
{
    return [
        ['name' => 'Facebook Videos', 'detail' => 'Public posts, profile & page uploads', 'icon' => 'facebook'],
        ['name' => 'Facebook Watch', 'detail' => 'Watch-tab videos and shows', 'icon' => 'tv'],
        ['name' => 'Facebook Reels', 'detail' => 'Short-form vertical reels', 'icon' => 'clapperboard'],
        ['name' => 'Facebook Live (replays)', 'detail' => 'Saved replays of past live streams', 'icon' => 'radio'],
        ['name' => 'fb.watch links', 'detail' => 'Shortened share links', 'icon' => 'link-2'],
        ['name' => 'Groups & Pages', 'detail' => 'Public group and page video posts', 'icon' => 'users'],
    ];
}

function keyFeatures(): array
{
    return [
        ['visual' => 'quality', 'title' => 'HD & SD quality options', 'description' => 'Choose the original high-definition file or a smaller standard-definition version to save data.', 'icon' => 'sparkles'],
        ['title' => 'No watermark, ever', 'description' => "You get the exact video Facebook hosts — we don't overlay logos or branding on your download.", 'icon' => 'badge-check'],
        ['title' => 'Works on any device', 'description' => 'A responsive, touch-friendly interface that works identically on phones, tablets, and desktops.', 'icon' => 'smartphone'],
        ['title' => 'No app install', 'description' => 'Runs entirely in your browser. Nothing to download, update, or grant permissions to.', 'icon' => 'globe'],
        ['title' => 'Privacy-first by design', 'description' => 'No account, no tracking pixels on the download flow, and video content never touches our servers longer than it takes to resolve a link.', 'icon' => 'lock'],
        ['visual' => 'preview', 'title' => 'Instant preview', 'description' => "See the thumbnail, title, and duration before you download so you know you've got the right video.", 'icon' => 'play-circle'],
    ];
}

function howItWorksSteps(): array
{
    return [
        ['step' => 1, 'title' => 'Copy the video link', 'description' => 'Open the Facebook video, tap Share, and copy the link — from the app or a browser.', 'icon' => 'copy'],
        ['step' => 2, 'title' => 'Paste it above', 'description' => 'Drop the link into the input field at the top of this page. No sign-in required.', 'icon' => 'clipboard-paste'],
        ['step' => 3, 'title' => 'Pick your quality', 'description' => 'We resolve the video and show you HD and SD download options along with a preview.', 'icon' => 'sliders'],
        ['step' => 4, 'title' => 'Download & enjoy', 'description' => "Tap download and the file saves straight to your device. That's it.", 'icon' => 'download'],
    ];
}

function faqItems(): array
{
    return [
        ['question' => 'Is it legal to download Facebook videos?', 'answer' => "You should only download videos you own, have permission to use, or that are covered by fair use in your jurisdiction. Respect the original creator's rights and Facebook's terms of service."],
        ['question' => 'Do I need to log in to Facebook?', 'answer' => "No. This tool only works with publicly viewable videos, so there's never a reason to enter your Facebook username or password."],
        ['question' => 'Why did my download fail?', 'answer' => "The most common reasons are that the video is private, was deleted, or the link was copied incorrectly. Group and friends-only posts can't be resolved because they aren't publicly accessible."],
        ['question' => "What's the difference between HD and SD?", 'answer' => 'HD is the original higher-resolution upload when Facebook makes it available; SD is a smaller, more compressed version that downloads faster and uses less storage.'],
        ['question' => 'Does this work on mobile?', 'answer' => 'Yes. The site is fully responsive and the download flow works the same way on iOS and Android browsers as it does on desktop.'],
        ['question' => 'Do you store the videos I download?', 'answer' => 'No. We resolve the direct video link per request and stream it straight to your browser — nothing is kept on our servers after your download finishes.'],
    ];
}

function contactChannels(): array
{
    return [
        ['label' => 'General support', 'value' => CONTACT_EMAIL, 'href' => 'mailto:' . CONTACT_EMAIL, 'icon' => 'mail'],
        ['label' => 'Report a bug', 'value' => 'Use the form below', 'href' => '#contact-form', 'icon' => 'bug'],
        ['label' => 'Privacy questions', 'value' => PRIVACY_EMAIL, 'href' => 'mailto:' . PRIVACY_EMAIL, 'icon' => 'shield-question'],
    ];
}

function privacyPoints(): array
{
    return [
        ['title' => 'No credential collection', 'description' => 'We never ask for, request, or store your Facebook login. Downloads work only on public content.', 'icon' => 'key-round'],
        ['title' => 'Ephemeral processing', 'description' => 'Video links are resolved per request and streamed straight through — nothing is written to disk on our servers.', 'icon' => 'timer'],
        ['title' => 'Minimal data collection', 'description' => 'We collect only anonymized, aggregate performance metrics — no personal identifiers, no video URLs are logged.', 'icon' => 'bar-chart'],
        ['title' => 'Encrypted in transit', 'description' => 'Every request between your browser and our servers is encrypted with HTTPS/TLS.', 'icon' => 'lock'],
    ];
}
