<?php require ROOT_PATH . '/templates/partials/header.php'; ?>
<?php
$heroLabel = 'Legal';
$heroTitle = $pageTitle;
$heroLead = 'What we collect when you use ' . SITE_NAME . ', why, and the choices you have. Short version: as little as possible.';
$heroMeta = [['icon' => 'calendar', 'label' => 'Last updated ' . date('F j, Y')], ['icon' => 'clock', 'label' => '4 min read']];
require ROOT_PATH . '/templates/partials/page-hero.php';
?>
<section class="doc-section">
  <div class="container doc-layout">
    <aside class="doc-aside" aria-label="On this page">
      <div class="doc-aside-inner">
        <p class="toc-title">On this page</p>
        <nav class="toc-list" data-toc></nav>
        <div class="aside-card">
          <p><strong>Questions?</strong>We're happy to explain anything on this page in plain English.</p>
          <a href="/#contact" class="btn btn-outline btn-sm">Contact us</a>
        </div>
      </div>
    </aside>
    <div class="prose" data-toc-source>
    <p><?= e(SITE_NAME) ?> ("we", "us", or "our") operates <?= e(SITE_URL) ?> (the "Service"). This page explains what information we collect when you use the Service, how we use it, and the choices you have.</p>

    <h2>1. Information we collect</h2>
    <p>The Service is designed to work without an account. We do not ask for, and you should never need to provide, your Facebook username, password, or any other social media credentials.</p>
    <ul>
      <li><strong>Video links you submit.</strong> When you paste a link, it is sent to our server so we can resolve a downloadable file. We do not store submitted links after the request completes, and we do not associate them with any personal identifier.</li>
      <li><strong>Basic technical data.</strong> Like virtually all web servers, ours automatically logs standard connection information (such as IP address, browser user agent, and timestamps) for security, abuse prevention, and rate limiting. These logs are kept only as long as necessary for that purpose.</li>
      <li><strong>Contact form submissions.</strong> If you email us or use the contact form, we receive the name, email address, and message you choose to provide, solely to respond to your inquiry.</li>
      <li><strong>Cookies.</strong> We use a single first-party session cookie to keep the site secure (CSRF protection) and to remember your light/dark theme preference. We do not use tracking or advertising cookies unless explicitly noted below.</li>
    </ul>

    <h2>2. How we use information</h2>
    <ul>
      <li>To operate, maintain, and secure the Service.</li>
      <li>To detect, prevent, and respond to abuse, fraud, or excessive automated use (rate limiting).</li>
      <li>To respond to support requests sent through the contact form or email.</li>
      <li>To understand aggregate, anonymized usage trends (e.g., total requests per day) so we can improve performance. These aggregate statistics cannot be traced back to an individual user.</li>
    </ul>

    <h2>3. What we don't do</h2>
    <ul>
      <li>We don't require or store Facebook login credentials.</li>
      <li>We don't sell your personal information.</li>
      <li>We don't retain a permanent copy of the videos you download — files are streamed from the source to your browser and are not stored on our servers.</li>
      <li>We don't build advertising profiles from the links you submit.</li>
    </ul>

    <?php if (ADSENSE_ENABLED): ?>
    <h2>4. Advertising & third-party cookies</h2>
    <p>We use Google AdSense to display advertising on this Service. Google, as a third-party vendor, uses cookies to serve ads based on your prior visits to this and other websites. Google's use of advertising cookies enables it and its partners to serve ads based on your visit to this site and/or other sites on the internet.</p>
    <p>You may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" rel="noopener noreferrer" target="_blank">Google's Ads Settings</a>. For more detail on how Google uses data when you use our Service, see <a href="https://policies.google.com/technologies/partner-sites" rel="noopener noreferrer" target="_blank">How Google uses information from sites or apps that use our services</a>.</p>
    <?php endif; ?>

    <h2>5. Data retention</h2>
    <p>Connection and security logs are retained for a limited period (typically no more than 30 days) and then deleted or anonymized. Contact form messages are kept only as long as needed to resolve your inquiry.</p>

    <h2>6. Your choices</h2>
    <p>Because the Service does not require an account, there is no profile data to access, export, or delete beyond the limited technical logs described above. If you contacted us directly, you can ask us to delete that correspondence at any time by emailing <a href="mailto:<?= e(PRIVACY_EMAIL) ?>"><?= e(PRIVACY_EMAIL) ?></a>.</p>

    <h2>7. Children's privacy</h2>
    <p>The Service is not directed at children under 13, and we do not knowingly collect personal information from children under 13.</p>

    <h2>8. Changes to this policy</h2>
    <p>We may update this Privacy Policy from time to time. Material changes will be reflected by updating the "Last updated" date above.</p>

    <h2>9. Contact us</h2>
    <p>Questions about this policy can be sent to <a href="mailto:<?= e(PRIVACY_EMAIL) ?>"><?= e(PRIVACY_EMAIL) ?></a>.</p>
    </div>
  </div>
</section>
<?php require ROOT_PATH . '/templates/partials/footer.php'; ?>
