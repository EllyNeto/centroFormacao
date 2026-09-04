<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Payment extends Model
{
    //Activar SoftDeletes
    use SoftDeletes;
    
    protected $table= 'payments';

    // O Laravel vai tratar automaticamente a coluna 'deleted_at'
    protected $dates = ['deleted_at']; 
    
    protected $fillable =[
        "type_of_payment",
        "value",
        "reference",
        "status",
        "date",
        "currency",
    ];
}
