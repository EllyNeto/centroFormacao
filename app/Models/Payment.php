<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        "type_of_payment",
        "value",
        "reference",
        "status",
        "date",
        "currency",
    ];

    protected $cast = [
        'status'=> 'boolean',
        'value' => 'float',
    ];
}
