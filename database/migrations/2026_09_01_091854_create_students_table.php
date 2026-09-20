<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração para criar a tabela principal de estudantes (students) na base de dados.
 * Armazena a informação dos formandos/candidatos, incluindo nome, email, BI, telefone, código, fotografia e saldo acumulado.
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
            // Chave primária auto-incrementável da tabela
            $table->id();

            // Nome completo do estudante ou candidato
            $table->string('name');

            // Endereço de e-mail de contacto
            $table->string('email');

            // Número do Bilhete de Identidade (BI) ou documento de identificação legal
            $table->string('identity_card_number');

            // Número de telefone de contacto principal do estudante
            $table->string('phone_number')->nullable();

            // Código numérico sequencial único de identificação do aluno no centro de formação
            $table->integer('code');

            // Caminho e nome do ficheiro da fotografia do estudante no storage
            $table->string('image')->nullable();

            // Saldo monetário acumulado do estudante (crédito para futuros pagamentos)
            $table->decimal('balance', 10, 2)->default(0.00);

            // Suporte para remoção lógica (Soft Deletes) - adiciona a coluna deleted_at
            $table->softDeletes();

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
