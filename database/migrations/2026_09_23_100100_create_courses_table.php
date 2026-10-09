<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável pela criação da tabela 'courses' (Oferta Formativa / Cursos do Centro).
 * Armazena a designação do curso, descrição, carga horária em horas, valor financeiro e estado de atividade.
 */
class CreateCoursesTable extends Migration
{
    /**
     * Executa a criação da tabela 'courses' na base de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            // Chave primária auto-incrementável (bigint)
            $table->id();

            // Nome único do curso (ex: "Informática Básica", "Redes de Computadores")
            $table->string('name')->unique();

            // Descrição detalhada do programa formativo ou requisitos do curso (opcional)
            $table->text('description')->nullable();

            // Carga horária total da formação em horas (inteiro positivo)
            $table->unsignedInteger('duration');

            // Estado de atividade do curso no sistema (true = Ativo, false = Inativo)
            $table->boolean('status')->default(true);

            // Preço do curso em Kwanzas (AOA), formatado até 15 dígitos e 2 casas decimais
            $table->decimal('value', 15, 2);

            // Suporte para eliminação lógica (deleted_at)
            $table->softDeletes();

            // Datas de criação e última modificação (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração eliminando a tabela 'courses'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
