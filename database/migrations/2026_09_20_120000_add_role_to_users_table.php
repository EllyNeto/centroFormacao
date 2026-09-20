<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração para adicionar os campos de perfil de acesso (role) e estado (status)
 * na tabela de utilizadores (users) do sistema Centro de Formação.
 */
class AddRoleToUsersTable extends Migration
{
    /**
     * Executa a alteração na tabela 'users'.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Perfil do utilizador: 'super_admin' (Super Administrador) ou 'admin' (Administrador / Operador)
            $table->string('role')->default('admin')->after('password');
            
            // Estado da conta: true = Ativo, false = Desativado
            $table->boolean('status')->default(true)->after('role');
        });
    }

    /**
     * Reverte as alterações efetuadas na tabela 'users'.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status']);
        });
    }
}
