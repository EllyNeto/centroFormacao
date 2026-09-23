<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    //
    use SoftDeletes;

    protected $table = 'rooms';

    protected $fillable = [
        "name",
        "start_time",
        "end_time",
        "days_of_week",
        "shift",
    ];
}
