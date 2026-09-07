<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração responsável por criar a tabela de faturas (invoices) na base de dados.
 */
class CreateInvoicesTable extends Migration
{
    /**
     * Cria a tabela invoices com a estrutura necessária para emissão financeira.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Identificador único da fatura
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->onDelete('cascade'); // Chave estrangeira da inscrição
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade'); // Chave estrangeira do curso
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null'); // Chave estrangeira do pagamento
            $table->decimal('amount_to_pay', 10, 2)->default(0.00); // Valor cobrado (a pagar)
            $table->decimal('amount_paid', 10, 2)->default(0.00);   // Valor recebido (pago)
            $table->decimal('change', 10, 2)->default(0.00);        // Troco calculado
            $table->softDeletes(); // Coluna deleted_at para eliminação lógica
            $table->timestamps();  // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração eliminando a tabela invoices.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
