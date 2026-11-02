<?php
declare(strict_types=1);

final class AuthController
{
    public function __construct(private Usuario $usuarios)
    {
    }

    public function form(): void
    {
        if (Auth::check()) redirect('barbeiros');
        view('auth/login');
    }

    public function login(): void
    {
        Csrf::validate();
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: '';
        $senha = (string)($_POST['senha'] ?? '');
        $usuario = $email ? $this->usuarios->byEmail($email) : null;
        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            flash('erro', 'E-mail ou senha inválidos.');
            redirect('login');
        }
        Auth::login($usuario);
        $this->usuarios->updateLastAccess((int)$usuario['id']);
        redirect('barbeiros');
    }

    public function logout(): void
    {
        Csrf::validate();
        Auth::logout();
        redirect('login');
    }

    public function passwordForm(): void
    {
        Auth::requireLogin();
        view('auth/senha');
    }

    public function changePassword(): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $atual = (string)($_POST['senha_atual'] ?? '');
        $nova = (string)($_POST['nova_senha'] ?? '');
        $confirmacao = (string)($_POST['confirmacao_senha'] ?? '');
        $usuario = $this->usuarios->findForPassword((int)Auth::user()['id']);
        if (!$usuario || !password_verify($atual, $usuario['senha_hash'])) {
            flash('erro', 'A senha atual está incorreta.');
            redirect('senha');
        }
        if (strlen($nova) < 8 || $nova !== $confirmacao) {
            flash('erro', 'A nova senha precisa ter ao menos 8 caracteres e ser igual à confirmação.');
            redirect('senha');
        }
        $this->usuarios->updatePassword((int)$usuario['id'], password_hash($nova, PASSWORD_DEFAULT));
        flash('sucesso', 'Senha alterada com sucesso.');
        redirect('barbeiros');
    }

    public function registerForm(): void
    {
        $this->requireAdmin();
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        view('auth/cadastro', ['old' => $old]);
    }

    public function register(): void
    {
        $this->requireAdmin();
        Csrf::validate();
        $nome = trim((string)($_POST['nome'] ?? ''));
        $emailBruto = trim((string)($_POST['email'] ?? ''));
        $email = filter_var($emailBruto, FILTER_VALIDATE_EMAIL) ?: '';
        $senha = (string)($_POST['senha'] ?? '');
        $confirmacao = (string)($_POST['confirmacao_senha'] ?? '');
        $_SESSION['old'] = ['nome' => $nome, 'email' => $emailBruto]; // para não perder o que foi digitado

        if ($nome === '' || mb_strlen($nome) > 120) {
            flash('erro', 'Informe o nome (até 120 caracteres).');
            redirect('usuarios/novo');
        }
        if ($email === '' || strlen($email) > 150) {
            flash('erro', 'Informe um e-mail válido.');
            redirect('usuarios/novo');
        }
        if (strlen($senha) < 8 || $senha !== $confirmacao) {
            flash('erro', 'A senha precisa ter ao menos 8 caracteres e ser igual à confirmação.');
            redirect('usuarios/novo');
        }
        if ($this->usuarios->emailExists($email)) {
            flash('erro', 'Já existe um usuário com esse e-mail.');
            redirect('usuarios/novo');
        }

        try {
            $this->usuarios->create($nome, $email, password_hash($senha, PASSWORD_DEFAULT));
        } catch (PDOException $erro) {
            flash('erro', 'Não foi possível cadastrar o administrador. Tente novamente.');
            redirect('usuarios/novo');
        }

        unset($_SESSION['old']);
        flash('sucesso', 'Administrador cadastrado com sucesso.');
        redirect('barbeiros');
    }

    private function requireAdmin(): void
    {
        Auth::requireLogin();
        if (Auth::user()['perfil'] !== 'ADMINISTRADOR') {
            http_response_code(403);
            exit('Acesso negado.');
        }
    }
}
