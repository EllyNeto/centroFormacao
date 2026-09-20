<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração responsável por criar a tabela de utilizadores (users) do sistema.
 * Contém a estrutura fundamental de autenticação, níveis de acesso (role) e estado da conta (status).
 */
class CreateUsersTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'users' na base de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Identificador único auto-incrementável do utilizador
            $table->id();

            // Nome completo do utilizador
            $table->string('name');

            // Endereço de e-mail único do utilizador para autenticação
            $table->string('email')->unique();

            // Data/hora de verificação do e-mail (opcional)
            $table->timestamp('email_verified_at')->nullable();

            // Palavra-passe encriptada (hash Bcrypt)
            $table->string('password');

            // Perfil de acesso do utilizador: 'super_admin' / 'admin' (Administrador), 'secretaria' (Operador - Secretaria), 'financas' (Operador - Finanças)
            $table->string('role')->default('secretaria');

            // Estado de acesso da conta do utilizador: true = Ativo, false = Desativado
            $table->boolean('status')->default(true);

            // Token para funcionalidade de "Lembrar-me" na autenticação
            $table->rememberToken();

            // Campos de auditoria: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'users'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
