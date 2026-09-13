<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="card login"><h1>Acesso ao sistema</h1>
    <form method="post" action="<?= url('login') ?>"><input type="hidden" name="csrf"
                                                            value="<?= Csrf::token() ?>"><label>E-mail<input
                    name="email" type="email" required autocomplete="email"></label><label>Senha<input name="senha"
                                                                                                       type="password"
                                                                                                       required
                                                                                                       autocomplete="current-password"></label>
        <button type="submit">Entrar</button>
    </form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
