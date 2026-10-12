<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property string $shift
 * @property string $status
 * @property \Carbon\Carbon|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Enrollment extends Model
{
    use SoftDeletes;

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
        'name',
        'email',
        'phone',
        'number_of_identify',
        'image',
        'course_id',
        'room_id',
        'shift',
        'status',
        'date',
    ];

    /**
     * Conversão de tipos de atributos (Type Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

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
     * Retorna o código oficial formatado da candidatura (Ex: INS-0001).
     *
     * @return string
     */
    public function formattedCode(): string
    {
        return 'INS-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
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
     * Calcula o montante total pago e confirmado para esta inscrição.
     *
     * @return float
     */
    public function totalPaid(): float
    {
        return (float) $this->payments()
            ->where('status', 'Confirmado')
            ->sum('value');
    }

    /**
     * Calcula o saldo em dívida face ao valor total da propina do curso.
     *
     * @return float
     */
    public function remainingBalance(): float
    {
        $coursePrice = $this->course ? (float) $this->course->value : 0.0;
        return max(0.0, $coursePrice - $this->totalPaid());
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
