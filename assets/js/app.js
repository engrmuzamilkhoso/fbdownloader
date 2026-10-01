(function () {
  "use strict";

  var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]');
  CSRF_TOKEN = CSRF_TOKEN ? CSRF_TOKEN.getAttribute("content") : "";

  var FACEBOOK_URL_PATTERN = /^https?:\/\/([a-z0-9-]+\.)*(facebook\.com|fb\.watch|fb\.com)\//i;

  /* ---------- Theme toggle ---------- */
  function initTheme() {
    var toggle = document.getElementById("theme-toggle");
    if (!toggle) return;

    toggle.addEventListener("click", function () {
      var isDark = document.documentElement.classList.contains("dark");
      var next = isDark ? "light" : "dark";
      document.documentElement.classList.toggle("dark", next === "dark");
      document.documentElement.dataset.theme = next;
      try {
        localStorage.setItem("theme", next);
      } catch (e) {}
    });
  }

  /* ---------- Mobile nav ---------- */
  function initMobileNav() {
    var toggle = document.getElementById("menu-toggle");
    var nav = document.getElementById("mobile-nav");
    if (!toggle || !nav) return;

    toggle.addEventListener("click", function () {
      var isOpen = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!isOpen));
      nav.hidden = isOpen;
    });

    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        toggle.setAttribute("aria-expanded", "false");
        nav.hidden = true;
      });
    });
  }

  /* ---------- "Download" CTAs scroll to the hero and focus the input ---------- */
  function initGetStarted() {
    var input = document.getElementById("download-input");
    if (!input) return;
    document.querySelectorAll("#get-started-btn, [data-focus-download]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        window.setTimeout(function () {
          input.focus({ preventScroll: true });
        }, 450);
      });
    });
  }

  /* ---------- Header border once the page scrolls ---------- */
  function initHeaderScroll() {
    var header = document.getElementById("site-header");
    if (!header) return;
    var update = function () {
      header.classList.toggle("is-scrolled", window.scrollY > 8);
    };
    update();
    window.addEventListener("scroll", update, { passive: true });
  }

  /* ---------- Auto table of contents for long-form pages ---------- */
  function slugify(text) {
    return text
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-+|-+$/g, "")
      .slice(0, 60);
  }

  function initToc() {
    var toc = document.querySelector("[data-toc]");
    var source = document.querySelector("[data-toc-source]");
    if (!toc || !source) return;

    var headings = Array.prototype.filter.call(source.querySelectorAll("h2"), function (h) {
      return !h.hasAttribute("data-toc-skip") && !h.closest(".guide-cta");
    });
    if (!headings.length) {
      toc.closest(".doc-aside-inner").querySelector(".toc-title").hidden = true;
      return;
    }

    var links = headings.map(function (heading, i) {
      if (!heading.id) heading.id = slugify(heading.textContent) || "section-" + (i + 1);
      var link = document.createElement("a");
      link.href = "#" + heading.id;
      link.textContent = heading.textContent;
      toc.appendChild(link);
      return link;
    });

    if (!("IntersectionObserver" in window)) return;
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var index = headings.indexOf(entry.target);
          links.forEach(function (link, i) {
            link.classList.toggle("is-active", i === index);
          });
        });
      },
      { rootMargin: "-80px 0px -70% 0px" },
    );
    headings.forEach(function (h) {
      observer.observe(h);
    });
  }

  /* ---------- Reveal on scroll ---------- */
  function initReveal() {
    var items = document.querySelectorAll(".reveal");
    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
      items.forEach(function (el) {
        el.classList.add("in-view");
      });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("in-view");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -40px 0px" },
    );

    items.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* ---------- FAQ accordion ---------- */
  function initAccordion() {
    var accordion = document.getElementById("faq-accordion");
    if (!accordion) return;

    accordion.querySelectorAll(".accordion-trigger").forEach(function (trigger) {
      trigger.addEventListener("click", function () {
        var panel = document.getElementById(trigger.getAttribute("aria-controls"));
        var isOpen = trigger.getAttribute("aria-expanded") === "true";

        trigger.setAttribute("aria-expanded", String(!isOpen));
        if (panel) panel.style.gridTemplateRows = isOpen ? "0fr" : "1fr";
      });
    });
  }

  /* ---------- Shared helpers ---------- */
  function apiFetch(url, options) {
    options = options || {};
    options.headers = Object.assign({ "X-CSRF-Token": CSRF_TOKEN }, options.headers || {});
    return fetch(url, options);
  }

  function formatBytes(bytes) {
    if (!bytes) return null;
    var mb = bytes / (1024 * 1024);
    if (mb < 1) return Math.round(bytes / 1024) + " KB";
    return mb.toFixed(1) + " MB";
  }

  function formatDuration(totalSeconds) {
    if (totalSeconds === null || totalSeconds === undefined) return null;
    var minutes = Math.floor(totalSeconds / 60);
    var seconds = totalSeconds % 60;
    return minutes + ":" + String(seconds).padStart(2, "0");
  }

  function sanitizeFilenamePart(value) {
    var trimmed = value.trim().replace(/[^a-zA-Z0-9\-_ ]/g, "").replace(/\s+/g, "-").slice(0, 80);
    return trimmed || "facebook-video";
  }

  async function readErrorMessage(response) {
    try {
      var payload = await response.json();
      if (payload && typeof payload.error === "string") return payload.error;
    } catch (e) {}
    return "Something went wrong. Please try again.";
  }

  /* ---------- Download form ---------- */
  function initDownloadForm() {
    var form = document.getElementById("download-form");
    var input = document.getElementById("download-input");
    var pasteBtn = document.getElementById("paste-btn");
    var submitBtn = document.getElementById("download-submit");
    var errorEl = document.getElementById("download-error");
    var resultEl = document.getElementById("download-result");
    if (!form || !input || !submitBtn || !errorEl || !resultEl) return;

    function setLoading(isLoading) {
      submitBtn.disabled = isLoading;
      submitBtn.querySelector(".btn-icon-default").hidden = isLoading;
      submitBtn.querySelector(".btn-icon-loading").hidden = !isLoading;
    }

    function showError(message) {
      errorEl.hidden = false;
      errorEl.querySelector(".form-error-text").textContent = message;
    }

    function clearError() {
      errorEl.hidden = true;
    }

    if (pasteBtn && navigator.clipboard && navigator.clipboard.readText) {
      pasteBtn.addEventListener("click", async function () {
        try {
          var text = await navigator.clipboard.readText();
          if (text) input.value = text.trim();
        } catch (e) {}
      });
    } else if (pasteBtn) {
      pasteBtn.hidden = true;
    }

    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      var url = input.value.trim();

      if (!FACEBOOK_URL_PATTERN.test(url)) {
        clearError();
        showError("Enter a valid facebook.com or fb.watch video link.");
        resultEl.hidden = true;
        return;
      }

      clearError();
      resultEl.hidden = true;
      setLoading(true);

      try {
        var response = await apiFetch("/api/download.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ url: url }),
        });
        var payload = await response.json();

        if (!response.ok || !payload.ok) {
          showError(payload.error || "Something went wrong. Please try again.");
          return;
        }

        renderResult(resultEl, payload.data);
        resultEl.hidden = false;
      } catch (e) {
        showError("Network error — check your connection and try again.");
      } finally {
        setLoading(false);
      }
    });
  }

  function el(tag, className, children) {
    var node = document.createElement(tag);
    if (className) node.className = className;
    (children || []).forEach(function (child) {
      if (child) node.appendChild(child);
    });
    return node;
  }

  function textEl(tag, className, text) {
    var node = el(tag, className);
    node.textContent = text;
    return node;
  }

  function iconSpan(svgMarkup, extraClass) {
    var span = document.createElement("span");
    if (extraClass) span.className = extraClass;
    span.innerHTML = svgMarkup; // svgMarkup is always one of our own hardcoded icon fragments, never user data.
    return span;
  }

  // All values below (title, thumbnail URL, format labels, sizes) can originate
  // from a third party's video metadata, so this builds real DOM nodes via
  // textContent/attribute setters instead of HTML string concatenation —
  // there is no code path where that data is parsed as markup.
  function renderResult(container, video) {
    container.innerHTML = "";
    var duration = formatDuration(video.durationSeconds);

    var thumb = el("div", "result-thumb");
    if (video.thumbnail) {
      var img = document.createElement("img");
      img.src = video.thumbnail;
      img.alt = "Thumbnail for " + video.title;
      img.loading = "lazy";
      img.addEventListener("error", function () {
        img.remove();
        thumb.prepend(el("div", "result-thumb-fallback", [iconSpan(filmIconSvg())]));
      });
      thumb.appendChild(img);
    } else {
      thumb.appendChild(el("div", "result-thumb-fallback", [iconSpan(filmIconSvg())]));
    }
    if (duration) {
      var durationBadge = el("span", "result-duration", [iconSpan(clockIconSvg())]);
      durationBadge.appendChild(document.createTextNode(duration));
      thumb.appendChild(durationBadge);
    }

    var titleBlock = el("div", null, [
      textEl("p", "result-eyebrow", "Ready to download"),
      textEl("h3", "result-title", video.title),
    ]);

    var actions = el("div", "result-actions");
    video.links.forEach(function (link) {
      var sizeLabel = formatBytes(link.sizeBytes);
      var variant = link.isBest ? "btn-primary" : "btn-outline";

      var defaultLabel = el("span", "btn-icon-default", [iconSpan(downloadIconSvg())]);
      defaultLabel.appendChild(document.createTextNode(" Download " + link.label));
      if (sizeLabel) {
        var sizeSpan = el("span", "result-size");
        sizeSpan.textContent = "· " + sizeLabel;
        defaultLabel.appendChild(document.createTextNode(" "));
        defaultLabel.appendChild(sizeSpan);
      }

      var loadingLabel = el("span", "btn-icon-loading", [iconSpan(loaderIconSvg())]);
      loadingLabel.hidden = true;
      loadingLabel.appendChild(document.createTextNode(" "));
      loadingLabel.appendChild(textEl("span", "progress-text", "Starting…"));

      var button = el("button", "btn " + variant + " result-download-btn", [defaultLabel, loadingLabel]);
      button.type = "button";
      button.dataset.formatId = link.formatId;
      button.dataset.label = link.label;
      actions.appendChild(button);
    });

    var errorMsg = textEl("span", "form-error-text", "");
    var errorEl = el("p", "form-error result-error", [errorMsg]);
    errorEl.setAttribute("role", "alert");
    errorEl.hidden = true;
    var statusWrap = el("div", "form-status", [errorEl]);
    statusWrap.setAttribute("aria-live", "polite");

    var body = el("div", "result-body", [titleBlock, actions, statusWrap]);

    container.appendChild(thumb);
    container.appendChild(body);
    container.dataset.sourceUrl = video.sourceUrl;
    container.dataset.title = video.title;

    var activeButton = null;
    container.querySelectorAll(".result-download-btn").forEach(function (btn) {
      btn.addEventListener("click", function () {
        if (activeButton) return;
        activeButton = btn;
        downloadWithProgress(container, btn, video).finally(function () {
          activeButton = null;
        });
      });
    });
  }

  async function downloadWithProgress(container, button, video) {
    var allButtons = container.querySelectorAll(".result-download-btn");
    var errorEl = container.querySelector(".result-error");
    var formatId = button.getAttribute("data-format-id");
    var label = button.getAttribute("data-label");

    allButtons.forEach(function (btn) {
      btn.disabled = true;
    });
    button.querySelector(".btn-icon-default").hidden = true;
    button.querySelector(".btn-icon-loading").hidden = false;
    if (errorEl) errorEl.hidden = true;

    function setProgress(text) {
      var progressText = button.querySelector(".progress-text");
      if (progressText) progressText.textContent = text;
    }

    try {
      var params = new URLSearchParams({ url: video.sourceUrl, format: formatId, title: video.title });
      var response = await apiFetch("/api/media.php?" + params.toString());

      if (!response.ok) {
        throw new Error(await readErrorMessage(response));
      }

      var reader = response.body ? response.body.getReader() : null;
      var blob;

      if (reader) {
        var chunks = [];
        var received = 0;
        for (;;) {
          var result = await reader.read();
          if (result.done) break;
          chunks.push(result.value);
          received += result.value.byteLength;
          setProgress("Downloading… " + (formatBytes(received) || ""));
        }
        blob = new Blob(chunks, { type: "video/mp4" });
      } else {
        blob = await response.blob();
      }

      var objectUrl = URL.createObjectURL(blob);
      var anchor = document.createElement("a");
      anchor.href = objectUrl;
      anchor.download = sanitizeFilenamePart(video.title) + "-" + label + ".mp4";
      document.body.appendChild(anchor);
      anchor.click();
      anchor.remove();
      window.setTimeout(function () {
        URL.revokeObjectURL(objectUrl);
      }, 10000);
    } catch (error) {
      if (errorEl) {
        errorEl.hidden = false;
        errorEl.querySelector(".form-error-text").textContent = error.message || "Download failed. Please try again.";
      }
    } finally {
      allButtons.forEach(function (btn) {
        btn.disabled = false;
      });
      button.querySelector(".btn-icon-default").hidden = false;
      button.querySelector(".btn-icon-loading").hidden = true;
    }
  }

  /* ---------- Contact form ---------- */
  function initContactForm() {
    var form = document.getElementById("contact-form");
    if (!form) return;

    var submitBtn = form.querySelector(".contact-submit");
    var errorEl = form.querySelector(".form-error");
    var successEl = form.querySelector(".contact-success");
    var resetBtn = form.querySelector(".contact-reset");

    function setLoading(isLoading) {
      submitBtn.disabled = isLoading;
      submitBtn.querySelector(".btn-icon-default").hidden = isLoading;
      submitBtn.querySelector(".btn-icon-loading").hidden = !isLoading;
    }

    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      errorEl.hidden = true;
      setLoading(true);

      var payload = {
        name: form.querySelector("#contact-name").value.trim(),
        email: form.querySelector("#contact-email").value.trim(),
        message: form.querySelector("#contact-message").value.trim(),
      };

      try {
        var response = await apiFetch("/api/contact.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
        var result = await response.json();

        if (!response.ok || !result.ok) {
          errorEl.hidden = false;
          errorEl.querySelector(".form-error-text").textContent = result.error || "Something went wrong. Please try again.";
          return;
        }

        form.reset();
        form.querySelectorAll(".form-grid-2, .form-field, .contact-submit").forEach(function (el) {
          el.hidden = true;
        });
        errorEl.hidden = true;
        successEl.hidden = false;
      } catch (e) {
        errorEl.hidden = false;
        errorEl.querySelector(".form-error-text").textContent = "Network error — check your connection and try again.";
      } finally {
        setLoading(false);
      }
    });

    if (resetBtn) {
      resetBtn.addEventListener("click", function () {
        successEl.hidden = true;
        form.querySelectorAll(".form-grid-2, .form-field, .contact-submit").forEach(function (el) {
          el.hidden = false;
        });
      });
    }
  }

  /* ---------- Inline icon fragments used in dynamically injected HTML ---------- */
  function downloadIconSvg() {
    return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7.5 10.5L12 15l4.5-4.5"/><path d="M5 19h14"/></svg>';
  }
  function loaderIconSvg() {
    return '<svg class="icon spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9"/></svg>';
  }
  function clockIconSvg() {
    return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>';
  }
  function filmIconSvg() {
    return '<svg class="icon" style="width:2rem;height:2rem" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M8 4v5"/><path d="M8 15v5"/></svg>';
  }

  document.addEventListener("DOMContentLoaded", function () {
    initTheme();
    initMobileNav();
    initGetStarted();
    initHeaderScroll();
    initToc();
    initReveal();
    initAccordion();
    initDownloadForm();
    initContactForm();
  });
})();
