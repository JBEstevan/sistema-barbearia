<?php
declare(strict_types=1);

final class Csrf
{
    public static function token(): string
    {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    public static function validate(): void
    {
        $token = $_POST['csrf'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(419);
            exit('Solicitação inválida. Atualize a página e tente novamente.');
        }
    }
}
