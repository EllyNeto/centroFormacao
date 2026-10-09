<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Classroom - Representa uma Sala de Aula Física do Centro de Formação.
 * 
 * Atributos:
 * @property int $id Identificador único da sala.
 * @property int $number_of_classroom Número visível da sala (ex: 101).
 * @property int $capacity Capacidade máxima física de lugares.
 * @property string|null $description Descrição/observações dos equipamentos.
 * @property \Illuminate\Support\Carbon|null $deleted_at Data de eliminação lógica.
 * @property \Illuminate\Support\Carbon $created_at Data de criação do registo.
 * @property \Illuminate\Support\Carbon $updated_at Data de última atualização.
 */
class Classroom extends Model
{
    // Permite a eliminação lógica (Soft Delete) mantendo o registo preservado na BD
    use SoftDeletes;

    /**
     * Nome da tabela associada no banco de dados.
     *
     * @var string
     */
    protected $table = 'classrooms';

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<string>
     */
    protected $fillable = [
        'number_of_classroom', // Número da sala
        'capacity',            // Capacidade máxima física
        'description',         // Descrição / observações adicionais
    ];

    /**
     * Define o relacionamento 1:N com as Turmas (Room).
     * Uma Sala de Aula pode albergar várias Turmas em horários/dias diferentes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'classroom_id');
    }

    /**
     * Verifica se a sala possui turmas ativas vinculadas.
     * Utilizado para impedir a eliminação de salas em uso.
     *
     * @return bool True se houver turmas ativas na sala, False caso contrário.
     */
    public function hasActiveRooms(): bool
    {
        return $this->rooms()
            ->whereIn('status', ['Aberta', 'Em curso'])
            ->exists();
    }

    /**
     * Obtém o número máximo de inscrições/formandos ativos atualmente alocados numa turma nesta sala.
     * Utilizado para validar se a capacidade pode ser reduzida na edição da sala.
     *
     * @return int O maior número de inscritos ativos em qualquer turma desta sala.
     */
    public function maxEnrolledInActiveRooms(): int
    {
        $max = 0;
        foreach ($this->rooms as $room) {
            if (method_exists($room, 'occupiedSeats')) {
                $occupied = $room->occupiedSeats();
                if ($occupied > $max) {
                    $max = $occupied;
                }
            }
        }
        return $max;
    }
}
