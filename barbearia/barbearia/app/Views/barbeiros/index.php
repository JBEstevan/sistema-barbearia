<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="page-title"><h1>Barbeiros</h1><a class="button" href="<?= url('barbeiros/novo') ?>">Cadastrar barbeiro</a>
</div>
<table>
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
        <tr>
        <td><?= e($barbeiro['nome']) ?></td>
        <td><?= e($barbeiro['telefone']) ?></td>
        <td><?= e($barbeiro['email']) ?></td>
        <td><?= e($barbeiro['especialidade']) ?></td>
        <td><?= $barbeiro['ativo'] ? 'Ativo' : 'Inativo' ?></td>
        <td><a href="<?= url('barbeiros/' . $barbeiro['id'] . '/editar') ?>">Editar</a>&nbsp;&nbsp;&nbsp;<form
                    class="inline" method="post" action="<?= url('barbeiros/' . $barbeiro['id'] . '/excluir') ?>"
                    onsubmit="return confirm('Remover este barbeiro?');"><input type="hidden" name="csrf"
                                                                                value="<?= Csrf::token() ?>">
                <button class="link danger">Excluir</button>
            </form>
        </td></tr><?php endforeach; ?>
    <?php if (!$barbeiros): ?>
        <tr>
            <td colspan="6">Nenhum barbeiro cadastrado.</td>
        </tr><?php endif; ?></tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>
