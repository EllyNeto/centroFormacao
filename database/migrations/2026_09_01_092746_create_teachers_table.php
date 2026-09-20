<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração responsável por criar a tabela principal de formadores/professores (teachers).
 */
class CreateTeachersTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'teachers' na base de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            // Chave primária auto-incrementável da tabela
            $table->id();

            // Nome completo do formador
            $table->string('name');

            // Endereço de e-mail do formador
            $table->string('email');

            // Número do Bilhete de Identidade (BI) ou documento de identificação
            $table->string('identity_card_number')->nullable();

            // Número de telefone de contacto do formador
            $table->string('phone_number')->nullable();

            // Caminho e nome do ficheiro da fotografia do formador no storage
            $table->string('image')->nullable();

            // Estado do registo do formador (true = Ativo, false = Inativo)
            $table->boolean('status')->default(true);

            // Suporte para remoção lógica (Soft Deletes) - adiciona a coluna deleted_at
            $table->softDeletes();

            // Campos de auditoria: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'teachers' da base de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
