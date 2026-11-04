<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela payments (pagamentos).
 *
 * A tabela armazena os dados de cada pagamento efectuado na instituição:
 * tipo de pagamento, valor, referência, estado, data e moeda.
 */
return new class extends Migration
{
    /**
     * Executa a migração: cria a tabela payments com as suas colunas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (id)
            $table->string('type_of_payment'); // Tipo de pagamento (ex.: Transferência, Multicaixa)
            $table->decimal('value', 15, 2); // Valor do pagamento (15 dígitos, 2 decimais)
            $table->integer('reference'); // Referência do pagamento
            $table->boolean('status'); // Estado do pagamento (true = pago, false = não pago)
            $table->datetime('date'); // Data e hora do pagamento
            $table->string('currency'); // Moeda do pagamento (ex.: AOA)
            $table->softDeletes(); // Coluna deleted_at para exclusão lógica (soft delete)
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverte a migração: remove a tabela payments se existir.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
