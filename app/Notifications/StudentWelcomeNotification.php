<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificação nativa do Laravel: Boas-vindas e Confirmação de Matrícula Oficial.
 *
 * Enviada automaticamente para o email do formando logo após a sua matrícula
 * ser formalizada e efetivada numa turma específica.
 *
 * Conteúdo:
 * - Código único de estudante (Ex: CF-2026-0001).
 * - Identificação da turma, curso e turno.
 * - Sala de aulas física e horário semanal.
 * - Identificação do formador responsável.
 */
class StudentWelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Instância do modelo de Formando (Student).
     *
     * @var \App\Models\Student
     */
    public $student;

    /**
     * Cria uma nova instância da notificação de boas-vindas do formando.
     *
     * @param  \App\Models\Student  $student Formando oficial recém-matriculado
     * @return void
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
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
     * Constrói o email de boas-vindas com todas as informações académicas da formação.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $room = $this->student->room;
        $courseName = $room && $room->course ? $room->course->name : ($this->student->course ? $this->student->course->name : 'Curso de Formação');
        $code = $this->student->formattedCode();

        $mail = (new MailMessage)
            ->subject("Bem-vindo(a) ao Centro de Formação — Matrícula Confirmada ({$code})")
            ->greeting("Olá, {$this->student->name}!")
            ->line("É com enorme satisfação que confirmamos a sua **matrícula oficial** no curso de **{$courseName}**.")
            ->line("**Código Oficial de Formando:** `{$code}`")
            ->line("**Documento de Identificação (BI):** {$this->student->number_of_identify}");

        if ($room) {
            $classroomLabel = $room->classroom ? "Sala {$room->classroom->number_of_classroom}" : 'A designar';
            $teacherName    = $room->teacher ? $room->teacher->name : 'A designar';
            $days           = is_array($room->days_of_week) ? implode(', ', $room->days_of_week) : $room->days_of_week;
            $schedule       = \Carbon\Carbon::parse($room->start_time)->format('H:i') . ' às ' . \Carbon\Carbon::parse($room->end_time)->format('H:i');

            $mail->line("**Turma:** {$room->name}")
                 ->line("**Turno:** {$room->shift}")
                 ->line("**Horário das Aulas:** {$schedule}")
                 ->line("**Dias da Semana:** {$days}")
                 ->line("**Sala de Aulas:** {$classroomLabel}")
                 ->line("**Formador(a) Responsável:** {$teacherName}");
        }

        $mail->action('Aceder à Sua Ficha de Aluno', route('student.show', $this->student->id))
             ->line('Por favor, guarde o seu código de formando para futuras consultas e emissão de certificados.')
             ->salutation('Votos de um excelente ano letivo e sucesso profissional! — Direção Pedagógica');

        return $mail;
    }

    /**
     * Representação em formato de array dos dados do formando.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'code'       => $this->student->formattedCode(),
            'name'       => $this->student->name,
            'email'      => $this->student->email,
            'room_id'    => $this->student->room_id,
        ];
    }
}
