<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa uma turma (room) da instituição de ensino.
 *
 * Uma turma tem nome, horário de início e término, dias da semana, turno,
 * capacidade máxima e ligações a um docente, um curso e uma sala de aula.
 *
 * @property int $id Identificador primário da turma
 * @property string $name Nome da turma
 * @property string $start_time Hora de início da turma (H:i)
 * @property string $end_time Hora de término da turma (H:i)
 * @property array $days_of_week Dias da semana em que a turma ocorre (guardado como JSON)
 * @property string $shift Turno da turma (Manhã, Tarde ou Pós-Laboral)
 * @property int $max_capacity Capacidade máxima de alunos da turma
 * @property int $teacher_id Chave estrangeira para o docente responsável
 * @property int $course_id Chave estrangeira para o curso da turma
 * @property int|null $classroom_id Chave estrangeira para a sala de aula (opcional)
 * @property \Carbon\CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property \Carbon\CarbonInterface|null $created_at Data de criação do registo
 * @property \Carbon\CarbonInterface|null $updated_at Data da última atualização
 */
class Room extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at).
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    #[Fillable(['name', 'start_time', 'end_time', 'days_of_week', 'shift', 'max_capacity', 'teacher_id', 'course_id', 'classroom_id'])]
    protected array $fillable = ['name', 'start_time', 'end_time', 'days_of_week', 'shift', 'max_capacity', 'teacher_id', 'course_id', 'classroom_id'];

    /**
     * Define a conversão de tipos (casts) dos atributos do modelo.
     *
     * O campo days_of_week é guardado como JSON na base de dados e devolvido
     * como array PHP pelo Eloquent.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'days_of_week' => 'array',
        ];
    }

    /**
     * Relacionamento "muitos para um": uma turma pertence a um docente (teacher).
     *
     * A coluna teacher_id na tabela rooms referencia teachers.id.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relacionamento "muitos para um": uma turma pertence a um curso (course).
     *
     * A coluna course_id na tabela rooms referencia courses.id.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relacionamento "muitos para um": uma turma pertence a uma sala de aula (classroom).
     *
     * A coluna classroom_id na tabela rooms referencia classrooms.id e pode ser nula.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
