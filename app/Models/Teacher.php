<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando a entidade Formador (Teacher).
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $identity_card_number
 * @property string|null $phone_number
 * @property string|null $phone
 * @property string|null $specialty
 * @property string|null $image
 * @property boolean $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Teacher extends Model
{
    use SoftDeletes;
    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'teachers';

    // O Laravel vai tratar automaticamente a coluna 'deleted_at'
    protected $dates = ['deleted_at'];
    
    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',                 // Nome completo do formador
        'email',                // Endereço de e-mail
        'identity_card_number', // Número do Bilhete de Identidade / Documento
        'phone_number',         // Número de telefone
        'image',                // Nome do ficheiro da foto do formador
        'status',               // Estado do registo (Ativo/Desativo)
    ];

    /**
     * Conversão de tipos de atributos (Casting).
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];
}
