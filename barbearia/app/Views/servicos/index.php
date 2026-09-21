<?php require __DIR__ . '/../layout/header.php'; ?>
    <div class="page-title"><h1>Serviços</h1><a class="button" href="<?= url('servicos/novo') ?>">Cadastrar serviço</a>
    </div>
    <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Duração</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($servicos as $servico): ?>
            <tr>
            <td><?= e($servico['nome']) ?></td>
            <td>R$ <?= number_format((float)$servico['preco'], 2, ',', '.') ?></td>
            <td><?= (int)$servico['duracao'] ?> min</td>
            <td><?= $servico['status'] ? 'Ativo' : 'Inativo' ?></td>
            <td><a href="<?= url('servicos/' . $servico['id'] . '/editar') ?>">Editar</a>&nbsp;&nbsp;&nbsp;<form
                    class="inline" method="post" action="<?= url('servicos/' . $servico['id'] . '/excluir') ?>"
                    onsubmit="return confirm('Remover este serviço?');"><input type="hidden" name="csrf"
                                                                               value="<?= Csrf::token() ?>">
                    <button class="link danger">Excluir</button>
                </form>
            </td></tr><?php endforeach; ?>
        <?php if (!$servicos): ?>
            <tr>
                <td colspan="5">Nenhum serviço cadastrado.</td>
            </tr><?php endif; ?></tbody>
    </table>
<?php require __DIR__ . '/../layout/footer.php'; ?>