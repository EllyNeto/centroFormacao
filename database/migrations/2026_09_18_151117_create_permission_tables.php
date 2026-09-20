<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * =========================================================================================
 * MIGRAÇÃO: Gestão de Permissões e Papéis de Acesso (Spatie Laravel-Permission)
 * =========================================================================================
 * Esta migração cria a infraestrutura de tabelas necessária para o controlo de acessos
 * baseado em papéis (Roles) e permissões (Permissions) no sistema do Centro de Formação.
 * 
 * Tabelas criadas:
 *  1. 'permissions'           -> Armazena as permissões individuais de ação do sistema.
 *  2. 'roles'                 -> Armazena os perfis/papéis de utilizador (ex: super_admin, secretaria, financas).
 *  3. 'model_has_permissions' -> Tabela polimórfica que associa permissões diretas aos utilizadores.
 *  4. 'model_has_roles'       -> Tabela polimórfica que associa perfis aos utilizadores.
 *  5. 'role_has_permissions'  -> Tabela associativa que define quais permissões cada perfil possui.
 */
class CreatePermissionTables extends Migration
{
    /**
     * Executa as alterações na base de dados (criação das tabelas de permissões e perfis).
     *
     * @return void
     * @throws \Exception Se as configurações do pacote Spatie não forem encontradas.
     */
    public function up()
    {
        // Obtém os nomes das tabelas e colunas a partir do ficheiro de configuração 'config/permission.php'
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Erro: O ficheiro config/permission.php não foi carregado. Execute [php artisan config:clear] e tente novamente.');
        }

        // ---------------------------------------------------------------------------------
        // 1. TABELA DE PERMISSÕES (permissions)
        // ---------------------------------------------------------------------------------
        // Guarda cada permissão específica do sistema (ex: 'criar-estudante', 'emitir-fatura', 'gerir-cursos').
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            // Chave primária auto-incrementável (BIGINT UNSIGNED)
            $table->bigIncrements('id');

            // Nome único identificador da permissão (ex: 'emitir-pagamento')
            $table->string('name');

            // Nome do guard de autenticação do Laravel (ex: 'web', 'api')
            $table->string('guard_name');

            // Timestamps para registo de data de criação (created_at) e atualização (updated_at)
            $table->timestamps();
        });

        // ---------------------------------------------------------------------------------
        // 2. TABELA DE PERFIS / PAPÉIS (roles)
        // ---------------------------------------------------------------------------------
        // Guarda os perfis de acesso do sistema (ex: 'super_admin', 'admin', 'secretaria', 'financas').
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            // Chave primária auto-incrementável (BIGINT UNSIGNED)
            $table->bigIncrements('id');

            // Nome identificador do perfil (ex: 'secretaria', 'financas')
            $table->string('name');

            // Nome do guard de autenticação do Laravel associado a este perfil
            $table->string('guard_name');

            // Timestamps para registo de data de criação (created_at) e atualização (updated_at)
            $table->timestamps();
        });

        // ---------------------------------------------------------------------------------
        // 3. TABELA POLIMÓRFICA: PERMISSÕES POR MODELO (model_has_permissions)
        // ---------------------------------------------------------------------------------
        // Permite atribuir permissões específicas diretamente a um utilizador individual (User).
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            // Chave estrangeira que referencia a permissão na tabela 'permissions'
            $table->unsignedBigInteger('permission_id');

            // Nome da classe do modelo polimórfico (ex: 'App\Models\User')
            $table->string('model_type');

            // ID do registo no modelo polimórfico (ex: user_id)
            $table->unsignedBigInteger($columnNames['model_morph_key']);

            // Índice composto para acelerar buscas por modelo e respetivo ID
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            // Restrição de chave estrangeira com eliminação em cascata se a permissão for apagada
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            // Chave primária composta garantindo que uma permissão não é atribuída duplicadamente ao mesmo modelo
            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        // ---------------------------------------------------------------------------------
        // 4. TABELA POLIMÓRFICA: PERFIS POR MODELO (model_has_roles)
        // ---------------------------------------------------------------------------------
        // Associa perfis/funções (roles) a utilizadores (ex: associar o perfil 'secretaria' ao utilizador ID 5).
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            // Chave estrangeira que referencia o perfil na tabela 'roles'
            $table->unsignedBigInteger('role_id');

            // Nome da classe do modelo polimórfico (ex: 'App\Models\User')
            $table->string('model_type');

            // ID do registo no modelo polimórfico (ex: user_id)
            $table->unsignedBigInteger($columnNames['model_morph_key']);

            // Índice composto para otimizar consultas de verificação de papel de utilizadores
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            // Restrição de chave estrangeira com eliminação em cascata se o perfil for removido
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            // Chave primária composta impedindo atribuição duplicada do mesmo perfil ao mesmo utilizador
            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        // ---------------------------------------------------------------------------------
        // 5. TABELA ASSOCIATIVA: PERMISSÕES DO PERFIL (role_has_permissions)
        // ---------------------------------------------------------------------------------
        // Mapeia quais permissões pertencem a cada perfil (ex: o perfil 'financas' tem permissão para 'emitir-fatura').
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            // Chave estrangeira referenciando a permissão
            $table->unsignedBigInteger('permission_id');

            // Chave estrangeira referenciando o perfil
            $table->unsignedBigInteger('role_id');

            // Chave estrangeira ligada à tabela 'permissions' com eliminação em cascata
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            // Chave estrangeira ligada à tabela 'roles' com eliminação em cascata
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            // Chave primária composta da associação entre permissão e perfil
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        // ---------------------------------------------------------------------------------
        // LIMPEZA DE CACHE DAS PERMISSÕES
        // ---------------------------------------------------------------------------------
        // Força a limpeza da cache do pacote Spatie para garantir a atualização imediata das permissões.
        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverte a migração, eliminando todas as tabelas de permissões e perfis na ordem inversa
     * para respeitar a integridade referencial das chaves estrangeiras.
     *
     * @return void
     * @throws \Exception Se as configurações do pacote Spatie não forem encontradas.
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Erro: O ficheiro config/permission.php não foi encontrado. Por favor publique a configuração do pacote antes de prosseguir.');
        }

        // Eliminação sequencial das tabelas associativas e principais
        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}

