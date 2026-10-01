(function () {
  "use strict";

  var config = window.FBVIDEO_ADSENSE || { enabled: false, clientId: "" };
  if (!config.enabled || !config.clientId) return;

  function loadAdsense() {
    if (document.getElementById("adsbygoogle-script")) return;
    var script = document.createElement("script");
    script.id = "adsbygoogle-script";
    script.async = true;
    script.crossOrigin = "anonymous";
    script.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=" + encodeURIComponent(config.clientId);
    document.head.appendChild(script);
  }

  function getStoredConsent() {
    try {
      return localStorage.getItem("ad_consent");
    } catch (e) {
      return null;
    }
  }

  function setStoredConsent(value) {
    try {
      localStorage.setItem("ad_consent", value);
    } catch (e) {}
  }

  var stored = getStoredConsent();
  if (stored === "accepted") {
    loadAdsense();
    return;
  }
  if (stored === "rejected") {
    return; // No ads until the visitor changes their mind (banner can be re-shown from a footer link if desired).
  }

  document.addEventListener("DOMContentLoaded", function () {
    var banner = document.getElementById("consent-banner");
    if (!banner) return;

    banner.hidden = false;

    var acceptBtn = document.getElementById("consent-accept");
    var rejectBtn = document.getElementById("consent-reject");

    if (acceptBtn) {
      acceptBtn.addEventListener("click", function () {
        setStoredConsent("accepted");
        banner.hidden = true;
        loadAdsense();
      });
    }
    if (rejectBtn) {
      rejectBtn.addEventListener("click", function () {
        setStoredConsent("rejected");
        banner.hidden = true;
      });
    }
  });
})();
