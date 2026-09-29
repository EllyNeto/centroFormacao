<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable; 

class Teacher extends Model
{
    //
    use SoftDeletes;
    use Notifiable;
    
    protected $table = 'teachers';

    protected $fillable = [
        "name",
        "specialization",
        "email",
        "number_of_identify",
        "phone",
        "image",
        "gender",
    ];
}
