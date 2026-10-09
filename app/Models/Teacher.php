<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'specialization',
        'number_of_identify',
        'phone',
        'image',
    ];

    /**
     * Turmas leccionadas por este formador.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'teacher_id');
    }
}
