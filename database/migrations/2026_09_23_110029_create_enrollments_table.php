<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela enrollments (matrículas).
 *
 * A tabela armazena os dados de cada matrícula de formando num curso:
 * dados pessoais, turno, estado, data e a referência ao curso escolhido.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela enrollments com as suas colunas e
     * a chave estrangeira para o curso.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->string('name'); // Nome completo do formando matriculado
            $table->string('email'); // Email do formando matriculado
            $table->string('phone'); // Telefone do formando matriculado
            $table->string('number_of_identify'); // Número de identificação civil (BI) do formando
            $table->string('image')->nullable(); // Caminho ou URL da imagem do formando (opcional)
            $table->string('shift'); // Turno pretendido pelo formando
            $table->string('status')->default('Pendente'); // Estado da matrícula (Pendente, Pago, Matriculado, Lista de Espera, Cancelado)
            $table->datetime('date')->nullable(); // Data da matrícula (opcional)
            $table->foreignId('course_id')->constrained('courses'); // Chave estrangeira para o curso escolhido (courses.id)
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela enrollments se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
