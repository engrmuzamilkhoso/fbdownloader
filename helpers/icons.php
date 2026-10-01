<?php

declare(strict_types=1);

/**
 * Minimal original line-icon set (24x24, stroke-based, currentColor) so the
 * app has no runtime dependency on an icon package. Usage: <?= icon('zap', 'w-5 h-5') ?>
 */
function icon(string $name, string $class = ''): string
{
    $attrs = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
    $classAttr = $class !== '' ? ' class="' . e($class) . '"' : '';

    $bodies = [
        'shield-check' => '<path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
        'eye-off' => '<path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M6.7 6.7C4.5 8.1 3 10 3 12c0 0 3.5 6 9 6 1.6 0 3-.4 4.2-1.1"/><path d="M17.4 17.4C19.6 16 21 12 21 12s-1.2-2.1-3.2-3.7"/><path d="M9.9 5.1A9.7 9.7 0 0 1 12 5c5.5 0 9 6 9 6"/>',
        'zap' => '<path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/>',
        'gift' => '<rect x="4" y="9" width="16" height="12" rx="1"/><path d="M4 13h16"/><path d="M12 9v12"/><path d="M12 9C9 9 8 7 8 5.5 8 4.1 9.1 3 10.5 3 12 3 12 9 12 9z"/><path d="M12 9c3 0 4-2 4-3.5C16 4.1 14.9 3 13.5 3 12 3 12 9 12 9z"/>',
        'facebook' => '<circle cx="12" cy="12" r="9"/><path d="M13.8 21v-6.5h2.1l.3-2.5h-2.4V10.3c0-.7.2-1.2 1.3-1.2h1.3V6.9C16.1 6.8 15.2 6.7 14.2 6.7c-2.2 0-3.6 1.3-3.6 3.7v2.1H8.5v2.5h2.1V21"/>',
        'tv' => '<rect x="3" y="6" width="18" height="13" rx="1.5"/><path d="M8 3l4 3 4-3"/>',
        'clapperboard' => '<path d="M4 8l1.3-3.8A1 1 0 0 1 6.3 3.5l12.6 2.6a1 1 0 0 1 .8 1.2L19.3 9"/><rect x="4" y="9" width="16" height="11" rx="1"/><path d="M4 13h16"/>',
        'radio' => '<circle cx="12" cy="12" r="2.5"/><path d="M8.5 8.5a5 5 0 0 0 0 7"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/><path d="M5.6 5.6a9 9 0 0 0 0 12.8"/><path d="M18.4 5.6a9 9 0 0 1 0 12.8"/>',
        'link-2' => '<path d="M9 15l6-6"/><path d="M13 4.5l1-1a3.5 3.5 0 0 1 5 5l-1 1"/><path d="M11 19.5l-1 1a3.5 3.5 0 0 1-5-5l1-1"/>',
        'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><path d="M16 6.2c1.4.4 2.4 1.7 2.4 3.2 0 1.5-1 2.8-2.4 3.2"/><path d="M21 20c0-2.6-1.8-4.8-4.2-5.6"/>',
        'sparkles' => '<path d="M11 3l1.2 3.6L16 8l-3.8 1.4L11 13l-1.2-3.6L6 8l3.8-1.4L11 3z"/><path d="M18 13l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7.7-2z"/>',
        'badge-check' => '<path d="M12 3l2 1.6 2.5-.3 1 2.3 2.3 1-.3 2.5L21 12l-1.6 2 .3 2.5-2.3 1-1 2.3-2.5-.3L12 21l-2-1.6-2.5.3-1-2.3-2.3-1 .3-2.5L3 12l1.6-2-.3-2.5 2.3-1 1-2.3 2.5.3L12 3z"/><path d="M9 12l2 2 4-4"/>',
        'smartphone' => '<rect x="6" y="2" width="12" height="20" rx="2"/><path d="M11 18h2"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.5 2.5 3.8 5.7 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.7-3.8-9S9.5 5.5 12 3z"/>',
        'lock' => '<rect x="4.5" y="11" width="15" height="9" rx="1.5"/><path d="M8 11V7.5a4 4 0 0 1 8 0V11"/>',
        'play-circle' => '<circle cx="12" cy="12" r="9"/><path d="M10 8.5l6 3.5-6 3.5v-7z"/>',
        'copy' => '<rect x="9" y="9" width="11" height="11" rx="1.5"/><path d="M5 15V5.5A1.5 1.5 0 0 1 6.5 4H15"/>',
        'clipboard-paste' => '<rect x="5" y="5" width="14" height="16" rx="1.5"/><rect x="9" y="2.5" width="6" height="4" rx="1"/><path d="M9 12h6"/><path d="M9 16h6"/>',
        'sliders' => '<path d="M4 6h10"/><path d="M18 6h2"/><circle cx="15" cy="6" r="2"/><path d="M4 12h2"/><path d="M10 12h10"/><circle cx="7" cy="12" r="2"/><path d="M4 18h13"/><path d="M20 18h0"/><circle cx="19" cy="18" r="2"/>',
        'download' => '<path d="M12 3v12"/><path d="M7.5 10.5L12 15l4.5-4.5"/><path d="M5 19h14"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M3.5 6.5L12 13l8.5-6.5"/>',
        'bug' => '<rect x="8" y="8" width="8" height="10" rx="4"/><path d="M12 8V5"/><path d="M9.5 5.5l-1.7-1.5"/><path d="M14.5 5.5l1.7-1.5"/><path d="M5 12H8"/><path d="M16 12h3"/><path d="M5 16.5l3-1"/><path d="M19 16.5l-3-1"/><path d="M5 20l3.2-2"/><path d="M19 20l-3.2-2"/>',
        'shield-question' => '<path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M10.2 10a1.8 1.8 0 1 1 2.7 1.6c-.7.4-.9.7-.9 1.4"/><path d="M12 15.2h.01"/>',
        'key-round' => '<circle cx="8" cy="14" r="4"/><path d="M11 11l8-8"/><path d="M16 6l2.5 2.5"/><path d="M13.5 8.5L16 11"/>',
        'timer' => '<circle cx="12" cy="13" r="8"/><path d="M12 13l3-2.5"/><path d="M10 2h4"/>',
        'bar-chart' => '<path d="M4 20V10"/><path d="M12 20V4"/><path d="M20 20v-7"/>',
        'chevron-down' => '<path d="M6 9l6 6 6-6"/>',
        'menu' => '<path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/>',
        'x' => '<path d="M5 5l14 14"/><path d="M19 5L5 19"/>',
        'moon' => '<path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5z"/>',
        'sun' => '<circle cx="12" cy="12" r="4.2"/><path d="M12 2.5v2.3"/><path d="M12 19.2v2.3"/><path d="M4.4 4.4l1.6 1.6"/><path d="M18 18l1.6 1.6"/><path d="M2.5 12h2.3"/><path d="M19.2 12h2.3"/><path d="M4.4 19.6L6 18"/><path d="M18 6l1.6-1.6"/>',
        'alert-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16.2h.01"/>',
        'loader' => '<path d="M12 3a9 9 0 1 0 9 9"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="M8.2 12.3l2.5 2.5 5-5.2"/>',
        'send' => '<path d="M21 3L3 10.5l7.5 3L14 21l7-18z"/><path d="M10.5 13.5L21 3"/>',
        'film' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M8 4v5"/><path d="M8 15v5"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
        'chrome' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3.2"/><path d="M12 3v6"/><path d="M5 8.3l5.2 3"/><path d="M8.8 20.4L12 15"/>',
        'arrow-right' => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
        'arrow-up' => '<path d="M12 19V5"/><path d="M6 11l6-6 6 6"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'book-open' => '<path d="M3 5.5C5.5 4.5 8.5 4.5 12 6.5c3.5-2 6.5-2 9-1V19c-2.5-1-5.5-1-9 1-3.5-2-6.5-2-9-1V5.5z"/><path d="M12 6.5V20"/>',
        'home' => '<path d="M4 10.5L12 4l8 6.5V20a1 1 0 0 1-1 1h-4.5v-6h-5v6H5a1 1 0 0 1-1-1v-9.5z"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="1.5"/><path d="M3.5 10h17"/><path d="M8 3v4"/><path d="M16 3v4"/>',
        'heart' => '<path d="M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.3a4.3 4.3 0 0 1 7.5 2.5C19.5 15.4 12 20 12 20z"/>',
    ];

    $body = $bodies[$name] ?? $bodies['sparkles'];

    return "<svg{$classAttr} {$attrs}>{$body}</svg>";
}
