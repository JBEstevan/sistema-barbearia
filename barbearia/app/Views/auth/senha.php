<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="card"><h1>Alterar senha</h1>
    <form method="post" action="<?= url('senha') ?>"><input type="hidden" name="csrf"
                                                            value="<?= Csrf::token() ?>"><label>Senha atual<input
                    name="senha_atual" type="password" required autocomplete="current-password"></label><label>Nova
            senha<input name="nova_senha" type="password" minlength="8" required autocomplete="new-password"><small>Use
                ao menos 8 caracteres.</small></label><label>Confirmar nova senha<input name="confirmacao_senha"
                                                                                        type="password" minlength="8"
                                                                                        required
                                                                                        autocomplete="new-password"></label>
        <button type="submit">Alterar senha</button>
        <a href="<?= url('barbeiros') ?>">Cancelar</a></form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
