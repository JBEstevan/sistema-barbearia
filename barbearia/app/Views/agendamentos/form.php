<?php
require __DIR__ . '/../layout/header.php';
$editando = $agendamento !== null;

$dt = $editando && !empty($agendamento->data_hora) ? new DateTime($agendamento->data_hora) : new DateTime();
$dataValor = $dt->format('Y-m-d');
$horaValor = $dt->format('H:i');
?>
<form class="form-card" method="post"
      action="<?= url($editando ? 'agendamentos/' . $agendamento->id . '/atualizar' : 'agendamentos') ?>" data-validar novalidate>
    <input type="hidden" name="csrf" value="<?= Csrf::token() ?>">

    <h1 class="form-titulo"><?= e($titulo) ?></h1>
    <p class="form-subtitulo">Os campos marcados com * são obrigatórios.</p>

    <div class="form-grid">
        <div class="campo">
            <label for="cliente_nome">Nome do cliente *</label>
            <input id="cliente_nome" name="cliente_nome" maxlength="120" required autocomplete="off"
                   value="<?= e($agendamento->cliente_nome ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="cliente_telefone">Telefone do cliente *</label>
            <input id="cliente_telefone" name="cliente_telefone" class="telefone" maxlength="15" inputmode="numeric" required
                   pattern="\(\d{2}\) \d{5}-\d{4}" placeholder="(47) 91234-5678"
                   data-erro-padrao="Use o formato (47) 91234-5678."
                   value="<?= e($agendamento->cliente_telefone ?? '') ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="barbeiro_id">Barbeiro *</label>
            <select id="barbeiro_id" name="barbeiro_id" required>
                <option value="">Selecione...</option>
                <?php foreach ($barbeiros as $b): ?>
                    <option value="<?= (int)$b->id ?>" <?= ($editando && (int)$agendamento->barbeiro_id === (int)$b->id) ? 'selected' : '' ?>>
                        <?= e($b->nome) ?><?= !empty($b->especialidade) ? ' — ' . e($b->especialidade) : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="servico_id">Serviço *</label>
            <select id="servico_id" name="servico_id" required>
                <option value="">Selecione...</option>
                <?php foreach ($servicos as $s): ?>
                    <option value="<?= (int)$s->id ?>" <?= ($editando && (int)$agendamento->servico_id === (int)$s->id) ? 'selected' : '' ?>>
                        <?= e($s->nome) ?> (R$ <?= number_format((float)$s->preco, 2, ',', '.') ?> · <?= (int)$s->duracao ?> min)
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="data">Data *</label>
            <input id="data" name="data" type="date" required value="<?= e($dataValor) ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo">
            <label for="hora">Horário *</label>
            <input id="hora" name="hora" type="time" required value="<?= e($horaValor) ?>">
            <small class="campo-erro"></small>
        </div>

        <div class="campo cheio">
            <label for="observacoes">Observações</label>
            <input id="observacoes" name="observacoes" maxlength="255" placeholder="Opcional" autocomplete="off"
                   value="<?= e($agendamento->observacoes ?? '') ?>">
            <small class="campo-erro"></small>
        </div>
    </div>

    <div class="form-acoes">
        <button type="submit" class="btn-cta" data-enviando="Salvando...">
            <span><?= $editando ? 'Salvar alterações' : 'Agendar horário' ?></span>
        </button>
        <a class="btn-secundario" href="<?= url('agendamentos') ?>">Cancelar</a>
    </div>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>
