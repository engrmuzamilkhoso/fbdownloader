      <div class="guide-cta">
        <div>
          <h2>Got your link ready?</h2>
          <p>Paste it into <?= e(SITE_NAME) ?> and pick HD or SD — no login, no watermark.</p>
        </div>
        <a href="/#top" class="btn btn-light btn-arrow">Download now <?= icon('arrow-right', 'icon') ?></a>
      </div>

      <nav class="related-guides" aria-labelledby="related-heading">
        <h2 id="related-heading" data-toc-skip>More guides</h2>
        <ul class="related-list">
          <?php foreach (guidesIndex() as $related): ?>
            <?php if ($related['slug'] === $guide['slug']) { continue; } ?>
            <li>
              <a href="/guides/<?= e($related['slug']) ?>.php" class="related-link focus-ring">
                <?= e($related['title']) ?> <?= icon('arrow-right', 'icon') ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <p class="guide-back"><a href="/guides/">← Back to all guides</a></p>
    </article>
  </div>
</section>
<?php $hideCtaBand = true; ?>
<?php require ROOT_PATH . '/templates/partials/footer.php'; ?>
