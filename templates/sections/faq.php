<?php $faqs = faqItems(); ?>
<section id="faq" aria-labelledby="faq-heading" class="section">
  <script type="application/ld+json"><?= json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => array_map(fn ($item) => [
          '@type' => 'Question',
          'name' => $item['question'],
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
      ], $faqs),
  ], JSON_UNESCAPED_SLASHES) ?></script>

  <div class="container faq-layout">
    <div class="section-head section-head-center">
      <p class="kicker"><span class="kicker-num">05</span> FAQ</p>
      <h2 id="faq-heading">Questions, answered straight.</h2>
      <p class="section-lead">The things people ask most before (and after) their first download.</p>
    </div>

    <div class="accordion" id="faq-accordion">
      <?php foreach ($faqs as $i => $item): ?>
        <?php $panelId = "faq-panel-{$i}"; $buttonId = "faq-button-{$i}"; ?>
        <div class="accordion-item">
          <h3>
            <button
              type="button"
              id="<?= e($buttonId) ?>"
              class="accordion-trigger focus-ring"
              aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
              aria-controls="<?= e($panelId) ?>"
            >
              <span><?= e($item['question']) ?></span>
              <?= icon('chevron-down', 'icon accordion-chevron') ?>
            </button>
          </h3>
          <div id="<?= e($panelId) ?>" role="region" aria-labelledby="<?= e($buttonId) ?>" class="accordion-panel" <?= $i === 0 ? '' : 'style="grid-template-rows:0fr"' ?>>
            <div class="accordion-panel-inner">
              <p><?= e($item['answer']) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="help-card">
      <div>
        <p class="help-card-title">Link not working?</p>
        <p>Most failures come down to privacy settings or a mangled share link. Our troubleshooting guide walks through each error.</p>
      </div>
      <a href="/guides/fix-facebook-video-download-errors.php" class="btn btn-outline btn-sm btn-arrow">Fix download errors <?= icon('arrow-right', 'icon') ?></a>
    </div>
  </div>
</section>
