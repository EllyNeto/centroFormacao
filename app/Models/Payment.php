<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent que representa a entidade de Pagamento (Payment).
 */
class Payment extends Model
{
    use SoftDeletes;
    
    protected $table = 'payments';

    protected $fillable = [
        "type_of_payment", // Tipo/Categoria do pagamento (Emolumento)
        "value",           // Valor monetário cobrado/pago
        "reference",       // Número de referência único
        "status",          // Estado do pagamento (1 = Pago, 0 = Pendente)
        "date",            // Data e hora de efetivação
        "currency",        // Sigla da moeda (ex: Kz, AOA)
        "payment_method",  // Forma de pagamento (Numerário, Cartão, Transferência)
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'payment_id');
    }
}
