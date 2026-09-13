<?php
declare(strict_types=1);

final class BarbeiroController
{
    public function __construct(private Barbeiro $barbeiros)
    {
    }

    public function index(): void
    {
        Auth::requireLogin();
        view('barbeiros/index', ['barbeiros' => $this->barbeiros->all()]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        view('barbeiros/form', ['barbeiro' => null, 'titulo' => 'Novo barbeiro']);
    }

    public function edit(int $id): void
    {
        Auth::requireLogin();
        $barbeiro = $this->barbeiros->find($id);
        if (!$barbeiro) {
            http_response_code(404);
            exit('Barbeiro não encontrado.');
        }
        view('barbeiros/form', compact('barbeiro') + ['titulo' => 'Editar barbeiro']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $this->save(null);
    }

    public function update(int $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $this->save($id);
    }

    public function destroy(int $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $this->barbeiros->delete($id);
        flash('sucesso', 'Barbeiro removido.');
        redirect('barbeiros');
    }

    private function save(?int $id): void
    {
        $dados = $this->validated($id === null ? 'barbeiros/novo' : 'barbeiros/' . $id . '/editar');

        try {
            if ($id === null) {
                $this->barbeiros->create($dados);
                flash('sucesso', 'Barbeiro cadastrado.');
            } else {
                $this->barbeiros->update($id, $dados);
                flash('sucesso', 'Barbeiro atualizado.');
            }
        } catch (PDOException $erro) {
            if ($erro->getCode() === '23000') {
                flash('erro', 'Já existe um barbeiro cadastrado com este e-mail.');
                redirect($id === null ? 'barbeiros/novo' : 'barbeiros/' . $id . '/editar');
            }
            flash('erro', 'Não foi possível salvar o barbeiro. Tente novamente.');
            redirect($id === null ? 'barbeiros/novo' : 'barbeiros/' . $id . '/editar');
        }

        redirect('barbeiros');
    }

    private function validated(string $rotaEmCasoDeErro): array
    {
        $nome = trim((string)($_POST['nome'] ?? ''));
        $telefone = trim((string)($_POST['telefone'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $especialidade = trim((string)($_POST['especialidade'] ?? ''));
        if ($nome === '' || mb_strlen($nome) > 120 || !preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $telefone) || ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150))) {
            flash('erro', 'Informe nome, telefone no formato (47) 00000-0000 e um e-mail válido.');
            redirect($rotaEmCasoDeErro);
        }
        return ['nome' => $nome, 'telefone' => $telefone, 'email' => $email ?: null, 'especialidade' => $especialidade ?: null, 'ativo' => isset($_POST['ativo']) ? 1 : 0];
    }
}
