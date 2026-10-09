<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável pela criação da tabela 'classrooms' (Salas de Aula Físicas).
 * Esta tabela armazena a identificação por número, capacidade física de alunos e observações da sala.
 */
class CreateClassroomsTable extends Migration
{
    /**
     * Executa as alterações na base de dados (criação da tabela classrooms).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            // Chave primária auto-incrementável (bigint)
            $table->id();

            // Número identificador da sala (ex: 101, 102). Deve ser único no sistema.
            $table->integer('number_of_classroom')->unique();

            // Capacidade máxima de lugares para formandos na sala (inteiro positivo)
            $table->unsignedInteger('capacity');

            // Descrição opcional da sala (ex: lista de equipamentos, localização no bloco)
            $table->text('description')->nullable();

            // Coluna para suporte a eliminação lógica (deleted_at)
            $table->softDeletes();

            // Colunas de controlo de data de criação e atualização (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverte as alterações da base de dados (eliminação da tabela classrooms).
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
