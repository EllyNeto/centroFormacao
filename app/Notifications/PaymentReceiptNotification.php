<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificação nativa do Laravel: Emissão de Recibo / Confirmação de Pagamento.
 *
 * Enviada automaticamente para o email associado à inscrição sempre que um pagamento
 * for registado ou confirmado no sistema.
 *
 * Conteúdo:
 * - Referência bancária ou número do recibo.
 * - Montante liquidado e método de pagamento.
 * - Estado financeiro da candidatura e instruções de acompanhamento.
 */
class PaymentReceiptNotification extends Notification
{
    use Queueable;

    /**
     * Instância do modelo de Pagamento associado.
     *
     * @var \App\Models\Payment
     */
    public $payment;

    /**
     * Cria uma nova instância da notificação de pagamento.
     *
     * @param  \App\Models\Payment  $payment Instância do pagamento efetuado
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Define os canais de entrega da notificação nativa.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Constrói a mensagem de correio eletrónico com o recibo e detalhes do pagamento.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $enrollment = $this->payment->enrollment;
        $candidateName = $enrollment ? $enrollment->name : 'Estimado(a) Formando(a)';
        $courseName    = ($enrollment && $enrollment->course) ? $enrollment->course->name : 'Curso de Formação';
        $formattedValue = number_format($this->payment->value, 2, ',', '.') . ' ' . $this->payment->currency;
        $isConfirmed   = $this->payment->status === 'Confirmado';

        $mail = (new MailMessage)
            ->subject("Recibo de Pagamento — Ref: {$this->payment->reference} — Centro de Formação")
            ->greeting("Olá, {$candidateName}!")
            ->line("Confirmamos o registo de pagamento referente ao curso de **{$courseName}**.")
            ->line("**Referência do Recibo:** {$this->payment->reference}")
            ->line("**Valor Pago:** {$formattedValue}")
            ->line("**Forma de Pagamento:** {$this->payment->type_of_payment}")
            ->line("**Data da Operação:** {$this->payment->formattedDate()}")
            ->line("**Estado:** " . ($isConfirmed ? 'Confirmado / Liquidado' : 'Pendente de Validação'));

        if ($isConfirmed) {
            $mail->line('O seu pagamento foi validado com sucesso! A sua propina encontra-se liquidada, permitindo a oficialização da sua matrícula.');
        } else {
            $mail->line('O seu pagamento encontra-se em análise administrativa. Será notificado logo que o montante seja confirmado.');
        }

        $mail->action('Visualizar Recibo Completo', route('payment.show', $this->payment->id))
             ->salutation('Atenciosamente, Departamento Financeiro — Centro de Formação');

        return $mail;
    }

    /**
     * Representação em array dos dados da notificação.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'payment_id'    => $this->payment->id,
            'reference'     => $this->payment->reference,
            'value'         => $this->payment->value,
            'status'        => $this->payment->status,
            'enrollment_id' => $this->payment->enrollment_id,
        ];
    }
}
