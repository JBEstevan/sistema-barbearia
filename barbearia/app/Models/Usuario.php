<?php
declare(strict_types=1);

final class Usuario
{
    public function __construct(private PDO $db)
    {
    }

    public function byEmail(string $email): ?object
    {
        $query = $this->db->prepare('SELECT id, nome, email, senha_hash, perfil FROM usuarios WHERE email = :email AND ativo = 1');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return $query->fetch() ?: null;
    }

    public function updateLastAccess(int $id): void
    {
        $query = $this->db->prepare('UPDATE usuarios SET ultimo_acesso_em = NOW() WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function findForPassword(int $id): ?object
    {
        $query = $this->db->prepare('SELECT id, senha_hash FROM usuarios WHERE id = :id AND ativo = 1');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch() ?: null;
    }

    public function updatePassword(int $id, string $hash): void
    {
        $query = $this->db->prepare('UPDATE usuarios SET senha_hash = :hash WHERE id = :id');
        $query->bindValue(':hash', $hash, PDO::PARAM_STR);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}
