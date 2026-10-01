<?php
$year = date('Y');
$isHome = ($canonicalPath ?? '') === '/';
?>
<?php if (empty($hideCtaBand)): ?>
<section class="cta-band-wrap" aria-labelledby="cta-band-heading">
  <div class="container">
    <div class="cta-band reveal">
      <div>
        <h2 id="cta-band-heading">Got a Facebook link? Paste it.</h2>
        <p>HD or SD, straight to your device in a few seconds. No account, no app, no watermark.</p>
      </div>
      <a href="<?= $isHome ? '#top' : '/#top' ?>" class="btn btn-light btn-lg btn-arrow" data-focus-download>
        Start downloading <?= icon('arrow-right', 'icon') ?>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>
</main>
<footer class="site-footer">
  <div class="container">
    <div class="footer-inner">
      <div class="footer-brand">
        <?php require ROOT_PATH . '/templates/partials/brand.php'; ?>
        <p class="footer-tagline"><?= e(SITE_DESCRIPTION) ?></p>
        <span class="footer-status">All systems operational</span>
      </div>

      <nav aria-label="Product">
        <p class="footer-col-title">Product</p>
        <div class="footer-links">
          <?php foreach (navLinks() as $link): ?>
            <a href="<?= e($link['href']) ?>" class="footer-link focus-ring"><?= e($link['label']) ?></a>
          <?php endforeach; ?>
        </div>
      </nav>

      <nav aria-label="Guides">
        <p class="footer-col-title">Guides</p>
        <div class="footer-links">
          <?php foreach (guidesIndex() as $guide): ?>
            <a href="/guides/<?= e($guide['slug']) ?>.php" class="footer-link focus-ring"><?= e($guide['short'] ?? $guide['title']) ?></a>
          <?php endforeach; ?>
        </div>
      </nav>

      <nav aria-label="Company">
        <p class="footer-col-title">Company</p>
        <div class="footer-links">
          <?php foreach (footerCompanyLinks() as $link): ?>
            <a href="<?= e($link['href']) ?>" class="footer-link focus-ring"><?= e($link['label']) ?></a>
          <?php endforeach; ?>
        </div>
      </nav>
    </div>

    <div class="footer-bottom">
      <p>© <?= e((string) $year) ?> <?= e(SITE_NAME) ?>. Not affiliated with or endorsed by Meta Platforms, Inc.</p>
      <p>Built for downloading content you own or have permission to use.</p>
    </div>

    <p class="footer-wordmark" aria-hidden="true"><?= e(SITE_NAME) ?></p>
  </div>
</footer>

<script src="<?= e(assetUrl('/assets/js/consent.js')) ?>"></script>
<script src="<?= e(assetUrl('/assets/js/app.js')) ?>" defer></script>
</body>
</html>
