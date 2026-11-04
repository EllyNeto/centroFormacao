<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela teachers (docentes/professores).
 *
 * A tabela armazena os dados de cada docente da instituição:
 * nome, email, género, especialização, número de identificação (BI),
 * telefone e imagem opcional.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela teachers com as suas colunas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->string('name'); // Nome completo do docente
            $table->string('email')->unique(); // Email do docente (único)
            $table->string('gender'); // Género do docente
            $table->string('specialization'); // Área de especialização do docente
            $table->string('number_of_identify')->unique(); // Número de identificação civil (BI), único
            $table->integer('phone'); // Número de telefone do docente
            $table->string('image')->nullable(); // Caminho ou URL da imagem do docente (opcional)
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela teachers se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
