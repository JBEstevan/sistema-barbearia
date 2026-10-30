<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="cabecalho-pagina">
    <h1>Barbeiros</h1>
    <a class="btn-cta" href="<?= url('barbeiros/novo') ?>">Cadastrar barbeiro</a>
</div>

<?php if ($barbeiros): ?>
    <div class="busca">
        <label class="sr-only" for="busca-barbeiros">Buscar barbeiro</label>
        <input id="busca-barbeiros" type="search" placeholder="Buscar por nome, e-mail ou especialidade"
               data-filtro="#tabela-barbeiros" autocomplete="off">
    </div>
<?php endif; ?>

<div class="tabela-wrap">
    <table class="tabela" id="tabela-barbeiros">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Especialidade</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($barbeiros as $barbeiro): ?>
            <tr data-linha>
                <td><?= e($barbeiro['nome']) ?></td>
                <td><?= e($barbeiro['telefone']) ?></td>
                <td><?= e($barbeiro['email']) ?></td>
                <td><?= e($barbeiro['especialidade']) ?></td>
                <td>
                    <span class="selo <?= $barbeiro['ativo'] ? 'selo-ativo' : 'selo-inativo' ?>"><?= $barbeiro['ativo'] ? 'Ativo' : 'Inativo' ?></span>
                </td>
                <td>
                    <div class="acoes">
                        <a class="acao" href="<?= url('barbeiros/' . $barbeiro['id'] . '/editar') ?>">Editar</a>
                        <form class="inline" method="post" action="<?= url('barbeiros/' . $barbeiro['id'] . '/excluir') ?>"
                              data-confirmar="Remover o barbeiro <?= e($barbeiro['nome']) ?>? Essa ação não pode ser desfeita.">
                            <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">
                            <button type="submit" class="acao perigo">Excluir</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$barbeiros): ?>
            <tr>
                <td colspan="6" class="vazio">Nenhum barbeiro cadastrado ainda.
                    <a href="<?= url('barbeiros/novo') ?>">Cadastrar o primeiro</a></td>
            </tr>
        <?php else: ?>
            <tr class="sem-resultado" hidden>
                <td colspan="6">Nenhum barbeiro encontrado para essa busca.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>