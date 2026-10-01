<?php

declare(strict_types=1);

final class PageController
{
    public function privacyPolicy(): void
    {
        $pageTitle = 'Privacy Policy';
        $pageDescription = 'How ' . SITE_NAME . ' collects, uses, and protects your information.';
        $canonicalPath = '/privacy-policy.php';
        $breadcrumbs = [['label' => 'Home', 'href' => '/'], ['label' => 'Privacy Policy', 'href' => $canonicalPath]];

        require ROOT_PATH . '/templates/pages/privacy-policy.php';
    }

    public function terms(): void
    {
        $pageTitle = 'Terms of Use';
        $pageDescription = 'The terms and conditions for using ' . SITE_NAME . '.';
        $canonicalPath = '/terms.php';
        $breadcrumbs = [['label' => 'Home', 'href' => '/'], ['label' => 'Terms of Use', 'href' => $canonicalPath]];

        require ROOT_PATH . '/templates/pages/terms.php';
    }

    public function about(): void
    {
        $pageTitle = 'About';
        $pageDescription = 'What ' . SITE_NAME . ' is, who built it, and why it exists.';
        $canonicalPath = '/about.php';
        $breadcrumbs = [['label' => 'Home', 'href' => '/'], ['label' => 'About', 'href' => $canonicalPath]];

        require ROOT_PATH . '/templates/pages/about.php';
    }

    public function notFound(): void
    {
        http_response_code(404);
        $pageTitle = 'Page not found';
        $pageDescription = 'The page you requested could not be found.';
        $canonicalPath = '/404.php';

        require ROOT_PATH . '/templates/pages/404.php';
    }
}
