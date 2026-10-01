<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/controllers/HomeController.php';

(new HomeController())->index();
