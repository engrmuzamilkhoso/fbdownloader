<?php require ROOT_PATH . '/templates/partials/header.php'; ?>
<?php
$guides = guidesIndex();
$heroLabel = 'Guides';
$heroTitle = 'Facebook video download guides';
$heroLead = 'Device-specific walkthroughs and troubleshooting for saving public Facebook videos and Reels — written for people, not search engines.';
$heroMeta = [
    ['icon' => 'book-open', 'label' => count($guides) . ' guides'],
    ['icon' => 'calendar', 'label' => 'Updated ' . date('F Y', strtotime(max(array_column($guides, 'updated'))))],
];
require ROOT_PATH . '/templates/partials/page-hero.php';
?>
<section class="doc-section">
  <div class="container">
    <ul class="guides-grid">
      <?php foreach ($guides as $i => $guide): ?>
        <li class="reveal" style="transition-delay:<?= $i * 70 ?>ms">
          <a href="/guides/<?= e($guide['slug']) ?>.php" class="guide-card-link focus-ring">
            <article class="guide-card">
              <div class="guide-card-top">
                <span class="icon-badge<?= $i % 2 ? ' icon-badge-accent' : '' ?>"><?= icon($guide['icon'] ?? 'book-open', 'icon') ?></span>
                <span class="guide-card-num">Guide <?= sprintf('%02d', $i + 1) ?></span>
              </div>
              <h2 class="card-title"><?= e($guide['title']) ?></h2>
              <p class="card-desc"><?= e($guide['description']) ?></p>
              <div class="guide-card-foot">
                <span class="guide-meta"><?= e(guideReadingTime($guide['slug'])) ?> min read · <?= e(date('M Y', strtotime($guide['updated']))) ?></span>
                <span class="guide-arrow" aria-hidden="true"><?= icon('arrow-right', 'icon') ?></span>
              </div>
            </article>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php require ROOT_PATH . '/templates/partials/footer.php'; ?>
