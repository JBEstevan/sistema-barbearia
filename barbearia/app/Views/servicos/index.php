<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="cabecalho-pagina">
    <h1>Serviços</h1>
    <a class="btn-cta" href="<?= url('servicos/novo') ?>">Cadastrar serviço</a>
</div>

<?php if ($servicos): ?>
    <div class="busca">
        <label class="sr-only" for="busca-servicos">Buscar serviço</label>
        <input id="busca-servicos" type="search" placeholder="Buscar serviço pelo nome"
               data-filtro="#tabela-servicos" autocomplete="off">
    </div>
<?php endif; ?>

<div class="tabela-wrap">
    <table class="tabela" id="tabela-servicos">
        <thead>
        <tr>
            <th>Nome</th>
            <th class="num">Preço</th>
            <th class="num">Duração</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($servicos as $servico): ?>
            <tr data-linha>
                <td><?= e($servico['nome']) ?></td>
                <td class="num">R$ <?= number_format((float)$servico['preco'], 2, ',', '.') ?></td>
                <td class="num"><?= (int)$servico['duracao'] ?> min</td>
                <td>
                    <span class="selo <?= $servico['status'] ? 'selo-ativo' : 'selo-inativo' ?>"><?= $servico['status'] ? 'Ativo' : 'Inativo' ?></span>
                </td>
                <td>
                    <div class="acoes">
                        <a class="acao" href="<?= url('servicos/' . $servico['id'] . '/editar') ?>">Editar</a>
                        <form class="inline" method="post" action="<?= url('servicos/' . $servico['id'] . '/excluir') ?>"
                              data-confirmar="Remover o serviço <?= e($servico['nome']) ?>? Essa ação não pode ser desfeita.">
                            <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">
                            <button type="submit" class="acao perigo">Excluir</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$servicos): ?>
            <tr>
                <td colspan="5" class="vazio">Nenhum serviço cadastrado ainda.
                    <a href="<?= url('servicos/novo') ?>">Cadastrar o primeiro</a></td>
            </tr>
        <?php else: ?>
            <tr class="sem-resultado" hidden>
                <td colspan="5">Nenhum serviço encontrado para essa busca.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>