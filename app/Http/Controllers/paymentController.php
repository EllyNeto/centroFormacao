<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir pagamentos (payments) efectuados na instituição.
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 */
class paymentController extends Controller
{
    /**
     * Lista todos os pagamentos cadastrados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // Devolve todos os registos da tabela payments.
        return Payment::all();
    }

    /**
     * Exibe o formulário para criar um novo pagamento (stub).
     *
     * Neste projeto a criação é feita por API, sem view de formulário Blade.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Armazena um novo pagamento na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados do pagamento
     * @return \App\Models\Payment
     */
    public function store(Request $request)
    {
        // Valida os dados do pagamento:
        // type_of_payment (string, ex.: Transferência, Multicaixa),
        // value (numérico), reference (inteiro), status (booleano: pago/não pago),
        // date (data) e currency (string, ex.: AOA).
        $validated = $request->validate([
            'type_of_payment' => 'required|string',
            'value' => 'required|numeric',
            'reference' => 'required|integer',
            'status' => 'required|boolean',
            'date' => 'required|date',
            'currency' => 'required|string',
        ]);

        return Payment::create($validated);
    }

    /**
     * Exibe os detalhes de um pagamento específico.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Payment  $payment Pagamento a ser exibido
     * @return \App\Models\Payment
     */
    public function show(Payment $payment)
    {
        // Devolve o registo do pagamento encontrado pelo binding de rota.
        return $payment;
    }

    /**
     * Exibe o formulário para editar um pagamento existente (stub).
     *
     * @param  \App\Models\Payment  $payment Pagamento a ser editado
     * @return \App\Models\Payment
     */
    public function edit(Payment $payment)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $payment;
    }

    /**
     * Atualiza os dados de um pagamento existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Payment  $payment Pagamento a ser atualizado
     * @return \App\Models\Payment
     */
    public function update(Request $request, Payment $payment)
    {
        // sometimes| aplica cada regra apenas quando o campo estiver presente,
        // permitindo atualizações parciais do pagamento.
        $validated = $request->validate([
            'type_of_payment' => 'sometimes|string',
            'value' => 'sometimes|numeric',
            'reference' => 'sometimes|integer',
            'status' => 'sometimes|boolean',
            'date' => 'sometimes|date',
            'currency' => 'sometimes|string',
        ]);

        // Persiste as alterações apenas nos campos enviados na requisição.
        $payment->update($validated);

        return $payment;
    }

    /**
     * Remove um pagamento da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (coluna deleted_at).
     *
     * @param  \App\Models\Payment  $payment Pagamento a ser removido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->noContent();
    }
}
