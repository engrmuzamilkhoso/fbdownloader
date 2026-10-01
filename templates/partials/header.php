<?php
/** @var string $pageTitle */
/** @var string $pageDescription */
/** @var string $canonicalPath */
require_once ROOT_PATH . '/helpers/icons.php';
trackPageView();

$canonicalUrl = SITE_URL . $canonicalPath;
$fullTitle = $pageTitle === SITE_NAME ? $pageTitle . ' — Save Facebook Videos & Reels in HD, Free' : $pageTitle . ' — ' . SITE_NAME;
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<link rel="canonical" href="<?= e($canonicalUrl) ?>">
<meta name="robots" content="index, follow">
<?php if (GOOGLE_SITE_VERIFICATION !== ''): ?>
<meta name="google-site-verification" content="<?= e(GOOGLE_SITE_VERIFICATION) ?>">
<?php endif; ?>
<?php if (BING_SITE_VERIFICATION !== ''): ?>
<meta name="msvalidate.01" content="<?= e(BING_SITE_VERIFICATION) ?>">
<?php endif; ?>
<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#070d0b" media="(prefers-color-scheme: dark)">
<meta name="csrf-token" content="<?= e(csrfToken()) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($pageDescription) ?>">
<meta property="og:url" content="<?= e($canonicalUrl) ?>">
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/images/og-image.png">
<meta property="og:locale" content="en_US">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($fullTitle) ?>">
<meta name="twitter:description" content="<?= e($pageDescription) ?>">
<link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/assets/images/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/assets/images/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
<link rel="manifest" href="/assets/manifest.webmanifest">
<link rel="preload" href="/assets/fonts/bricolage-grotesque.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/dm-sans.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(assetUrl('/assets/css/styles.css')) ?>">
<script>
(function () {
  try {
    var stored = localStorage.getItem('theme');
    var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.dataset.theme = theme;
  } catch (e) {}
})();
window.FBVIDEO_ADSENSE = { enabled: <?= ADSENSE_ENABLED && ADSENSE_CLIENT_ID !== '' ? 'true' : 'false' ?>, clientId: <?= json_encode(ADSENSE_CLIENT_ID) ?> };
</script>
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => SITE_NAME,
    'applicationCategory' => 'MultimediaApplication',
    'operatingSystem' => 'Any',
    'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
    'description' => SITE_DESCRIPTION,
    'url' => SITE_URL,
], JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => SITE_NAME,
    'url' => SITE_URL,
    'logo' => SITE_URL . '/assets/images/apple-touch-icon.png',
], JSON_UNESCAPED_SLASHES) ?></script>
<?php if (!empty($breadcrumbs)): ?>
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_map(fn ($crumb, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $crumb['label'],
        'item' => SITE_URL . $crumb['href'],
    ], $breadcrumbs, array_keys($breadcrumbs)),
], JSON_UNESCAPED_SLASHES) ?></script>
<?php endif; ?>
<?php if (!empty($extraJsonLd)): ?>
<script type="application/ld+json"><?= json_encode($extraJsonLd, JSON_UNESCAPED_SLASHES) ?></script>
<?php endif; ?>
</head>
<body>
<a href="#main-content" class="skip-link">Skip to main content</a>
<?php require ROOT_PATH . '/templates/partials/consent-banner.php'; ?>
<header class="site-header" id="site-header">
  <div class="container header-inner">
    <?php require ROOT_PATH . '/templates/partials/brand.php'; ?>

    <nav aria-label="Primary" class="nav-links">
      <?php foreach (navLinks() as $link): ?>
        <a href="<?= e($link['href']) ?>" class="nav-link focus-ring"><?= e($link['label']) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="header-actions">
      <button type="button" class="theme-toggle focus-ring" id="theme-toggle" aria-label="Toggle dark mode">
        <span class="theme-icon theme-icon-sun"><?= icon('sun', 'icon') ?></span>
        <span class="theme-icon theme-icon-moon"><?= icon('moon', 'icon') ?></span>
      </button>
      <a href="/#top" class="btn btn-ink btn-sm hide-mobile" id="get-started-btn"><?= icon('download', 'icon') ?> Download a video</a>
      <button type="button" class="menu-toggle focus-ring hide-desktop" id="menu-toggle" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu">
        <span class="menu-icon-open"><?= icon('menu', 'icon') ?></span>
        <span class="menu-icon-close"><?= icon('x', 'icon') ?></span>
      </button>
    </div>
  </div>

  <nav id="mobile-nav" aria-label="Mobile" class="mobile-nav" hidden>
    <div class="container mobile-nav-inner">
      <?php foreach (navLinks() as $link): ?>
        <a href="<?= e($link['href']) ?>" class="mobile-nav-link focus-ring"><?= e($link['label']) ?></a>
      <?php endforeach; ?>
      <a href="/#contact" class="mobile-nav-link focus-ring">Contact</a>
    </div>
  </nav>
</header>
<main id="main-content">
