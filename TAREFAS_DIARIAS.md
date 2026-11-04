# Tarefas Diárias — 2026-11-04

## Resumo
Ajustes de documentação e verificação da estrutura do projeto **Centro de Formação** (Laravel 13 / PHP 8.5).

## Tarefas efetuadas

1. **Atualização do `README.md` (Laravel 13)**
   - Pré-requisitos: PHP `>= 8.4` → `PHP 8.5` (ajustado à versão do ambiente).
   - Corrigido o nome do ficheiro de estrutura: `ESTRUTURA_PROJETO.md` → `ESTRUTURA_DO_PROJETO.md`.
   - Removida referência ao ficheiro inexistente `DOCUMENTACAO_VIEWS.md`.
   - Ajustada a secção "Documentação Complementar do Repositório".

2. **Atualização da `ESTRUTURA_DO_PROJETO.md` (estrutura do projeto)**
   - Versão ajustada para **Laravel 13** (PHP 8.5).
   - Pilha de assets: **Laravel Mix (`webpack.mix.js`)** → **Vite (`vite.config.js`)**.
   - Descrição do projeto atualizada para Laravel 13 / PHP 8.5.
   - Secção **4. Base de Dados** reescrita com o esquema real das migrações (ver ponto 3).

3. **Verificação e documentação das migrações**
   - Lidas todas as migrações em `database/migrations/` (12 ficheiros).
   - Confirmado esquema real de `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `students`, `teachers`, `courses`, `payments`, `rooms`, `enrollments`, `classrooms`.
   - Detetado que `2019_08_19_000000_create_failed_jobs_table.php` é um **no-op** (a tabela `failed_jobs` já é criada pela migration de jobs).
   - Detetado que `classroom_id` é adicionado a `rooms` apenas pela migration `2026_10_01_000000_add_classroom_id_to_rooms_table.php` (não vem na criação inicial).
   - Confirmado que `payments`, `students` e `enrollments` **não têm** chaves estrangeiras umas para as outras (colunas `enrollment_id` / `room_id` inexistentes).

4. **Criação deste ficheiro (`TAREFAS_DIARIAS.md`)**
   - Registo das tarefas realizadas nesta sessão.

---
*Data: 2026-11-04 | Ambiente: PHP 8.5.12, Laravel Framework 13.40.0*
