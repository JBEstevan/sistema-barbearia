<?php
require __DIR__ . '/../layout/header.php';
$editando = $agendamento !== null;

$dataValor = '';
$horaValor = '';
if ($editando && !empty($agendamento['data_hora'])) {
    $dt = new DateTime($agendamento['data_hora']);
    $dataValor = $dt->format('Y-m-d');
    $horaValor = $dt->format('H:i');
} else {
    $dataValor = date('Y-m-d');
    $horaValor = date('H:i');
}
?>
<section class="card">
    <h1><?= e($titulo) ?></h1>
    <form method="post" action="<?= url($editando ? 'agendamentos/' . $agendamento['id'] . '/atualizar' : 'agendamentos') ?>">
        <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

        <label>Nome do cliente
            <input name="cliente_nome" maxlength="120" required value="<?= e($agendamento['cliente_nome'] ?? '') ?>">
        </label>

        <label>Telefone do cliente
            <input name="cliente_telefone" class="telefone" maxlength="15" required placeholder="(47) 00000-0000"
                   value="<?= e($agendamento['cliente_telefone'] ?? '') ?>">
            <small>Formato: (47) 00000-0000</small>
        </label>

        <label>Barbeiro
            <select name="barbeiro_id" required>
                <option value="">Selecione um barbeiro...</option>
                <?php foreach ($barbeiros as $b): ?>
                    <option value="<?= (int)$b['id'] ?>" <?= ($editando && (int)$agendamento['barbeiro_id'] === (int)$b['id']) ? 'selected' : '' ?>>
                        <?= e($b['nome']) ?> <?= !empty($b['especialidade']) ? '— ' . e($b['especialidade']) : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Serviço
            <select name="servico_id" required>
                <option value="">Selecione um serviço...</option>
                <?php foreach ($servicos as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= ($editando && (int)$agendamento['servico_id'] === (int)$s['id']) ? 'selected' : '' ?>>
                        <?= e($s['nome']) ?> (R$ <?= number_format((float)$s['preco'], 2, ',', '.') ?> - <?= (int)$s['duracao'] ?> min)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Data
            <input name="data" type="date" required value="<?= e($dataValor) ?>">
        </label>

        <label>Horário
            <input name="hora" type="time" required value="<?= e($horaValor) ?>">
        </label>

        <label>Observações
            <input name="observacoes" maxlength="255" placeholder="Opcional" value="<?= e($agendamento['observacoes'] ?? '') ?>">
        </label>

        <button type="submit">Salvar</button>
        <a class="cancelar" href="<?= url('agendamentos') ?>">Cancelar</a>
    </form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
