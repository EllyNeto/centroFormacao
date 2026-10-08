<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Executa a criação da tabela 'enrollments' (Inscrições / Candidaturas).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // Dados do Candidato
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('number_of_identify');
            $table->string('image')->nullable();

            // Opções da Candidatura
            $table->string('shift'); // Turno pretendido: Manhã, Tarde, Pós-Laboral
            $table->string('status')->default('Pendente'); // Pendente, Pago, Matriculado, Lista de Espera, Cancelado
            $table->dateTime('date')->nullable();

            // Relações
            $table->foreignId('course_id')->constrained();

            $table->softDeletes();
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
