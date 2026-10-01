<section aria-label="Why people trust this tool" class="stats-strip">
  <div class="container">
    <ul class="stats-grid">
      <?php foreach (trustHighlights() as $i => $item): ?>
        <li class="reveal" style="transition-delay:<?= $i * 70 ?>ms">
          <div class="stat-item">
            <p class="stat-value"><span><?= e($item['stat']) ?></span><?= e($item['statSuffix']) ?></p>
            <h3 class="stat-title"><?= icon($item['icon'], 'icon') ?> <?= e($item['title']) ?></h3>
            <p class="stat-desc"><?= e($item['description']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
