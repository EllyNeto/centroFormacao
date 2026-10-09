<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Course - Representa um Curso (Oferta Formativa) do Centro de Formação.
 *
 * Atributos:
 * @property int $id Identificador único do curso.
 * @property string $name Designação única do curso.
 * @property string|null $description Descrição do conteúdo programático.
 * @property int $duration Carga horária total em horas.
 * @property bool $status Estado do curso (true = Ativo, false = Inativo).
 * @property float $value Valor financeiro do curso em Kwanzas (AOA).
 * @property \Illuminate\Support\Carbon|null $deleted_at Data de eliminação lógica.
 * @property \Illuminate\Support\Carbon $created_at Data de criação do registo.
 * @property \Illuminate\Support\Carbon $updated_at Data de última atualização.
 */
class Course extends Model
{
    // Permite a eliminação lógica (Soft Delete) mantendo a integridade dos dados históricos
    use SoftDeletes;

    /**
     * Nome da tabela no banco de dados.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * Atributos atribuíveis em massa (Mass Assignment).
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',        // Nome do curso
        'description', // Descrição detalhada
        'duration',    // Carga horária em horas
        'status',      // Estado (1 = Ativo, 0 = Inativo)
        'value',       // Preço em Kwanzas
    ];

    /**
     * Conversão de tipos de atributos (Attribute Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'value'  => 'float',
    ];

    /**
     * Relacionamento 1:N com as Turmas (Room).
     * Um Curso pode ter várias Turmas criadas em semestres ou horários distintos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'course_id');
    }

    /**
     * Relacionamento 1:N com as Inscrições (Enrollment).
     * Um Curso pode receber várias candidaturas de formandos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * Verifica se o curso possui turmas ativas vinculadas (Abertas ou Em curso).
     * Utilizado para impedir a eliminação ou desativação acidental de cursos em uso.
     *
     * @return bool True se houver turmas ativas, False caso contrário.
     */
    public function hasActiveRooms(): bool
    {
        return $this->rooms()
            ->whereIn('status', ['Aberta', 'Em curso'])
            ->exists();
    }

    /**
     * Verifica se o curso possui inscrições ativas pendentes ou efetuadas.
     *
     * @return bool True se houver inscrições ativas, False caso contrário.
     */
    public function hasActiveEnrollments(): bool
    {
        return $this->enrollments()
            ->whereIn('status', ['Pendente', 'Pago', 'Matriculado'])
            ->exists();
    }
}
