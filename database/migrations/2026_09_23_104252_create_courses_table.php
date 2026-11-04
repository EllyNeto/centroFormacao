<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela courses (cursos).
 *
 * A tabela armazena os dados de cada curso oferecido pela instituição:
 * nome, descrição, duração (em horas), estado (activo/inactivo) e valor.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela courses com as suas colunas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->string('name'); // Nome do curso
            $table->text('description'); // Descrição detalhada do curso (texto longo)
            $table->integer('duration'); // Duração do curso em horas
            $table->boolean('status'); // Estado do curso (true = activo, false = inactivo)
            $table->decimal('value', 15, 2); // Valor/preço do curso (15 dígitos, 2 decimais)
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela courses se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
