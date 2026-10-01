<?php require ROOT_PATH . '/templates/partials/header.php'; ?>
<?php
$heroLabel = 'Legal';
$heroTitle = $pageTitle;
$heroLead = 'The ground rules for using ' . SITE_NAME . ' — including what you can and cannot use downloaded videos for.';
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
    <p>Please read these Terms of Use ("Terms") carefully before using <?= e(SITE_URL) ?> (the "Service"), operated by <?= e(SITE_NAME) ?>. By accessing or using the Service, you agree to be bound by these Terms.</p>

    <h2>1. What the Service does</h2>
    <p>The Service lets you paste a link to a publicly viewable Facebook video and retrieve a copy of that video file. The Service does not host, index, or archive any video content — it resolves a link at the time of your request only.</p>

    <h2>2. Acceptable use</h2>
    <p>You agree to use the Service only for lawful purposes and only to download content that you own, that you have express permission to use, or that you are otherwise legally entitled to download under applicable copyright law (including fair use or fair dealing exceptions where they apply). You agree not to:</p>
    <ul>
      <li>Use the Service to infringe the intellectual property rights of any third party.</li>
      <li>Use the Service to download private, friends-only, or otherwise non-public content, or attempt to circumvent access controls.</li>
      <li>Use automated means (bots, scripts, scrapers) to send bulk requests to the Service outside of normal, individual use.</li>
      <li>Attempt to interfere with, disrupt, or overload the Service's infrastructure.</li>
      <li>Use the Service for any illegal purpose or in violation of Facebook's own Terms of Service.</li>
    </ul>

    <h2>3. Copyright and takedown requests</h2>
    <p>We respect the intellectual property rights of others. Because the Service does not store or host video files, there is no content on our servers to remove. If you believe a third party is using the Service to systematically infringe your copyright, contact us at <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a> and we will investigate.</p>

    <h2>4. No warranty</h2>
    <p>The Service is provided "as is" and "as available" without warranties of any kind, whether express or implied. We do not guarantee that the Service will be uninterrupted, error-free, or that every video link will resolve successfully — Facebook may change its systems at any time in ways that affect availability.</p>

    <h2>5. Limitation of liability</h2>
    <p>To the fullest extent permitted by law, <?= e(SITE_NAME) ?> shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of, or inability to use, the Service.</p>

    <h2>6. Third-party content</h2>
    <p>Videos accessible through the Service originate from Facebook and belong to their respective owners. We make no claim of ownership over any third-party content and are not responsible for its accuracy, legality, or appropriateness.</p>

    <h2>7. Changes to the Service or these Terms</h2>
    <p>We may modify or discontinue the Service, in whole or in part, at any time. We may also update these Terms from time to time; continued use of the Service after changes take effect constitutes acceptance of the revised Terms.</p>

    <h2>8. Governing law</h2>
    <p>These Terms are governed by the laws of the jurisdiction in which <?= e(SITE_NAME) ?> operates, without regard to conflict-of-law principles.</p>

    <h2>9. Contact us</h2>
    <p>Questions about these Terms can be sent to <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>
    </div>
  </div>
</section>
<?php require ROOT_PATH . '/templates/partials/footer.php'; ?>
