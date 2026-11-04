<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa um formando (aluno) da instituição de ensino.
 *
 * Um formando tem nome, email, número de identificação (BI), telefone, código
 * de formando e imagem.
 *
 * @property int $id Identificador primário do formando
 * @property string $name Nome completo do formando
 * @property string $email Endereço de email do formando
 * @property string $number_of_identify Número de identificação civil (BI)
 * @property int $phone Número de telefone do formando
 * @property int $code Código do formando (número de formando)
 * @property string|null $image Caminho ou URL da imagem do formando
 * @property \Carbon\CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property \Carbon\CarbonInterface|null $created_at Data de criação do registo
 * @property \Carbon\CarbonInterface|null $updated_at Data da última atualização
 */
class Student extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at).
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    #[Fillable(['name', 'email', 'number_of_identify', 'phone', 'code', 'image'])]
    protected array $fillable = ['name', 'email', 'number_of_identify', 'phone', 'code', 'image'];
}
