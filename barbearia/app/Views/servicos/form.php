<?php require __DIR__ . '/../layout/header.php';
$editando = $servico !== null; ?>
<form class="form-card" method="post"
      action="<?= url($editando ? 'servicos/' . $servico['id'] . '/atualizar' : 'servicos') ?>" data-validar novalidate>
    <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

    <h1 class="form-titulo"><?= e($titulo) ?></h1>
    <p class="form-subtitulo">Todos os campos são obrigatórios.</p>

    <div class="form-grid">
        <div class="campo cheio">
            <label for="nome">Nome *</label>
            <input id="nome" name="nome" maxlength="120" required autocomplete="off"
                   value="<?= e($servico['nome'] ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="preco">Preço *</label>
            <div class="campo-unidade">
                <span class="unidade prefixo">R$</span>
                <input id="preco" name="preco" type="number" class="com-prefixo" inputmode="decimal"
                       step="0.01" min="0.01" max="9999.99" required placeholder="35.00"
                       data-erro-faixa="O preço deve ficar entre R$ 0,01 e R$ 9.999,99."
                       data-erro-passo="Use no máximo duas casas decimais."
                       value="<?= isset($servico['preco']) ? number_format((float)$servico['preco'], 2, '.', '') : '' ?>">
            </div>
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="duracao">Duração *</label>
            <div class="campo-unidade">
                <input id="duracao" name="duracao" type="number" class="com-sufixo" inputmode="numeric"
                       step="1" min="1" max="180" required placeholder="30"
                       data-erro-faixa="A duração deve ficar entre 1 e 180 minutos."
                       data-erro-passo="Use um número inteiro de minutos."
                       value="<?= isset($servico['duracao']) ? (int)$servico['duracao'] : '' ?>">
                <span class="unidade sufixo">min</span>
            </div>
            <small class="campo-erro"></small>
        </div>

        <div class="cheio">
            <label class="interruptor">
                <input name="status" type="checkbox" <?= (!$editando || $servico['status']) ? 'checked' : '' ?>>
                <span class="interruptor-trilho"></span>
                <span class="interruptor-texto">Ativo</span>
            </label>
        </div>
    </div>

    <div class="form-acoes">
        <button type="submit" class="btn-cta" data-enviando="Salvando...">
            <span><?= $editando ? 'Salvar alterações' : 'Cadastrar serviço' ?></span>
        </button>
        <a class="btn-secundario" href="<?= url('servicos') ?>">Cancelar</a>
    </div>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>