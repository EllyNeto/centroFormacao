<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent representando a entidade Inscrição (Enrollment).
 * Regista a associação entre estudantes e cursos.
 */
class Enrollment extends Model
{
    use SoftDeletes;
    
    // Nome da tabela associada no banco de dados
    protected $table = 'enrollments';

    // Coluna para exclusão lógica (Soft Delete)
    protected $dates = ['deleted_at'];

    // Atributos preenchíveis em massa
    protected $fillable = [
        "student_id", // ID do estudante inscrito
        "course_id",  // ID do curso associado
        "date",       // Data e hora do registo da inscrição
        "status",     // Estado da inscrição (1 = Confirmada/Ativa, 0 = Pendente/Inativa)
    ];

    // Conversão de tipos de dados
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relação de pertença com o modelo Student (Estudante).
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relação de pertença com o modelo Course (Curso).
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
