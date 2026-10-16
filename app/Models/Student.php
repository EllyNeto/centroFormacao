<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Eloquent representativo da entidade Formando / Aluno Oficial (Student).
 *
 * Módulo 7: Formandos
 * Modela o formando oficial matriculado no centro de formação, derivado de uma
 * inscrição com pagamento confirmado e alocado a uma turma ativa.
 *
 * @property int $id Identificador primário do formando
 * @property int $enrollment_id Chave estrangeira para a inscrição de origem
 * @property int $room_id Chave estrangeira para a turma frequentada
 * @property string $code Código único de estudante (Ex: CF-2026-0001)
 * @property string $name Nome completo do formando
 * @property string $email Endereço de correio eletrónico
 * @property string $number_of_identify Número de identificação civil (BI ou Passaporte)
 * @property string $phone Número de telefone de contacto
 * @property string|null $image Caminho da fotografia de perfil no armazenamento
 * @property \Carbon\Carbon|null $created_at Data de criação do registo
 * @property \Carbon\Carbon|null $updated_at Data da última atualização
 * @property \Carbon\Carbon|null $deleted_at Data de eliminação lógica (Soft Delete)
 */
class Student extends Model
{
    use SoftDeletes;
    use Notifiable; // Permite envio nativo de notificações por email diretamente ao modelo

    /**
     * Nome da tabela associada ao modelo na base de dados.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enrollment_id',
        'room_id',
        'code',
        'name',
        'email',
        'number_of_identify',
        'phone',
        'image',
    ];

    /**
     * Boot do modelo Eloquent:
     * Regista eventos de ciclo de vida do modelo.
     * No evento 'creating', gera automaticamente o código único do formando caso não seja fornecido.
     * Padrão oficial: CF-ANO-NNNN (Exemplo: CF-2026-0001).
     * CORREÇÃO: Adicionado bloqueio transacional para evitar condições de corrida na geração de códigos.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Intercepta a criação para atribuir o código único sequencial anual
        static::creating(function ($student) {
            if (empty($student->code)) {
                $year = date('Y');
                /* CORREÇÃO: Usar transação com bloqueio para evitar condições de corrida
                 *
                 * PROBLEMA: Sem bloqueio, dois processos podem ler o mesmo max('id')
                 * antes de qualquer um inserir, resultando em IDs duplicados.
                 *
                 * SOLUÇÃO: lockForUpdate() bloqueia as linhas lidas até o fim da transação,
                 * garantindo que apenas um processo por vez calcule o próximo ID.
                 */
                DB::transaction(function () use ($student, $year) {
                    // Procura o maior ID existente (mesmo eliminado via soft delete) para evitar colisões
                    $nextId = (int) self::withTrashed()->lockForUpdate()->max('id') + 1;
                    $student->code = 'CF-' . $year . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                });
            }
        });
    }

    // =========================================================================
    // RELACIONAMENTOS ELOQUENT
    // =========================================================================

    /**
     * Relacionamento N:1 (Inscrição de Origem):
     * Cada formando descende diretamente de uma candidatura / inscrição formalizada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    /**
     * Relacionamento N:1 (Turma Frequentada):
     * Retorna a turma ativa à qual o formando pertence e onde assiste às aulas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relacionamento 1:N Indireto (Histórico de Pagamentos):
     * Acede aos pagamentos associados ao formando através da sua inscrição de origem.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Enrollment::class,
            'id',            // Chave primária em enrollments
            'enrollment_id', // Chave estrangeira em payments
            'enrollment_id', // Chave local em students
            'id'             // Chave local em enrollments
        );
    }

    // =========================================================================
    // MÉTODOS AUXILIARES E ACESSORES
    // =========================================================================

    /**
     * Retorna o código oficial formatado do formando.
     *
     * @return string Código único (Ex: CF-2026-0001)
     */
    public function formattedCode(): string
    {
        return $this->code ?? ('CF-' . date('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT));
    }

    /**
     * Retorna o curso frequentado pelo formando, obtido através da turma ou da inscrição.
     *
     * @return \App\Models\Course|null
     */
    public function getCourseAttribute()
    {
        if ($this->room && $this->room->course) {
            return $this->room->course;
        }

        if ($this->enrollment && $this->enrollment->course) {
            return $this->enrollment->course;
        }

        return null;
    }

    /**
     * Retorna o URL da fotografia de perfil do formando ou avatar padrão.
     *
     * @return string URL utilizável em tags de imagem <img>
     */
    public function avatarUrl(): string
    {
        if ($this->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        // Se o formando não tiver imagem direta mas a inscrição de origem tiver
        if ($this->enrollment && $this->enrollment->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->enrollment->image)) {
            return asset('storage/' . $this->enrollment->image);
        }

        return asset('images/no-img-avatar.png');
    }
}