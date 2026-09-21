<?php
declare(strict_types=1);

final class Servicos
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM servicos ORDER BY nome')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM servicos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /* lista apenas os serviços ativos*/
    public function ativos(): array
    {
        return $this->db->query('SELECT * FROM servicos WHERE status = 1 ORDER BY nome')->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO servicos (nome, preco, duracao, status) VALUES (:nome, :preco, :duracao, :status)');
        $stmt->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare('UPDATE servicos SET nome=:nome, preco=:preco, duracao=:duracao, status=:status WHERE id=:id');
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM servicos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
