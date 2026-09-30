<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $table = 'rooms';

    protected $fillable = [
        "name",
        "start_time",
        "end_time",
        "days_of_week",
        "shift",
        "teacher_id",
        "course_id",
        "max_capacity",
    ];

    protected $casts = [
        'days_of_week' => 'array',
    ];

    /**
     * Obter o formador responsável pela turma.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Obter o curso associado à turma.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Obter o formando associado à turma.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
