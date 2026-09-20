<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Enrollment;
use App\Models\Student;

/**
 * Controlador responsável pela gestão completa das operações CRUD da entidade Pagamento (Payment).
 * Gere o registo de pagamentos de emolumentos, associação com formandos via datalist,
 * cálculo e abatimento de saldo de crédito do estudante e atualização automática de inscrições.
 */
class paymentController extends Controller
{
    /**
     * Exibe a listagem de todos os pagamentos registados na base de dados.
     * Utiliza Eager Loading para carregar os relacionamentos 'student' e 'enrollment'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtém todos os pagamentos ordenados pelo ID mais recente com as suas relações, incluindo a fatura associada
        $payments = Payment::with(['student', 'enrollment.course', 'invoice'])->orderBy('id', 'desc')->get();
        return view('admin.payment.list.index', ['payments' => $payments]);
    }

    /**
     * Exibe o formulário para registar um novo pagamento.
     * Carrega a lista de estudantes ativos para associação no campo datalist e verifica se veio de uma inscrição.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Obtém a lista de todos os estudantes ordenados por nome para preenchimento do datalist
        $students = Student::orderBy('name', 'asc')->get();

        // Verifica se o formulário foi aberto a partir de uma inscrição específica
        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedEnrollment   = $selectedEnrollmentId ? Enrollment::with(['student', 'course'])->find($selectedEnrollmentId) : null;

        return view('admin.payment.create.index', [
            'students'             => $students,
            'selectedEnrollmentId' => $selectedEnrollmentId,
            'selectedEnrollment'   => $selectedEnrollment,
        ]);
    }

    /**
     * Valida os dados submetidos, gere a utilização ou acumulação de saldo do estudante e guarda o pagamento.
     * Se o pagamento for marcado como concluído (status = 1), atualiza a inscrição associada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Converte valores monetários formatados (ex: "26.000,00" ou "26 000,00") para formato numérico do PHP ("26000.00")
        $cleanCurrency = function ($val) {
            if (is_null($val) || $val === '') return 0.00;
            if (is_numeric($val)) return (float)$val;
            $cleaned = preg_replace('/[^\d,.-]/', '', (string)$val);
            if (strpos($cleaned, ',') !== false) {
                $cleaned = str_replace('.', '', $cleaned);
                $cleaned = str_replace(',', '.', $cleaned);
            }
            return is_numeric($cleaned) ? (float)$cleaned : 0.00;
        };

        if ($request->has('value')) {
            $request->merge(['value' => $cleanCurrency($request->input('value'))]);
        }
        if ($request->has('used_balance')) {
            $request->merge(['used_balance' => $cleanCurrency($request->input('used_balance'))]);
        }
        if ($request->has('add_to_balance')) {
            $request->merge(['add_to_balance' => $cleanCurrency($request->input('add_to_balance'))]);
        }

        // Se student_id ou enrollment_id vierem como string vazia, converte em null para não falhar a regra de chave estrangeira
        if (!$request->filled('student_id')) {
            $request->merge(['student_id' => null]);
        }
        if (!$request->filled('enrollment_id')) {
            $request->merge(['enrollment_id' => null]);
        }

        // Se a referência for gerada automaticamente no frontend ou não enviada, gera um número inteiro automático de 8 dígitos
        if (!$request->has('reference') || empty($request->reference)) {
            $request->merge(['reference' => rand(10000000, 99999999)]);
        } else {
            $request->merge(['reference' => (int)$request->input('reference')]);
        }

        // Caso o tipo de pagamento esteja vazio mas uma inscrição esteja associada, assume "Inscrição" por omissão
        if (empty($request->type_of_payment) && $request->filled('enrollment_id')) {
            $request->merge(['type_of_payment' => 'Inscrição']);
        }

        // Validação rigorosa dos campos enviados pelo formulário de pagamento
        $validatedData = $request->validate([
            'student_id'      => 'nullable|exists:students,id',
            'enrollment_id'   => 'nullable|exists:enrollments,id',
            'type_of_payment' => 'required|string|max:255',
            'value'           => 'required|numeric|min:0',
            'reference'       => 'required|integer',
            'status'          => 'nullable|boolean',
            'payment_method'  => 'required|string|max:255',
            'date'            => 'nullable',
            'currency'        => 'required|string|max:10',
            'used_balance'    => 'nullable|numeric|min:0',
            'add_to_balance'  => 'nullable|numeric|min:0',
        ], [
            'type_of_payment.required' => 'O tipo de emolumento/pagamento é obrigatório.',
            'value.required'           => 'O valor do pagamento é obrigatório.',
            'payment_method.required'  => 'Selecione a forma de pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
        ]);

        // Se a data de pagamento não tiver sido fornecida, assume a data/hora atual
        if (empty($validatedData['date'])) {
            $validatedData['date'] = now();
        }

        // Gestão do saldo de crédito do estudante (se um estudante for selecionado)
        $studentId = $request->input('student_id');
        $enrollmentId = $request->input('enrollment_id');

        if ($studentId) {
            $student = Student::find($studentId);
            if ($student) {
                $usedBalance  = floatval($request->input('used_balance', 0));
                $addToBalance = floatval($request->input('add_to_balance', 0));

                // Abate o saldo que foi utilizado no pagamento
                if ($usedBalance > 0) {
                    $student->balance = max(0, $student->balance - $usedBalance);
                }

                // Adiciona o valor pago a mais (excesso) como saldo de crédito para futuros pagamentos
                if ($addToBalance > 0) {
                    $student->balance += $addToBalance;
                }

                $student->save();
            }
        }

        // Se veio de uma inscrição e o student_id não foi preenchido explicitamente, associa pelo enrollment
        if (!$studentId && $enrollmentId) {
            $enrollmentObj = Enrollment::find($enrollmentId);
            if ($enrollmentObj) {
                $validatedData['student_id'] = $enrollmentObj->student_id;
            }
        }

        // Se o student_id foi fornecido mas o enrollment_id não, associa a inscrição mais recente do formando
        if ($studentId && !$enrollmentId) {
            $studentEnrollment = Enrollment::where('student_id', $studentId)->orderBy('id', 'desc')->first();
            if ($studentEnrollment) {
                $validatedData['enrollment_id'] = $studentEnrollment->id;
                $enrollmentId = $studentEnrollment->id;
            }
        }

        // Garante que o estado de novos pagamentos é sempre Concluído / Pago (status = 1)
        $request->merge(['status' => 1]);

        // Cria o registo do pagamento na base de dados
        $payment = Payment::create([
            'student_id'      => $validatedData['student_id'] ?? null,
            'enrollment_id'   => $validatedData['enrollment_id'] ?? null,
            'type_of_payment' => $validatedData['type_of_payment'],
            'value'           => $validatedData['value'],
            'reference'       => $validatedData['reference'],
            'status'          => 1, // Sempre Concluído / Pago
            'date'            => $validatedData['date'],
            'currency'        => $validatedData['currency'],
            'payment_method'  => $validatedData['payment_method'],
        ]);

        // Se o pagamento for marcado como Pago/Concluído (status = 1), marca a inscrição associada como confirmada (status = 1)
        if ($payment->status) {
            $targetEnrollmentId = $enrollmentId;

            if (!$targetEnrollmentId && $payment->student_id) {
                // Procura uma inscrição pendente do estudante para atualizar
                $pendingEnrollment = Enrollment::where('student_id', $payment->student_id)->where('status', 0)->first();
                if ($pendingEnrollment) {
                    $targetEnrollmentId = $pendingEnrollment->id;
                }
            }

            if ($targetEnrollmentId) {
                Enrollment::where('id', $targetEnrollmentId)->update(['status' => 1]);
            }
        }

        // Redireciona o utilizador para a tela de Emissão de Fatura para confirmação manual antes de gerar a fatura final
        return redirect()->route('invoice.create', [
            'payment_id'    => $payment->id,
            'enrollment_id' => $payment->enrollment_id,
        ])->with('success', 'Pagamento guardado com sucesso! Verifique os dados e clique em "Confirmar e Gerar Fatura".');
    }

    /**
     * Exibe a página de detalhes de um pagamento específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o pagamento com as relações do estudante, da inscrição e da fatura associada
        $payment = Payment::with(['student', 'enrollment.course', 'invoice'])->findOrFail($id);
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
        $payment  = Payment::with(['student', 'enrollment'])->findOrFail($id);
        $students = Student::orderBy('name', 'asc')->get();

        return view('admin.payment.edit.index', [
            'payment'  => $payment,
            'students' => $students,
        ]);
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

        $cleanCurrency = function ($val) {
            if (is_null($val) || $val === '') return null;
            $cleaned = preg_replace('/[^\d,.-]/', '', (string)$val);
            if (strpos($cleaned, ',') !== false) {
                $cleaned = str_replace('.', '', $cleaned);
                $cleaned = str_replace(',', '.', $cleaned);
            }
            return is_numeric($cleaned) ? (float)$cleaned : $val;
        };

        if ($request->has('value')) {
            $request->merge(['value' => $cleanCurrency($request->input('value'))]);
        }

        $validatedData = $request->validate([
            'student_id'      => 'nullable|exists:students,id',
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

        // Se o pagamento for atualizado para Pago/Concluído (status = 1), marca a inscrição associada como confirmada
        if ($payment->status) {
            if ($payment->enrollment_id) {
                Enrollment::where('id', $payment->enrollment_id)->update(['status' => 1]);
            } elseif ($payment->student_id) {
                $pendingEnrollment = Enrollment::where('student_id', $payment->student_id)->where('status', 0)->first();
                if ($pendingEnrollment) {
                    Enrollment::where('id', $pendingEnrollment->id)->update(['status' => 1]);
                }
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
