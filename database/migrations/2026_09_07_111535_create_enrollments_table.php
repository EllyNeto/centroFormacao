<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração responsável por criar a tabela principal de inscrições (enrollments) na base de dados.
 */
class CreateEnrollmentsTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'enrollments'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            // Chave primária auto-incrementável da tabela
            $table->id();

            // Chave estrangeira ligando ao estudante/candidato inscrito (student_id)
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            // Chave estrangeira ligando ao Curso (course_id)
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');

            // Data e hora de registo da inscrição
            $table->dateTime('date');

            // Estado da inscrição (false = 0 [Pendente/Aguarda Pagamento], true = 1 [Confirmada/Ativa])
            $table->boolean('status')->default(false);

            // Suporte para remoção lógica (Soft Deletes) - adiciona a coluna deleted_at
            $table->softDeletes();

            // Campos de auditoria: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'enrollments' da base de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
