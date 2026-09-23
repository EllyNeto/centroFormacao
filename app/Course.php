<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    //
    use SoftDeletes;
    protected $table = 'courses';

    protected $fillable = [
        "name",
        "description",
        "duration",
        "status",
    ];

    protected $cast = [
        'status'=> 'boolean',
    ];
}
