<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Enrollment;
use App\Models\Student;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Pagamentos (paymentController)
 * =========================================================================================
 * Este controlador é responsável pelo ciclo de vida completo dos registos de pagamento de emolumentos
 * no sistema do Centro de Formação.
 * 
 * Principais Funcionalidades:
 *  - Sanitização e Conversão Monetária (suporte a formatos de moeda com vírgulas e pontos).
 *  - Sanitização de Chaves Estrangeiras (`student_id` e `enrollment_id`).
 *  - Geração Automática de Número de Referência Único.
 *  - Gestão Avançada de Saldo do Formando (Abatimento de crédito existente vs Acúmulo de troco em saldo).
 *  - Sincronização de Estado da Inscrição (Atualiza inscrição pendente para status = 1 quando paga).
 *  - Redirecionamento para o Fluxo de Confirmação e Emissão de Faturas em Duas Etapas.
 */
class paymentController extends Controller
{
    /**
     * Exibe a listagem de todos os pagamentos registados na base de dados.
     * Utiliza Eager Loading ('student', 'enrollment.course', 'invoice') para otimização de desempenho.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os pagamentos ordenados pelo ID mais recente com as suas relações preenchidas
        $payments = Payment::with(['student', 'enrollment.course', 'invoice'])->orderBy('id', 'desc')->get();
        
        // Retorna a view da lista de pagamentos no painel administrativo
        return view('admin.payment.list.index', ['payments' => $payments]);
    }

    /**
     * Exibe o formulário para registar um novo pagamento de emolumento.
     * Carrega os estudantes para sugestão via datalist e resolve eventual inscrição pré-selecionada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Carrega a lista completa de estudantes ordenados por nome para preenchimento dinâmico do datalist
        $students = Student::orderBy('name', 'asc')->get();

        // Verifica se o pagamento está a ser iniciado a partir de uma inscrição específica via URL (?enrollment_id=X)
        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedEnrollment   = $selectedEnrollmentId ? Enrollment::with(['student', 'course'])->find($selectedEnrollmentId) : null;

        // Retorna a view de criação de pagamento
        return view('admin.payment.create.index', [
            'students'             => $students,
            'selectedEnrollmentId' => $selectedEnrollmentId,
            'selectedEnrollment'   => $selectedEnrollment,
        ]);
    }

    /**
     * Valida os dados submetidos, processa abatimento/crédito de saldo, cria o pagamento
     * e redireciona para a tela de revisão e confirmação da fatura.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // ---------------------------------------------------------------------------------
        // 1. FUNÇÃO AUXILIAR DE SANITIZAÇÃO DE MOEDA (cleanCurrency)
        // ---------------------------------------------------------------------------------
        // Converte strings de valor monetário (ex: "26.000,00 KZ" ou "26 000.00") em float puro PHP (26000.00)
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

        // Aplica a limpeza nos campos monetários recebidos no pedido
        if ($request->has('value')) {
            $request->merge(['value' => $cleanCurrency($request->input('value'))]);
        }
        if ($request->has('used_balance')) {
            $request->merge(['used_balance' => $cleanCurrency($request->input('used_balance'))]);
        }
        if ($request->has('add_to_balance')) {
            $request->merge(['add_to_balance' => $cleanCurrency($request->input('add_to_balance'))]);
        }

        // ---------------------------------------------------------------------------------
        // 2. TRATAMENTO DE VALORES NULOS E REFERÊNCIA AUTOMÁTICA
        // ---------------------------------------------------------------------------------
        // Converte strings vazias em null para evitar erros de restrição de chave estrangeira
        if (!$request->filled('student_id')) {
            $request->merge(['student_id' => null]);
        }
        if (!$request->filled('enrollment_id')) {
            $request->merge(['enrollment_id' => null]);
        }

        // Gera um número de referência aleatório de 8 dígitos caso não tenha sido preenchido
        if (!$request->has('reference') || empty($request->reference)) {
            $request->merge(['reference' => rand(10000000, 99999999)]);
        } else {
            $request->merge(['reference' => (int)$request->input('reference')]);
        }

        // Preenche o tipo de emolumento como 'Inscrição' caso venha omisso e haja uma inscrição vinculada
        if (empty($request->type_of_payment) && $request->filled('enrollment_id')) {
            $request->merge(['type_of_payment' => 'Inscrição']);
        }

        // ---------------------------------------------------------------------------------
        // 3. VALIDAÇÃO RIGOROSA DOS REQUISITOS DO FORMULÁRIO
        // ---------------------------------------------------------------------------------
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
            'type_of_payment.required' => 'O tipo de emolumento/pagamento é de preenchimento obrigatório.',
            'value.required'           => 'O valor do pagamento é de preenchimento obrigatório.',
            'payment_method.required'  => 'Selecione a forma de pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
        ]);

        // Atribui a data e hora atual do sistema se o campo de data vier vazio
        if (empty($validatedData['date'])) {
            $validatedData['date'] = now();
        }

        // ---------------------------------------------------------------------------------
        // 4. GESTÃO DE SALDO DE CRÉDITO DO ESTUDANTE
        // ---------------------------------------------------------------------------------
        $studentId = $request->input('student_id');
        $enrollmentId = $request->input('enrollment_id');

        if ($studentId) {
            $student = Student::find($studentId);
            if ($student) {
                $usedBalance  = floatval($request->input('used_balance', 0));
                $addToBalance = floatval($request->input('add_to_balance', 0));

                // Abate do saldo do formando o montante utilizado para cobrir a transação
                if ($usedBalance > 0) {
                    $student->balance = max(0, $student->balance - $usedBalance);
                }

                // Adiciona o montante excedente fornecido pelo aluno ao seu saldo de crédito
                if ($addToBalance > 0) {
                    $student->balance += $addToBalance;
                }

                $student->save();
            }
        }

        // ---------------------------------------------------------------------------------
        // 5. INFERÊNCIA E RESOLUÇÃO CRUZADA DE STUDENT_ID E ENROLLMENT_ID
        // ---------------------------------------------------------------------------------
        // Se a inscrição veio informada mas o student_id não, extrai o student_id a partir da inscrição
        if (!$studentId && $enrollmentId) {
            $enrollmentObj = Enrollment::find($enrollmentId);
            if ($enrollmentObj) {
                $validatedData['student_id'] = $enrollmentObj->student_id;
            }
        }

        // Se o student_id veio informado mas o enrollment_id não, associa a última inscrição do formando
        if ($studentId && !$enrollmentId) {
            $studentEnrollment = Enrollment::where('student_id', $studentId)->orderBy('id', 'desc')->first();
            if ($studentEnrollment) {
                $validatedData['enrollment_id'] = $studentEnrollment->id;
                $enrollmentId = $studentEnrollment->id;
            }
        }

        // ---------------------------------------------------------------------------------
        // 6. CRIAÇÃO DO REGISTO DE PAGAMENTO NA BASE DE DADOS
        // ---------------------------------------------------------------------------------
        // Garante que o estado de novos pagamentos é sempre Pago / Concluído (status = 1)
        $request->merge(['status' => 1]);

        $payment = Payment::create([
            'student_id'      => $validatedData['student_id'] ?? null,
            'enrollment_id'   => $validatedData['enrollment_id'] ?? null,
            'type_of_payment' => $validatedData['type_of_payment'],
            'value'           => $validatedData['value'],
            'reference'       => $validatedData['reference'],
            'status'          => 1, // Sempre Pago / Concluído
            'date'            => $validatedData['date'],
            'currency'        => $validatedData['currency'],
            'payment_method'  => $validatedData['payment_method'],
        ]);

        // ---------------------------------------------------------------------------------
        // 7. ATUALIZAÇÃO DO ESTADO DA INSCRIÇÃO ASSOCIADA
        // ---------------------------------------------------------------------------------
        // Marca a inscrição associada como confirmada (status = 1) após a conclusão do pagamento
        if ($payment->status) {
            $targetEnrollmentId = $enrollmentId;

            if (!$targetEnrollmentId && $payment->student_id) {
                // Procura uma inscrição pendente (status = 0) do formando para confirmar
                $pendingEnrollment = Enrollment::where('student_id', $payment->student_id)->where('status', 0)->first();
                if ($pendingEnrollment) {
                    $targetEnrollmentId = $pendingEnrollment->id;
                }
            }

            if ($targetEnrollmentId) {
                Enrollment::where('id', $targetEnrollmentId)->update(['status' => 1]);
            }
        }

        // ---------------------------------------------------------------------------------
        // 8. REDIRECIONAMENTO PARA REVISÃO E CONFIRMAÇÃO DA FATURA (2ª ETAPA)
        // ---------------------------------------------------------------------------------
        // Redireciona o utilizador para a view invoice.create antes de emitir a fatura final
        return redirect()->route('invoice.create', [
            'payment_id'    => $payment->id,
            'enrollment_id' => $payment->enrollment_id,
        ])->with('success', 'Pagamento guardado com sucesso! Verifique os dados e clique em "Confirmar e Gerar Fatura".');
    }

    /**
     * Exibe a página com os detalhes de um pagamento específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o pagamento com as suas relações de estudante, inscrição/curso e fatura associada
        $payment = Payment::with(['student', 'enrollment.course', 'invoice'])->findOrFail($id);

        // Retorna a view com os detalhes do pagamento
        return view('admin.payment.details.index', ['payment' => $payment]);
    }

    /**
     * Exibe o formulário para editar os dados de um pagamento existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Carrega o pagamento e a lista de estudantes para opção de alteração
        $payment        = Payment::with(['student', 'enrollment'])->findOrFail($id);
        $students       = Student::orderBy('name', 'asc')->get();
        $currentStudent = $payment->student;

        return view('admin.payment.edit.index', [
            'payment'        => $payment,
            'students'       => $students,
            'currentStudent' => $currentStudent,
        ]);
    }

    /**
     * Valida e atualiza os dados de um pagamento na base de dados.
     * Atualiza o estado da inscrição correspondente se o estado for alterado para Pago.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza o pagamento a ser editado
        $payment = Payment::findOrFail($id);

        // Limpeza de formato de moeda
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

        // Validação dos dados de atualização
        $validatedData = $request->validate([
            'student_id'      => 'nullable|exists:students,id',
            'type_of_payment' => 'required|string|max:255',
            'value'           => 'required|numeric|min:0',
            'status'          => 'required|boolean',
            'payment_method'  => 'required|string|max:255',
            'currency'        => 'required|string|max:10',
        ], [
            'type_of_payment.required' => 'O tipo de emolumento/pagamento é de preenchimento obrigatório.',
            'value.required'           => 'O valor do pagamento é de preenchimento obrigatório.',
            'status.required'          => 'Por favor selecione o estado do pagamento.',
            'payment_method.required'  => 'Selecione a forma de pagamento.',
            'currency.required'        => 'A indicação da moeda é obrigatória.',
        ]);

        // Mantém inalterados o número de referência e a data original de registo do pagamento
        $validatedData['reference'] = $payment->reference;
        $validatedData['date']      = $payment->date;

        // Atualiza o pagamento na base de dados
        $payment->update($validatedData);

        // Se o pagamento for atualizado para Concluído/Pago (status = 1), sincroniza a inscrição associada
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

        // Redireciona para a lista de pagamentos com mensagem de confirmação
        return redirect()->route('payment.index')->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove um pagamento da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Localiza e remove o pagamento selecionado
        $payment = Payment::findOrFail($id);
        $payment->delete();

        // Redireciona para a lista de pagamentos com mensagem de sucesso
        return redirect()->route('payment.index')->with('success', 'Pagamento eliminado com sucesso!');
    }

    /**
     * Exibe o painel principal do sistema (Dashboard Administrativo).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }
}

