<section id="how-it-works" aria-labelledby="how-it-works-heading" class="section section-alt">
  <div class="container steps-layout">
    <div class="section-head">
      <p class="kicker"><span class="kicker-num">04</span> How it works</p>
      <h2 id="how-it-works-heading">Four steps. Zero sign-ups.</h2>
      <p class="section-lead">From link to local file in well under a minute — on a phone, tablet, or laptop.</p>
      <div class="steps-cta">
        <a href="/guides/" class="btn btn-outline btn-sm btn-arrow"><?= icon('book-open', 'icon') ?> Device guides <?= icon('arrow-right', 'icon') ?></a>
        <a href="#top" class="btn btn-primary btn-sm" data-focus-download><?= icon('download', 'icon') ?> Try it now</a>
      </div>
    </div>

    <ol class="steps-grid">
      <?php foreach (howItWorksSteps() as $i => $step): ?>
        <li class="reveal" style="transition-delay:<?= $i * 90 ?>ms">
          <div class="step-item">
            <span class="step-number"><?= sprintf('%02d', (int) $step['step']) ?></span>
            <div class="step-body">
              <h3 class="step-title"><?= icon($step['icon'], 'icon') ?> <?= e($step['title']) ?></h3>
              <p class="step-desc"><?= e($step['description']) ?></p>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
