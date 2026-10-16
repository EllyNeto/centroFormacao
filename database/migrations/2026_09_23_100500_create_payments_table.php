<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável pela criação da tabela 'payments' (Pagamentos).
 *
 * Módulo 6: Pagamentos
 * Regista todas as transações financeiras e liquidações de propinas associadas
 * às inscrições dos formandos no Centro de Formação.
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Executa as migrações na base de dados.
     *
     * Cria a tabela 'payments' com os respetivos campos, chaves forasteiras e índices.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            // Identificador primário único da transação
            $table->id();

            // Chave forasteira referenciando a inscrição a que o pagamento pertence
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade');

            // Método ou forma de pagamento (Transferência, Multicaixa, Numerário)
            $table->string('type_of_payment');

            // Montante liquidado na transação (precisão de 15 dígitos com 2 casas decimais)
            $table->decimal('value', 15, 2);

            // Código da moeda utilizada na transação (padrão ISO 4217: AOA - Kwanza)
            $table->string('currency', 3)->default('AOA');

            // Código de referência único do pagamento (recibo interno ou comprovativo bancário)
            $table->string('reference')->unique();

            // Estado atual do pagamento (Pendente, Confirmado, Anulado)
            $table->string('status')->default('Pendente');

            // Data e hora de efetivação da transação financeira
            $table->dateTime('date');

            // Suporte para exclusão lógica (Soft Deletes)
            $table->softDeletes();

            // Marcas temporais de criação (created_at) e atualização (updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverte as migrações da base de dados.
     *
     * Remove a tabela 'payments' da base de dados se a mesma existir.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
