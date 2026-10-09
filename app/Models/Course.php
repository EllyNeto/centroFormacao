<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'description',
        'duration',
        'status',
        'value',
    ];

    protected $casts = [
        'status' => 'boolean',
        'value'  => 'float',
    ];

    /**
     * Turmas deste curso.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'course_id');
    }

    /**
     * Inscrições efetuadas para este curso.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }
}
