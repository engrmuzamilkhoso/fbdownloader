<section id="platforms" aria-labelledby="platforms-heading" class="section">
  <div class="container platforms-layout">
    <div class="section-head section-head-center">
      <p class="kicker"><span class="kicker-num">01</span> Supported formats</p>
      <h2 id="platforms-heading">If it plays on Facebook, you can keep it.</h2>
      <p class="section-lead">Feed posts, Reels, Watch shows, live replays — every public format is resolved through the same one-step flow.</p>
      <p class="platform-note"><?= icon('alert-circle', 'icon') ?><span>Private, friends-only, and closed-group videos can't be fetched by any tool. <a href="/guides/fix-facebook-video-download-errors.php">Here's how to tell</a>.</span></p>
    </div>

    <ul class="platform-list">
      <?php foreach (supportedPlatforms() as $i => $platform): ?>
        <li class="reveal" style="transition-delay:<?= $i * 50 ?>ms">
          <div class="platform-item">
            <span class="icon-badge"><?= icon($platform['icon'], 'icon') ?></span>
            <span>
              <span class="platform-name"><?= e($platform['name']) ?></span>
              <span class="platform-detail"><?= e($platform['detail']) ?></span>
            </span>
            <span class="platform-check" aria-label="Supported"><?= icon('check-circle', 'icon') ?></span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
