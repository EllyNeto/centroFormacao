<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent para a entidade Course (Curso).
 * Mapeia os registos da tabela 'courses' no banco de dados.
 */
class Course extends Model
{
    // Trait para activar o SoftDeletes (exclusão lógica sem remover da base de dados)
    use SoftDeletes;

    // Nome explícito da tabela na base de dados
    protected $table = 'courses';

    // Campos permitidos para atribuição em massa (Mass Assignment)
    protected $fillable = [
        "name",
        "description",
        "duration",
        "status",
        "value",
    ];

    // Conversão automática de tipos de dados ao ler os atributos
    protected $casts = [
        'status' => 'boolean',
    ];
}
