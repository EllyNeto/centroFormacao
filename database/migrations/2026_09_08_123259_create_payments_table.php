<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de migração responsável por criar a tabela principal de pagamentos (payments) na base de dados.
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Executa a migração para criar a tabela 'payments'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            // Chave primária auto-incrementável da tabela
            $table->id();

            // Chave estrangeira opcional ligando o pagamento ao estudante/formando (student_id)
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');

            // Chave estrangeira opcional ligando o pagamento a uma inscrição específica (enrollment_id)
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->onDelete('set null');

            // Tipo de emolumento ou descrição do pagamento (Ex: Inscrição, Valor do Curso, Certificado...)
            $table->string('type_of_payment');

            // Valor monetário total do pagamento
            $table->float('value');

            // Número de referência único gerado para a transação
            $table->integer('reference');

            // Estado do pagamento (true = Concluído/Pago, false = Pendente/Não Pago)
            $table->boolean('status');

            // Data e hora de efetuação da transação financeira
            $table->dateTime('date')->nullable();

            // Código da moeda utilizada na transação (Ex: AOA, USD, EUR)
            $table->string('currency');

            // Forma ou meio de pagamento utilizado (Ex: Numerário, Cartão, Transferência)
            $table->string('payment_method')->nullable()->default('Numerário');

            // Suporte para remoção lógica (Soft Deletes) - adiciona a coluna deleted_at
            $table->softDeletes();

            // Campos de auditoria da tabela: created_at e updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'payments' da base de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
