# Sistema de Gestão de Barbearia

Sistema web desenvolvido em **PHP 8+** e **MySQL** para gerenciamento de uma barbearia, implementando arquitetura **MVC** orientada a objetos, **Composer com Autoload** e packages externos (`vlucas/phpdotenv` e `monolog/monolog`), **sistema de rotas com URLs transparentes**, autenticação por sessão, cadastro de administradores, controle de profissionais, catálogo de serviços e operação de agendamentos com validações de conflito de horários no servidor.

---

## 👥 1. Integrantes da Equipe & Divisão de Tarefas

| Integrante | Papel Principal | Tarefas e CRUDs Desenvolvidos |
| :--- | :--- | :--- |
| **Gabriel Lidani** | Segurança & Equipe | Criação da estrutura base do Roteador (`index.php`), implementação da Autenticação/Sessão segura (`Auth`, `Csrf`, `Database`), telas de login/senha, refatoração do acesso a dados com `PDO::FETCH_OBJ` e `bindValue` tipado (`PDO::PARAM_*`) e desenvolvimento completo do **CRUD de Barbeiros** (`BarbeiroController`, `Barbeiro`, views e validações). |
| **Antony Rinaldi** | Interface & Catálogo | Criação e padronização visual (`style.css`), interações dinâmicas em JavaScript (`app.js`: busca em tempo real nas tabelas, modal `<dialog>` de confirmação de exclusão, medidor de força de senha e validação visual de formulários), desenvolvimento completo do **CRUD de Serviços** (`ServicoController`, `Servicos`, views com validações de preço positivo e duração) e módulo de **Cadastro de Administrador** (`auth/cadastro.php`). |
| **Juan Estevan** | Operação (Agendamentos) | Desenvolvimento completo do **CRUD de Agendamentos** (`AgendamentoController`, `Agendamento`, views de listagem e formulário) com a **regra de negócio de validação no PHP para evitar horário duplicado no mesmo barbeiro**, configuração do **Composer com Autoload** e URLs transparentes, atualização do script do banco com seeds, integração/merge das branches da equipe e documentação do projeto no `README.md`. |

---

## 🚀 2. Requisitos do Sistema

- **PHP 8.0+** (com extensões `pdo_mysql`, `mbstring`, `session`, `zip`)
- **Composer 2.x** (para gerenciamento de dependências e Autoload)
- **MySQL 8.0+** ou **MariaDB 10.4+**
- **Servidor Web:** Apache com `mod_rewrite` (XAMPP / Laragon / WampServer) ou Servidor Embutido do PHP (`php -S`)
- **Navegador Web** (Chrome, Firefox, Edge, etc.)

---

## ⚙️ 3. Instalação e Configuração

### Passo 1: Clonar o Repositório e Instalar Dependências (Composer)
```bash
git clone https://github.com/JBEstevan/sistema-barbearia.git
cd sistema-barbearia/barbearia
composer install
```
> O Composer gerará a pasta `vendor/` com o **Autoload** (`vendor/autoload.php`) de todas as classes (`Core`, `Models`, `Controllers`) e instalará os pacotes **`vlucas/phpdotenv`** (variáveis de ambiente `.env`) e **`monolog/monolog`** (registro estruturado de logs de erro).

### Passo 2: Importar o Banco de Dados
1. Abra o **phpMyAdmin** (`http://localhost/phpmyadmin`) ou o terminal do MySQL.
2. Importe o script SQL localizado em `barbearia/database/barbearia.sql`.
3. O script configurará o charset `utf8mb4` e criará o banco `barbearia` com as 4 tabelas relacionais (`usuarios`, `barbeiros`, `servicos`, `agendamentos`) e dados iniciais de teste.

### Passo 3: Configurar a Conexão (`.env` ou `config/config.php`)
Você pode copiar o arquivo `barbearia/.env.example` para `barbearia/.env` (carregado automaticamente pelo pacote `vlucas/phpdotenv`) ou ajustar diretamente o arquivo `barbearia/config/config.php`:
```php
<?php
declare(strict_types=1);

return [
    'app_name' => $_ENV['APP_NAME'] ?? 'Barbearia',
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'name' => $_ENV['DB_NAME'] ?? 'barbearia',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '', // Senha do seu MySQL (se tiver)
    ],
];
```

### Passo 4: Executar o Projeto

#### Via Servidor Embutido do PHP (no terminal do VS Code ou PowerShell):
```bash
cd barbearia
C:\xampp\php\php.exe -S localhost:8000
```
Acesse no navegador: **`http://localhost:8000`**

#### Via XAMPP (Apache):
Copie a pasta para `C:\xampp\htdocs\` e acesse **`http://localhost/sistema-barbearia/barbearia/`**.

---

## 🔐 4. Credenciais de Demonstração

- **E-mail:** `admin@barbearia.com`
- **Senha:** `password`

---

## 🏗️ 5. Estrutura do Projeto (Padrão MVC + Composer + Rotas Transparentes)

```text
barbearia/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login, Logout, Troca de Senha e Cadastro de Admin
│   │   ├── BarbeiroController.php      # CRUD de Barbeiros
│   │   ├── ServicoController.php       # CRUD de Serviços
│   │   └── AgendamentoController.php   # CRUD de Agendamentos (com validação de conflito)
│   ├── Core/
│   │   ├── Auth.php                    # Controle de Sessão e Login
│   │   ├── Csrf.php                    # Proteção contra ataques CSRF
│   │   ├── Database.php                # Conexão PDO segura (retorna objetos: FETCH_OBJ)
│   │   ├── helpers.php                 # Funções auxiliares e gerador de URLs transparentes
│   │   └── view.php                    # Renderização de views
│   ├── Models/
│   │   ├── Usuario.php
│   │   ├── Barbeiro.php
│   │   ├── Servicos.php
│   │   └── Agendamento.php
│   └── Views/
│       ├── auth/                       # Login, Alterar Senha e Cadastro de Admin (cadastro.php)
│       ├── barbeiros/                  # Listagem e Formulário de Barbeiros
│       ├── servicos/                   # Listagem e Formulário de Serviços
│       ├── agendamentos/               # Listagem e Formulário de Agendamentos
│       └── layout/                     # Header e Footer
├── assets/
│   ├── style.css                       # Folha de estilos e variáveis CSS (:root)
│   └── app.js                          # Máscara de telefone, validação em tempo real, busca e modais
├── config/
│   └── config.php                      # Configuração do banco e aplicação (integrada ao .env)
├── database/
│   └── barbearia.sql                   # Criação do banco (utf8mb4) e seeds
├── .env.example                        # Exemplo de variáveis de ambiente (vlucas/phpdotenv)
├── .htaccess                           # Reescrita de URLs transparentes no Apache
├── composer.json                       # Configuração de Autoload e packages (phpdotenv, monolog)
├── composer.lock                       # Versões exatas travadas dos pacotes Composer
└── index.php                           # Front Controller & Roteador de URLs transparentes
```

---

## 🛡️ 6. Validações e Regras de Negócio no Servidor (PHP 8)

Todas as validações são executadas no servidor pelo PHP:
1. **Agendamentos:**
   - Validação de nome do cliente e telefone brasileiro formatado com regex.
   - Validação de seleção de barbeiro e serviço válidos, impedindo agendamento caso não existam barbeiros ou serviços ativos.
   - **Verificação de conflito de horário no Barbeiro:** O PHP consulta o banco antes de salvar (`Agendamento::hasConflict`) para garantir que o barbeiro escolhido não possui outro agendamento no mesmo horário (permitindo manter o horário ao editar o próprio registro).
2. **Serviços:**
   - Nome obrigatório (até 120 caracteres).
   - Preço validado como numérico e estritamente positivo (`0.01` a `9999.99`).
   - Duração validada como inteiro entre `1` e `180` minutos.
3. **Barbeiros:**
   - Nome obrigatório, telefone com máscara/regex `(47) 91234-5678` e validação de e-mail único.
4. **Usuários / Administradores:**
   - Cadastro de novos administradores restrito a usuários logados com perfil `ADMINISTRADOR`.
   - Verificação de e-mail único (`Usuario::emailExists`), senha com mínimo de 8 caracteres + confirmação e preservação dos dados digitados em sessão (`$_SESSION['old']`) em caso de erro.
5. **Segurança Geral:**
   - Proteção CSRF com token validado (`hash_equals`) em todas as requisições POST.
   - Hash de senhas com `password_hash()` (Bcrypt) e regeneração de ID de sessão no login (`session_regenerate_id`).
   - Consultas com Prepared Statements no PDO para prevenção contra SQL Injection.
   - Sanitização de saída com `htmlspecialchars` contra XSS.
6. **Acesso a Dados (PDO):**
   - A conexão (`Database::connect`) define `PDO::FETCH_OBJ` como modo padrão: as consultas devolvem **objetos** (`$barbeiro->nome`) em vez de arrays (`$barbeiro['nome']`).
   - Nos Models de `Usuario` e `Barbeiro`, as consultas usam `prepare` + `bindValue` (com tipo `PDO::PARAM_*`) + `execute`.
   - `Barbeiro::delete()` devolve o número de linhas removidas (`rowCount()`), permitindo avisar quando o registro não existe.
