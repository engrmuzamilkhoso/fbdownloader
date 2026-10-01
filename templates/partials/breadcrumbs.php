<?php
/** @var array<int, array{label: string, href: string}> $breadcrumbs */
if (empty($breadcrumbs)) {
    return;
}
?>
<nav aria-label="Breadcrumb" class="breadcrumbs">
  <div class="container">
    <ol>
      <?php foreach ($breadcrumbs as $i => $crumb): ?>
        <li>
          <?php if ($i === count($breadcrumbs) - 1): ?>
            <span aria-current="page"><?= e($crumb['label']) ?></span>
          <?php else: ?>
            <a href="<?= e($crumb['href']) ?>" class="focus-ring"><?= e($crumb['label']) ?></a>
            <span class="breadcrumb-sep" aria-hidden="true">/</span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</nav>
