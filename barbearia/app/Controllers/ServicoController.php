<?php
declare(strict_types=1);

/*não vai funcionar pois está sem view ainda, eu acho*/
final class ServicoController
{

    public function __construct(private Servicos $servicos)
    {
    }

    public function index(): void
    {
        Auth::requireLogin();
        view('servicos/index', ['servicos' => $this->servicos->All()]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        view('servicos/form', ['servico' => null, 'titulo' => 'Novo serviço']);
    }

    public function edit(int $id): void
    {
        Auth::requireLogin();
        $servico = $this->servicos->find($id);
        if (!$servico) {
            http_response_code(404);
            exit('Serviço não encontrado.');
        }
        view('servicos/form', compact('servico') + ['titulo' => 'Editar serviço']);
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
        $this->servicos->delete($id);
        flash('sucesso', 'Serviço removido.');
        redirect('servicos');
    }

    private function save(?int $id): void
    {
        $dados = $this->validated($id === null ? 'servicos/novo' : 'servicos/' . $id . '/editar');

        try {
            if ($id === null) {
                $this->servicos->create($dados);
                flash('sucesso', 'Serviço cadastrado.');
            } else {
                $this->servicos->update($id, $dados);
                flash('sucesso', 'Serviço atualizado.');
            }
        } catch (PDOException $erro) {
            flash('erro', 'Não foi possível salvar o serviço. Tente novamente.');
            redirect($id === null ? 'servicos/novo' : 'servicos/' . $id . '/editar');
        }

        redirect('servicos');
    }


    /*validação suprema php, máximo 3 horas (180 minutos)*/
    private function validated(string $rotaEmCasoDeErro): array
    {
        $nome = trim((string)($_POST['nome'] ?? ''));
        $precoBruto = trim((string)($_POST['preco'] ?? ''));
        $duracaoBruta = trim((string)($_POST['duracao'] ?? ''));

        $precoValido = is_numeric($precoBruto) && (float)$precoBruto > 0 && (float)$precoBruto <= 9999.99;
        $duracaoValida = filter_var($duracaoBruta, FILTER_VALIDATE_INT) !== false
            && (int)$duracaoBruta >= 1
            && (int)$duracaoBruta <= 180;

        if ($nome === '' || mb_strlen($nome) > 120 || !$precoValido || !$duracaoValida) {
            flash('erro', 'Informe nome, preço (maior que zero) e duração em minutos (entre 1 e 180) válidos.');
            redirect($rotaEmCasoDeErro);
        }

        return [
            'nome' => $nome,
            'preco' => (float)$precoBruto,
            'duracao' => (int)$duracaoBruta,
            'status' => isset($_POST['status']) ? 1 : 0,
        ];
    }

}