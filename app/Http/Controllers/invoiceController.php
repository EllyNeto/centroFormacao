<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Faturas (invoiceController)
 * =========================================================================================
 * Este controlador é responsável por gerir a emissão, listagem, edição e eliminação de faturas
 * no sistema do Centro de Formação.
 * 
 * Principais Funcionalidades:
 *  - Emissão de Faturas em Segunda Etapa (após revisão e confirmação do pagamento pelo operador).
 *  - Resolução Automática de Relações entre Pagamento, Inscrição, Curso e Formando.
 *  - Atualização Automática do Estado da Inscrição para Confirmado/Pago (status = 1).
 *  - Gestão de Saldo do Formando: Acúmulo de valores excedentes no campo 'balance' do estudante.
 */
class invoiceController extends Controller
{
    /**
     * Exibe a listagem de todas as faturas registadas no sistema.
     * Utiliza Eager Loading para otimizar as consultas às tabelas de inscrições, estudantes, cursos e pagamentos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todas as faturas ordenadas pela mais recente, carregando antecipadamente os relacionamentos
        $invoices = Invoice::with(['enrollment.student', 'course', 'payment'])->orderBy('id', 'desc')->get();

        // Retorna a view da lista de faturas na área administrativa
        return view('admin.invoice.list.index', ['invoices' => $invoices]);
    }

    /**
     * Exibe o formulário de revisão e emissão de nova fatura.
     * Resolve automaticamente o pagamento e a inscrição associados passados via Query String (?payment_id=X&enrollment_id=Y).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Obtém os parâmetros 'payment_id' e 'enrollment_id' passados no pedido HTTP
        $selectedPaymentId    = $request->query('payment_id');
        $selectedEnrollmentId = $request->query('enrollment_id');

        // Carrega os dados do pagamento selecionado com os relacionamentos do formando e curso
        $selectedPayment = $selectedPaymentId 
            ? Payment::with(['student.enrollments.course', 'enrollment.course'])->find($selectedPaymentId) 
            : null;

        // Tenta inferir a inscrição associada a partir do pagamento ou do registo do estudante
        $selectedEnrollment = null;
        if ($selectedPayment) {
            if ($selectedPayment->enrollment) {
                // Inscrição diretamente associada ao pagamento
                $selectedEnrollment = $selectedPayment->enrollment;
            } elseif ($selectedPayment->student && $selectedPayment->student->enrollments->isNotEmpty()) {
                // Última inscrição realizada pelo estudante ligado a este pagamento
                $selectedEnrollment = $selectedPayment->student->enrollments->sortByDesc('id')->first();
            }
        }

        // Se a inscrição não tiver sido resolvida pelo pagamento, tenta procurá-la pelo ID enviado
        if (!$selectedEnrollment && $selectedEnrollmentId) {
            $selectedEnrollment = Enrollment::with(['student', 'course'])->find($selectedEnrollmentId);
        }

        // Carrega coleções auxiliares para listagens do sistema
        $enrollments = Enrollment::with(['student', 'course'])->get();
        $courses     = Course::where('status', 1)->get();
        $payments    = Payment::with(['student.enrollments.course', 'enrollment.course'])->orderBy('id', 'desc')->get();

        // Retorna a view de emissão da fatura com todas as variáveis e pré-seleções resolvidas
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
     * Valida e guarda uma nova fatura na base de dados após a confirmação do operador.
     * Atualiza automaticamente o estado da inscrição para confirmada/paga (status = 1)
     * e credita eventuais valores excedentes no saldo do estudante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // ---------------------------------------------------------------------------------
        // 1. RESOLUÇÃO E AUTO-PREENCHIMENTO DE DADOS OMITIDOS
        // ---------------------------------------------------------------------------------
        // Se a fatura estiver associada a um pagamento, preenche automaticamente os IDs de inscrição e curso
        if ($request->filled('payment_id')) {
            $payment = Payment::with(['student.enrollments.course', 'enrollment.course'])->find($request->payment_id);
            if ($payment) {
                // Inscrição associada
                if (!$request->filled('enrollment_id')) {
                    if ($payment->enrollment_id) {
                        $request->merge(['enrollment_id' => $payment->enrollment_id]);
                    } elseif ($payment->student && $payment->student->enrollments->isNotEmpty()) {
                        $request->merge(['enrollment_id' => $payment->student->enrollments->sortByDesc('id')->first()->id]);
                    }
                }

                // Curso associado
                $resolvedEnrollment = $request->filled('enrollment_id') ? Enrollment::find($request->enrollment_id) : null;
                if (!$request->filled('course_id')) {
                    if ($resolvedEnrollment) {
                        $request->merge(['course_id' => $resolvedEnrollment->course_id]);
                    } elseif ($payment->enrollment) {
                        $request->merge(['course_id' => $payment->enrollment->course_id]);
                    }
                }

                // Valor a pagar por padrão igual ao valor total do pagamento
                if (!$request->filled('amount_to_pay')) {
                    $request->merge(['amount_to_pay' => $payment->value]);
                }
            }
        }

        // Garante a vinculação do curso a partir da inscrição se o curso não for informado explicitamente
        if ($request->filled('enrollment_id') && !$request->filled('course_id')) {
            $enrollment = Enrollment::find($request->enrollment_id);
            if ($enrollment) {
                $request->merge(['course_id' => $enrollment->course_id]);
            }
        }

        // ---------------------------------------------------------------------------------
        // 2. VALIDAÇÃO DOS DADOS ENVIADOS
        // ---------------------------------------------------------------------------------
        $validatedData = $request->validate([
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'course_id'     => 'nullable|exists:courses,id',
            'amount_to_pay' => 'required|numeric|min:0',
            'amount_paid'   => 'required|numeric|min:0',
            'payment_id'    => 'nullable|exists:payments,id',
        ], [
            'amount_to_pay.required' => 'O valor a pagar é de preenchimento obrigatório.',
            'amount_paid.required'   => 'O valor recebido/pago é de preenchimento obrigatório.',
        ]);

        // ---------------------------------------------------------------------------------
        // 3. CONVERSÃO E CÁLCULO DE VALORES FINANCEIROS E TROCO/SALDO
        // ---------------------------------------------------------------------------------
        $amountToPay = (float) $validatedData['amount_to_pay'];
        $amountPaid  = (float) $validatedData['amount_paid'];
        $validatedData['amount_to_pay'] = $amountToPay;

        // Calcula o valor do troco / excedente da transação financeira
        $validatedData['change'] = max(0, $amountPaid - $amountToPay);

        // ---------------------------------------------------------------------------------
        // 4. GESTÃO DE SALDO DO ESTUDANTE (CRÉDITO ACUMULADO)
        // ---------------------------------------------------------------------------------
        // Se o valor pago for superior ao necessário, o troco/excedente é adicionado ao saldo do formando
        $targetStudent = null;
        if (!empty($validatedData['payment_id'])) {
            $paymentObj = Payment::find($validatedData['payment_id']);
            if ($paymentObj && $paymentObj->student_id) {
                $targetStudent = Student::find($paymentObj->student_id);
            }
        }
        if (!$targetStudent && !empty($validatedData['enrollment_id'])) {
            $enrollmentObj = Enrollment::find($validatedData['enrollment_id']);
            if ($enrollmentObj && $enrollmentObj->student_id) {
                $targetStudent = Student::find($enrollmentObj->student_id);
            }
        }

        if ($targetStudent && $validatedData['change'] > 0) {
            // Soma o excedente ao saldo acumulado do formando para abatimento automático em pagamentos futuros
            $targetStudent->balance += $validatedData['change'];
            $targetStudent->save();
        }

        // ---------------------------------------------------------------------------------
        // 5. CRIAÇÃO DA FATURA E ATUALIZAÇÃO DO ESTADO DA INSCRIÇÃO
        // ---------------------------------------------------------------------------------
        // Regista formalmente a fatura na base de dados
        $invoice = Invoice::create($validatedData);

        // Atualiza a inscrição associada para o estado 'Confirmada/Paga' (status = 1)
        if (!empty($validatedData['enrollment_id']) && ($amountPaid >= $amountToPay || !empty($validatedData['payment_id']))) {
            Enrollment::where('id', $validatedData['enrollment_id'])->update(['status' => 1]);
        }

        // Redireciona a página para a visualização detalhada da fatura emitida
        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Fatura confirmada e emitida com sucesso!');
    }

    /**
     * Exibe a página com os detalhes completos de uma fatura específica (visualização e impressão).
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura a fatura pelo ID com todas as suas relações (formando, inscrição, curso e pagamento)
        $invoice = Invoice::with(['enrollment.student', 'course', 'payment'])->findOrFail($id);

        // Retorna a view com os detalhes e discriminativo da fatura
        return view('admin.invoice.details.index', ['invoice' => $invoice]);
    }

    /**
     * Exibe o formulário de edição de uma fatura previamente emitida.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Carrega a fatura e os registos auxiliares necessários para preencher o formulário
        $invoice = Invoice::with(['enrollment.student', 'enrollment.course', 'course', 'payment'])->findOrFail($id);
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
     * Valida e atualiza os dados de uma fatura na base de dados.
     * Sincroniza o valor pago e o estado da inscrição associada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza a fatura a ser editada
        $invoice = Invoice::findOrFail($id);

        // Infere o curso a partir da inscrição se não fornecido
        if ($request->filled('enrollment_id') && !$request->filled('course_id')) {
            $enrollment = Enrollment::find($request->enrollment_id);
            if ($enrollment) {
                $request->merge(['course_id' => $enrollment->course_id]);
            }
        }

        // Define por padrão o valor a pagar como o valor pago se omitido
        if (!$request->filled('amount_to_pay')) {
            $request->merge(['amount_to_pay' => $request->input('amount_paid', 0)]);
        }

        // Valida os dados submetidos no formulário de edição
        $validatedData = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'course_id'     => 'required|exists:courses,id',
            'amount_to_pay' => 'nullable|numeric|min:0',
            'amount_paid'   => 'required|numeric|min:0',
            'payment_id'    => 'nullable|exists:payments,id',
        ], [
            'enrollment_id.required' => 'A inscrição é de preenchimento obrigatório.',
            'course_id.required'     => 'O curso é de preenchimento obrigatório.',
            'amount_paid.required'   => 'O valor pago é de preenchimento obrigatório.',
        ]);

        // Processa os cálculos de montantes e troco
        $amountToPay = (float) ($validatedData['amount_to_pay'] ?? $validatedData['amount_paid']);
        $amountPaid  = (float) $validatedData['amount_paid'];
        $validatedData['amount_to_pay'] = $amountToPay;
        $validatedData['change']        = max(0, $amountPaid - $amountToPay);

        // Atualiza a fatura na base de dados
        $invoice->update($validatedData);

        // Atualiza o estado da inscrição se a liquidação tiver sido concluída
        if ($amountPaid >= $amountToPay || !empty($validatedData['payment_id'])) {
            Enrollment::where('id', $validatedData['enrollment_id'])->update(['status' => 1]);
        }

        // Redireciona para a lista de faturas com mensagem de sucesso
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
        // Procura a fatura pelo ID e remove o registo
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        // Redireciona para a listagem com mensagem de confirmação
        return redirect()->route('invoice.index')->with('success', 'Fatura eliminada com sucesso!');
    }
}

