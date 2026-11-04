<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela rooms (turmas).
 *
 * A tabela armazena os dados de cada turma da instituição: nome, horário,
 * dias da semana, turno, capacidade máxima e as chaves estrangeiras para
 * docente, curso e sala de aula.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela rooms com as suas colunas e chaves
     * estrangeiras.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->string('name'); // Nome da turma
            $table->time('start_time'); // Hora de início da turma (H:i)
            $table->time('end_time'); // Hora de término da turma (H:i)
            $table->json('days_of_week'); // Dias da semana da turma, guardados como JSON (ex.: ["Segunda-feira","Quarta-feira"])
            $table->string('shift'); // Turno da turma (Manhã / Tarde / Pós-Laboral)
            $table->integer('max_capacity'); // Capacidade máxima de alunos da turma
            $table->foreignId('teacher_id')->constrained('teachers'); // Chave estrangeira para o docente responsável (teachers.id)
            $table->foreignId('course_id')->constrained('courses'); // Chave estrangeira para o curso da turma (courses.id)
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete(); // Chave estrangeira para a sala de aula (classrooms.id), opcional; nullOnDelete define NULL se a sala for apagada
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela rooms se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
