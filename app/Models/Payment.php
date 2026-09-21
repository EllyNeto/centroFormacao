<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent que representa a entidade de Pagamento (Payment).
 * Responsável por gerir os registos de emolumentos pagos ou pendentes, associação com o formando
 * e a respetiva inscrição, além da fatura associada.
 */
class Payment extends Model
{
    // Ativa a funcionalidade de eliminação suave (soft deletes)
    use SoftDeletes;
    
    /**
     * Tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * Atributos permitidos para atribuição em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        "student_id",      // Identificador do estudante/formando associado ao pagamento
        "enrollment_id",   // Identificador da inscrição associada (opcional)
        "type_of_payment", // Tipo/Categoria dos emolumentos selecionados (Ex: Inscrição, Valor do Curso)
        "value",           // Valor monetário cobrado/pago no ato da transação
        "reference",       // Número de referência único gerado para o pagamento
        "status",          // Estado do pagamento (1 = Concluído/Pago, 0 = Pendente/Não Pago)
        "date",            // Data e hora de efetivação do pagamento
        "currency",        // Sigla da moeda utilizada (Ex: AOA, USD, EUR)
        "payment_method",  // Forma de pagamento (Numerário, Cartão, Transferência)
    ];

    /**
     * Conversão de tipos de atributos (Casting).
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'value'  => 'float',
    ];

    /**
     * Relação de pertença (BelongsTo) com o estudante/formando associado ao pagamento.
     * Inclui registos eliminados logicamente (withTrashed) para integridade de auditoria.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->withTrashed();
    }

    /**
     * Relação de pertença (BelongsTo) com a inscrição associada ao pagamento.
     * Inclui registos eliminados logicamente (withTrashed) para auditoria.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id')->withTrashed();
    }

    /**
     * Relação de um-para-um (HasOne) com a fatura gerada a partir deste pagamento.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'payment_id');
    }
}
