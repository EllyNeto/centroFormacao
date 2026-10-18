<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo Room - Representa uma Turma pedagógica do Centro de Formação.
 *
 * Módulo 4: Turmas
 *
 * Atributos:
 * @property int $id Identificador primário da turma.
 * @property string $slug Identificador amigável único (URL slug).
 * @property string $name Nome descritivo da turma.
 * @property int $course_id Chave estrangeira do curso associado.
 * @property int $teacher_id Chave estrangeira do formador responsável.
 * @property int $classroom_id Chave estrangeira da sala física atribuída.
 * @property int $max_capacity Lotação máxima herdada da sala física.
 * @property string $shift Turno da turma (Manhã, Tarde, Pós-Laboral).
 * @property array $days_of_week Array com os dias da semana das aulas.
 * @property string $start_time Horário de início das aulas (HH:MM).
 * @property string $end_time Horário de término das aulas (HH:MM).
 * @property \Illuminate\Support\Carbon|null $start_date Data de início das aulas.
 * @property \Illuminate\Support\Carbon|null $end_date Data de término das aulas.
 * @property string $status Estado da turma ('Aberta', 'Em curso', 'Concluída', 'Cancelada').
 * @property \Illuminate\Support\Carbon|null $deleted_at Data de eliminação lógica.
 * @property \Illuminate\Support\Carbon $created_at Data de criação do registo.
 * @property \Illuminate\Support\Carbon $updated_at Data de última atualização.
 *
 * Relacionamentos:
 * @property-read \App\Models\Classroom $classroom Sala física atribuída.
 * @property-read \App\Models\Course $course Curso associado à turma.
 * @property-read \App\Models\Teacher $teacher Formador responsável.
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Enrollment[] $enrollments Inscrições efetuadas nesta turma.
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $students Formandos matriculados nesta turma.
 */
class Room extends Model
{
    // Permite a eliminação lógica mantendo o histórico de formações e certificados
    use SoftDeletes;

    /**
     * Tabela associada ao modelo no banco de dados.
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<string>
     */
    protected $fillable = [
        'slug',
        'name',
        'course_id',
        'teacher_id',
        'classroom_id',
        'max_capacity',
        'shift',
        'days_of_week',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Conversão de tipos nativos dos atributos (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'days_of_week' => 'array',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    /**
     * Regista eventos do ciclo de vida do modelo para geração automática de slug único.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (Room $room) {
            if (empty($room->slug)) {
                $room->slug = static::generateUniqueSlug($room->name);
            }
        });

        static::updating(function (Room $room) {
            if ($room->isDirty('name') || empty($room->slug)) {
                $room->slug = static::generateUniqueSlug($room->name, $room->id);
            }
        });
    }

    /**
     * Gera um slug único para a turma (incluindo registos em Soft Delete).
     *
     * @param  string    $name
     * @param  int|null  $ignoreId
     * @return string
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'turma';
        $slug = $baseSlug;
        $counter = 1;

        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Relacionamento N:1 com a Sala de Aula física (Classroom).
     * A turma decorre presencialmente numa sala de aula específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Relacionamento N:1 com o Curso (Course).
     * Cada turma leciona o conteúdo programático de um curso específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relacionamento N:1 com o Formador (Teacher).
     * Formador responsável por ministrar as aulas da turma.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Relacionamento 1:N com as Inscrições/Candidaturas (Enrollment).
     * Retorna todas as candidaturas que escolheram esta turma.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'room_id');
    }

    /**
     * Relacionamento 1:N com os Formandos matriculados (Student).
     * Retorna os formandos que frequentam oficialmente esta turma.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'room_id');
    }

    /**
     * Calcula o total de vagas ocupadas na turma.
     * Considera inscrições ativas com estado 'Pendente', 'Pago' ou 'Matriculado'.
     *
     * @return int Quantidade de lugares ocupados.
     */
    public function occupiedSeats(): int
    {
        return $this->enrollments()
            ->whereIn('status', ['Pendente', 'Pago', 'Matriculado'])
            ->count();
    }

    /**
     * Calcula a quantidade de vagas ainda disponíveis na turma.
     *
     * @return int Vagas livres (mínimo zero).
     */
    public function availableSeats(): int
    {
        return max(0, (int) $this->max_capacity - $this->occupiedSeats());
    }

    /**
     * Verifica se a turma atingiu a sua lotação máxima.
     *
     * @return bool True se não existirem vagas disponíveis, False caso contrário.
     */
    public function isFull(): bool
    {
        return $this->availableSeats() === 0;
    }

    /**
     * Calcula a percentagem de ocupação da turma para barras de progresso visuais.
     *
     * @return float Valor percentual entre 0 e 100.
     */
    public function occupancyPercentage(): float
    {
        if ($this->max_capacity <= 0) {
            return 0.0;
        }

        $percentage = ($this->occupiedSeats() / $this->max_capacity) * 100;
        return round(min(100, $percentage), 1);
    }

    /**
     * Verifica se existem formandos matriculados associados a esta turma.
     * Usado para proteger a integridade de dados impedindo a eliminação indevida.
     *
     * @return bool
     */
    public function hasActiveStudents(): bool
    {
        return $this->students()->exists();
    }

    /**
     * Verifica se existem inscrições ativas associadas a esta turma.
     *
     * @return bool
     */
    public function hasActiveEnrollments(): bool
    {
        return $this->enrollments()
            ->whereIn('status', ['Pendente', 'Pago', 'Matriculado'])
            ->exists();
    }

    /**
     * Aloca automaticamente candidatos da Lista de Espera (ou sem turma atribuída)
     * pertencentes ao mesmo curso para esta turma, respeitando a lotação máxima (FIFO)
     * e dando prioridade aos candidatos que escolheram o mesmo turno.
     *
     * @return int Quantidade de candidatos alocados automaticamente nesta turma.
     */
    public function allocateWaitingEnrollments(): int
    {
        if ($this->status !== 'Aberta') {
            return 0;
        }

        $available = $this->availableSeats();
        if ($available <= 0) {
            return 0;
        }

        $waitingEnrollments = Enrollment::with('course')
            ->where('course_id', $this->course_id)
            ->whereNull('room_id')
            ->whereIn('status', ['Lista de Espera', 'Pendente', 'Pago'])
            ->orderByRaw('CASE WHEN shift = ? THEN 0 ELSE 1 END', [$this->shift])
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->limit($available)
            ->get();

        $allocatedCount = 0;

        foreach ($waitingEnrollments as $enrollment) {
            $coursePrice = $enrollment->course ? (float) $enrollment->course->value : 0.0;
            $isPaidFull  = $coursePrice > 0 && $enrollment->totalPaid() >= $coursePrice;

            $newStatus = $enrollment->status;
            if ($enrollment->status === 'Lista de Espera') {
                $newStatus = $isPaidFull ? 'Pago' : 'Pendente';
            }

            $enrollment->update([
                'room_id' => $this->id,
                'shift'   => $this->shift ?: $enrollment->shift,
                'status'  => $newStatus,
            ]);

            $allocatedCount++;
        }

        return $allocatedCount;
    }
}


