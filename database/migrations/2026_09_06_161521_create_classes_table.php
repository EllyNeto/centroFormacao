<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração responsável por criar a tabela principal de turmas e aulas (classes) na base de dados.
 */
class CreateClassesTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'classes'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            // Chave primária auto-incrementável da tabela
            $table->id();

            // Nome ou designação da turma (Ex: Turma A - Web Dev)
            $table->string('name');

            // Código de identificação da turma (opcional)
            $table->string('code')->nullable();

            // Sala de aula associada (opcional)
            $table->string('room')->nullable();

            // Dias da semana em que as aulas ocorrem (Ex: Segunda, Quarta, Sexta)
            $table->string('days_of_week');

            // Turno do curso (Ex: Manhã, Tarde, Noite)
            $table->string('shift')->default('Manhã');

            // Capacidade máxima de formandos na turma
            $table->integer('capacity')->default(25);

            // Estado de atividade da turma (true = Ativa, false = Inativa)
            $table->boolean('status')->default(true);

            // Hora de início das aulas da turma
            $table->time('start_time');

            // Hora de fim das aulas da turma
            $table->time('end_time');

            // Chave estrangeira ligando ao formador responsável (teacher_id)
            $table->foreignId('teacher_id')->constrained('teachers');

            // Chave estrangeira ligando ao curso associado (course_id)
            $table->foreignId('course_id')->constrained('courses');

            // Chave estrangeira opcional ligando ao formando/estudante (student_id)
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');

            // Número total de faltas do estudante na turma (padrão: 0)
            $table->integer('falta')->default(0);

            // Suporte para remoção lógica (Soft Deletes) - adiciona a coluna deleted_at
            $table->softDeletes();

            // Campos de auditoria: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'classes' da base de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
