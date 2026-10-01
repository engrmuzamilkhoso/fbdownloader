<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/guides_content.php';

final class GuideController
{
    public function index(): void
    {
        $pageTitle = 'Guides';
        $pageDescription = 'Practical, step-by-step guides for downloading Facebook videos and Reels on any device.';
        $canonicalPath = '/guides/';
        $breadcrumbs = [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Guides', 'href' => '/guides/'],
        ];

        require ROOT_PATH . '/templates/pages/guides/index.php';
    }

    public function show(string $slug): void
    {
        $guide = findGuideBySlug($slug);
        if ($guide === null) {
            http_response_code(404);
            require ROOT_PATH . '/templates/pages/404.php';
            return;
        }

        $pageTitle = $guide['title'];
        $pageDescription = $guide['description'];
        $canonicalPath = '/guides/' . $guide['slug'] . '.php';
        $breadcrumbs = [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Guides', 'href' => '/guides/'],
            ['label' => $guide['title'], 'href' => $canonicalPath],
        ];
        $extraJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $guide['title'],
            'description' => $guide['description'],
            'dateModified' => $guide['updated'],
            'author' => ['@type' => 'Organization', 'name' => SITE_NAME],
            'publisher' => ['@type' => 'Organization', 'name' => SITE_NAME],
            'mainEntityOfPage' => SITE_URL . $canonicalPath,
        ];

        $contentTemplate = ROOT_PATH . '/templates/pages/guides/' . $guide['slug'] . '.php';
        if (!is_file($contentTemplate)) {
            http_response_code(404);
            require ROOT_PATH . '/templates/pages/404.php';
            return;
        }

        require ROOT_PATH . '/templates/pages/guides/_layout-start.php';
        require $contentTemplate;
        require ROOT_PATH . '/templates/pages/guides/_layout-end.php';
    }
}
