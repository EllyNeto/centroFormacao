<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Eloquent representativo da entidade Inscrição / Candidatura (Enrollment).
 *
 * Módulo 5: Inscrições
 * Modela o processo de candidatura de um formando a um curso e turma.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $number_of_identify
 * @property string|null $image
 * @property int $course_id
 * @property int|null $room_id
 * @property string $status
 * @property int $cycle               Ciclo de frequência atual (1 = 1.ª frequência, 2 = 1.ª repetição …)
 * @property \Carbon\Carbon|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Enrollment extends Model
{
    use SoftDeletes;
    use Notifiable; // Permite envio nativo de notificações por email diretamente ao modelo

    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'enrollments';

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'number_of_identify',
        'image',
        'course_id',
        'room_id',
        'shift',
        'status',
        'cycle',
        'date',
    ];

    /**
     * Conversão de tipos de atributos (Type Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date'  => 'datetime',
        'cycle' => 'integer',
    ];

    /**
     * Boot do modelo: gera automaticamente o código único de inscrição
     * no momento da criação (evento 'creating').
     *
     * Formato: INS-ANO-NNNN  (Ex: INS-2026-0001)
     * O número sequencial é baseado no último ID existente + 1,
     * garantindo unicidade mesmo com soft deletes.
     * CORREÇÃO: Adicionado bloqueio transacional para evitar condições de corrida na geração de códigos.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (empty($enrollment->code)) {
                $year   = date('Y');
                // CORREÇÃO: Usar transação com bloqueio para evitar condições de corrida
                // Quando dois processos tentam criar inscrições simultaneamente,
                // o lockForUpdate() garante que apenas um possa ler o máximo ID de cada vez
                DB::transaction(function () use ($enrollment, $year) {
                    $nextId = (int) self::withTrashed()->lockForUpdate()->max('id') + 1;
                    $enrollment->code = 'INS-' . $year . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                });
            }
        });
    }

    // =========================================================================
    // RELACIONAMENTOS ELOQUENT
    // =========================================================================

    /**
     * Curso selecionado pelo candidato para a formação.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Turma específica à qual o candidato está alocado (pode ser null se em lista de espera).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Pagamentos associados a esta inscrição.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id');
    }

    /**
     * Registo oficial do formando gerado após confirmação de matrícula.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'enrollment_id');
    }

    // =========================================================================
    // MÉTODOS AUXILIARES E REGRAS DE NEGÓCIO
    // =========================================================================

    /**
     * Retorna o código oficial da candidatura.
     * Usa o campo 'code' gerado automaticamente (Ex: INS-2026-0001).
     * Fallback para o formato baseado no ID caso o código ainda não exista
     * (registos anteriores à migração).
     *
     * @return string
     */
    public function formattedCode(): string
    {
        return $this->code ?? ('INS-' . str_pad($this->id, 4, '0', STR_PAD_LEFT));
    }

    /**
     * Verifica se a candidatura está no estado Pendente.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'Pendente';
    }

    /**
     * Verifica se a candidatura já teve o pagamento registado.
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->status === 'Pago';
    }

    /**
     * Verifica se o candidato já foi formalmente matriculado como aluno.
     *
     * @return bool
     */
    public function isEnrolled(): bool
    {
        return $this->status === 'Matriculado';
    }

    /**
     * Verifica se o candidato está na Lista de Espera por falta de vagas.
     *
     * @return bool
     */
    public function isWaitlist(): bool
    {
        return $this->status === 'Lista de Espera';
    }

    /**
     * Verifica se a candidatura foi cancelada.
     *
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->status === 'Cancelado';
    }

    /**
     * Verifica se a turma associada a esta inscrição está Concluída.
     * Condição necessária para o aluno poder repetir o curso.
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->room && $this->room->status === 'Concluída';
    }

    /**
     * Verifica se o aluno pode repetir o curso.
     * Condição: inscrição não cancelada E turma concluída E propina paga/matriculado.
     *
     * @return bool
     */
    public function canRepeat(): bool
    {
        return !$this->isCanceled()
            && $this->isFinished()
            && ($this->isPaid() || $this->isEnrolled());
    }

    /**
     * Retorna os pagamentos do ciclo atual da inscrição.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentCyclePayments()
    {
        return $this->payments()->where('cycle', $this->cycle ?? 1);
    }

    /**
     * Calcula o montante já comprometido (Confirmado + Pendente) no ciclo atual.
     * Utilizado para bloquear novos pagamentos enquanto houver um Pendente.
     *
     * @param int|null $ignorePaymentId Exclui um pagamento específico do cálculo (usado no update)
     * @return float
     */
    public function committedAmount(?int $ignorePaymentId = null): float
    {
        $query = $this->payments()
            ->where('cycle', $this->cycle ?? 1)
            ->whereIn('status', ['Confirmado', 'Pendente']);

        if ($ignorePaymentId) {
            $query->where('id', '!=', $ignorePaymentId);
        }

        return (float) $query->sum('value');
    }

    /**
     * Calcula o montante total Confirmado no ciclo atual (para verificar se está pago).
     *
     * @return float
     */
    public function totalPaid(): float
    {
        return (float) $this->currentCyclePayments()
            ->where('status', 'Confirmado')
            ->sum('value');
    }

    /**
     * Calcula o saldo em dívida face ao valor total da propina do curso no ciclo atual.
     *
     * @param int|null $ignorePaymentId Exclui um pagamento do cálculo (usado no update)
     * @return float
     */
    public function remainingBalance(?int $ignorePaymentId = null): float
    {
        $coursePrice = $this->course ? (float) $this->course->value : 0.0;
        return max(0.0, $coursePrice - $this->committedAmount($ignorePaymentId));
    }

    /**
     * Calcula o crédito excedente quando o curso mudou para um valor inferior ao pago.
     *
     * @return float
     */
    public function creditBalance(): float
    {
        $coursePrice = $this->course ? (float) $this->course->value : 0.0;
        $committed   = $this->committedAmount();
        return max(0.0, $committed - $coursePrice);
    }

    /**
     * Verifica se esta inscrição pode receber um novo pagamento.
     * Regra R6: bloqueia se já existe pagamento Pendente ou Confirmado no ciclo atual.
     * Regra R3: permite se canRepeat() (repetição de curso).
     *
     * @return bool
     */
    public function canReceivePayment(): bool
    {
        if ($this->isCanceled()) {
            return false;
        }

        // Pode repetir o curso (turma concluída e paga)
        if ($this->canRepeat()) {
            return true;
        }

        // Tem saldo em dívida (sem pagamento Pendente/Confirmado a cobrir tudo)
        return $this->remainingBalance() > 0;
    }

    /**
     * Sincroniza o estado da inscrição com base nos pagamentos Confirmados do ciclo atual.
     *
     * Regra R7: só passa a 'Pago' quando os pagamentos Confirmados cobrem o preço total.
     * Inscrições 'Matriculado' não são alteradas.
     *
     * @return void
     */
    public function syncPaymentStatus(): void
    {
        // Estado Matriculado é imutável por este método
        if ($this->isEnrolled()) {
            return;
        }

        $coursePrice = $this->course ? (float) $this->course->value : 0.0;
        $confirmed   = $this->totalPaid();

        if ($coursePrice > 0 && $confirmed >= $coursePrice) {
            $this->update(['status' => 'Pago']);
        } else {
            $this->update(['status' => 'Pendente']);
        }
    }

    /**
     * Scope: inscrições ativas da mesma pessoa no mesmo curso.
     * Usado para impedir inscrições duplicadas (R1).
     * Inclui inscrições finalizadas porque a repetição usa a mesma inscrição (R3).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $bi                Número do BI do candidato
     * @param int $courseId             ID do curso
     * @param int|null $ignoreId        ID da inscrição a ignorar (edição)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveForSameCourse($query, string $bi, int $courseId, ?int $ignoreId = null)
    {
        return $query
            ->where('number_of_identify', $bi)
            ->where('course_id', $courseId)
            ->whereIn('status', ['Pendente', 'Pago', 'Matriculado', 'Lista de Espera'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId));
    }

    /**
     * Retorna a classe CSS de badge apropriada para o estado atual.
     *
     * @return string
     */
    public function statusBadgeClass(): string
    {
        $badges = [
            'Pago'            => 'badge-primary',
            'Matriculado'     => 'badge-success',
            'Lista de Espera' => 'badge-warning',
            'Cancelado'       => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }
}