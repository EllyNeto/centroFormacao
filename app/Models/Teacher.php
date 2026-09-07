<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando a entidade Formador (Teacher).
 */
class Teacher extends Model
{
    use SoftDeletes;

    protected $table = 'teachers';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',                 // Nome completo do formador
        'email',                // Endereço de e-mail
        'identity_card_number', // Número do Bilhete de Identidade / Documento
        'phone_number',         // Número de telefone
        'image',                // Nome do ficheiro da foto do formador
        'status',               // Estado do registo (Ativo/Desativo)
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relação de um-para-muitos com as turmas lecionadas pelo formador.
     */
    public function classes()
    {
        return $this->hasMany(_Class::class, 'teacher_id');
    }
}
