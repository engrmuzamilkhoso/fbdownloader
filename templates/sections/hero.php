<section id="top" class="hero">
  <div class="hero-bg-grid" aria-hidden="true"></div>
  <div class="hero-bg-glow" aria-hidden="true"></div>
  <div class="hero-bg-glow-2" aria-hidden="true"></div>

  <div class="container hero-inner">
    <div class="hero-copy">
      <span class="hero-badge animate-fade-in">
        <span class="hero-badge-tag">Free</span>
        <span class="hero-badge-dot" aria-hidden="true"></span>
        No sign-up · No watermark · No app
      </span>

      <h1 class="hero-title animate-fade-up">
        Save any Facebook video in
        <span class="hero-title-accent"><em>one paste.</em><svg viewBox="0 0 200 20" preserveAspectRatio="none" aria-hidden="true"><path d="M2 14C40 4 120 2 198 10" stroke="currentColor" stroke-width="9" stroke-linecap="round" fill="none"/></svg></span>
      </h1>

      <p class="hero-subtitle animate-fade-up" style="animation-delay:80ms">
        Drop in a public video, Reel, or Watch link and walk away with the original HD file — or a lighter SD copy — in a few seconds.
      </p>

      <div class="hero-form-wrap animate-fade-up" style="animation-delay:160ms">
        <form id="download-form" novalidate>
          <div class="download-shell">
            <div class="download-input-row">
              <div class="download-input-field">
                <label for="download-input" class="sr-only">Facebook video link</label>
                <span class="download-input-icon"><?= icon('link-2', 'icon') ?></span>
                <input
                  id="download-input"
                  name="url"
                  type="url"
                  inputmode="url"
                  autocomplete="off"
                  autocapitalize="off"
                  spellcheck="false"
                  placeholder="https://www.facebook.com/watch?v=…"
                  class="download-input"
                  aria-describedby="download-error download-hint"
                >
                <button type="button" id="paste-btn" class="paste-btn focus-ring" aria-label="Paste from clipboard">
                  <?= icon('clipboard-paste', 'icon') ?><span class="hide-mobile">Paste</span>
                </button>
              </div>

              <button type="submit" id="download-submit" class="btn btn-primary btn-lg download-submit">
                <span class="btn-icon-default"><?= icon('download', 'icon') ?> Download</span>
                <span class="btn-icon-loading" hidden><?= icon('loader', 'icon spin') ?> Resolving…</span>
              </button>
            </div>
          </div>

          <div class="form-status" aria-live="polite">
            <p id="download-error" class="form-error" role="alert" hidden>
              <?= icon('alert-circle', 'icon') ?>
              <span class="form-error-text"></span>
            </p>
          </div>
          <p id="download-hint" class="form-hint">Works with <code>facebook.com</code>, <code>fb.watch</code> and <code>m.facebook.com</code> links.</p>
        </form>

        <div id="download-result" class="download-result" hidden></div>
      </div>

      <ul class="hero-trust-points animate-fade-up" style="animation-delay:220ms">
        <li><?= icon('check-circle', 'icon') ?> No login required</li>
        <li><?= icon('check-circle', 'icon') ?> Resolves in seconds</li>
        <li><?= icon('check-circle', 'icon') ?> Nothing stored</li>
      </ul>
    </div>

    <div class="hero-visual reveal" aria-hidden="true">
      <div class="mock-window">
        <div class="mock-bar">
          <span></span><span></span><span></span>
          <div class="mock-url"><?= icon('lock', 'icon') ?> facebook.com/reel/8213…</div>
        </div>
        <div class="mock-thumb">
          <span class="mock-chip-hd">HD</span>
          <span class="mock-play"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 4.5v15l12.5-7.5z"/></svg></span>
          <span class="mock-duration">03:42</span>
        </div>
        <div class="mock-side">
          <div class="mock-meta">
            <p class="mock-meta-title">Morning trail through the hills 🌿</p>
            <p class="mock-meta-sub">Ready to download · 2 formats found</p>
          </div>
          <div class="mock-options">
            <div class="mock-opt is-best"><strong>HD · 1080p</strong>48.2 MB</div>
            <div class="mock-opt"><strong>SD · 360p</strong>12.6 MB</div>
          </div>
          <div class="mock-progress"><span></span></div>
          <div class="mock-progress-label"><span>Downloading…</span><span>HD</span></div>
        </div>
      </div>
      <span class="hero-sticker"><?= icon('badge-check', 'icon') ?> No watermark</span>
      <div class="hero-toast">
        <span class="hero-toast-icon"><?= icon('check', 'icon') ?></span>
        <div><strong>Saved to Downloads</strong><span>morning-trail.mp4</span></div>
      </div>
    </div>
  </div>
</section>

<div class="ticker-wrap" aria-hidden="true">
<div class="ticker">
  <div class="ticker-track">
    <?php for ($copy = 0; $copy < 2; $copy++): ?>
      <ul class="ticker-list">
        <?php foreach (['Facebook Videos', 'Reels', 'Facebook Watch', 'Live replays', 'fb.watch links', 'Page videos', 'Public groups', 'HD 1080p', 'SD 360p', 'No watermark'] as $label): ?>
          <li><?= e($label) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endfor; ?>
  </div>
</div>
</div>
