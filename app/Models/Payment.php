<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent representativo da entidade Pagamento (Payment).
 *
 * Módulo 6: Pagamentos
 * Regista e gere as liquidações financeiras de propinas efetuadas pelos formandos
 * associadas às respetivas inscrições no Centro de Formação.
 *
 * @property int $id
 * @property int $enrollment_id
 * @property int $cycle                         Ciclo de frequência a que pertence este pagamento
 * @property int|null $previous_room_id         Turma anterior (em caso de repetição)
 * @property string $type_of_payment
 * @property float $value
 * @property string $currency
 * @property string $reference
 * @property string $status
 * @property string|null $cancellation_reason   Motivo da anulação (obrigatório ao anular Confirmado)
 * @property \Carbon\Carbon|null $canceled_at   Data/hora da anulação
 * @property \Carbon\Carbon|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Enrollment $enrollment
 * @property-read \App\Models\Room|null $previousRoom
 */
class Payment extends Model
{
    use SoftDeletes;

    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enrollment_id',
        'cycle',
        'previous_room_id',
        'type_of_payment',
        'value',
        'currency',
        'reference',
        'status',
        'cancellation_reason',
        'canceled_at',
        'date',
    ];

    /**
     * Conversão e tipificação de atributos (Type Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value'        => 'float',
        'cycle'        => 'integer',
        'date'         => 'datetime',
        'canceled_at'  => 'datetime',
    ];

    // =========================================================================
    // RELACIONAMENTOS ELOQUENT
    // =========================================================================

    /**
     * Inscrição associada a este pagamento.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    /**
     * Turma anterior (antes da repetição do curso).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function previousRoom()
    {
        return $this->belongsTo(Room::class, 'previous_room_id');
    }

    // =========================================================================
    // MÉTODOS AUXILIARES E REGRAS DE NEGÓCIO
    // =========================================================================

    /**
     * Verifica se este pagamento ainda pode ser editado (apenas Pendente).
     * Pagamentos Confirmados só podem ser Anulados.
     * Pagamentos Anulados são imutáveis.
     *
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->status === 'Pendente';
    }

    /**
     * Retorna o valor do pagamento devidamente formatado com o símbolo da moeda.
     *
     * @return string
     */
    public function formattedValue(): string
    {
        return number_format($this->value, 2, ',', '.') . ' ' . ($this->currency ?: 'AOA');
    }

    /**
     * Retorna a data e hora do pagamento formatada no padrão português.
     *
     * @return string
     */
    public function formattedDate(): string
    {
        return $this->date ? $this->date->format('d/m/Y H:i') : '-';
    }

    /**
     * Verifica se o pagamento se encontra no estado Confirmado.
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'Confirmado';
    }

    /**
     * Verifica se o pagamento se encontra no estado Pendente.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'Pendente';
    }

    /**
     * Verifica se o pagamento foi Anulado.
     *
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->status === 'Anulado';
    }

    /**
     * Retorna a classe CSS de badge apropriada para o estado do pagamento.
     *
     * @return string
     */
    public function statusBadgeClass(): string
    {
        $badges = [
            'Confirmado' => 'badge-success',
            'Pendente'   => 'badge-warning',
            'Anulado'    => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    /**
     * Retorna o ícone FontAwesome correspondente ao tipo de pagamento.
     *
     * @return string
     */
    public function typeIcon(): string
    {
        $icons = [
            'Transferência' => 'fa fa-university',
            'Multicaixa'    => 'fa fa-credit-card',
            'Numerário'     => 'fa fa-money',
        ];

        return $icons[$this->type_of_payment] ?? 'fa fa-money-bill';
    }

    /**
     * Gera uma sugestão sequencial e única de referência/recibo de pagamento.
     *
     * @return string
     */
    public static function generateSuggestedReference(): string
    {
        $nextId = (int) self::withTrashed()->max('id') + 1;
        return 'REC-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
