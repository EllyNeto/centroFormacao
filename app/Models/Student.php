<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

/**
 * Modelo Eloquent representando a entidade Estudante (Student).
 * Esta classe é responsável pelo mapeamento dos dados dos estudantes e formandos na base de dados,
 * gerenciando as relações com inscrições, turmas e pagamentos, além de saldo de crédito acumulado.
 */
class Student extends Model
{
    // Ativa a funcionalidade de eliminação suave (soft deletes) para preservar registos eliminados
    use SoftDeletes;

    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * Atributos que devem ser tratados como instâncias de data.
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
        'name',                 // Nome completo do estudante/candidato
        'email',                // Endereço de e-mail de contacto
        'identity_card_number', // Número do Bilhete de Identidade (BI) ou documento legal
        'phone_number',         // Número de telefone principal armazenado na coluna da base de dados
        'code',                 // Código único sequencial de identificação do aluno no centro
        'image',                // Caminho e nome do ficheiro da fotografia do estudante no storage
        'balance',              // Saldo monetário acumulado do estudante (crédito de pagamentos em excesso)
    ];

    /**
     * Conversão de tipos de atributos nativos (Casting).
     *
     * @var array
     */
    protected $casts = [
        'balance' => 'float',
    ];

    /**
     * Accessor Eloquent para obter o número de telefone através de $student->phone.
     * Mapeia $student->phone diretamente para o valor da coluna 'phone_number'.
     *
     * @return string|null
     */
    public function getPhoneAttribute()
    {
        return $this->attributes['phone_number'] ?? null;
    }

    /**
     * Mutator Eloquent para definir o número de telefone através de $student->phone = $valor.
     * Mapeia a alteração diretamente para a coluna 'phone_number' da base de dados sem criar colunas adicionais.
     *
     * @param string|null $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone_number'] = $value;
    }

    /**
     * Relação de pertença (BelongsTo) com o modelo _Class (Turma).
     * Um estudante pode estar associado a uma determinada turma.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classe()
    {
        return $this->belongsTo(_Class::class);
    }

    /**
     * Relação de um-para-muitos (HasMany) com as inscrições do estudante.
     * Um estudante pode possuir múltiplas inscrições em diferentes cursos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Relação de um-para-muitos (HasMany) com os pagamentos efetuados pelo estudante.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id');
    }
}
