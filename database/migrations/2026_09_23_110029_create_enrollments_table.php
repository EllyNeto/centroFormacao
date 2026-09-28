<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Executa a criação da tabela 'enrollments' (Inscrições/Matrículas).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            // Chave primária (id)
            $table->id();

            // Data e hora em que a inscrição foi realizada
            $table->dateTime('date');

            // Estado da inscrição: 1 = Activa/Confirmada, 0 = Pendente/Cancelada
            $table->boolean('status');

            // Chave estrangeira ligada à tabela 'students' (Formando inscrito)
            $table->foreignId('student_id')->constrained();

            // Chave estrangeira ligada à tabela 'courses' (Curso seleccionado)
            $table->foreignId('course_id')->constrained();

            // Suporte para exclusão lógica (coluna 'deleted_at') para SoftDeletes
            $table->softDeletes();

            // Datas de controlo do registo ('created_at' e 'updated_at')
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'enrollments'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
