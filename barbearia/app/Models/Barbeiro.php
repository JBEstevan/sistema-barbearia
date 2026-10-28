<?php
declare(strict_types=1);

final class Barbeiro
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        $query = $this->db->prepare('SELECT * FROM barbeiros ORDER BY nome');
        $query->execute();
        return $query->fetchAll();
    }

    public function find(int $id): ?object
    {
        $query = $this->db->prepare('SELECT * FROM barbeiros WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $query = $this->db->prepare('INSERT INTO barbeiros (nome, telefone, email, especialidade, ativo) VALUES (:nome, :telefone, :email, :especialidade, :ativo)');
        $this->bindData($query, $data);
        $query->execute();
    }

    public function update(int $id, array $data): void
    {
        $query = $this->db->prepare('UPDATE barbeiros SET nome = :nome, telefone = :telefone, email = :email, especialidade = :especialidade, ativo = :ativo WHERE id = :id');
        $this->bindData($query, $data);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Remove o barbeiro e devolve quantas linhas foram apagadas
     * (0 = não existia nenhum barbeiro com esse id).
     */
    public function delete(int $id): int
    {
        $query = $this->db->prepare('DELETE FROM barbeiros WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->rowCount();
    }

    /** Liga os campos do barbeiro à query (usado por create e update). */
    private function bindData(PDOStatement $query, array $data): void
    {
        $query->bindValue(':nome', $data['nome'], PDO::PARAM_STR);
        $query->bindValue(':telefone', $data['telefone'], PDO::PARAM_STR);
        $query->bindValue(':email', $data['email'], $data['email'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $query->bindValue(':especialidade', $data['especialidade'], $data['especialidade'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $query->bindValue(':ativo', $data['ativo'], PDO::PARAM_INT);
    }
}
