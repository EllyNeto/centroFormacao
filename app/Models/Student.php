<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent representando a entidade Estudante (Student).
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $identity_card_number
 * @property string|int $phone_number
 * @property string|int $phone
 * @property int $code
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Student extends Model
{
    use SoftDeletes;
    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'students';

    // O Laravel vai tratar automaticamente a coluna 'deleted_at'
    protected $dates = ['deleted_at'];
     
    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',                 // Nome completo do estudante
        'email',                // Endereço de e-mail
        'identity_card_number', // Número do Bilhete de Identidade / Documento
        'phone_number',         // Número de telefone (nome padrão na migração)
        'code',                 // Código de identificação do aluno
        'image',                // Nome do ficheiro de foto do estudante
    ];
}
