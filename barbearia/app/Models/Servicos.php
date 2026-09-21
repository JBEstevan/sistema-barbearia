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

    /*lista todos os servicos ativos*/
    public function listAtivos(): array
    {
        $stmt = $this->db->query('SELECT * FROM servicos WHERE status = 1 ORDER BY nome ASC');
        return $stmt->fetchAll(db::FETCH_ASSOC);
    }

    public function atualizar(int $id, string $nome, float $preco, int $duracao): bool
    {
        $sql = 'UPDATE servicos SET nome = :nome, preco = :preco, duracao = :duracao WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nome' => $nome, 'preco' => $preco, 'duracao' => $duracao, 'id' => $id,
        ]);
    }

    public function alterarStatus(int $id, int $status): bool
    {
        $stmt = $this->db->prepare('UPDATE servicos SET status = :status WHERE id = :id');
        return $stmt->execute([
            'status' => $status, 'id' => $id,
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM servicos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

}