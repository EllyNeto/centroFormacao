<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela students (formandos/alunos).
 *
 * A tabela armazena os dados pessoais de cada formando da instituição:
 * nome, email, número de identificação (BI), telefone, código de formando
 * e imagem opcional.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela students com as suas colunas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->string('name'); // Nome completo do formando
            $table->string('email')->unique(); // Email do formando (único)
            $table->string('number_of_identify')->unique(); // Número de identificação civil (BI), único
            $table->integer('phone'); // Número de telefone do formando
            $table->integer('code'); // Código do formando (número de formando)
            $table->string('image')->nullable(); // Caminho ou URL da imagem do formando (opcional)
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela students se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
