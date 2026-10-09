<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $table = 'rooms';

    protected $fillable = [
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

    protected $casts = [
        'days_of_week' => 'array',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    /**
     * Sala física onde decorre a turma.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Curso associado à turma.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Formador responsável pela turma.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Inscrições na turma.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'room_id');
    }

    /**
     * Formandos matriculados nesta turma.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'room_id');
    }

    /**
     * Total de lugares ocupados (inscrições activas).
     */
    public function occupiedSeats(): int
    {
        return $this->enrollments()
            ->whereIn('status', ['Pendente', 'Pago', 'Matriculado'])
            ->count();
    }

    /**
     * Total de lugares disponíveis.
     */
    public function availableSeats(): int
    {
        return max(0, (int) $this->max_capacity - $this->occupiedSeats());
    }

    /**
     * Verifica se a turma está lotada.
     */
    public function isFull(): bool
    {
        return $this->availableSeats() === 0;
    }
}
