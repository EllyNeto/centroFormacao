<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

/**
 * Controlador responsável pela gestão completa das operações CRUD da entidade Pagamento (Payment).
 */
class paymentController extends Controller
{
    /**
     * Exibe a listagem de todos os pagamentos registados na base de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os pagamentos registados ordenados pelo ID decrescente (mais recentes primeiro)
        $payments = Payment::orderBy('id', 'desc')->get();

        // Retorna a vista de listagem passando a coleção de pagamentos
        return view('admin.payment.list.index', ['payments' => $payments]);
    }

    /**
     * Exibe o formulário para registar um novo pagamento.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista contendo o formulário de criação de pagamento
        return view('admin.payment.create.index');
    }

    /**
     * Valida os dados submetidos pelo formulário de criação e guarda um novo pagamento na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação rigorosa dos dados recebidos do formulário de criação de pagamento
        $validatedData = $request->validate([
            'type_of_payment' => 'required|string|max:255',
            'value'           => 'required|numeric|min:0',
            'reference'       => 'required|integer|min:1',
            'status'          => 'required|boolean',
            'date'            => 'nullable|date',
            'currency'        => 'required|string|max:10',
        ], [
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.string'   => 'O tipo de pagamento deve ser um texto válido.',
            'type_of_payment.max'      => 'O tipo de pagamento não pode exceder 255 carateres.',
            'value.required'           => 'O valor do pagamento é obrigatório.',
            'value.numeric'            => 'O valor deve ser um número válido.',
            'value.min'                => 'O valor não pode ser negativo.',
            'reference.required'       => 'O número de referência do pagamento é obrigatório.',
            'reference.integer'        => 'A referência deve ser um número inteiro.',
            'reference.min'            => 'A referência deve ser um número positivo.',
            'status.required'          => 'Por favor selecione o estado do pagamento.',
            'status.boolean'           => 'O estado do pagamento é inválido.',
            'date.date'                => 'Insira uma data e hora válidas para o pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
            'currency.max'             => 'A moeda não pode ter mais de 10 carateres.',
        ]);

        // Se a data e hora não forem submetidas, atribui automaticamente o momento atual do sistema (data e hora)
        if (empty($validatedData['date'])) {
            $validatedData['date'] = now();
        }

        // Criação do registo na tabela de pagamentos com os dados validados
        Payment::create($validatedData);

        // Redireciona a navegação para a listagem principal com mensagem de sucesso armazenada na sessão flash
        return redirect()->route('payment.index')->with('success', 'Pagamento registado com sucesso!');
    }

    /**
     * Exibe a página de detalhes de um pagamento específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o pagamento pelo ID ou lança uma exceção de erro 404 (Not Found) se não for encontrado
        $payment = Payment::findOrFail($id);

        // Retorna a vista de visualização de detalhes passando o objeto de pagamento
        return view('admin.payment.show.index', ['payment' => $payment]);
    }

    /**
     * Exibe o formulário de edição para alterar os dados de um pagamento existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o pagamento pelo ID para pré-preencher o formulário de edição
        $payment = Payment::findOrFail($id);

        // Retorna a vista de edição passando o objeto de pagamento encontrado
        return view('admin.payment.edit.index', ['payment' => $payment]);
    }

    /**
     * Valida e atualiza os dados de um pagamento existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura o pagamento a ser atualizado pelo ID fornecido
        $payment = Payment::findOrFail($id);

        // Validação dos dados submetidos no formulário de edição do pagamento
        $validatedData = $request->validate([
            'type_of_payment' => 'required|string|max:255',
            'value'           => 'required|numeric|min:0',
            'reference'       => 'required|integer|min:1',
            'status'          => 'required|boolean',
            'date'            => 'nullable|date',
            'currency'        => 'required|string|max:10',
        ], [
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.string'   => 'O tipo de pagamento deve ser um texto válido.',
            'type_of_payment.max'      => 'O tipo de pagamento não pode exceder 255 carateres.',
            'value.required'           => 'O valor do pagamento é obrigatório.',
            'value.numeric'            => 'O valor deve ser um número válido.',
            'value.min'                => 'O valor não pode ser negativo.',
            'reference.required'       => 'O número de referência do pagamento é obrigatório.',
            'reference.integer'        => 'A referência deve ser um número inteiro.',
            'reference.min'            => 'A referência deve ser um número positivo.',
            'status.required'          => 'Por favor selecione o estado do pagamento.',
            'status.boolean'           => 'O estado do pagamento é inválido.',
            'date.date'                => 'Insira uma data e hora válidas para o pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
            'currency.max'             => 'A moeda não pode ter mais de 10 carateres.',
        ]);

        // Se a data e hora não forem fornecidas na atualização, mantêm-se ou assume o momento atual
        if (empty($validatedData['date'])) {
            $validatedData['date'] = now();
        }

        // Atualiza os campos do modelo de pagamento com os dados validados
        $payment->update($validatedData);

        // Redireciona para a listagem com mensagem de confirmação de atualização na sessão
        return redirect()->route('payment.index')->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove um pagamento da base de dados (utilizando SoftDeletes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o pagamento pelo ID
        $payment = Payment::findOrFail($id);

        // Executa a eliminação suave (soft delete) do pagamento
        $payment->delete();

        // Redireciona de volta para a listagem com mensagem explicativa de sucesso
        return redirect()->route('payment.index')->with('success', 'Pagamento eliminado com sucesso!');
    }

    /**
     * Método auxiliar de dashboard do pagamento (mantido para compatibilidade de rotas e navegação).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Retorna a vista do dashboard principal
        return view('admin.dashboard.index');
    }
}

