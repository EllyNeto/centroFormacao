<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent representando a entidade Turma (Class/_Class).
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $course_name
 * @property string|null $teacher_name
 * @property string|null $room
 * @property string|null $shift
 * @property int|null $capacity
 * @property boolean $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class _Class extends Model
{
    use SoftDeletes;

    /**
     * Nome da tabela associada na base de dados.
     *
     * @var string
     */
    protected $table = 'classes';

    /**
     * Colunas de data tratadas pelo Eloquent para SoftDeletes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',        // Nome da turma
        'days_of_week', // Dias da semana
        'code',        // Código de referência da turma
        'shift',       // Turno (Manhã, Tarde, Pós-Laboral)
        'capacity',    // Capacidade máxima de estudantes
        'status',      // Estado da turma (1 = Ativa, 0 = Inativa)
        'teacher_id',  // Dados da entidade formador
        'course_id',  // Dados da entidade cursos
        'start_time',
        'end_time',
    ];

    /**
     * Conversão de tipos de dados nativos.
     *
     * @var array
     */
    protected $casts = [
        'status'   => 'boolean',
        'capacity' => 'integer',
        'days_of_week' => 'array'
    ];

    public function students()
    {
        return $this->hasMany('App\Http\Models\Student');
    }

    public function course()
    {
        return $this->belongsTo('App\Http\Models\Course');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Http\Models\Teacher');
    }
}
