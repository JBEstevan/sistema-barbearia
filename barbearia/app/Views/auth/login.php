<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="auth">
    <form class="auth-card" method="post" action="<?= url('login') ?>" novalidate>
        <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

        <h1 class="auth-title">Acesso ao sistema</h1>
        <p class="auth-subtitle">Entre com suas credenciais de administrador.</p>

        <div class="campo">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" required autocomplete="email">
            <small class="campo-erro" data-erro-de="email"></small>
        </div>

        <div class="campo">
            <label for="senha">Senha</label>
            <div class="campo-senha">
                <input id="senha" name="senha" type="password" required autocomplete="current-password">
                <button type="button" class="alternar-senha" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <small class="campo-erro" data-erro-de="senha"></small>
        </div>

        <button type="submit" class="auth-cta" data-enviando="Entrando...">
            <span class="auth-cta-texto">Entrar</span>
        </button>
    </form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>