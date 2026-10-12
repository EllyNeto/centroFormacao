<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável pela criação da tabela 'rooms' (Turmas).
 * 
 * Módulo 4: Turmas
 * Representa as turmas pedagógicas do centro de formação.
 * Cada turma agrega um Curso, um Formador, uma Sala Física, um Turno e um Horário.
 * A Capacidade Máxima é herdada obrigatoriamente da capacidade da sala de aula física associada.
 */
class CreateRoomsTable extends Migration
{
    /**
     * Executa a migração para criação da tabela 'rooms'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            // Chave primária auto-incremental da turma
            $table->id();

            // Nome descritivo da turma (ex: "Turma de Redes - Manhã", "Programação Web - T1")
            $table->string('name');

            // Chave estrangeira para o curso ministrado na turma
            $table->foreignId('course_id')
                ->constrained('courses')
                ->onDelete('restrict');

            // Chave estrangeira para o formador responsável pela turma
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->onDelete('restrict');

            // Chave estrangeira obrigatória para a sala física onde decorrerão as aulas presenciais
            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->onDelete('restrict');

            // Capacidade máxima de alunos permitidos (copiada/herdada de classrooms.capacity)
            $table->unsignedInteger('max_capacity');

            // Turno de funcionamento das aulas (ex: Manhã, Tarde, Pós-Laboral)
            $table->string('shift');

            // Dias da semana em que as aulas ocorrem, armazenados em formato JSON
            // Exemplo: ["Segunda-feira", "Quarta-feira", "Sexta-feira"]
            $table->json('days_of_week');

            // Horário de início e término das aulas
            $table->time('start_time');
            $table->time('end_time');

            // Datas previstas para o início e conclusão do período formativo da turma
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Estado atual da turma no ciclo de formação:
            // 'Aberta' (aceita inscrições), 'Em curso' (em lecionação), 'Concluída' ou 'Cancelada'
            $table->string('status')->default('Aberta');

            // Suporte a eliminação lógica (Soft Delete) para manter o histórico de inscrições e pagamentos
            $table->softDeletes();

            // Registo de timestamps automáticos (created_at e updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'rooms' se ela existir.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}

