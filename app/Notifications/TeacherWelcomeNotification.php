<?php

namespace App\Notifications;

use App\Models\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificação nativa do Laravel: Boas-vindas ao Formador (Docente).
 *
 * Enviada automaticamente para o email do formador no momento em que
 * o seu cadastro for registado no sistema administrativo do Centro de Formação.
 *
 * Conteúdo:
 * - Confirmação do registo no corpo docente.
 * - Especialização profissional associada.
 * - Dados de contacto registados.
 */
class TeacherWelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Instância do modelo de Formador (Teacher).
     *
     * @var \App\Models\Teacher
     */
    public $teacher;

    /**
     * Cria uma nova instância da notificação de boas-vindas do formador.
     *
     * @param  \App\Models\Teacher  $teacher Formador recém-cadastrado
     * @return void
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
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
     * Constrói o email de boas-vindas para o formador.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Bem-vindo(a) ao Corpo Docente — Centro de Formação")
            ->greeting("Estimado(a) Formador(a) {$this->teacher->name},")
            ->line("É com grande honra que lhe damos as boas-vindas ao corpo docente do **Centro de Formação**.")
            ->line("O seu perfil profissional foi registado com sucesso no nosso sistema de gestão pedagógica.")
            ->line("**Área de Especialização:** {$this->teacher->specialization}")
            ->line("**Documento de Identificação (BI):** {$this->teacher->number_of_identify}")
            ->line("**Contacto Registado:** {$this->teacher->phone}")
            ->action('Aceder ao Meu Perfil de Formador', route('teacher.show', $this->teacher->id))
            ->line('Em breve será notificado quando novas turmas lhe forem atribuídas pela coordenação pedagógica.')
            ->salutation('Com os melhores cumprimentos, Coordenação Pedagógica — Centro de Formação');
    }

    /**
     * Representação em formato de array dos dados do formador.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'teacher_id'     => $this->teacher->id,
            'name'           => $this->teacher->name,
            'email'          => $this->teacher->email,
            'specialization' => $this->teacher->specialization,
        ];
    }
}
