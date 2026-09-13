<?php
declare(strict_types=1);

function url(string $route = ''): string
{
    return 'index.php?r=' . rawurlencode($route);
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
