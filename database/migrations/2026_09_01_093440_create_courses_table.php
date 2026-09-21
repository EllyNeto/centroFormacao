<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração responsável por criar a tabela principal de cursos (courses) na base de dados.
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
            // Chave primária auto-incrementável da tabela
            $table->id();

            // Nome ou título do curso de formação
            $table->string('name');

            // Estado de disponibilidade do curso (true = Ativo, false = Inativo)
            $table->boolean('status')->default(true);

            // Duração total do curso em horas (número inteiro)
            $table->integer('duration');

            // Descrição detalhada do programa ou ementa do curso
            $table->text('description')->nullable();

            // Suporte para remoção lógica (Soft Deletes) - adiciona a coluna deleted_at
            $table->softDeletes();

            // Campos de auditoria: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'courses' da base de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
