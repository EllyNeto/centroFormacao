# Documentação do Processo de Autenticação & Níveis de Acesso Super Admin

Este documento descreve detalhadamente a arquitetura de autenticação, níveis de acesso, gestão de utilizadores e regras de auditoria implementadas na plataforma **Centro de Formação**.

---

## 1. Visão Geral do Sistema de Autenticação

A plataforma **Centro de Formação** utiliza um modelo de autenticação protegido baseado em sessões seguras do Laravel, complementado por um sistema de papéis (**Roles**) para controlo de acesso baseado em funções (RBAC).

### Níveis de Acesso (Roles)
1. **Super Administrador (`super_admin`)**:
   - Possui **permissões totais** no sistema.
   - É a única função autorizada a aceder ao módulo de **Gestão de Utilizadores** (`/user/index`, `/user/create`, etc.).
   - Pode criar, editar, desativar e eliminar contas de outros administradores e operadores.
2. **Administrador / Operador (`admin`)**:
   - Acesso operacional completo à gestão académica e financeira: **Inscrições, Pagamentos, Faturas, Formandos, Formadores, Cursos e Turmas**.
   - **Bloqueado**: Não possui acesso ao módulo de criação e gestão de utilizadores do sistema.

---

## 2. Credenciais Iniciais do Super Administrador

Para o primeiro acesso do sistema, foi criada via Seeder a conta mestra do Super Administrador:

- **E-mail**: `admin@centro.com`
- **Palavra-passe**: `password123`
- **Perfil (`role`)**: `super_admin`
- **Estado**: `true` (Ativo)

> ⚠️ **Recomendação de Segurança**: Após o primeiro acesso, a palavra-passe do Super Admin pode ser alterada no módulo de utilizadores.

---

## 3. Fluxo de Acesso, Restrição de Registo e Pedido de Conta

### 3.1. Tela de Login Institucional (`/login`)
- A tela de login foi completamente personalizada com a identidade visual **Centro de Formação**, removendo todas as marcas ou textos genéricos do Laravel.
- Apresenta campos para **E-mail** e **Palavra-passe**, a opção *"Lembrar-me nesta sessão"* e um botão em destaque **"Não possui uma conta? Contactar administração"**.

### 3.2. Restrição de Registo Público (`/register`)
- O auto-registo de contas foi **desativado publicamente** para impedir que utilizadores não autorizados criem contas de acesso por iniciativa própria.
- Ao tentar aceder a `/register`, é apresentada uma mensagem institucional informando que o acesso é restrito e disponibilizado um botão para contactar a administração.

### 3.3. Formulário de Pedido de Acesso (`/contact-admin`)
- Ao clicar em **"Não possui uma conta? Contactar administração"**, o utilizador é redirecionado para a rota `/contact-admin`.
- Nesta página, o utilizador introduz o seu endereço de e-mail para registar uma solicitação de acesso junto da equipa de administração da instituição.

---

## 4. Passo a Passo: Como o Super Admin Adiciona Novos Administradores

1. Efetue login na plataforma com a conta de **Super Administrador** (`admin@centro.com`).
2. No menu lateral principal (Sidebar), clique na secção **"Utilizadores"** -> **"Adicionar Novo Admin"** (ou aceda diretamente a `/user/create`).
3. Preencha o formulário com os dados do novo elemento:
   - **Nome Completo**: Nome do operador/administrador.
   - **Endereço de E-mail**: E-mail único de acesso.
   - **Perfil / Função de Acesso**: Selecione **"Administrador / Operador Normal"** (`admin`) ou **"Super Administrador"** (`super_admin`).
   - **Palavra-passe** e **Confirmação**.
4. Clique em **"Cadastrar Utilizador"**.
5. O novo utilizador fica imediatamente apto a entrar no sistema com as suas credenciais.

---

## 5. Arquitetura Técnica & Proteção de Rotas (Middleware)

### 5.1. Modelo `User` (`app/Models/User.php`)
O modelo Eloquent `User` contém a definição do fillable e dois métodos auxiliares:
- `isSuperAdmin()`: Retorna `true` se `$this->role === 'super_admin'`.
- `isAdmin()`: Retorna `true` se a conta estiver ativa e com função válida.

### 5.2. Middleware `CheckSuperAdmin` (`app/Http/Middleware/CheckSuperAdmin.php`)
- Intercepta requisições dirigidas às rotas do grupo `super_admin`.
- Valida se o utilizador em sessão é Super Admin. Caso contrário, interrompe o acesso e redireciona para a Dashboard com uma mensagem de aviso.

```php
// Registo no Kernel HTTP (app/Http/Kernel.php)
'super_admin' => \App\Http\Middleware\CheckSuperAdmin::class,
```

---

## 6. Integridade Contabilística, Auditoria e Saldo do Aluno

### 6.1. Remoção de Formandos e Preservação de Pagamentos
- Quando um formando é removido do sistema, é aplicada uma remoção lógica (`SoftDeletes`).
- **Histórico Financeiro Intacto**: Os registos de pagamentos e faturas associadas a esse formando **nunca são eliminados nem alterados**.
- As relações Eloquent `student()` e `enrollment()` nos modelos `Payment` e `Invoice` utilizam a instrução `.withTrashed()`, garantindo que os relatórios e auditorias financeiras continuam a exibir os dados históricos com a indicação **"Formando removido (Já não faz parte da instituição)"**.

### 6.2. Abatimento Automático de Saldo do Aluno (`Student Balance`)
- Quando um aluno efetua um pagamento em excesso, o valor excedente é acumulado como crédito em `students.balance`.
- No próximo pagamento:
  - O sistema deteta o saldo acumulado do formando.
  - Abate automaticamente o valor do saldo nos emolumentos selecionados.
  - Define o **Valor Total a Cobrar** apenas para a **Diferença** em dinheiro a solicitar ao aluno.
  - Exibe um aviso informativo com a discriminação do saldo aplicado e da diferença cobrada.

---

## 7. Resumo de Ficheiros Criados e Modificados

| Ficheiro | Descrição |
| :--- | :--- |
| `database/migrations/2026_09_20_120000_add_role_to_users_table.php` | Migração da coluna `role` e `status` na tabela `users`. |
| `app/Models/User.php` | Modelo User atualizado com métodos `isSuperAdmin()` e `isAdmin()`. |
| `database/seeds/UserSeeder.php` | Seeder da conta inicial do Super Administrador. |
| `app/Http/Middleware/CheckSuperAdmin.php` | Middleware de proteção de rotas exclusivas do Super Admin. |
| `app/Http/Controllers/userController.php` | Controlador CRUD de gestão de utilizadores. |
| `resources/views/admin/user/index.blade.php` | Lista de utilizadores para o Super Admin. |
| `resources/views/admin/user/create/index.blade.php` | Formulário de cadastro de novos administradores. |
| `resources/views/auth/login.blade.php` | Nova interface de login personalizada **Centro de Formação**. |
| `resources/views/auth/register.blade.php` | Tela de bloqueio de registo público com aviso institucional. |
| `resources/views/auth/contact_admin.blade.php` | Formulário de introdução de e-mail para suporte de acesso. |
| `public/js/currency-formatter.js` | Utilitário JS para autoformatação de milhares em tempo real ao digitar. |
| `public/js/payment-form.js` | JS interativo para boxes por emolumento e abatimento de saldo. |
