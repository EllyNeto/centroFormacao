<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa uma matrícula (enrollment) de um formando num curso.
 *
 * Uma matrícula tem dados pessoais do formando, turno pretendido, estado da
 * matrícula e a referência ao curso escolhido.
 *
 * @property int $id Identificador primário da matrícula
 * @property string $name Nome completo do formando
 * @property string $email Endereço de email do formando
 * @property string $phone Número de telefone do formando
 * @property string $number_of_identify Número de identificação civil (BI)
 * @property string|null $image Caminho ou URL da imagem do formando
 * @property string $shift Turno pretendido pelo formando
 * @property string $status Estado da matrícula (Pendente, Pago, Matriculado, Lista de Espera, Cancelado)
 * @property CarbonInterface|null $date Data da matrícula
 * @property int $course_id Chave estrangeira para o curso escolhido
 * @property CarbonInterface|null $deleted_at Data de exclusão lógica (soft delete)
 * @property CarbonInterface|null $created_at Data de criação do registo
 * @property CarbonInterface|null $updated_at Data da última atualização
 */
class Enrollment extends Model
{
    // HasFactory permite criar factories deste modelo para testes e seeders.
    // SoftDeletes implementa a exclusão lógica (coluna deleted_at).
    use HasFactory, SoftDeletes;

    // Define os campos que podem ser preenchidos em massa através de create() e update().
    #[Fillable(['name', 'email', 'phone', 'number_of_identify', 'image', 'shift', 'status', 'date', 'course_id'])]
    protected array $fillable = ['name', 'email', 'phone', 'number_of_identify', 'image', 'shift', 'status', 'date', 'course_id'];

    /**
     * Relacionamento "muitos para um": uma matrícula pertence a um curso (course).
     *
     * A coluna course_id na tabela enrollments referencia courses.id.
     *
     * @return BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Retorna a classe CSS de badge correspondente ao estado da candidatura/inscrição.
     *
     * Utilizado para estilização visual dinâmica nas tabelas e no painel principal.
     *
     * @return string Classe CSS do Bootstrap / tema (ex.: badge-success, badge-warning)
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'Pago' => 'badge-success',
            'Matriculado' => 'badge-info',
            'Lista de Espera' => 'badge-secondary',
            'Cancelado' => 'badge-danger',
            default => 'badge-warning',
        };
    }
}
