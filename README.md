# Sistema de Gestão de Barbearia

Sistema web desenvolvido em **PHP 8+** e **MySQL** para gerenciamento de uma barbearia, implementando arquitetura MVC nativa, autenticação por sessão, controle de profissionais, catálogo de serviços e operação de agendamentos com validações de conflito de horários no servidor.

---

## 👥 1. Integrantes da Equipe & Divisão de Tarefas

| Integrante | Papel Principal | Tarefas e CRUDs Desenvolvidos |
| :--- | :--- | :--- |
| **Gabriel Lidani** | Segurança & Equipe | Criação da estrutura base do Roteador (`index.php`), implementação da Autenticação/Sessão segura (`Auth`, `Csrf`, `Database`), telas de login/senha e desenvolvimento completo do **CRUD de Barbeiros** (`BarbeiroController`, `Barbeiro`, views e validações). |
| **Antony Rinaldi** | Interface & Catálogo | Criação do arquivo base de estilo visual (`style.css`) e desenvolvimento completo do **CRUD de Serviços** (`ServicoController`, `Servicos`, views com validações de preço positivo e duração). |
| **Juan Estevan** | Operação (Agendamentos) | Desenvolvimento completo do **CRUD de Agendamentos** (`AgendamentoController`, `Agendamento`, views de listagem e formulário) com a **regra de negócio de validação no PHP para evitar horário duplicado no mesmo barbeiro**, atualização do script do banco com seeds e documentação do projeto no README. |

---

## 🚀 2. Requisitos do Sistema

- **PHP 8.0+** (com extensões `pdo_mysql`, `mbstring`, `session`)
- **MySQL 8.0+** ou **MariaDB 10.4+**
- **Servidor Web:** Apache (XAMPP / Laragon / WampServer)
- **Navegador Web** (Chrome, Firefox, Edge, etc.)

---

## ⚙️ 3. Instalação e Configuração

### Passo 1: Clonar o Repositório
```bash
git clone https://github.com/JBEstevan/sistema-barbearia.git
cd sistema-barbearia
```

### Passo 2: Importar o Banco de Dados
1. Abra o **phpMyAdmin** (`http://localhost/phpmyadmin`) ou o terminal do MySQL.
2. Importe o script SQL localizado em `barbearia/database/barbearia.sql` (ou `barbearia.sql` na raiz).
3. O script criará o banco `barbearia` com as 4 tabelas relacionais (`usuarios`, `barbeiros`, `servicos`, `agendamentos`) e dados de teste.

### Passo 3: Configurar a Conexão
Se necessário, ajuste as credenciais de banco no arquivo `barbearia/config/config.php`:
```php
<?php
declare(strict_types=1);

return [
    'app_name' => 'Barbearia',
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'name'     => 'barbearia',
        'user'     => 'root',
        'password' => '', // Senha do seu MySQL (se tiver)
    ],
];
```

### Passo 4: Executar o Projeto

#### Via Servidor Embutido do PHP:
```bash
cd barbearia
php -S localhost:8000
```
Acesse no navegador: **`http://localhost:8000`**

#### Via XAMPP:
Copie a pasta para `C:\xampp\htdocs\` e acesse **`http://localhost/sistema-barbearia/barbearia/`**.

---

## 🔐 4. Credenciais de Demonstração

- **E-mail:** `admin@barbearia.com`
- **Senha:** `password`

---

## 🏗️ 5. Estrutura do Projeto (Padrão MVC em PHP Nativo)

```text
barbearia/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login, Logout e Troca de Senha
│   │   ├── BarbeiroController.php      # CRUD de Barbeiros
│   │   ├── ServicoController.php       # CRUD de Serviços
│   │   └── AgendamentoController.php   # CRUD de Agendamentos (com validação de conflito)
│   ├── Core/
│   │   ├── Auth.php                    # Controle de Sessão e Login
│   │   ├── Csrf.php                    # Proteção contra ataques CSRF
│   │   ├── Database.php                # Conexão PDO segura
│   │   ├── helpers.php                 # Funções auxiliares (url, flash, redirect, e)
│   │   └── view.php                    # Renderização de views
│   ├── Models/
│   │   ├── Usuario.php
│   │   ├── Barbeiro.php
│   │   ├── Servicos.php
│   │   └── Agendamento.php
│   └── Views/
│       ├── auth/                       # Login e Alterar Senha
│       ├── barbeiros/                  # Listagem e Formulário de Barbeiros
│       ├── servicos/                   # Listagem e Formulário de Serviços
│       ├── agendamentos/               # Listagem e Formulário de Agendamentos
│       └── layout/                     # Header e Footer
├── assets/
│   ├── style.css                       # Folha de estilos
│   └── app.js                          # Máscara de telefone e interações
├── config/
│   └── config.php                      # Configuração do banco e aplicação
├── database/
│   └── barbearia.sql                   # Criação do banco e seeds
└── index.php                           # Front Controller & Rotas
```

---

## 🛡️ 6. Validações e Regras de Negócio no Servidor (PHP 8)

Todas as validações são executadas no servidor pelo PHP:
1. **Agendamentos:**
   - Validação de nome do cliente e telefone brasileiro formatado com regex.
   - Validação de seleção de barbeiro e serviço válidos.
   - **Verificação de conflito de horário no Barbeiro:** O PHP consulta o banco antes de salvar para garantir que o barbeiro escolhido não possui outro agendamento no mesmo horário.
2. **Serviços:**
   - Nome obrigatório (<= 120 chars).
   - Preço validado como numérico e estritamente positivo.
   - Duração validada entre 1 e 180 minutos.
3. **Barbeiros:**
   - Nome, telefone com regex e validação de e-mail único.
4. **Segurança Geral:**
   - Proteção CSRF com token validado em todas as requisições POST.
   - Hash de senhas com `password_hash()` (Bcrypt).
   - Consultas com Prepared Statements no PDO para prevenção contra SQL Injection.
   - Sanitização de saída com `htmlspecialchars` contra XSS.