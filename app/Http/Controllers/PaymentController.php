<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Enrollment;
use App\Models\Payment;
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
class PaymentController extends Controller
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
     * @param \App\Http\Requests\PaymentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PaymentRequest $request)
    {
        $validatedData = $request->validated();

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

                // R3: Repetição do curso — incrementa ciclo e guarda turma anterior
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

        // Envio de notificação nativa por email com o recibo do pagamento
        try {
            if ($payment && $payment->enrollment && !empty($payment->enrollment->email)) {
                $payment->enrollment->notify(new \App\Notifications\PaymentReceiptNotification($payment));
            }
        } catch (\Throwable $e) {
            // Regista no log sem interromper a resposta em caso de indisponibilidade de email
            \Illuminate\Support\Facades\Log::warning("Falha ao enviar recibo por email: " . $e->getMessage());
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
     * @param \App\Http\Requests\PaymentRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PaymentRequest $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // Anulado é imutável
        if ($payment->isCanceled()) {
            return redirect()
                ->route('payment.show', $payment->id)
                ->with('error', 'Este pagamento já foi anulado e não pode ser alterado.');
        }

        $validatedData = $request->validated();

        // Pagamento Confirmado: só permite anular
        if ($payment->isConfirmed()) {
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

            // Envio de notificação nativa por email informando a anulação do pagamento
            try {
                if ($enrollment && !empty($enrollment->email)) {
                    $enrollment->notify(new \App\Notifications\PaymentCanceledNotification($payment));
                }
            } catch (\Throwable $e) {
                // Registo de falha silenciosa para não quebrar a transação
                \Illuminate\Support\Facades\Log::warning("Falha ao enviar notificação de cancelamento de pagamento: " . $e->getMessage());
            }

            return redirect()
                ->route('payment.show', $payment->id)
                ->with('success', 'Pagamento anulado com sucesso. A inscrição foi revertida para Pendente (se aplicável).');
        }

        // Pagamento Pendente: permite editar forma, referência e data
        // (O valor e inscrição são bloqueados pelo PaymentRequest - não podem ser alterados)
        $payment->update($validatedData);

        // Reavalia o estado da inscrição
        $enrollment = $payment->enrollment;
        if ($enrollment) {
            $enrollment->refresh();
            $enrollment->syncPaymentStatus();

            // Envio de notificação nativa por email conforme a alteração de estado
            try {
                if (!empty($enrollment->email)) {
                    if ($payment->status === 'Anulado') {
                        $enrollment->notify(new \App\Notifications\PaymentCanceledNotification($payment));
                    } elseif ($payment->status === 'Confirmado') {
                        $enrollment->notify(new \App\Notifications\PaymentReceiptNotification($payment));
                    }
                }
            } catch (\Throwable $e) {
                // Registo de falha silenciosa para não impedir a resposta HTTP
                \Illuminate\Support\Facades\Log::warning("Falha ao enviar notificação de atualização de pagamento: " . $e->getMessage());
            }
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
