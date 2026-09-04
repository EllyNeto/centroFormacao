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
    //Activar SoftDeletes
    use SoftDeletes;
    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'courses';

    // O Laravel vai tratar automaticamente a coluna 'deleted_at'
    protected $dates = ['deleted_at']; 
    
    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',        // Nome do curso
        'status',      // Estado do curso
        'duration',    // Carga horária total (em horas)
        'description', // Descrição/Ementa do curso
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
