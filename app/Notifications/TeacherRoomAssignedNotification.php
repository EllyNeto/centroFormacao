<?php

namespace App\Notifications;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificação nativa do Laravel: Atribuição de Nova Turma a Formador.
 *
 * Enviada automaticamente para o email do formador quando a coordenação pedagógica
 * o designa como docente responsável por uma nova turma criada no sistema.
 *
 * Conteúdo:
 * - Designação da turma e curso associado.
 * - Sala de aulas física atribuída.
 * - Turno, horário diário e dias da semana.
 * - Lotação máxima de alunos.
 */
class TeacherRoomAssignedNotification extends Notification
{
    use Queueable;

    /**
     * Instância do modelo de Turma atribuída.
     *
     * @var \App\Models\Room
     */
    public $room;

    /**
     * Cria uma nova instância da notificação de atribuição de turma.
     *
     * @param  \App\Models\Room  $room Instância da turma criada
     * @return void
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Define os canais de entrega da notificação.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Constrói o corpo do email informando o formador sobre a nova turma.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $teacherName    = $this->room->teacher ? $this->room->teacher->name : 'Formador(a)';
        $courseName     = $this->room->course ? $this->room->course->name : 'Curso de Formação';
        $classroomLabel = $this->room->classroom ? "Sala {$this->room->classroom->number_of_classroom}" : 'Sem sala definida';
        $days           = is_array($this->room->days_of_week) ? implode(', ', $this->room->days_of_week) : $this->room->days_of_week;
        $schedule       = \Carbon\Carbon::parse($this->room->start_time)->format('H:i') . ' às ' . \Carbon\Carbon::parse($this->room->end_time)->format('H:i');

        return (new MailMessage)
            ->subject("Atribuição de Nova Turma: {$this->room->name} — Centro de Formação")
            ->greeting("Estimado(a) Formador(a) {$teacherName},")
            ->line("Informamos que foi designado(a) como formador(a) responsável pela turma **{$this->room->name}** no curso de **{$courseName}**.")
            ->line("**Turno:** {$this->room->shift}")
            ->line("**Horário das Aulas:** {$schedule}")
            ->line("**Dias da Semana:** {$days}")
            ->line("**Sala de Aulas:** {$classroomLabel}")
            ->line("**Lotação Máxima:** {$this->room->max_capacity} formandos")
            ->action('Consultar Turma e Lista de Alunos', route('room.show', $this->room->id))
            ->line('Poderá aceder à plataforma a qualquer momento para consultar a pauta nominal de formandos inscritos.')
            ->salutation('Votos de um excelente trabalho pedagógico! — Direção Pedagógica');
    }

    /**
     * Representação em formato de array dos dados da turma.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'room_id'    => $this->room->id,
            'room_name'  => $this->room->name,
            'teacher_id' => $this->room->teacher_id,
            'course_id'  => $this->room->course_id,
        ];
    }
}
