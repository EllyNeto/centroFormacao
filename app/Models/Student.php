<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent representando a entidade Estudante (Student).
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
        'phone_number',         // Número de telefone
        'code',                 // Código de identificação do aluno
        'image',                // Nome do ficheiro de foto do estudante
    ];

    /**
     * Relação de pertença com o modelo _Class (Turma).
     */
    public function classe()
    {
        return $this->belongsTo(_Class::class);
    }

    /**
     * Relação de um-para-muitos com as inscrições do estudante.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }
}
