<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent que representa a entidade Fatura (Invoice).
 * Armazena informações financeiras sobre inscrições, cursos, valores cobrados, pagos e trocos.
 */
class Invoice extends Model
{
    use SoftDeletes;

    // Nome da tabela associada no banco de dados
    protected $table = 'invoices';

    // Atributos de datas para exclusão lógica (Soft Deletes)
    protected $dates = ['deleted_at'];

    // Atributos que podem ser preenchidos em massa (Mass Assignment)
    protected $fillable = [
        'enrollment_id', // ID da inscrição associada
        'course_id',     // ID do curso associado
        'amount_to_pay', // Valor a pagar (emolumento/propina)
        'amount_paid',   // Valor efetivamente pago pelo cliente
        'change',        // Valor do troco devolvido (se pago > cobrado)
        'payment_id',    // ID do registo de pagamento associado
    ];

    // Conversão de tipos de dados (Casting)
    protected $casts = [
        'amount_to_pay' => 'float',
        'amount_paid'   => 'float',
        'change'        => 'float',
    ];

    /**
     * Relação de pertença com o modelo Enrollment (Inscrição).
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    /**
     * Relação de pertença com o modelo Course (Curso).
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relação de pertença opcional com o modelo Payment (Pagamento).
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
