<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    //
    use SoftDeletes;

    protected $table = 'enrollments';

    protected $fillable = [
        "date",
        "status",
    ];

    protected $cast = [
        'status' => 'boolean',
    ];
}
