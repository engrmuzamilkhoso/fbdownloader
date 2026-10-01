<?php
/**
 * Renders nothing until ADSENSE_ENABLED=true and a real ADSENSE_CLIENT_ID is set,
 * so no empty ad placeholders are ever shown pre-approval.
 * @var string $adSlotId
 */
if (!ADSENSE_ENABLED || ADSENSE_CLIENT_ID === '' || empty($adSlotId)) {
    return;
}
?>
<div class="ad-slot">
  <ins class="adsbygoogle"
    style="display:block"
    data-ad-client="<?= e(ADSENSE_CLIENT_ID) ?>"
    data-ad-slot="<?= e($adSlotId) ?>"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
  <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>
