<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa um curso oferecido pela instituição de ensino.
 *
 * Um curso tem nome, descrição, duração (em horas), estado (activo/inactivo) e valor.
 * Pode ter várias turmas (rooms) e várias matrículas (enrollments) associadas.
 *
 * @property int $id Identificador primário do curso
 * @property string $name Nome do curso
 * @property string $description Descrição detalhada do curso
 * @property int $duration Duração do curso em horas
 * @property bool $status Estado do curso (true = activo, false = inactivo)
 * @property float $value Valor/preço do curso
 * @property \Carbon\CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property \Carbon\CarbonInterface|null $created_at Data de criação do registo
 * @property \Carbon\CarbonInterface|null $updated_at Data da última atualização
 */
class Course extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at).
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    #[Fillable(['name', 'description', 'duration', 'status', 'value'])]
    protected array $fillable = ['name', 'description', 'duration', 'status', 'value'];

    /**
     * Define a conversão de tipos (casts) dos atributos do modelo.
     *
     * No Laravel 13 a forma recomendada é o método casts(): array, em vez da
     * propriedade $casts do Laravel 7. O campo status é guardado como inteiro
     * (0/1) na base de dados e devolvido como booleano pelo Eloquent.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /**
     * Relacionamento "um para muitos": um curso tem muitas turmas (rooms).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Relacionamento "um para muitos": um curso tem muitas matrículas (enrollments).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
