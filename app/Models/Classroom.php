<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa uma sala de aula física (classroom) da instituição de ensino.
 *
 * Uma sala tem número de identificação, capacidade e descrição, e pode estar
 * associada a várias turmas (rooms) através do relacionamento "um para muitos".
 *
 * @property int $id Identificador primário da sala
 * @property int $number_of_classroom Número da sala (único)
 * @property int $capacity Capacidade máxima de alunos da sala
 * @property string|null $description Descrição detalhada da sala
 * @property \Carbon\CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property \Carbon\CarbonInterface|null $created_at Data de criação do registo
 * @property \Carbon\CarbonInterface|null $updated_at Data da última atualização
 */
class Classroom extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at) em vez de apagar registos fisicamente.
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    // O atributo #[Fillable] é a convenção do Laravel 13 com atributos PHP 8,
    // equivalente à propriedade $fillable do Laravel 7.
    #[Fillable(['number_of_classroom', 'capacity', 'description'])]
    protected array $fillable = ['number_of_classroom', 'capacity', 'description'];

    /**
     * Relacionamento "um para muitos": uma sala de aula tem muitas turmas (rooms).
     *
     * Uma sala (classrooms.id) é referenciada pela coluna classroom_id na tabela rooms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
