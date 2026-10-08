<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $table = 'enrollments';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'number_of_identify',
        'image',
        'shift',
        'status',
        'date',
        'course_id',
    ];

    /**
     * Relação com o Curso pretendido.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relação com os Pagamentos efetuados.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id');
    }

    /**
     * Relação com o registo de Estudante oficial (após efetivação).
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'enrollment_id');
    }
}
