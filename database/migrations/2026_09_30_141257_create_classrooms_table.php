<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela classrooms (salas de aula).
 *
 * A tabela armazena os dados de cada sala de aula física: número da sala
 * (único), capacidade e descrição.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela classrooms com as suas colunas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->integer('number_of_classroom')->unique(); // Número da sala (único, não pode repetir)
            $table->integer('capacity'); // Capacidade máxima de alunos da sala
            $table->text('description')->nullable(); // Descrição detalhada da sala (opcional)
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela classrooms se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
