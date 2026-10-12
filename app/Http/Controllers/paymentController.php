<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controlador responsável pela gestão de Pagamentos no Centro de Formação.
 *
 * Módulo 6: Pagamentos
 * Controla a listagem, registo, alteração, detalhes e anulação de pagamentos,
 * bem como a transição automática de estados da Inscrição (Pendente -> Pago).
 */
class paymentController extends Controller
{
    /**
     * Apresenta a listagem global de todos os pagamentos registados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Recupera todos os pagamentos com os relacionamentos necessários carregados previamente
        $payments = Payment::with(['enrollment.course'])
            ->orderBy('id', 'desc')
            ->get();

        // Cálculo de métricas financeiras para o resumo do topo
        $totalCollected = Payment::where('status', 'Confirmado')->sum('value');
        $totalConfirmed = Payment::where('status', 'Confirmado')->count();
        $totalPending   = Payment::where('status', 'Pendente')->count();
        $totalCanceled  = Payment::where('status', 'Anulado')->count();

        return view('admin.payment.list.index', compact(
            'payments',
            'totalCollected',
            'totalConfirmed',
            'totalPending',
            'totalCanceled'
        ));
    }

    /**
     * Apresenta o formulário para registo de um novo pagamento.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Carrega as candidaturas disponíveis para seleção (excluindo apenas as canceladas)
        $enrollments = Enrollment::with('course')
            ->whereNotIn('status', ['Cancelado'])
            ->orderBy('name', 'asc')
            ->get();

        // Se tiver sido passado o parâmetro enrollment_id via URL (atalho a partir dos detalhes)
        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedEnrollment = null;

        if ($selectedEnrollmentId) {
            $selectedEnrollment = Enrollment::with('course')->find($selectedEnrollmentId);
        }

        // Gera uma sugestão sequencial e amigável de referência para o recibo
        $suggestedReference = Payment::generateSuggestedReference();

        return view('admin.payment.create.index', compact(
            'enrollments',
            'selectedEnrollment',
            'suggestedReference'
        ));
    }

    /**
     * Armazena um novo pagamento na base de dados e atualiza o estado da inscrição.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação estrita em linha (inline) conforme diretrizes arquiteturais
        $validatedData = $request->validate([
            'enrollment_id'   => 'required|exists:enrollments,id',
            'type_of_payment' => 'required|string|in:Transferência,Multicaixa,Numerário',
            'value'           => 'required|numeric|min:1',
            'currency'        => 'nullable|string|size:3',
            'reference'       => 'required|string|max:100|unique:payments,reference',
            'status'          => 'required|string|in:Pendente,Confirmado,Anulado',
            'date'            => 'required|date',
        ], [
            'enrollment_id.required'   => 'É obrigatório selecionar o candidato / inscrição.',
            'enrollment_id.exists'     => 'A inscrição selecionada não existe no sistema.',
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.in'       => 'O tipo de pagamento deve ser Transferência, Multicaixa ou Numerário.',
            'value.required'           => 'O montante do pagamento é obrigatório.',
            'value.numeric'            => 'O montante deve ser um valor numérico válido.',
            'value.min'                => 'O montante a liquidar deve ser superior a zero.',
            'currency.size'            => 'A divisa monetária deve ter exatamente 3 caracteres (Ex: AOA).',
            'reference.required'       => 'A referência ou número do recibo é obrigatória.',
            'reference.unique'         => 'Esta referência ou número de recibo já se encontra registada.',
            'reference.max'            => 'A referência não pode exceder 100 caracteres.',
            'status.required'          => 'O estado do pagamento é obrigatório.',
            'status.in'                => 'O estado selecionado é inválido.',
            'date.required'            => 'A data da transação é obrigatória.',
            'date.date'                => 'A data informada não possui um formato válido.',
        ]);

        // Atribui moeda padrão AOA caso não tenha sido especificada
        if (empty($validatedData['currency'])) {
            $validatedData['currency'] = 'AOA';
        }

        // Formata a data e hora
        $validatedData['date'] = Carbon::parse($validatedData['date']);

        // Cria o registo de pagamento
        $payment = Payment::create($validatedData);

        // Sincroniza o estado da inscrição associada de acordo com o pagamento
        $enrollment = Enrollment::find($validatedData['enrollment_id']);
        if ($enrollment) {
            $this->syncEnrollmentStatus($enrollment);
        }

        return redirect()->route('payment.show', $payment->id)
            ->with('success', 'Pagamento registado com sucesso! O estado da candidatura é agora: ' . $enrollment->fresh()->status . '.');
    }

    /**
     * Exibe o recibo e detalhes completos do pagamento.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $payment = Payment::with([
            'enrollment.course',
            'enrollment.room.classroom',
            'enrollment.room.teacher'
        ])->findOrFail($id);

        return view('admin.payment.details.index', compact('payment'));
    }

    /**
     * Apresenta o formulário de edição de um pagamento existente.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $payment = Payment::with('enrollment.course')->findOrFail($id);

        $enrollments = Enrollment::with('course')
            ->whereNotIn('status', ['Cancelado'])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.payment.edit.index', compact('payment', 'enrollments'));
    }

    /**
     * Atualiza as informações do pagamento e reavalia o estado da inscrição.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $oldEnrollmentId = (int) $payment->enrollment_id;

        // Validação estrita em linha (inline)
        $validatedData = $request->validate([
            'enrollment_id'   => 'required|exists:enrollments,id',
            'type_of_payment' => 'required|string|in:Transferência,Multicaixa,Numerário',
            'value'           => 'required|numeric|min:1',
            'currency'        => 'nullable|string|size:3',
            'reference'       => 'required|string|max:100|unique:payments,reference,' . $id,
            'status'          => 'required|string|in:Pendente,Confirmado,Anulado',
            'date'            => 'required|date',
        ], [
            'enrollment_id.required'   => 'É obrigatório selecionar o candidato / inscrição.',
            'enrollment_id.exists'     => 'A inscrição selecionada não existe no sistema.',
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.in'       => 'O tipo de pagamento deve ser Transferência, Multicaixa ou Numerário.',
            'value.required'           => 'O montante do pagamento é obrigatório.',
            'value.numeric'            => 'O montante deve ser um valor numérico válido.',
            'value.min'                => 'O montante a liquidar deve ser superior a zero.',
            'currency.size'            => 'A divisa monetária deve ter exatamente 3 caracteres.',
            'reference.required'       => 'A referência ou número do recibo é obrigatória.',
            'reference.unique'         => 'Esta referência ou número de recibo já se encontra registada.',
            'reference.max'            => 'A referência não pode exceder 100 caracteres.',
            'status.required'          => 'O estado do pagamento é obrigatório.',
            'status.in'                => 'O estado selecionado é inválido.',
            'date.required'            => 'A data da transação é obrigatória.',
            'date.date'                => 'A data informada não possui um formato válido.',
        ]);

        if (empty($validatedData['currency'])) {
            $validatedData['currency'] = 'AOA';
        }

        $validatedData['date'] = Carbon::parse($validatedData['date']);

        // Atualiza os dados do pagamento
        $payment->update($validatedData);

        // Sincroniza a inscrição atual
        $currentEnrollment = Enrollment::find($validatedData['enrollment_id']);
        if ($currentEnrollment) {
            $this->syncEnrollmentStatus($currentEnrollment);
        }

        // Se a inscrição associada foi alterada, sincroniza também a inscrição antiga
        if ($oldEnrollmentId !== (int) $validatedData['enrollment_id']) {
            $oldEnrollment = Enrollment::find($oldEnrollmentId);
            if ($oldEnrollment) {
                $this->syncEnrollmentStatus($oldEnrollment);
            }
        }

        return redirect()->route('payment.show', $payment->id)
            ->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove (exclusão lógica) o registo do pagamento e atualiza a inscrição.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $enrollment = $payment->enrollment;

        // Executa a exclusão lógica
        $payment->delete();

        // Sincroniza o estado da inscrição caso já não existam pagamentos confirmados
        if ($enrollment) {
            $this->syncEnrollmentStatus($enrollment);
        }

        return redirect()->route('payment.index')
            ->with('success', 'Registo de pagamento removido com sucesso.');
    }

    // =========================================================================
    // MÉTODOS DE APOIO / REGRAS DE NEGÓCIO INTERNAS
    // =========================================================================

    /**
     * Sincroniza e atualiza o estado da inscrição com base no histórico de pagamentos confirmados.
     *
     * Regra de Negócio:
     * - O estado inicial da candidatura é sempre 'Pendente'.
     * - Apenas transita para 'Pago' após a existência de pagamento com estado 'Confirmado'.
     * - Caso todos os pagamentos confirmados sejam anulados ou eliminados, o estado regressa a 'Pendente'.
     * - Formandos já matriculados ('Matriculado') mantêm a matrícula inalterada.
     *
     * @param \App\Models\Enrollment $enrollment
     * @return void
     */
    protected function syncEnrollmentStatus(Enrollment $enrollment)
    {
        // Se a inscrição já foi formalmente matriculada como aluno, preservamos o estado
        if ($enrollment->status === 'Matriculado') {
            return;
        }

        // Verifica se existe pelo menos um pagamento formalmente Confirmado
        $hasConfirmedPayment = $enrollment->payments()
            ->where('status', 'Confirmado')
            ->exists();

        if ($hasConfirmedPayment) {
            $enrollment->update(['status' => 'Pago']);
        } else {
            $enrollment->update(['status' => 'Pendente']);
        }
    }
}
