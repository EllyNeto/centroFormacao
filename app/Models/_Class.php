<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent representando a entidade Turma (Class/_Class).
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
        'name',         // Nome da turma
        'days_of_week', // Dias da semana
        'code',         // Código de referência da turma
        'shift',        // Turno (Manhã, Tarde, Pós-Laboral)
        'capacity',     // Capacidade máxima de estudantes
        'status',       // Estado da turma (1 = Ativa, 0 = Inativa)
        'teacher_id',   // Formador responsável
        'course_id',    // Curso associado
        'student_id',   // Aluno associado à turma
        'falta',        // Número de faltas
        'start_time',
        'end_time',
    ];

    /**
     * Conversão de tipos de dados nativos.
     *
     * @var array
     */
    protected $casts = [
        'status'       => 'boolean',
        'capacity'     => 'integer',
        'falta'        => 'integer',
        'days_of_week' => 'array'
    ];

    /**
     * Relação de pertença com o estudante associado à turma (opcional).
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relação de pertença com o curso associado à turma.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relação de pertença com o formador responsável pela turma.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
