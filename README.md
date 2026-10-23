*Este projeto foi criado como parte do currículo do estágio profissional do INFOSI por Eliandra Neto.*

# Centro de Formação — Sistema Integrado de Gestão Académica e Financeira

---

## Descrição

O **Centro de Formação** é uma aplicação web completa de gestão académica, pedagógica e financeira desenvolvida em **Laravel 7** com base de dados **MySQL**.

### Objetivo do Projeto
O objetivo principal do sistema é centralizar e automatizar todo o ciclo de vida operacional de um centro de formação profissional — desde a criação de cursos, alocação de salas físicas e corpo docente, abertura de turmas por turnos, receção de candidaturas/inscrições e controlo de lista de espera, até à liquidação de propinas em Kwanzas (`AOA`), emissão de recibos e efetivação de matrículas de formandos oficiais.

### Visão Geral e Fluxo de Negócio
O sistema implementa um fluxo de trabalho rigoroso e auditável dividido em 4 etapas principais:
1. **Oferta Formativa e Infraestrutura**: Registo de **Salas de Aula** (com limite físico de lugares), **Cursos** (com duração e valor em Kwanzas), **Formadores** e abertura de **Turmas** (associando curso, formador, sala, turno, dias da semana e horário sem conflitos).
2. **Candidatura / Inscrição**: O candidato inscreve-se num curso e turno pretendidos. Caso exista uma turma aberta com vagas disponíveis, a inscrição fica no estado **`Pendente`** (aguardando pagamento). Caso não exista turma aberta ou a lotação esteja esgotada, o candidato é colocado automaticamente em **`Lista de Espera`** (sendo alocado automaticamente assim que uma nova turma compatível é criada).
3. **Liquidação Financeira**: A secretaria regista o pagamento da inscrição (`Multicaixa`, `Transferência`, `Depósito` ou `Numerário`). Ao confirmar o pagamento, o sistema gera uma referência única de recibo (`REC-AAAA-XXXXX`) e atualiza automaticamente o estado da inscrição para **`Pago`**.
4. **Efetivação de Matrícula (Formando Oficial)**: Apenas inscrições com estado **`Pago`** podem ser convertidas em formandos oficiais (`EST-AAAA-XXXXX`), garantindo controlo financeiro e pedagógico integrado.

---

## Funcionalidades e Escolhas Técnicas

- **Autenticação Oficial (`laravel/ui:^2.4`)**: Controlo de acesso seguro com sessões, proteção contra força bruta (*login throttling*), recuperação de palavra-passe, formulário público de contacto (*leads*) e gestão interna de contas administrativas.
- **Dashboard Analítico e Executivo**: Painel em tempo real com 4 cartões de KPIs principais, 4 mini-indicadores de funil/infraestrutura, gráficos interativos **Chart.js** (evolução semestral de inscrições, matrículas e receita + distribuição de turmas por turno), *ranking* dos Top 5 cursos mais procurados e tabelas operacionais responsivas.
- **Validação Desacoplada (`FormRequest`)**: Todas as regras de negócio (como verificação de conflitos de horário de formadores/salas, formato de Bilhete de Identidade e limites de capacidade) encontram-se isoladas em classes `app/Http/Requests/`.
- **Design System Institucional & Modo Escuro**: Interface moderna com identidade visual Azul Institucional (`#1E40AF`), tabelas otimizadas com **DataTables**, pautas nominais prontas para impressão A4 e suporte nativo a **Modo Claro / Modo Escuro**.

---

## Instruções

### 1. Pré-requisitos
Certifique-se de que o ambiente possui instalados os seguintes componentes:
- **PHP** `>= 7.4` (com extensões `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer** `>= 2.x`
- **MySQL** `>= 5.7` ou **MariaDB** `>= 10.3`
- **Node.js & NPM** *(opcional, apenas caso deseje recompilar assets front-end)*

### 2. Instalação Passo a Passo

1. **Clonar o repositório e aceder ao diretório do projeto**:
   ```bash
   git clone git@github.com:EllyNeto/centroFormacao.git
   cd centroFormacao
   ```

2. **Instalar as dependências PHP via Composer**:
   ```bash
   composer install
   ```

3. **Configurar as variáveis de ambiente (`.env`)**:
   Copie o ficheiro de exemplo (caso ainda não exista) e gere a chave de encriptação da aplicação:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   No ficheiro `.env`, configure a ligação para a base de dados **MySQL**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=centroformacao
   DB_USERNAME=admin
   DB_PASSWORD=sua_palavra_passe
   ```

4. **Executar as migrações e popular a base de dados com dados de demonstração (*Seeders*)**:
   ```bash
   php artisan config:clear
   php artisan migrate --seed
   ```

5. **Criar o atalho público de armazenamento para fotografias de formadores e formandos**:
   ```bash
   php artisan storage:link
   ```

### 3. Execução da Aplicação

Inicie o servidor de desenvolvimento integrado do Laravel:
```bash
php artisan serve
```

Aceda à aplicação no navegador através do endereço:
- **URL de Acesso**: `http://127.0.0.1:8000/login`

#### Credenciais de Demonstração (criadas pelo `DatabaseSeeder`)
| Perfil | E-mail | Palavra-passe |
| :--- | :--- | :--- |
| **Administrador** | `admin@centroformacao.ao` | `12345678` |
| **Secretaria Académica** | `secretaria@centroformacao.ao` | `12345678` |

---

## Documentação Complementar do Repositório

Para facilitar a navegação pelo código-fonte, o repositório inclui dois documentos técnicos detalhados:
- **[`ESTRUTURA_PROJETO.md`](file:///home/eneto/Documents/infosi%20intership/projects/centroFormacao/ESTRUTURA_PROJETO.md)**: Árvore completa de diretórios, diagrama Entidade-Relacionamento (ER) da base de dados MySQL e descrição das camadas MVC.
- **[`DOCUMENTACAO_VIEWS.md`](file:///home/eneto/Documents/infosi%20intership/projects/centroFormacao/DOCUMENTACAO_VIEWS.md)**: Catálogo detalhado de todas as *views* Blade do sistema organizadas por módulo.

---

## Recursos

### Referências Técnicas e Documentação Oficial
- **Laravel 7.x Documentation**: [https://laravel.com/docs/7.x](https://laravel.com/docs/7.x) — Arquitetura MVC, Eloquent ORM, Migrations, Form Requests e Blade Templates.
- **Laravel UI (`laravel/ui:^2.4`)**: [https://github.com/laravel/ui](https://github.com/laravel/ui) — Scaffolding oficial de autenticação (`AuthenticatesUsers`, `RegistersUsers` e `Auth::routes()`).
- **MySQL 8.0 Reference Manual**: [https://dev.mysql.com/doc/](https://dev.mysql.com/doc/) — Modelação relacional, chaves estrangeiras e índices.
- **Bootstrap 5 Documentation**: [https://getbootstrap.com/docs/5.0/](https://getbootstrap.com/docs/5.0/) — Sistema de grelha responsiva, utilitários de layout e componentes de interface.
- **Chart.js Documentation**: [https://www.chartjs.org/docs/](https://www.chartjs.org/docs/) — Renderização dos gráficos analíticos de barras e *doughnut* no Dashboard.
- **DataTables Documentation**: [https://datatables.net/manual/](https://datatables.net/manual/) — Paginação, ordenação e pesquisa instantânea nas listagens administrativas.

### Utilização de Inteligência Artificial (IA) no Projeto
Em conformidade com as diretrizes de transparência, a Inteligência Artificial foi utilizada como assistente de programação (*pair programming*) nas seguintes tarefas e componentes do projeto:
1. **Refatoração da Autenticação (`app/Http/Controllers/Auth/`, `routes/web.php`)**:
   - Apoio na transição da autenticação manual para o pacote oficial `laravel/ui:^2.4` (`php artisan ui bootstrap --auth`), preservando integralmente o design personalizado das vistas `resources/views/auth/login.blade.php`, `register.blade.php` e `contact.blade.php`.
2. **Design System, Responsividade e UI/UX (`public/css/custom-tables.css`, `public/js/dashboard-init.js`, `resources/views/admin/dashboard/index.blade.php`)**:
   - Otimização da responsividade dos gráficos **Chart.js** e das tabelas do Dashboard e dos módulos administrativos (simplificação de colunas às informações essenciais, adaptação para ecrãs móveis e centralização do rodapé institucional).
   - Implementação consistente do Modo Claro e Modo Escuro (`public/js/theme-init.js`).
3. **Dados de Demonstração e Regras de Validação (`database/seeds/DatabaseSeeder.php`, `app/Http/Requests/`)**:
   - Geração de cenários realistas angolanos no `DatabaseSeeder` (cursos, salas, formadores, turmas, pagamentos em Kwanzas e inscrições nos estados *Matriculado*, *Pago*, *Pendente* e *Lista de Espera*) e revisão de mensagens de validação em português.
4. **Documentação Técnica (`README.md`, `ESTRUTURA_PROJETO.md`, `DOCUMENTACAO_VIEWS.md`)**:
   - Estruturação da documentação arquitetural, diagramas Mermaid e catálogo de vistas do sistema.
