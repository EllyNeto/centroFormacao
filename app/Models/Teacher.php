<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    //
    use SoftDeletes;
    protected $table = 'teachers';

    protected $fillable = [
        "name",
        "email",
        "number_of_identify",
        "phone",
        "image",
    ];
}
