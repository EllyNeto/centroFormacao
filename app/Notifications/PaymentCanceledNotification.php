<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificação nativa do Laravel: Aviso de Anulação de Pagamento.
 *
 * Enviada automaticamente para o email do formando/candidato quando um pagamento
 * previamente registado for anulado administrativamente no sistema.
 *
 * Conteúdo:
 * - Referência bancária do pagamento anulado.
 * - Valor correspondente.
 * - Motivo formal registado para a anulação.
 * - Instruções para contactar a secretaria.
 */
class PaymentCanceledNotification extends Notification
{
    use Queueable;

    /**
     * Instância do pagamento anulado.
     *
     * @var \App\Models\Payment
     */
    public $payment;

    /**
     * Cria uma nova instância da notificação de anulação de pagamento.
     *
     * @param  \App\Models\Payment  $payment Instância do modelo de pagamento
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
     * Constrói o corpo do email notificando o cancelamento do pagamento.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $enrollment = $this->payment->enrollment;
        $name = $enrollment ? $enrollment->name : 'Estimado(a) Formando(a)';
        $formattedValue = number_format($this->payment->value, 2, ',', '.') . ' ' . $this->payment->currency;
        $reason = $this->payment->cancellation_reason ?? 'Não especificado pela administração.';

        return (new MailMessage)
            ->subject("Aviso de Anulação de Pagamento — Ref: {$this->payment->reference} — Centro de Formação")
            ->greeting("Olá, {$name}!")
            ->line("Informamos que o pagamento com a referência **{$this->payment->reference}** no valor de **{$formattedValue}** foi **anulado** na nossa base de dados.")
            ->line("**Motivo da Anulação:** {$reason}")
            ->line("**Data da Anulação:** " . ($this->payment->canceled_at ? $this->payment->canceled_at->format('d/m/Y \à\s H:i') : now()->format('d/m/Y \à\s H:i')))
            ->line("Caso pretenda regularizar a sua situação financeira ou tenha dúvidas, por favor dirija-se à secretaria do centro de formação com o comprovativo bancário.")
            ->action('Consultar Histórico de Candidatura', route('enrollment.show', $this->payment->enrollment_id))
            ->salutation('Atenciosamente, Departamento Financeiro — Centro de Formação');
    }

    /**
     * Representação em formato de array dos dados da notificação.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'payment_id'          => $this->payment->id,
            'reference'           => $this->payment->reference,
            'cancellation_reason' => $this->payment->cancellation_reason,
            'canceled_at'         => $this->payment->canceled_at,
        ];
    }
}
