<?php require ROOT_PATH . '/templates/partials/header.php'; ?>
<?php
$heroLabel = 'About';
$heroTitle = 'A small tool that does one thing properly.';
$heroLead = SITE_NAME . ' turns a public Facebook video link into a file on your device — without logins, installs, pop-ups, or a pile of fake download buttons.';
$heroMeta = [];
require ROOT_PATH . '/templates/partials/page-hero.php';
?>
<section class="doc-section">
  <div class="container doc-layout">
    <aside class="doc-aside" aria-label="On this page">
      <div class="doc-aside-inner">
        <p class="toc-title">On this page</p>
        <nav class="toc-list" data-toc></nav>
        <div class="aside-card">
          <p><strong>Try it now</strong>Paste any public Facebook video link and see for yourself.</p>
          <a href="/#top" class="btn btn-primary btn-sm">Open the downloader</a>
        </div>
      </div>
    </aside>

    <div class="prose" data-toc-source>
      <p class="prose-lead"><?= e(SITE_NAME) ?> is a small, focused tool that does one thing well: turn a public Facebook video link into a downloadable file, in seconds, without asking you to log in, install anything, or hand over personal data.</p>

      <h2>Why we built it</h2>
      <p>Saving a copy of a public video you have permission to use — a family clip, a talk you gave, a post from your own page — shouldn't require an account, a browser extension, or wading through ad-heavy sites full of misleading download buttons. We wanted something fast, honest, and easy to trust.</p>
      <p>Most "free" video downloaders make their money by making the experience worse: redirect chains, notification-permission prompts, bundled installers, and download buttons that aren't really download buttons. We decided to build the opposite.</p>

      <h2>What we stand for</h2>
      <div class="value-grid">
        <div class="value-card">
          <span class="icon-badge"><?= icon('zap', 'icon') ?></span>
          <h3>Speed over everything</h3>
          <p>One page, one paste, one click. Most links resolve in about two seconds.</p>
        </div>
        <div class="value-card">
          <span class="icon-badge icon-badge-accent"><?= icon('eye-off', 'icon') ?></span>
          <h3>Privacy by default</h3>
          <p>No accounts, no stored links, no video files kept on our servers.</p>
        </div>
        <div class="value-card">
          <span class="icon-badge"><?= icon('heart', 'icon') ?></span>
          <h3>Respect for creators</h3>
          <p>Built for content you own or are allowed to use — not for reposting other people's work.</p>
        </div>
      </div>

      <h2>How it works, briefly</h2>
      <p>When you paste a link, our server fetches the public video's metadata and available quality options using an open-source, actively maintained media-extraction engine, then streams the file straight to your browser. Nothing is stored on our end after your download finishes.</p>
      <ol>
        <li>You paste a public Facebook video, Reel, or Watch link.</li>
        <li>We resolve the available HD and SD versions and show you a preview.</li>
        <li>You pick a quality and the file streams directly to your device.</li>
      </ol>
      <p>You can read the full technical and legal details on our <a href="/privacy-policy.php">Privacy Policy</a> and <a href="/terms.php">Terms of Use</a> pages.</p>

      <h2>What we don't do</h2>
      <ul>
        <li>We never ask for your Facebook login — the tool only works with public content.</li>
        <li>We don't keep copies of the videos you download.</li>
        <li>We don't add watermarks, logos, or intros to your files.</li>
        <li>We don't sell or share the links you submit.</li>
      </ul>

      <h2>Need step-by-step help?</h2>
      <p>Our <a href="/guides/">guides section</a> covers device-specific instructions for iPhone and Android, downloading Reels, and fixing the most common download errors.</p>

      <h2>Responsible use</h2>
      <p>This tool is meant for downloading content you own, have explicit permission to use, or that you're otherwise legally entitled to save. Please respect creators' rights and Facebook's own Terms of Service when using the Service. <?= e(SITE_NAME) ?> is an independent project and is not affiliated with or endorsed by Meta Platforms, Inc.</p>

      <h2>Get in touch</h2>
      <p>Have feedback, found a bug, or have a question we haven't answered? Visit our <a href="/#contact">Contact section</a> or email <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>
    </div>
  </div>
</section>
<?php require ROOT_PATH . '/templates/partials/footer.php'; ?>
