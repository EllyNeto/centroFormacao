<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent que representa a entidade de Pagamento (Payment).
 * Responsável pelo mapeamento da tabela 'payments' e gestão do ciclo de vida dos registos financeiros.
 */
class Payment extends Model
{
    // Ativa o recurso de remoção suave (SoftDeletes) para que os registos eliminados fiquem salvaguardados com carimbo temporal sem apagar fisicamente da BD
    use SoftDeletes;
    
    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * Atributos que devem ser convertidos para instâncias de data (Carbon).
     * O Laravel trata automaticamente a coluna 'deleted_at' para controlo do SoftDeletes.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'date']; 

    /**
     * Lista de atributos da tabela que podem ser atribuídos em massa (Mass Assignment) através dos métodos create() e update().
     *
     * @var array
     */
    protected $fillable = [
        "type_of_payment", // Tipo/Categoria do pagamento (ex: Propinas, Matrícula, Inscrição, Emolumentos)
        "value",           // Valor monetário cobrado/pago
        "reference",       // Número de referência único da transação bancária/comprovativo
        "status",          // Estado do pagamento (1 = Pago / Concluído, 0 = Pendente / Cancelado)
        "date",            // Data e hora de efetivação do pagamento
        "currency",        // Sigla da moeda utilizada (ex: AOA, USD, EUR)
    ];
}

