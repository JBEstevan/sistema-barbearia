<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="auth">
    <form class="auth-card" method="post" action="<?= url('usuarios') ?>" novalidate>
        <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

        <h1 class="auth-title">Cadastrar administrador</h1>
        <p class="auth-subtitle">O novo administrador terá acesso total ao sistema.</p>

        <div class="campo">
            <label for="nome">Nome</label>
            <input id="nome" name="nome" maxlength="120" required autocomplete="off" value="<?= e($old['nome'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" maxlength="150" required autocomplete="off" value="<?= e($old['email'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="senha">Senha</label>
            <div class="campo-senha">
                <input id="senha" name="senha" type="password" minlength="8" required autocomplete="new-password">
                <button type="button" class="alternar-senha" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <div class="forca" data-forca hidden>
                <div class="forca-barra"><span></span><span></span><span></span><span></span></div>
                <small class="forca-texto"></small>
            </div>
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="confirmacao_senha">Confirmar senha</label>
            <div class="campo-senha">
                <input id="confirmacao_senha" name="confirmacao_senha" type="password" minlength="8" required
                       autocomplete="new-password" data-igual-a="senha">
                <button type="button" class="alternar-senha" aria-label="Mostrar senha">Mostrar</button>
            </div>
            <small class="campo-erro"></small>
        </div>

        <button type="submit" class="auth-cta" data-enviando="Cadastrando...">
            <span class="auth-cta-texto">Cadastrar administrador</span>
        </button>
        <a class="auth-voltar" href="<?= url('barbeiros') ?>">Cancelar</a>
    </form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
