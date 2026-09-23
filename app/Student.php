<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    //
    use SoftDeletes;
    protected $table = 'students';

    protected $fillable = [
        "name",
        "email",
        "number_of_identify",
        "phone",
        "code",
        "image",
    ];
}
