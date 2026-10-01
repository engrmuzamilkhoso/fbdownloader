<section id="features" aria-labelledby="features-heading" class="section section-alt">
  <div class="container">
    <div class="section-head section-head-split">
      <p class="kicker"><span class="kicker-num">02</span> Why it's better</p>
      <h2 id="features-heading">The downloader that skips the nonsense.</h2>
      <p class="section-lead">No pop-ups, no fake download buttons, no redirect chains. One page, one paste, one file — exactly the way a free tool should work.</p>
    </div>

    <ul class="bento">
      <?php foreach (keyFeatures() as $i => $feature): ?>
        <?php $visual = $feature['visual'] ?? null; ?>
        <li class="reveal<?= $visual === 'quality' ? ' bento-tall' : ($visual ? ' bento-wide' : '') ?>" style="transition-delay:<?= ($i % 3) * 70 ?>ms">
          <div class="card card-hover">
            <div>
              <span class="feature-num"><?= sprintf('%02d', $i + 1) ?></span>
              <span class="icon-badge<?= $i % 2 ? ' icon-badge-accent' : '' ?>"><?= icon($feature['icon'], 'icon') ?></span>
              <h3 class="card-title"><?= e($feature['title']) ?></h3>
              <p class="card-desc"><?= e($feature['description']) ?></p>
            </div>
            <?php if ($visual === 'quality'): ?>
              <div class="feature-visual" aria-hidden="true">
                <div class="quality-row is-best"><span class="quality-tag">HD</span><span class="quality-label">1080p · Original</span><span class="quality-size">48.2 MB</span></div>
                <div class="quality-row"><span class="quality-tag">SD</span><span class="quality-label">360p · Data saver</span><span class="quality-size">12.6 MB</span></div>
              </div>
            <?php elseif ($visual === 'preview'): ?>
              <div class="feature-visual" aria-hidden="true">
                <div class="preview-strip"><span></span><span></span><span></span></div>
              </div>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
      <li class="reveal" style="transition-delay:140ms">
        <div class="cta-tile">
          <div>
            <h3>Try it on your next video.</h3>
            <p>It takes less time than reading this sentence twice.</p>
          </div>
          <a href="#top" class="btn btn-light btn-arrow" data-focus-download>Paste a link <?= icon('arrow-right', 'icon') ?></a>
        </div>
      </li>
    </ul>
  </div>
</section>
