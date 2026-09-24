<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="page-title">
    <h1>Agendamentos</h1>
    <a class="button" href="<?= url('agendamentos/novo') ?>">Novo agendamento</a>
</div>
<table>
    <thead>
    <tr>
        <th>Data & Hora</th>
        <th>Cliente</th>
        <th>Telefone</th>
        <th>Barbeiro</th>
        <th>Serviço</th>
        <th>Valor</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($agendamentos as $ag): ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($ag['data_hora'])) ?></td>
            <td><?= e($ag['cliente_nome']) ?></td>
            <td><?= e($ag['cliente_telefone']) ?></td>
            <td><?= e($ag['barbeiro_nome']) ?></td>
            <td><?= e($ag['servico_nome']) ?></td>
            <td>R$ <?= number_format((float)$ag['servico_preco'], 2, ',', '.') ?></td>
            <td>
                <a href="<?= url('agendamentos/' . $ag['id'] . '/editar') ?>">Editar</a>&nbsp;&nbsp;&nbsp;
                <form class="inline" method="post" action="<?= url('agendamentos/' . $ag['id'] . '/excluir') ?>"
                      onsubmit="return confirm('Remover este agendamento?');">
                    <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">
                    <button class="link danger">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$agendamentos): ?>
        <tr>
            <td colspan="7">Nenhum agendamento cadastrado.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>
