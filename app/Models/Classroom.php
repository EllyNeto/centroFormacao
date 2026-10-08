<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $table = 'classrooms';

    protected $fillable = [
        "name",
        "number_of_classroom",
        "capacity",
        "description",
    ];

    /**
     * Obter as turmas associadas a esta sala.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'classroom_id');
    }
}
