<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'enrollment_id',
        'room_id',
        'code',
        'name',
        'email',
        'number_of_identify',
        'phone',
        'image',
    ];

    /**
     * Inscrição de origem deste formando.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    /**
     * Turma frequentada pelo formando.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
