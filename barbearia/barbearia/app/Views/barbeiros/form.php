<?php require __DIR__ . '/../layout/header.php';
$editando = $barbeiro !== null; ?>
<section class="card"><h1><?= e($titulo) ?></h1>
    <form method="post" action="<?= url($editando ? 'barbeiros/' . $barbeiro['id'] . '/atualizar' : 'barbeiros') ?>">
        <input type="hidden" name="csrf" value="<?= Csrf::token() ?>"><label>Nome<input name="nome" maxlength="120"
                                                                                        required
                                                                                        value="<?= e($barbeiro['nome'] ?? '') ?>"></label><label>Telefone<input
                    name="telefone" class="telefone" maxlength="15" pattern="\(\d{2}\) \d{5}-\d{4}" inputmode="numeric"
                    required placeholder="(47) 0000-0000" title="Use o formato (47) 0000-0000"
                    value="<?= e($barbeiro['telefone'] ?? '') ?>"><small>Formato obrigatório: (47)
                0000-0000</small></label><label>E-mail<input name="email" type="email" maxlength="150" inputmode="email"
                                                             autocomplete="email" placeholder="exemplo@barbearia.com"
                                                             title="Exemplo: exemplo@barbearia.com"
                                                             value="<?= e($barbeiro['email'] ?? '') ?>"><small>Exemplo:
                exemplo@barbearia.com</small></label><label>Especialidade<input name="especialidade" maxlength="150"
                                                                                value="<?= e($barbeiro['especialidade'] ?? '') ?>"></label><label
                class="check"><input name="ativo"
                                     type="checkbox" <?= (!$editando || $barbeiro['ativo']) ? 'checked' : '' ?>>
            Ativo</label>
        <button type="submit">Salvar</button>
        <a href="<?= url('barbeiros') ?>">Cancelar</a></form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
