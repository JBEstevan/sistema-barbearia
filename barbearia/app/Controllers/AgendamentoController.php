<?php
declare(strict_types=1);

final class AgendamentoController
{
    public function __construct(
        private Agendamento $agendamentos,
        private Barbeiro $barbeiros,
        private Servicos $servicos
    ) {
    }

    public function index(): void
    {
        Auth::requireLogin();
        view('agendamentos/index', ['agendamentos' => $this->agendamentos->all()]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $barbeirosAtivos = array_filter($this->barbeiros->all(), fn($b) => (int)$b['ativo'] === 1);
        $servicosAtivos = $this->servicos->ativos();

        if (empty($barbeirosAtivos)) {
            flash('erro', 'Cadastre ao menos um barbeiro ativo antes de realizar um agendamento.');
            redirect('barbeiros/novo');
        }

        if (empty($servicosAtivos)) {
            flash('erro', 'Cadastre ao menos um serviço ativo antes de realizar um agendamento.');
            redirect('servicos/novo');
        }

        view('agendamentos/form', [
            'agendamento' => null,
            'barbeiros' => $barbeirosAtivos,
            'servicos' => $servicosAtivos,
            'titulo' => 'Novo agendamento'
        ]);
    }

    public function edit(int $id): void
    {
        Auth::requireLogin();
        $agendamento = $this->agendamentos->find($id);
        if (!$agendamento) {
            http_response_code(404);
            exit('Agendamento não encontrado.');
        }

        view('agendamentos/form', [
            'agendamento' => $agendamento,
            'barbeiros' => $this->barbeiros->all(),
            'servicos' => $this->servicos->all(),
            'titulo' => 'Editar agendamento'
        ]);
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
        $this->agendamentos->delete($id);
        flash('sucesso', 'Agendamento removido.');
        redirect('agendamentos');
    }

    private function save(?int $id): void
    {
        $rotaErro = $id === null ? 'agendamentos/novo' : 'agendamentos/' . $id . '/editar';
        $dados = $this->validated($rotaErro, $id);

        try {
            if ($id === null) {
                $this->agendamentos->create($dados);
                flash('sucesso', 'Agendamento cadastrado com sucesso.');
            } else {
                $this->agendamentos->update($id, $dados);
                flash('sucesso', 'Agendamento atualizado.');
            }
        } catch (PDOException $erro) {
            flash('erro', 'Não foi possível salvar o agendamento. Tente novamente.');
            redirect($rotaErro);
        }

        redirect('agendamentos');
    }

    /**
     * Validação no lado do servidor com regra de conflito de horário.
     */
    private function validated(string $rotaEmCasoDeErro, ?int $idAtual = null): array
    {
        $clienteNome = trim((string)($_POST['cliente_nome'] ?? ''));
        $clienteTelefone = trim((string)($_POST['cliente_telefone'] ?? ''));
        $barbeiroId = filter_input(INPUT_POST, 'barbeiro_id', FILTER_VALIDATE_INT) ?: 0;
        $servicoId = filter_input(INPUT_POST, 'servico_id', FILTER_VALIDATE_INT) ?: 0;
        $data = trim((string)($_POST['data'] ?? ''));
        $hora = trim((string)($_POST['hora'] ?? ''));
        $observacoes = trim((string)($_POST['observacoes'] ?? ''));

        // Validação de campos obrigatórios
        if ($clienteNome === '' || mb_strlen($clienteNome) > 120) {
            flash('erro', 'Informe o nome do cliente (até 120 caracteres).');
            redirect($rotaEmCasoDeErro);
        }

        if (!preg_match('/^\(\d{2}\) \d{4,5}-\d{4}$/', $clienteTelefone)) {
            flash('erro', 'Informe um telefone no formato (47) 00000-0000.');
            redirect($rotaEmCasoDeErro);
        }

        if ($barbeiroId <= 0 || $servicoId <= 0) {
            flash('erro', 'Selecione um barbeiro e um serviço válidos.');
            redirect($rotaEmCasoDeErro);
        }

        if ($data === '' || $hora === '') {
            flash('erro', 'Informe a data e o horário do agendamento.');
            redirect($rotaEmCasoDeErro);
        }

        // Validação de formato de data e hora
        $dataHoraStr = $data . ' ' . $hora . ':00';
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $dataHoraStr);
        if (!$dt || $dt->format('Y-m-d H:i:s') !== $dataHoraStr) {
            flash('erro', 'Data ou horário inválidos.');
            redirect($rotaEmCasoDeErro);
        }

        // Regra de Negócio: Verificar se o barbeiro já tem cliente marcado no memso horário
        if ($this->agendamentos->hasConflict($barbeiroId, $dataHoraStr, $idAtual)) {
            flash('erro', 'Este barbeiro já possui um agendamento marcado para este horário. Escolha outro horário.');
            redirect($rotaEmCasoDeErro);
        }

        return [
            'cliente_nome' => $clienteNome,
            'cliente_telefone' => $clienteTelefone,
            'barbeiro_id' => $barbeiroId,
            'servico_id' => $servicoId,
            'data_hora' => $dataHoraStr,
            'observacoes' => $observacoes !== '' ? $observacoes : null,
        ];
    }
}
