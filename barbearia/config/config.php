<?php
declare(strict_types=1);

return [
    'app_name' => $_ENV['APP_NAME'] ?? 'Barbearia',
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'name' => $_ENV['DB_NAME'] ?? 'barbearia',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '', // Altere conforme seu MySQL/phpMyAdmin ou arquivo .env
    ],
];
