<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Executa a criação da tabela 'courses' no banco de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            // Chave primária auto-incrementável (id)
            $table->id();

            // Nome do curso (texto curto)
            $table->string('name');

            // Descrição detalhada do curso (conteúdo programático, pré-requisitos)
            $table->text('description');

            // Duração do curso em horas (número inteiro)
            $table->integer('duration');

            // Estado do curso: 1 = Activo, 0 = Inactivo (booleano)
            $table->boolean('status');

            // Valor do curso em Kz (decimal 15,2 para suportar grandes montantes monetários)
            $table->decimal('value', 15, 2);

            // Suporte para exclusão lógica (coluna 'deleted_at') utilizada pelo SoftDeletes do Eloquent
            $table->softDeletes();

            // Colunas de controlo de data de criação ('created_at') e actualização ('updated_at')
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'courses'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
