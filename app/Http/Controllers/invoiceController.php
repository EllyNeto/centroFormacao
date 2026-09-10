<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Payment;

class invoiceController extends Controller
{
    /**
     * Exibe a listagem de todas as faturas registadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $invoices = Invoice::with(['enrollment.student', 'course', 'payment'])->orderBy('id', 'desc')->get();
        return view('admin.invoice.list.index', ['invoices' => $invoices]);
    }

    /**
     * Exibe o formulário de emissão de nova fatura.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $enrollments = Enrollment::with(['student', 'course'])->get();
        $courses     = Course::where('status', 1)->get();
        $payments    = Payment::all();

        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedPaymentId    = $request->query('payment_id');

        return view('admin.invoice.create.index', [
            'enrollments'          => $enrollments,
            'courses'              => $courses,
            'payments'             => $payments,
            'selectedEnrollmentId' => $selectedEnrollmentId,
            'selectedPaymentId'    => $selectedPaymentId,
        ]);
    }

    /**
     * Valida e guarda uma nova fatura na base de dados.
     * Atualiza automaticamente o estado da inscrição para confirmada (status = 1) após o pagamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Obtém o curso automaticamente a partir da inscrição selecionada se não for enviado
        if ($request->filled('enrollment_id') && !$request->filled('course_id')) {
            $enrollment = Enrollment::find($request->enrollment_id);
            if ($enrollment) {
                $request->merge(['course_id' => $enrollment->course_id]);
            }
        }

        // Se o valor a pagar não for enviado no formulário, assume por padrão o valor pago enviado
        if (!$request->filled('amount_to_pay')) {
            $request->merge(['amount_to_pay' => $request->input('amount_paid', 0)]);
        }

        $validatedData = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'course_id'     => 'required|exists:courses,id',
            'amount_to_pay' => 'nullable|numeric|min:0',
            'amount_paid'   => 'required|numeric|min:0',
            'payment_id'    => 'nullable|exists:payments,id',
        ], [
            'enrollment_id.required' => 'A inscrição é obrigatória.',
            'course_id.required'     => 'O curso é obrigatório.',
            'amount_paid.required'   => 'O valor pago é obrigatório.',
        ]);

        // Conversão dos valores recebidos para ponto flutuante
        $amountToPay = (float) ($validatedData['amount_to_pay'] ?? $validatedData['amount_paid']);
        $amountPaid  = (float) $validatedData['amount_paid'];
        $validatedData['amount_to_pay'] = $amountToPay;

        // Cálculo do troco (apenas quando o valor pago for estritamente superior ao valor a pagar)
        $validatedData['change'] = max(0, $amountPaid - $amountToPay);

        // Criação do registo da fatura na base de dados
        $invoice = Invoice::create($validatedData);

        // Atualização automática da inscrição para Pago/Confirmado (status = 1) se liquidação concluída
        if ($amountPaid >= $amountToPay || !empty($validatedData['payment_id'])) {
            Enrollment::where('id', $validatedData['enrollment_id'])->update(['status' => 1]);
        }

        return redirect()->route('invoice.index')->with('success', 'Fatura emitida com sucesso e inscrição atualizada para confirmada!');
    }

    /**
     * Exibe os detalhes de uma fatura específica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $invoice = Invoice::with(['enrollment.student', 'course', 'payment'])->findOrFail($id);
        return view('admin.invoice.details.index', ['invoice' => $invoice]);
    }

    /**
     * Exibe o formulário de edição de uma fatura existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $enrollments = Enrollment::with(['student', 'course'])->get();
        $courses     = Course::where('status', 1)->get();
        $payments    = Payment::all();

        return view('admin.invoice.edit.index', [
            'invoice'     => $invoice,
            'enrollments' => $enrollments,
            'courses'     => $courses,
            'payments'    => $payments,
        ]);
    }

    /**
     * Valida e atualiza os dados da fatura na base de dados.
     * Atualiza automaticamente o estado da inscrição para confirmada (status = 1) se paga.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($request->filled('enrollment_id') && !$request->filled('course_id')) {
            $enrollment = Enrollment::find($request->enrollment_id);
            if ($enrollment) {
                $request->merge(['course_id' => $enrollment->course_id]);
            }
        }

        // Se o valor a pagar não for enviado no formulário, assume por padrão o valor pago enviado
        if (!$request->filled('amount_to_pay')) {
            $request->merge(['amount_to_pay' => $request->input('amount_paid', 0)]);
        }

        $validatedData = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'course_id'     => 'required|exists:courses,id',
            'amount_to_pay' => 'nullable|numeric|min:0',
            'amount_paid'   => 'required|numeric|min:0',
            'payment_id'    => 'nullable|exists:payments,id',
        ], [
            'enrollment_id.required' => 'A inscrição é obrigatória.',
            'course_id.required'     => 'O curso é obrigatório.',
            'amount_paid.required'   => 'O valor pago é obrigatório.',
        ]);

        $amountToPay = (float) ($validatedData['amount_to_pay'] ?? $validatedData['amount_paid']);
        $amountPaid  = (float) $validatedData['amount_paid'];
        $validatedData['amount_to_pay'] = $amountToPay;
        $validatedData['change']        = max(0, $amountPaid - $amountToPay);

        $invoice->update($validatedData);

        // Se o valor pago for maior ou igual ao valor por pagar (ou pagamento associado), atualiza o estado da inscrição para pago/confirmado (1)
        if ($amountPaid >= $amountToPay || !empty($validatedData['payment_id'])) {
            Enrollment::where('id', $validatedData['enrollment_id'])->update(['status' => 1]);
        }

        return redirect()->route('invoice.index')->with('success', 'Fatura e estado da inscrição atualizados com sucesso!');
    }

    /**
     * Remove uma fatura da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoice.index')->with('success', 'Fatura eliminada com sucesso!');
    }
}
