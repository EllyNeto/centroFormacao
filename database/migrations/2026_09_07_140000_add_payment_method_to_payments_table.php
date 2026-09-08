<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável por adicionar o campo payment_method (Forma de Pagamento) à tabela payments.
 */
class AddPaymentMethodToPaymentsTable extends Migration
{
    /**
     * Adiciona a coluna payment_method à tabela payments.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Adiciona o campo payment_method com valor por padrão 'Numerário'
            $table->string('payment_method')->nullable()->default('Numerário')->after('currency');
        });
    }

    /**
     * Remove a coluna payment_method da tabela payments.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
}
