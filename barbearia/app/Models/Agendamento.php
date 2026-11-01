<?php
declare(strict_types=1);

final class Agendamento
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        $sql = 'SELECT a.*, b.nome AS barbeiro_nome, s.nome AS servico_nome, s.preco AS servico_preco
                FROM agendamentos a
                INNER JOIN barbeiros b ON a.barbeiro_id = b.id
                INNER JOIN servicos s ON a.servico_id = s.id
                ORDER BY a.data_hora DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, b.nome AS barbeiro_nome, s.nome AS servico_nome 
             FROM agendamentos a
             INNER JOIN barbeiros b ON a.barbeiro_id = b.id
             INNER JOIN servicos s ON a.servico_id = s.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Verifica se o barbeiro já possui agendamento no mesmo horário.
     */
    public function hasConflict(int $barbeiroId, string $dataHora, ?int $ignoreId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM agendamentos WHERE barbeiro_id = :barbeiro_id AND data_hora = :data_hora';
        $params = [
            'barbeiro_id' => $barbeiroId,
            'data_hora' => $dataHora,
        ];

        if ($ignoreId !== null) {
            $sql .= ' AND id != :ignore_id';
            $params['ignore_id'] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO agendamentos (cliente_nome, cliente_telefone, barbeiro_id, servico_id, data_hora, observacoes) 
             VALUES (:cliente_nome, :cliente_telefone, :barbeiro_id, :servico_id, :data_hora, :observacoes)'
        );
        $stmt->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            'UPDATE agendamentos 
             SET cliente_nome = :cliente_nome, cliente_telefone = :cliente_telefone, 
                 barbeiro_id = :barbeiro_id, servico_id = :servico_id, 
                 data_hora = :data_hora, observacoes = :observacoes 
             WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM agendamentos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
