<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent representando a entidade Curso (Course).
 *
 * @property int $id
 * @property string $name
 * @property boolean $status
 * @property int $duration
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Course extends Model
{
    // Ativar a funcionalidade de exclusão lógica (Soft Deletes)
    use SoftDeletes;

    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * Tratamento automático da coluna 'deleted_at' pelo Eloquent.
     *
     * @var array
     */
    protected $dates = ['deleted_at']; 
    
    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',        // Nome completo do curso
        'status',      // Estado do curso (Ativo/Inativo)
        'duration',    // Carga horária total do curso
        'description', // Descrição detalhada ou ementa do curso
    ];

    /**
     * Conversão automática de tipos de dados.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relação de um-para-muitos com o modelo de Turmas (_Class).
     * Um curso pode conter várias turmas associadas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classe()
    {
        return $this->hasMany(_Class::class, 'course_id');
    }
}
