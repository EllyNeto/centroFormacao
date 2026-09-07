<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável por adicionar as colunas student_id (chave estrangeira) e falta (controlo de faltas) à tabela classes.
 */
class AddStudentIdAndFaltaToClassesTable extends Migration
{
    /**
     * Executa a alteração na estrutura da tabela classes.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            // Adiciona a chave estrangeira do estudante (opcional/chave nula se for eliminado)
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            // Adiciona o campo numérico de faltas com valor por padrão 0
            $table->integer('falta')->default(0);
        });
    }

    /**
     * Reverte a alteração eliminando as colunas adicionadas.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn(['student_id', 'falta']);
        });
    }
}
