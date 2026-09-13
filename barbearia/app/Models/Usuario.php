<?php
declare(strict_types=1);

final class Usuario
{
    public function __construct(private PDO $db)
    {
    }

    public function byEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nome, email, senha_hash, perfil FROM usuarios WHERE email = ? AND ativo = 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function updateLastAccess(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET ultimo_acesso_em = NOW() WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function findForPassword(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, senha_hash FROM usuarios WHERE id = ? AND ativo = 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
        $stmt->execute([$hash, $id]);
    }
}
