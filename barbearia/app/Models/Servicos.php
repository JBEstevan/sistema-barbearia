<?php
declare(strict_types=1);

final class Servicos
{
    public function __construct(private PDO $db)
    {
    }

    public function listAll()
    {
        return $this->db->query('SELECT * FROM servicos ORDER BY nome')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM servicos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function addServico(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO servicos (nome, preco, duracao, status) 
        VALUES (:nome, :preco, :duracao, :status)');
        $stmt->execute($data);
    }
}