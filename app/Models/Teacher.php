<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa um docente (professor) da instituição de ensino.
 *
 * Um docente tem nome, email, género, especialização, número de identificação (BI),
 * telefone e imagem. Pode estar associado a várias turmas (rooms) como responsável.
 *
 * @property int $id Identificador primário do docente
 * @property string $name Nome completo do docente
 * @property string $email Endereço de email do docente
 * @property string $gender Género do docente
 * @property string $specialization Área de especialização do docente
 * @property string $number_of_identify Número de identificação civil (BI)
 * @property int $phone Número de telefone do docente
 * @property string|null $image Caminho ou URL da imagem do docente
 * @property \Carbon\CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property \Carbon\CarbonInterface|null $created_at Data de criação do registo
 * @property \Carbon\CarbonInterface|null $updated_at Data da última atualização
 */
class Teacher extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at).
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    #[Fillable(['name', 'email', 'gender', 'specialization', 'number_of_identify', 'phone', 'image'])]
    protected array $fillable = ['name', 'email', 'gender', 'specialization', 'number_of_identify', 'phone', 'image'];

    /**
     * Relacionamento "um para muitos": um docente é responsável por muitas turmas (rooms).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
