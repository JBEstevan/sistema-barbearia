<?php require __DIR__ . '/../layout/header.php';
$editando = $servico !== null; ?>
    <section class="card"><h1><?= e($titulo) ?></h1>
        <form method="post" action="<?= url($editando ? 'servicos/' . $servico['id'] . '/atualizar' : 'servicos') ?>">
            <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">
            <label>Nome
                <input name="nome" maxlength="120" required value="<?= e($servico['nome'] ?? '') ?>">
            </label>
            <label>Preço (R$)
                <input name="preco" type="number" step="0.01" min="0.01" max="9999.99" required
                       value="<?= isset($servico['preco']) ? number_format((float)$servico['preco'], 2, '.', '') : '' ?>"
                       placeholder="35.00">
                <small>Use ponto para os centavos, ex: 35.00</small>
            </label>
            <label>Duração (minutos)
                <input name="duracao" type="number" step="1" min="1" max="180" required
                       value="<?= isset($servico['duracao']) ? (int)$servico['duracao'] : '' ?>"
                       placeholder="30">
                <small>Entre 1 e 180 minutos</small>
            </label>
            <label class="check">
                <input name="status" type="checkbox" <?= (!$editando || $servico['status']) ? 'checked' : '' ?>>
                Ativo
            </label>
            <button type="submit">Salvar</button>
            <a class="cancelar" href="<?= url('servicos') ?>">Cancelar</a>
        </form>
    </section>
<?php require __DIR__ . '/../layout/footer.php'; ?>