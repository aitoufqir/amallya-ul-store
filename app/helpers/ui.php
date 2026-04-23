<?php

declare(strict_types=1);

function assetPath(string $path, string $base = ''): string
{
    return ($base !== '' ? rtrim($base, '/') . '/' : '') . ltrim($path, '/');
}

function renderPageStart(string $title, string $base = ''): void
{
    $css = htmlspecialchars(assetPath('css/style.css', $base));
    echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <link rel="stylesheet" href="{$css}">
</head>
<body>
HTML;
}

function renderPageEnd(string $base = '', bool $withScript = false): void
{
    if ($withScript) {
        $script = htmlspecialchars(assetPath('js/script.js', $base));
        echo '<script src="' . $script . '"></script>';
    }

    echo '</body></html>';
}

function renderTopNav(array $links, array $actions = [], string $base = '', bool $withSearch = false): void
{
    echo '<header class="top-nav">';
    echo '<a href="' . htmlspecialchars(assetPath('index.php', $base)) . '" class="logo">amallya_ul</a>';
    echo '<nav class="main-nav">';
    foreach ($links as $link) {
        echo '<a href="' . htmlspecialchars(assetPath($link['href'], $base)) . '">' . htmlspecialchars($link['label']) . '</a>';
    }
    echo '</nav>';
    echo '<div class="nav-right">';
    if ($withSearch) {
        echo '<input type="text" id="search" placeholder="Rechercher..." onkeyup="searchProduct()">';
    }
    foreach ($actions as $action) {
        $class = htmlspecialchars($action['class'] ?? 'btn-outline');
        echo '<a href="' . htmlspecialchars(assetPath($action['href'], $base)) . '" class="' . $class . '">' . htmlspecialchars($action['label']) . '</a>';
    }
    echo '</div>';
    echo '</header>';
}

function renderSectionHeading(string $eyebrow, string $title, string $copy = ''): void
{
    echo '<div class="section-heading">';
    echo '<p class="eyebrow">' . htmlspecialchars($eyebrow) . '</p>';
    echo '<h2 class="page-title">' . htmlspecialchars($title) . '</h2>';
    if ($copy !== '') {
        echo '<p class="section-copy">' . htmlspecialchars($copy) . '</p>';
    }
    echo '</div>';
}

function renderAuthShell(string $title, string $subtitle, string $content, array $links): void
{
    renderPageStart($title);
    renderTopNav($links);
    echo '<main class="page-shell">';
    echo '<section class="auth-hero reveal is-visible">';
    echo '<div class="auth-copy">';
    echo '<p class="eyebrow">amallya_ul Access</p>';
    echo '<h1>' . htmlspecialchars($title) . '</h1>';
    echo '<p class="section-copy">' . htmlspecialchars($subtitle) . '</p>';
    echo '</div>';
    echo '<div class="form-container">' . $content . '</div>';
    echo '</section>';
    echo '</main>';
    renderPageEnd();
}
