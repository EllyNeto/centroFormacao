<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Teacher - Representa um Formador / Professor do Centro de Formação.
 *
 * Atributos:
 * @property int $id Identificador único do formador.
 * @property string $name Nome completo do formador.
 * @property string $email Email único de contacto.
 * @property string $gender Género do formador.
 * @property string $specialization Área de especialização.
 * @property string $number_of_identify Número do BI (único).
 * @property string $phone Telefone de contacto.
 * @property string|null $image Foto de perfil (caminho/nome do ficheiro).
 * @property \Illuminate\Support\Carbon|null $deleted_at Data de eliminação lógica.
 * @property \Illuminate\Support\Carbon $created_at Data de criação do registo.
 * @property \Illuminate\Support\Carbon $updated_at Data de última atualização.
 */
class Teacher extends Model
{
    // Permite a eliminação lógica (Soft Delete) mantendo o histórico de turmas lecionadas
    use SoftDeletes;
    use Notifiable; // Permite envio nativo de notificações por email diretamente ao modelo

    /**
     * Nome da tabela associada no banco de dados.
     *
     * @var string
     */
    protected $table = 'teachers';

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',               // Nome completo
        'email',              // Email único
        'gender',             // Género (ex: Masculino / Feminino)
        'specialization',     // Área de especialidade
        'number_of_identify', // Bilhete de Identidade (BI)
        'phone',              // Contacto telefónico
        'image',              // Fotografia de perfil
    ];

    /**
     * Relacionamento 1:N com as Turmas (Room).
     * Um Formador pode ser responsável por lecionar várias Turmas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'teacher_id');
    }

    /**
     * Verifica se o formador possui turmas ativas vinculadas (Abertas ou Em curso).
     * Utilizado para impedir a eliminação de formadores em exercício de funções.
     *
     * @return bool True se houver turmas ativas lecionadas pelo formador, False caso contrário.
     */
    public function hasActiveRooms(): bool
    {
        return $this->rooms()
            ->whereIn('status', ['Aberta', 'Em curso'])
            ->exists();
    }
}
