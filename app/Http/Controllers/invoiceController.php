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
        $selectedPaymentId    = $request->query('payment_id');
        $selectedEnrollmentId = $request->query('enrollment_id');

        // Carrega o pagamento selecionado com as relações do formando, inscrições e curso
        $selectedPayment = $selectedPaymentId 
            ? Payment::with(['student.enrollments.course', 'enrollment.course'])->find($selectedPaymentId) 
            : null;

        // Tenta resolver a inscrição e curso associados a partir do pagamento ou do formando
        $selectedEnrollment = null;
        if ($selectedPayment) {
            if ($selectedPayment->enrollment) {
                $selectedEnrollment = $selectedPayment->enrollment;
            } elseif ($selectedPayment->student && $selectedPayment->student->enrollments->isNotEmpty()) {
                $selectedEnrollment = $selectedPayment->student->enrollments->sortByDesc('id')->first();
            }
        }

        if (!$selectedEnrollment && $selectedEnrollmentId) {
            $selectedEnrollment = Enrollment::with(['student', 'course'])->find($selectedEnrollmentId);
        }

        $enrollments = Enrollment::with(['student', 'course'])->get();
        $courses     = Course::where('status', 1)->get();
        $payments    = Payment::with(['student.enrollments.course', 'enrollment.course'])->orderBy('id', 'desc')->get();

        return view('admin.invoice.create.index', [
            'enrollments'          => $enrollments,
            'courses'              => $courses,
            'payments'             => $payments,
            'selectedPaymentId'    => $selectedPaymentId,
            'selectedEnrollmentId' => $selectedEnrollmentId,
            'selectedPayment'      => $selectedPayment,
            'selectedEnrollment'   => $selectedEnrollment,
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
        // Se o pagamento for enviado, infere a inscrição e o curso caso não venham preenchidos
        if ($request->filled('payment_id')) {
            $payment = Payment::with(['student.enrollments.course', 'enrollment.course'])->find($request->payment_id);
            if ($payment) {
                if (!$request->filled('enrollment_id')) {
                    if ($payment->enrollment_id) {
                        $request->merge(['enrollment_id' => $payment->enrollment_id]);
                    } elseif ($payment->student && $payment->student->enrollments->isNotEmpty()) {
                        $request->merge(['enrollment_id' => $payment->student->enrollments->sortByDesc('id')->first()->id]);
                    }
                }

                $resolvedEnrollment = $request->filled('enrollment_id') ? Enrollment::find($request->enrollment_id) : null;
                if (!$request->filled('course_id')) {
                    if ($resolvedEnrollment) {
                        $request->merge(['course_id' => $resolvedEnrollment->course_id]);
                    } elseif ($payment->enrollment) {
                        $request->merge(['course_id' => $payment->enrollment->course_id]);
                    }
                }

                if (!$request->filled('amount_to_pay')) {
                    $request->merge(['amount_to_pay' => $payment->value]);
                }
            }
        }

        if ($request->filled('enrollment_id') && !$request->filled('course_id')) {
            $enrollment = Enrollment::find($request->enrollment_id);
            if ($enrollment) {
                $request->merge(['course_id' => $enrollment->course_id]);
            }
        }

        $validatedData = $request->validate([
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'course_id'     => 'nullable|exists:courses,id',
            'amount_to_pay' => 'required|numeric|min:0',
            'amount_paid'   => 'required|numeric|min:0',
            'payment_id'    => 'nullable|exists:payments,id',
        ], [
            'amount_to_pay.required' => 'O valor a pagar é obrigatório.',
            'amount_paid.required'   => 'O valor recebido é obrigatório.',
        ]);

        // Conversão dos valores recebidos para ponto flutuante
        $amountToPay = (float) $validatedData['amount_to_pay'];
        $amountPaid  = (float) $validatedData['amount_paid'];
        $validatedData['amount_to_pay'] = $amountToPay;

        // Cálculo do troco / saldo acumulado
        $validatedData['change'] = max(0, $amountPaid - $amountToPay);

        // Se houver troco/excedente e o método de pagamento não for Numerário (Transferência ou Cartão/TPA), adiciona ao saldo do formando
        if ($validatedData['change'] > 0 && !empty($validatedData['payment_id'])) {
            $paymentObj = Payment::find($validatedData['payment_id']);
            if ($paymentObj && $paymentObj->student_id) {
                $pMethod = strtolower($paymentObj->payment_method ?? '');
                $isNumerario = str_contains($pMethod, 'numerári') || str_contains($pMethod, 'numerari');
                if (!$isNumerario) {
                    $student = \App\Models\Student::find($paymentObj->student_id);
                    if ($student) {
                        $student->balance += $validatedData['change'];
                        $student->save();
                    }
                }
            }
        }

        // Criação do registo da fatura na base de dados
        $invoice = Invoice::create($validatedData);

        // Atualização automática da inscrição para Pago/Confirmado (status = 1) se liquidação concluída
        if (!empty($validatedData['enrollment_id']) && ($amountPaid >= $amountToPay || !empty($validatedData['payment_id']))) {
            Enrollment::where('id', $validatedData['enrollment_id'])->update(['status' => 1]);
        }

        return redirect()->route('payment.index')->with('success', 'Fatura emitida com sucesso!');
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
