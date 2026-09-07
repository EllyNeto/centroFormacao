<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Enrollment extends Model
{
    use SoftDeletes;
    protected $table = 'enrollments';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "student_id",
        "_class_id",
        "date",
        "status",
    ];
}
