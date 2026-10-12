<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável pela criação da tabela 'teachers' (Formadores / Professores).
 * Armazena os dados pessoais, identificação oficial (BI), especialização pedagógica e contactos do formador.
 */
class CreateTeachersTable extends Migration
{
    /**
     * Executa a criação da tabela 'teachers' na base de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            // Chave primária auto-incrementável (bigint)
            $table->id();

            // Nome completo do formador
            $table->string('name');

            // Endereço de correio eletrónico único
            $table->string('email')->unique();

            // Género do formador (ex: "Masculino", "Feminino")
            $table->string('gender');

            // Área de especialização do formador (ex: "Engenharia de Software", "Redes")
            $table->string('specialization');

            // Número do Bilhete de Identidade (BI) - Deve ser único no sistema
            $table->string('number_of_identify')->unique();

            // Número de telefone de contacto (string de até 20 caracteres para preservar indicação de país "+244")
            $table->string('phone', 20);

            // Caminho/nome do ficheiro da fotografia do formador (opcional)
            $table->string('image')->nullable();

            // Suporte para eliminação lógica (deleted_at)
            $table->softDeletes();

            // Datas de registo e atualização (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração eliminando a tabela 'teachers'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
