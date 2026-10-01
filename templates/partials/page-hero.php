<?php
/**
 * Inner-page header band.
 * @var string $heroLabel   Mono kicker above the title (e.g. "Legal")
 * @var string $heroTitle   The page's <h1>
 * @var string $heroLead    Optional intro paragraph
 * @var array  $heroMeta    Optional list of ['icon' => ..., 'label' => ...] pills
 */
?>
<header class="page-hero">
  <div class="container">
    <?php require ROOT_PATH . '/templates/partials/breadcrumbs.php'; ?>
    <p class="kicker"><span class="kicker-num"><?= e($heroLabel) ?></span></p>
    <h1><?= e($heroTitle) ?></h1>
    <?php if (!empty($heroLead)): ?>
      <p class="page-hero-lead"><?= e($heroLead) ?></p>
    <?php endif; ?>
    <?php if (!empty($heroMeta)): ?>
      <div class="page-meta">
        <?php foreach ($heroMeta as $meta): ?>
          <span class="meta-pill"><?= icon($meta['icon'], 'icon') ?> <?= e($meta['label']) ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</header>
