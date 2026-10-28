<?php
declare(strict_types=1);

final class Database
{
    public static function connect(array $config): PDO
    {
        $db = $config['db'];
        $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";
        return new PDO($dsn, $db['user'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Aula 06: os resultados voltam como objetos (acesso com ->) e não como arrays.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
