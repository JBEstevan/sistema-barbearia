<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="auth">
    <form class="auth-card" method="post" action="<?= url('senha') ?>" novalidate>
        <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

        <h1 class="auth-title">Alterar senha</h1>
        <p class="auth-subtitle">Confirme sua senha atual e escolha uma nova.</p>

        <div class="campo">
            <label for="senha_atual">Senha atual</label>
            <div class="campo-senha">
                <input id="senha_atual" name="senha_atual" type="password" required autocomplete="current-password">
                <button type="button" class="alternar-senha" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="nova_senha">Nova senha</label>
            <div class="campo-senha">
                <input id="nova_senha" name="nova_senha" type="password" minlength="8" required autocomplete="new-password">
                <button type="button" class="alternar-senha" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <div class="forca" data-forca hidden>
                <div class="forca-barra"><span></span><span></span><span></span><span></span></div>
                <small class="forca-texto"></small>
            </div>
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="confirmacao_senha">Confirmar nova senha</label>
            <div class="campo-senha">
                <input id="confirmacao_senha" name="confirmacao_senha" type="password" minlength="8" required
                       autocomplete="new-password" data-igual-a="nova_senha">
                <button type="button" class="alternar-senha" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <small class="campo-erro"></small>
        </div>

        <button type="submit" class="auth-cta" data-enviando="Salvando...">
            <span class="auth-cta-texto">Alterar senha</span>
        </button>
        <a class="auth-voltar" href="<?= url('barbeiros') ?>">Cancelar</a>
    </form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>