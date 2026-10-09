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
        'course_id',
        'room_id',
        'shift',
        'status',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Curso pretendido.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Turma pretendida.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Pagamentos efetuados relativos a esta inscrição.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id');
    }

    /**
     * Registo oficial do estudante formado após efectivação.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'enrollment_id');
    }
}
