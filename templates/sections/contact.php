<section id="contact" aria-labelledby="contact-heading" class="section">
  <div class="container">
    <div class="section-head">
      <p class="kicker"><span class="kicker-num">07</span> Contact</p>
      <h2 id="contact-heading">Found a bug? Got an idea? Tell us.</h2>
      <p class="section-lead">A real person reads every message — especially the ones about links that didn't work.</p>
    </div>

    <div class="contact-grid">
      <div>
        <ul class="contact-channels">
          <?php foreach (contactChannels() as $channel): ?>
            <li>
              <a href="<?= e($channel['href']) ?>" class="contact-channel focus-ring">
                <span class="icon-badge"><?= icon($channel['icon'], 'icon') ?></span>
                <span>
                  <span class="contact-channel-label"><?= e($channel['label']) ?></span>
                  <span class="contact-channel-value"><?= e($channel['value']) ?></span>
                </span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="contact-response">
          <?= icon('clock', 'icon') ?>
          <p><strong>Reporting a broken link?</strong>Include the URL (or describe the post) and which device you used — it helps us reproduce the issue fast.</p>
        </div>
      </div>

      <form id="contact-form" class="contact-form" novalidate>
        <div class="form-grid-2">
          <div class="form-field">
            <label for="contact-name">Name</label>
            <input id="contact-name" name="name" type="text" required autocomplete="name" placeholder="Your name">
          </div>
          <div class="form-field">
            <label for="contact-email">Email</label>
            <input id="contact-email" name="email" type="email" required autocomplete="email" placeholder="you@example.com">
          </div>
        </div>
        <div class="form-field">
          <label for="contact-message">Message</label>
          <textarea id="contact-message" name="message" required minlength="10" rows="5" placeholder="What can we help with?"></textarea>
        </div>

        <p class="form-error" role="alert" hidden><span class="form-error-text"></span></p>

        <button type="submit" class="btn btn-primary contact-submit">
          <span class="btn-icon-default"><?= icon('send', 'icon') ?> Send message</span>
          <span class="btn-icon-loading" hidden><?= icon('loader', 'icon spin') ?> Sending…</span>
        </button>

        <div class="contact-success" hidden>
          <?= icon('check-circle', 'icon icon-lg') ?>
          <p class="contact-success-title">Message sent</p>
          <p class="contact-success-desc">Thanks for reaching out — we'll get back to you shortly.</p>
          <button type="button" class="btn btn-outline btn-sm contact-reset">Send another message</button>
        </div>
      </form>
    </div>
  </div>
</section>
