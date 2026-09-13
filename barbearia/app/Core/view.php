<?php
declare(strict_types=1);

function view(string $name, array $data = []): void
{
    global $config;
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../Views/' . $name . '.php';
}
