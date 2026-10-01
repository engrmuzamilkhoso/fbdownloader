<?php require ROOT_PATH . '/templates/partials/header.php'; ?>
<section class="not-found">
  <div class="container not-found-inner">
    <p class="not-found-code" aria-hidden="true">404</p>
    <h1>This page went private.</h1>
    <p>The page you're looking for doesn't exist or has moved. Unlike a private video, though, we can point you somewhere useful.</p>
    <div class="not-found-actions">
      <a href="/" class="btn btn-primary"><?= icon('home', 'icon') ?> Back to the downloader</a>
      <a href="/guides/" class="btn btn-outline"><?= icon('book-open', 'icon') ?> Browse guides</a>
    </div>

    <div class="not-found-links">
      <p class="toc-title">Popular pages</p>
      <ul>
        <?php foreach (guidesIndex() as $guide): ?>
          <li>
            <a href="/guides/<?= e($guide['slug']) ?>.php" class="related-link focus-ring">
              <?= e($guide['short'] ?? $guide['title']) ?> <?= icon('arrow-right', 'icon') ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
<?php $hideCtaBand = true; ?>
<?php require ROOT_PATH . '/templates/partials/footer.php'; ?>
