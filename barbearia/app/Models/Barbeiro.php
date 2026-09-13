<?php
declare(strict_types=1);

final class Barbeiro
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM barbeiros ORDER BY nome')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM barbeiros WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO barbeiros (nome, telefone, email, especialidade, ativo) VALUES (:nome, :telefone, :email, :especialidade, :ativo)');
        $stmt->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare('UPDATE barbeiros SET nome=:nome, telefone=:telefone, email=:email, especialidade=:especialidade, ativo=:ativo WHERE id=:id');
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM barbeiros WHERE id = ?');
        $stmt->execute([$id]);
    }
}
