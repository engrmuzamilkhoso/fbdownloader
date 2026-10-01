<?php
// Nothing to consent to until ads are actually enabled — the site otherwise
// sets no advertising/analytics cookies (see privacy-policy.php).
if (!ADSENSE_ENABLED || ADSENSE_CLIENT_ID === '') {
    return;
}
?>
<div id="consent-banner" class="consent-banner" role="dialog" aria-live="polite" aria-label="Cookie consent" hidden>
  <div class="container consent-banner-inner">
    <p>
      We use cookies to show ads and measure their performance. See our
      <a href="/privacy-policy.php" class="focus-ring">Privacy Policy</a> for details.
    </p>
    <div class="consent-banner-actions">
      <button type="button" class="btn btn-outline btn-sm" id="consent-reject">Reject</button>
      <button type="button" class="btn btn-primary btn-sm" id="consent-accept">Accept</button>
    </div>
  </div>
</div>
