<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'enrollment_id',
        'type_of_payment',
        'value',
        'currency',
        'reference',
        'status',
        'date',
    ];

    protected $casts = [
        'value' => 'float',
        'date'  => 'datetime',
    ];

    /**
     * Inscrição associada a este pagamento.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
}
