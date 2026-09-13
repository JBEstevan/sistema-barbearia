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
}
