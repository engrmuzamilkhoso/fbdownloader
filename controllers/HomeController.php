<?php

declare(strict_types=1);

final class HomeController
{
    public function index(): void
    {
        $pageTitle = SITE_NAME;
        $pageDescription = SITE_DESCRIPTION;
        $canonicalPath = '/';

        require ROOT_PATH . '/templates/pages/home.php';
    }
}
