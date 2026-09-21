<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php if (Auth::check()): ?>
    <header><strong><?= e($config['app_name']) ?></strong>
    <nav><a href="<?= url('barbeiros') ?>">Barbeiros</a><a href="<?= url('servicos') ?>">Serviços</a><a href="<?= url('senha') ?>">Alterar
            senha</a><span><?= e(Auth::user()['nome']) ?></span>
        <form method="post" action="<?= url('logout') ?>"><input type="hidden" name="csrf" value="<?= Csrf::token() ?>">
            <button>Sair</button>
        </form>
    </nav></header><?php endif; ?>
<main>
    <?php if ($message = flash('sucesso')): ?><p class="alert success"><?= e($message) ?></p><?php endif; ?>
    <?php if ($message = flash('erro')): ?><p class="alert error"><?= e($message) ?></p><?php endif; ?>
