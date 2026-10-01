<?php

declare(strict_types=1);

function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => IS_HTTPS,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_name('fbvideo_session');
    session_start();

    if (empty($_SESSION['created_at'])) {
        $_SESSION['created_at'] = time();
    } elseif (time() - $_SESSION['created_at'] > 1800) {
        // Rotate the session ID roughly every 30 minutes to limit fixation exposure.
        session_regenerate_id(true);
        $_SESSION['created_at'] = time();
    }
}
