<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificação nativa do Laravel: Confirmação de Candidatura / Inscrição.
 *
 * Enviada automaticamente para o email do candidato logo após a submissão
 * bem-sucedida do formulário de pré-inscrição no sistema.
 *
 * Conteúdo:
 * - Código único gerado da candidatura (Ex: INS-2026-0001).
 * - Designação do curso e turno selecionado.
 * - Valor da propina e orientações para liquidação e garantia de vaga.
 */
class EnrollmentConfirmationNotification extends Notification
{
    use Queueable;

    /**
     * Instância do modelo de Inscrição associado à notificação.
     *
     * @var \App\Models\Enrollment
     */
    public $enrollment;

    /**
     * Cria uma nova instância da notificação de confirmação de inscrição.
     *
     * @param  \App\Models\Enrollment  $enrollment Modelo da candidatura criada
     * @return void
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Define os canais de entrega da notificação nativa.
     * Utiliza o canal 'mail' para envio de email.
     *
     * @param  mixed  $notifiable Entidade que recebe a notificação
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Constrói a mensagem de correio eletrónico a ser enviada ao candidato.
     *
     * @param  mixed  $notifiable Entidade que recebe o email
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $courseName  = $this->enrollment->course ? $this->enrollment->course->name : 'Curso de Formação';
        $coursePrice = $this->enrollment->course ? number_format($this->enrollment->course->value, 2, ',', '.') : '0,00';
        $code        = $this->enrollment->formattedCode();

        return (new MailMessage)
            ->subject("Confirmação de Candidatura — {$code} — Centro de Formação")
            ->greeting("Olá, {$this->enrollment->name}!")
            ->line("A sua candidatura ao curso de **{$courseName}** foi registada com sucesso no sistema do Centro de Formação.")
            ->line("**Código da Candidatura:** {$code}")
            ->line("**Documento de Identificação (BI):** {$this->enrollment->number_of_identify}")
            ->line("**Turno Pretendido:** {$this->enrollment->shift}")
            ->line("**Valor da Propina:** {$coursePrice} Kz")
            ->line("Para garantir a sua vaga na turma pretendida e oficializar a sua matrícula, dirija-se à secretaria ou efetue a liquidação da propina através dos meios de pagamento autorizados.")
            ->action('Consultar Estado da Candidatura', route('enrollment.show', $this->enrollment->id))
            ->line('Se não efetuou esta candidatura, por favor ignore este email ou contacte a nossa secretaria.')
            ->salutation('Atenciosamente, Equipa do Centro de Formação');
    }

    /**
     * Representação da notificação em formato de array para armazenamento ou registo.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'enrollment_id' => $this->enrollment->id,
            'code'          => $this->enrollment->formattedCode(),
            'course_id'     => $this->enrollment->course_id,
            'name'          => $this->enrollment->name,
            'email'         => $this->enrollment->email,
        ];
    }
}
