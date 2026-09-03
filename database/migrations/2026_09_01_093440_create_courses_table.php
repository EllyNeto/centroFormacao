<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração para criar a tabela de cursos no banco de dados.
 */
class CreateCoursesTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'courses'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            // Chave primária auto-incrementável
            $table->id();

            // Nome ou título do curso (ex: Programação Web)
            $table->string('name');

            // Estado do curso (ex: activo ou desaivado.)
            $table->boolean('status')->default(true); // O curso fica ativo por padrão

            // Duração do curso em horas (inteiro)
            $table->integer('duration');

            // Descrição detalhada do programa do curso (opcional/pode ser nulo)
            $table->text('description')->nullable();

            // Campos de auditoria: created_at (data de criação) e updated_at (data de atualização)
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'courses' se ela existir.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
