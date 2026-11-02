<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="cabecalho-pagina">
    <h1>Agendamentos</h1>
    <a class="btn-cta" href="<?= url('agendamentos/novo') ?>">Novo agendamento</a>
</div>

<?php if ($agendamentos): ?>
    <div class="busca">
        <label class="sr-only" for="busca-agendamentos">Buscar agendamento</label>
        <input id="busca-agendamentos" type="search" placeholder="Buscar por data, cliente, barbeiro ou serviço"
               data-filtro="#tabela-agendamentos" autocomplete="off">
    </div>
<?php endif; ?>

<div class="tabela-wrap">
    <table class="tabela" id="tabela-agendamentos">
        <thead>
        <tr>
            <th>Data e hora</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Barbeiro</th>
            <th>Serviço</th>
            <th class="num">Valor</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($agendamentos as $ag): $ts = strtotime($ag['data_hora']); ?>
            <tr data-linha<?= $ts < time() ? ' class="passado"' : '' ?>>
                <td>
                    <?= date('d/m/Y', $ts) ?> <strong><?= date('H:i', $ts) ?></strong>
                    <?php if (date('Y-m-d', $ts) === date('Y-m-d')): ?><span class="selo selo-ativo">Hoje</span><?php endif; ?>
                </td>
                <td><?= e($ag['cliente_nome']) ?></td>
                <td><?= e($ag['cliente_telefone']) ?></td>
                <td><?= e($ag['barbeiro_nome']) ?></td>
                <td><?= e($ag['servico_nome']) ?></td>
                <td class="num">R$ <?= number_format((float)$ag['servico_preco'], 2, ',', '.') ?></td>
                <td>
                    <div class="acoes">
                        <a class="acao" href="<?= url('agendamentos/' . $ag['id'] . '/editar') ?>">Editar</a>
                        <form class="inline" method="post" action="<?= url('agendamentos/' . $ag['id'] . '/excluir') ?>"
                              data-confirmar="Remover o agendamento de <?= e($ag['cliente_nome']) ?> em <?= date('d/m/Y', $ts) ?> às <?= date('H:i', $ts) ?>? Essa ação não pode ser desfeita.">
                            <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">
                            <button type="submit" class="acao perigo">Excluir</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$agendamentos): ?>
            <tr>
                <td colspan="7" class="vazio">Nenhum agendamento cadastrado ainda.
                    <a href="<?= url('agendamentos/novo') ?>">Marcar o primeiro</a></td>
            </tr>
        <?php else: ?>
            <tr class="sem-resultado" hidden>
                <td colspan="7">Nenhum agendamento encontrado para essa busca.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>