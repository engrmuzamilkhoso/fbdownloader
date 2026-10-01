<?php

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/guides_content.php';

header('Content-Type: application/xml; charset=utf-8');

$pages = [
    ['path' => '/', 'priority' => '1.0', 'lastmod' => null],
    ['path' => '/guides/', 'priority' => '0.7', 'lastmod' => null],
    ['path' => '/about.php', 'priority' => '0.6', 'lastmod' => null],
    ['path' => '/privacy-policy.php', 'priority' => '0.4', 'lastmod' => null],
    ['path' => '/terms.php', 'priority' => '0.4', 'lastmod' => null],
];

foreach (guidesIndex() as $guide) {
    $pages[] = ['path' => '/guides/' . $guide['slug'] . '.php', 'priority' => '0.6', 'lastmod' => $guide['updated']];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $page): ?>
  <url>
    <loc><?= htmlspecialchars(SITE_URL . $page['path'], ENT_QUOTES | ENT_XML1, 'UTF-8') ?></loc>
    <?php if ($page['lastmod']): ?><lastmod><?= htmlspecialchars($page['lastmod'], ENT_QUOTES | ENT_XML1, 'UTF-8') ?></lastmod><?php endif; ?>
    <priority><?= $page['priority'] ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
