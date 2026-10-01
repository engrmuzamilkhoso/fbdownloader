<section id="privacy" aria-labelledby="privacy-heading" class="section section-ink">
  <div class="container">
    <div class="section-head section-head-split">
      <p class="kicker"><span class="kicker-num">06</span> Privacy &amp; security</p>
      <h2 id="privacy-heading">We can't leak what we never keep.</h2>
      <p class="section-lead">Collect the minimum, store nothing, encrypt everything in transit. That's the whole policy in one line — the long version is in our <a href="/privacy-policy.php">Privacy Policy</a>.</p>
    </div>

    <ul class="ink-grid">
      <?php foreach (privacyPoints() as $i => $point): ?>
        <li class="reveal" style="transition-delay:<?= $i * 70 ?>ms">
          <div class="ink-item">
            <span class="icon-badge"><?= icon($point['icon'], 'icon') ?></span>
            <div>
              <h3><?= e($point['title']) ?></h3>
              <p><?= e($point['description']) ?></p>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
