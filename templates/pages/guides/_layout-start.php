<?php require ROOT_PATH . '/templates/partials/header.php'; ?>
<?php
$heroLabel = 'Guide';
$heroTitle = $guide['title'];
$heroLead = $guide['description'];
$heroMeta = [
    ['icon' => 'calendar', 'label' => 'Updated ' . date('F Y', strtotime($guide['updated']))],
    ['icon' => 'clock', 'label' => guideReadingTime($guide['slug']) . ' min read'],
];
require ROOT_PATH . '/templates/partials/page-hero.php';
?>
<section class="doc-section">
  <div class="container doc-layout">
    <aside class="doc-aside" aria-label="In this guide">
      <div class="doc-aside-inner">
        <p class="toc-title">In this guide</p>
        <nav class="toc-list" data-toc></nav>
        <div class="aside-card">
          <p><strong>Ready to try?</strong>Paste your link on the homepage — it takes a few seconds.</p>
          <a href="/#top" class="btn btn-primary btn-sm">Open the downloader</a>
        </div>
      </div>
    </aside>
    <article class="prose" data-toc-source>
