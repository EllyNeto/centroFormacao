<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração para criar a tabela de estudantes (students) no banco de dados.
 */
class CreateStudentsTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'students'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            // Chave primária auto-incrementável
            $table->id();

            // Nome completo do estudante
            $table->string('name');

            // Endereço de e-mail do estudante
            $table->string('email');

            // Número do Bilhete de Identidade / Documento de identificação
            $table->string('identity_card_number');

            // Número de telefone do estudante (campo principal na migração)
            $table->string('phone_number')->nullable();

            // Código numérico de identificação do aluno
            $table->integer('code');

            // Nome do ficheiro da fotografia do estudante (pode ser nulo se não for enviada foto)
            $table->string('image')->nullable();

            // Campos de auditoria da tabela: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'students' se ela existir.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
