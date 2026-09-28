<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Executa a criação da tabela 'payments' (Pagamentos).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            // Chave primária (id)
            $table->id();

            // Tipo/Forma de pagamento (ex: Numerário, TPA, Transferência)
            $table->string('type_of_payment');

            // Valor do pagamento (decimal 15,2 para precisão monetária em Kz)
            $table->decimal('value', 15, 2);

            // Número de referência ou comprovativo do pagamento
            $table->integer('reference');

            // Estado do pagamento: 1 = Confirmado/Pago, 0 = Pendente
            $table->boolean('status');

            // Data e hora em que a transacção foi efectuada
            $table->dateTime('date');

            // Moeda utilizada na transação (ex: Kz, USD)
            $table->string('currency');

            // Suporte para exclusão lógica (coluna 'deleted_at') para SoftDeletes
            $table->softDeletes();

            // Datas de registo ('created_at' e 'updated_at')
            $table->timestamps();
        });
    }

    /**
     * Reverte a migração, eliminando a tabela 'payments'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
