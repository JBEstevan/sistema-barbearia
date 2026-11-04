<?php
declare(strict_types=1);

function base_path(): string
{
    if (PHP_SAPI === 'cli-server') {
        return '';
    }
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    return $dir === '/' || $dir === '.' ? '' : rtrim($dir, '/');
}

function url(string $route = ''): string
{
    $base = base_path();
    $cleanRoute = ltrim($route, '/');
    if ($cleanRoute === '') {
        return $base !== '' ? $base . '/' : '/';
    }
    return $base . '/' . $cleanRoute;
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function redirect(string $route): never
{
    header('Location: ' . url($route));
    exit;
}
