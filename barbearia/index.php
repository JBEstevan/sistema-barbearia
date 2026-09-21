<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');

foreach (glob(__DIR__ . '/app/Core/*.php') as $file) require_once $file;
foreach (glob(__DIR__ . '/app/Models/*.php') as $file) require_once $file;
foreach (glob(__DIR__ . '/app/Controllers/*.php') as $file) require_once $file;

$config = require __DIR__ . '/config/config.php';
Auth::start();

try {
    $db = Database::connect($config);

    $auth = new AuthController(new Usuario($db));
    $barbeiros = new BarbeiroController(new Barbeiro($db));
    $servicos = new ServicoController(new Servicos($db));
    $route = trim((string)($_GET['r'] ?? ''), '/');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($route === '' || $route === 'login') {
        $method === 'POST' ? $auth->login() : $auth->form();
    } elseif ($route === 'logout' && $method === 'POST') {
        $auth->logout();
    } elseif ($route === 'senha' && $method === 'GET') {
        $auth->passwordForm();
    } elseif ($route === 'senha' && $method === 'POST') {
        $auth->changePassword();
    } elseif ($route === 'barbeiros' && $method === 'GET') {
        $barbeiros->index();
    } elseif ($route === 'barbeiros/novo' && $method === 'GET') {
        $barbeiros->create();
    } elseif ($route === 'barbeiros' && $method === 'POST') {
        $barbeiros->store();
    } elseif (preg_match('#^barbeiros/(\d+)/editar$#', $route, $m) && $method === 'GET') {
        $barbeiros->edit((int)$m[1]);
    } elseif (preg_match('#^barbeiros/(\d+)/atualizar$#', $route, $m) && $method === 'POST') {
        $barbeiros->update((int)$m[1]);
    } elseif (preg_match('#^barbeiros/(\d+)/excluir$#', $route, $m) && $method === 'POST') {
        $barbeiros->destroy((int)$m[1]);
    } elseif ($route === 'servicos' && $method === 'GET') {
        $servicos->index();
    } elseif ($route === 'servicos/novo' && $method === 'GET') {
        $servicos->create();
    } elseif ($route === 'servicos' && $method === 'POST') {
        $servicos->store();
    } elseif (preg_match('#^servicos/(\d+)/editar$#', $route, $m) && $method === 'GET') {
        $servicos->edit((int)$m[1]);
    } elseif (preg_match('#^servicos/(\d+)/atualizar$#', $route, $m) && $method === 'POST') {
        $servicos->update((int)$m[1]);
    } elseif (preg_match('#^servicos/(\d+)/excluir$#', $route, $m) && $method === 'POST') {
        $servicos->destroy((int)$m[1]);
    } else {
        http_response_code(404);
        trigger_error("Rota não encontrada: {$route}", E_USER_WARNING);
        echo 'Página não encontrada.';
    }
} catch (PDOException $e) {
    error_log('Erro de banco de dados: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco. Confira config/config.php.');
} catch (Throwable $e) {
    // Qualquer outro erro inesperado é registrado no log, sem expor detalhes ao usuário.
    error_log('Erro inesperado: ' . $e->getMessage());
    http_response_code(500);
    exit('ocorreu um erro inexperado');
}
