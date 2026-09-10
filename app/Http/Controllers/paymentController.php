<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Enrollment;

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
        $payments = Payment::orderBy('id', 'desc')->get();
        return view('admin.payment.list.index', ['payments' => $payments]);
    }

    /**
     * Exibe o formulário para registar um novo pagamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedEnrollment   = $selectedEnrollmentId ? Enrollment::with(['student', 'course'])->find($selectedEnrollmentId) : null;

        return view('admin.payment.create.index', [
            'selectedEnrollmentId' => $selectedEnrollmentId,
            'selectedEnrollment'   => $selectedEnrollment,
        ]);
    }

    /**
     * Valida os dados submetidos e guarda um novo pagamento.
     * Se o pagamento for marcado como concluído (status = 1), atualiza a inscrição associada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Se a referência for gerada automaticamente no frontend ou não enviada, gera um número inteiro automático de 8 dígitos
        if (!$request->has('reference') || empty($request->reference)) {
            $request->merge(['reference' => rand(10000000, 99999999)]);
        }

        $validatedData = $request->validate([
            'type_of_payment' => 'required|string|max:255',
            'value'           => 'required|numeric|min:0',
            'reference'       => 'required|integer',
            'status'          => 'required|boolean',
            'payment_method'  => 'required|string|max:255',
            'date'            => 'nullable|date',
            'currency'        => 'required|string|max:10',
            'enrollment_id'   => 'nullable|exists:enrollments,id',
        ], [
            'type_of_payment.required' => 'O tipo de emolumento/pagamento é obrigatório.',
            'value.required'           => 'O valor do pagamento é obrigatório.',
            'status.required'          => 'Por favor selecione o estado do pagamento.',
            'payment_method.required'  => 'Selecione a forma de pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
        ]);

        if (empty($validatedData['date'])) {
            $validatedData['date'] = now();
        }

        $enrollmentId = $request->input('enrollment_id');

        // Criação do pagamento
        $payment = Payment::create($validatedData);

        // Se o pagamento estiver como Pago/Concluído (status = 1), marca a inscrição associada como confirmada (status = 1)
        if ($payment->status) {
            if ($enrollmentId) {
                Enrollment::where('id', $enrollmentId)->update(['status' => 1]);
            } else {
                $invoice = Invoice::where('payment_id', $payment->id)->first();
                if ($invoice && $invoice->enrollment_id) {
                    Enrollment::where('id', $invoice->enrollment_id)->update(['status' => 1]);
                }
            }
        }

        // Se veio do fluxo de inscrição ou clicou em "Salvar e Emitir Fatura", redireciona para a emissão da fatura
        if ($request->input('action') === 'save_and_invoice' || $enrollmentId) {
            return redirect()->route('invoice.create', [
                'enrollment_id' => $enrollmentId,
                'payment_id'    => $payment->id,
            ])->with('success', 'Pagamento registado com sucesso! Emita agora a fatura/recibo.');
        }

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
        $payment = Payment::findOrFail($id);
        return view('admin.payment.details.index', ['payment' => $payment]);
    }

    /**
     * Exibe o formulário de edição para alterar os dados de um pagamento existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payment.edit.index', ['payment' => $payment]);
    }

    /**
     * Valida e atualiza os dados de um pagamento existente na base de dados.
     * Atualiza automaticamente a inscrição se o pagamento for concluído.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validatedData = $request->validate([
            'type_of_payment' => 'required|string|max:255',
            'value'           => 'required|numeric|min:0',
            'status'          => 'required|boolean',
            'payment_method'  => 'required|string|max:255',
            'currency'        => 'required|string|max:10',
        ], [
            'type_of_payment.required' => 'O tipo de emolumento/pagamento é obrigatório.',
            'value.required'           => 'O valor do pagamento é obrigatório.',
            'status.required'          => 'Por favor selecione o estado do pagamento.',
            'payment_method.required'  => 'Selecione a forma de pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
        ]);

        // Impede alteração do número de referência e da data original do pagamento
        $validatedData['reference'] = $payment->reference;
        $validatedData['date']      = $payment->date;

        $payment->update($validatedData);

        // Se o pagamento for atualizado para Pago/Concluído (status = 1), marca a inscrição associada como confirmada (status = 1)
        if ($payment->status) {
            $invoice = Invoice::where('payment_id', $payment->id)->first();
            if ($invoice && $invoice->enrollment_id) {
                Enrollment::where('id', $invoice->enrollment_id)->update(['status' => 1]);
            }
        }

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
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payment.index')->with('success', 'Pagamento eliminado com sucesso!');
    }

    /**
     * Exibe o painel principal do sistema (Dashboard).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }
}
