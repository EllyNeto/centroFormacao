<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável pela criação da tabela 'enrollments' (Inscrições / Candidaturas).
 *
 * Módulo 5: Inscrições
 * Regista o processo de candidatura de um formando ao centro de formação.
 * Cada inscrição está vinculada a um Curso obrigatório e pode estar alocada
 * a uma Turma específica (ou null caso esteja em lista de espera).
 * Suporta ciclo de vida: 'Pendente', 'Pago', 'Matriculado', 'Lista de Espera', 'Cancelado'.
 */
class CreateEnrollmentsTable extends Migration
{
    /**
     * Executa a migração para criação da tabela 'enrollments'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            // Chave primária auto-incremental da inscrição
            $table->id();

            // Dados Pessoais do Candidato
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('number_of_identify', 50); // Número do BI ou Passaporte
            $table->string('image')->nullable();      // Fotografia de identificação (caminho relativo em storage)

            // Associação Obrigatória ao Curso pretendido
            $table->foreignId('course_id')
                ->constrained('courses')
                ->onDelete('restrict');

            // Associação à Turma específica (nullable para candidatos em lista de espera)
            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->onDelete('set null');

            // Turno pretendido para a formação (Manhã, Tarde, Pós-Laboral)
            $table->string('shift');

            // Estado da Inscrição:
            // 'Pendente' (aguarda pagamento), 'Pago' (propina paga), 'Matriculado' (aluno efetivado),
            // 'Lista de Espera' (turma sem vagas imediatas), 'Cancelado'
            $table->string('status')->default('Pendente');

            // Data oficial de registo da candidatura
            $table->dateTime('date')->nullable();

            // Eliminação lógica (Soft Delete) para preservar histórico financeiro e pedagógico
            $table->softDeletes();

            // Registo de timestamps automáticos (created_at e updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'enrollments' se ela existir.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
