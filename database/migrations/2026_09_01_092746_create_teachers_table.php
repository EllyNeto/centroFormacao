<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /**
     * Executa a migração para criar a tabela 'teachers' (Formadores).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            // Chave primária auto-incrementável
            $table->id();

            // Nome completo do formador
            $table->string('name');

            // Endereço de e-mail do formador
            $table->string('email');

            // Número do Bilhete de Identidade (BI) ou documento de identificação
            $table->string('identity_card_number')->nullable();

            // Número de telefone do formador (campo principal da migração)
            $table->string('phone_number')->nullable();

            // Nome do ficheiro da fotografia do formador
            $table->string('image')->nullable();

            // Estado do registo do formador (Ativo = true, Desativo = false)
            $table->boolean('status')->default(true);

            // Campos de auditoria: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
