<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa um pagamento (payment) efectuado na instituição de ensino.
 *
 * Um pagamento tem tipo (ex.: Transferência, Multicaixa), valor, referência,
 * estado (pago/não pago), data e moeda.
 *
 * @property int $id Identificador primário do pagamento
 * @property string $type_of_payment Tipo de pagamento (ex.: Transferência, Multicaixa)
 * @property float $value Valor do pagamento
 * @property int $reference Referência do pagamento
 * @property bool $status Estado do pagamento (true = pago, false = não pago)
 * @property \Carbon\CarbonInterface $date Data e hora do pagamento
 * @property string $currency Moeda do pagamento (ex.: AOA)
 * @property \Carbon\CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property \Carbon\CarbonInterface|null $created_at Data de criação do registo
 * @property \Carbon\CarbonInterface|null $updated_at Data da última atualização
 */
class Payment extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at).
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    #[Fillable(['type_of_payment', 'value', 'reference', 'status', 'date', 'currency'])]
    protected array $fillable = ['type_of_payment', 'value', 'reference', 'status', 'date', 'currency'];

    /**
     * Define a conversão de tipos (casts) dos atributos do modelo.
     *
     * O campo status é guardado como inteiro (0/1) na base de dados e devolvido
     * como booleano pelo Eloquent.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'date' => 'datetime',
        ];
    }

    /**
     * Retorna o valor do pagamento formatado em moeda nacional (Kwanzas).
     *
     * @return string Exemplo: "45.000,00 Kz"
     */
    public function formattedValue(): string
    {
        return number_format((float) $this->value, 2, ',', '.') . ' ' . ($this->currency ?? 'Kz');
    }

    /**
     * Retorna a data do pagamento formatada para exibição legível.
     *
     * @return string Exemplo: "24/10/2026 às 14:30"
     */
    public function formattedDate(): string
    {
        return $this->date ? $this->date->format('d/m/Y \à\s H:i') : ($this->created_at ? $this->created_at->format('d/m/Y') : 'N/D');
    }
}
