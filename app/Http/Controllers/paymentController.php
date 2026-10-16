<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador responsável pela gestão de Pagamentos no Centro de Formação.
 *
 * Módulo 6: Pagamentos
 * Controla a listagem, registo, alteração, detalhes e anulação de pagamentos,
 * bem como a transição automática de estados da Inscrição (Pendente → Pago).
 *
 * Regras de Negócio Implementadas:
 * R5  - Pagamento integral único: o valor é exatamente o preço do curso (não editável pelo utilizador).
 * R6  - Pagamentos Pendente ou Confirmado bloqueiam novos pagamentos na mesma inscrição.
 * R7  - Inscrição passa a 'Pago' só quando os Confirmados cobrem o preço total.
 * R8  - Pagamento Confirmado só pode ser Anulado (com motivo obrigatório); para corrigir regista-se um novo.
 * R3  - Repetição: incrementa ciclo, guarda turma anterior, limpa room_id.
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
        $payments = Payment::with(['enrollment.course'])
            ->orderBy('id', 'desc')
            ->get();

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
     * Lista apenas inscrições que podem receber pagamento (canReceivePayment()).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        // Se vier enrollment_id via URL, valida que pode receber pagamento
        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedEnrollment   = null;

        if ($selectedEnrollmentId) {
            $selectedEnrollment = Enrollment::with(['course', 'room'])->find($selectedEnrollmentId);

            if ($selectedEnrollment && !$selectedEnrollment->canReceivePayment()) {
                return redirect()
                    ->route('enrollment.show', $selectedEnrollmentId)
                    ->with('error', 'Esta inscrição não tem saldo em dívida ou não está em condições de receber um novo pagamento.');
            }
        }

        // Lista apenas inscrições que podem receber pagamento
        $allEnrollments = Enrollment::with(['course', 'room'])->get();
        $enrollments = $allEnrollments->filter(fn($e) => $e->canReceivePayment())->values();

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
     * Regras aplicadas:
     * - R5: o valor é fixado ao preço do curso (ignora valor do formulário)
     * - R6: bloqueia se já existe Pendente ou Confirmado no ciclo atual
     * - R3: se canRepeat(), incrementa ciclo e guarda turma anterior
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'enrollment_id'   => 'required|exists:enrollments,id',
            'type_of_payment' => 'required|string|in:Transferência,Multicaixa,Numerário',
            'currency'        => 'nullable|string|size:3',
            'reference'       => 'required|string|max:100|unique:payments,reference',
            'status'          => 'required|string|in:Pendente,Confirmado',
            'date'            => 'required|date',
        ], [
            'enrollment_id.required'   => 'É obrigatório selecionar o candidato / inscrição.',
            'enrollment_id.exists'     => 'A inscrição selecionada não existe no sistema.',
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.in'       => 'O tipo de pagamento deve ser Transferência, Multicaixa ou Numerário.',
            'currency.size'            => 'A divisa monetária deve ter exatamente 3 caracteres (Ex: AOA).',
            'reference.required'       => 'A referência ou número do recibo é obrigatória.',
            'reference.unique'         => 'Esta referência ou número de recibo já se encontra registada.',
            'reference.max'            => 'A referência não pode exceder 100 caracteres.',
            'status.required'          => 'O estado do pagamento é obrigatório.',
            'status.in'                => 'O estado deve ser Pendente ou Confirmado.',
            'date.required'            => 'A data da transação é obrigatória.',
            'date.date'                => 'A data informada não possui um formato válido.',
        ]);

        // C2: try/catch para capturar violações de regras de negócio (R6, R3)
        // e devolver mensagem amigável em vez de página de erro 500.
        try {
            $payment = DB::transaction(function () use ($validatedData) {
                // Bloqueia a inscrição contra acessos simultâneos
                $enrollment = Enrollment::with(['course', 'room'])
                    ->lockForUpdate()
                    ->findOrFail($validatedData['enrollment_id']);

                // R6/R3: verifica se pode receber pagamento
                if (!$enrollment->canReceivePayment()) {
                    throw new \Exception('Esta inscrição já tem um pagamento registado ou não está em condições de receber um novo pagamento.');
                }

                $isRepetition = $enrollment->canRepeat();
                $currentCycle = $enrollment->cycle ?? 1;

                // R3: Repetição do curso — incrementa ciclo, guarda turma anterior, limpa turma atual
                if ($isRepetition) {
                    $previousRoomId = $enrollment->room_id;
                    $currentCycle   = $currentCycle + 1;

                    $enrollment->update([
                        'cycle'   => $currentCycle,
                        'room_id' => null,
                        'status'  => 'Pendente',
                    ]);
                } else {
                    $previousRoomId = null;
                }

                // R5: o valor é fixado ao preço atual do curso (ignora o que vier do form)
                $coursePrice = $enrollment->course ? (float) $enrollment->course->value : 0.0;

                $payment = Payment::create([
                    'enrollment_id'    => $enrollment->id,
                    'cycle'            => $currentCycle,
                    'previous_room_id' => $previousRoomId,
                    'type_of_payment'  => $validatedData['type_of_payment'],
                    'value'            => $coursePrice,
                    'currency'         => $validatedData['currency'] ?? 'AOA',
                    'reference'        => $validatedData['reference'],
                    'status'           => $validatedData['status'],
                    'date'             => Carbon::parse($validatedData['date']),
                ]);

                // Atualiza o estado da inscrição com base no pagamento
                $enrollment->refresh();
                $enrollment->syncPaymentStatus();

                return $payment;
            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('payment.show', $payment->id)
            ->with('success', 'Pagamento registado com sucesso!');
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
            'enrollment.room.teacher',
            'previousRoom.classroom',
            'previousRoom.teacher',
        ])->findOrFail($id);

        return view('admin.payment.details.index', compact('payment'));
    }

    /**
     * Apresenta o formulário de edição de um pagamento existente.
     * Pagamentos Confirmados só podem ser anulados.
     * Pagamentos Anulados são imutáveis.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $payment = Payment::with('enrollment.course')->findOrFail($id);

        // Pagamento Anulado é imutável
        if ($payment->isCanceled()) {
            return redirect()
                ->route('payment.show', $payment->id)
                ->with('error', 'Este pagamento já foi anulado e não pode ser alterado.');
        }

        return view('admin.payment.edit.index', compact('payment'));
    }

    /**
     * Atualiza as informações do pagamento e reavalia o estado da inscrição.
     *
     * Regra R8:
     * - Confirmado → só aceita mudança de estado para Anulado + cancellation_reason obrigatório.
     * - Pendente   → permite editar forma, referência e data (valor e inscrição bloqueados).
     * - Anulado    → imutável; redireciona com erro.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // Anulado é imutável
        if ($payment->isCanceled()) {
            return redirect()
                ->route('payment.show', $payment->id)
                ->with('error', 'Este pagamento já foi anulado e não pode ser alterado.');
        }

        // Pagamento Confirmado: só permite anular
        if ($payment->isConfirmed()) {
            $validatedData = $request->validate([
                'cancellation_reason' => 'required|string|max:500',
            ], [
                'cancellation_reason.required' => 'O motivo da anulação é obrigatório.',
                'cancellation_reason.max'      => 'O motivo não pode exceder 500 caracteres.',
            ]);

            $payment->update([
                'status'               => 'Anulado',
                'cancellation_reason'  => $validatedData['cancellation_reason'],
                'canceled_at'          => now(),
            ]);

            // Reavalia o estado da inscrição
            $enrollment = $payment->enrollment;
            if ($enrollment) {
                $enrollment->refresh();
                $enrollment->syncPaymentStatus();
            }

            return redirect()
                ->route('payment.show', $payment->id)
                ->with('success', 'Pagamento anulado com sucesso. A inscrição foi revertida para Pendente (se aplicável).');
        }

        // Pagamento Pendente: permite editar forma, referência e data
        $validatedData = $request->validate([
            'type_of_payment'     => 'required|string|in:Transferência,Multicaixa,Numerário',
            'reference'           => 'required|string|max:100|unique:payments,reference,' . $id,
            'status'              => 'required|string|in:Pendente,Confirmado,Anulado',
            'date'                => 'required|date',
            'cancellation_reason' => 'nullable|string|max:500',
        ], [
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.in'       => 'O tipo de pagamento deve ser Transferência, Multicaixa ou Numerário.',
            'reference.required'       => 'A referência ou número do recibo é obrigatória.',
            'reference.unique'         => 'Esta referência já se encontra registada.',
            'reference.max'            => 'A referência não pode exceder 100 caracteres.',
            'status.required'          => 'O estado do pagamento é obrigatório.',
            'status.in'                => 'Estado inválido.',
            'date.required'            => 'A data da transação é obrigatória.',
            'date.date'                => 'A data informada não possui um formato válido.',
        ]);

        // Se estiver a anular, cancellation_reason é obrigatório
        if ($validatedData['status'] === 'Anulado') {
            $request->validate([
                'cancellation_reason' => 'required|string|max:500',
            ], [
                'cancellation_reason.required' => 'O motivo da anulação é obrigatório ao anular um pagamento.',
            ]);
            $validatedData['cancellation_reason'] = $request->input('cancellation_reason');
            $validatedData['canceled_at']         = now();
        }

        $validatedData['date'] = Carbon::parse($validatedData['date']);

        $payment->update($validatedData);

        // Reavalia o estado da inscrição
        $enrollment = $payment->enrollment;
        if ($enrollment) {
            $enrollment->refresh();
            $enrollment->syncPaymentStatus();
        }

        return redirect()
            ->route('payment.show', $payment->id)
            ->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove (exclusão lógica) o registo do pagamento.
     * Bloqueado para pagamentos Confirmados e Pendentes (é preciso anular primeiro).
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        // Confirmado e Pendente não podem ser eliminados diretamente
        if ($payment->isConfirmed() || $payment->isPending()) {
            return back()->with('error', 'Não é possível eliminar um pagamento Confirmado ou Pendente. Anule-o primeiro.');
        }

        $enrollment = $payment->enrollment;
        $payment->delete();

        if ($enrollment) {
            $enrollment->refresh();
            $enrollment->syncPaymentStatus();
        }

        return redirect()
            ->route('payment.index')
            ->with('success', 'Registo de pagamento eliminado com sucesso.');
    }
}
