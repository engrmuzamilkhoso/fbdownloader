<?php

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

header('Content-Type: text/plain; charset=utf-8');

if (ADSENSE_ENABLED && ADSENSE_CLIENT_ID !== '') {
    $pubId = str_starts_with(ADSENSE_CLIENT_ID, 'ca-pub-') ? substr(ADSENSE_CLIENT_ID, 3) : ADSENSE_CLIENT_ID;
    echo "google.com, {$pubId}, DIRECT, f08c47fec0942fa0\n";
}
