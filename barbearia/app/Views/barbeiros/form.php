<?php require __DIR__ . '/../layout/header.php';
$editando = $barbeiro !== null; ?>
<form class="form-card" method="post"
      action="<?= url($editando ? 'barbeiros/' . $barbeiro['id'] . '/atualizar' : 'barbeiros') ?>" data-validar novalidate>
    <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

    <h1 class="form-titulo"><?= e($titulo) ?></h1>
    <p class="form-subtitulo">Os campos marcados com * são obrigatórios.</p>

    <div class="form-grid">
        <div class="campo cheio">
            <label for="nome">Nome *</label>
            <input id="nome" name="nome" maxlength="120" required autocomplete="off"
                   value="<?= e($barbeiro['nome'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="telefone">Telefone *</label>
            <input id="telefone" name="telefone" class="telefone" maxlength="15" inputmode="numeric" required
                   pattern="\(\d{2}\) \d{5}-\d{4}" placeholder="(47) 91234-5678"
                   data-erro-padrao="Use o formato (47) 91234-5678."
                   value="<?= e($barbeiro['telefone'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" maxlength="150" inputmode="email" autocomplete="email"
                   placeholder="nome@exemplo.com" value="<?= e($barbeiro['email'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo cheio">
            <label for="especialidade">Especialidade</label>
            <input id="especialidade" name="especialidade" maxlength="150" autocomplete="off"
                   value="<?= e($barbeiro['especialidade'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="cheio">
            <label class="interruptor">
                <input name="ativo" type="checkbox" <?= (!$editando || $barbeiro['ativo']) ? 'checked' : '' ?>>
                <span class="interruptor-trilho"></span>
                <span class="interruptor-texto">Ativo</span>
            </label>
        </div>
    </div>

    <div class="form-acoes">
        <button type="submit" class="btn-cta" data-enviando="Salvando...">
            <span><?= $editando ? 'Salvar alterações' : 'Cadastrar barbeiro' ?></span>
        </button>
        <a class="btn-secundario" href="<?= url('barbeiros') ?>">Cancelar</a>
    </div>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>