<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tesis;

class TesisRevisadaNotification extends Notification
{
    public $tesis;
    public $revision;

    public function __construct(Tesis $tesis, $revision = null)
    {
        $this->tesis = $tesis;
        $this->revision = $revision;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Detectamos si es rechazo
        $esRechazo = $this->revision && $this->revision->estado === 'rechazado';

        // Texto dinámico
        $subject = $esRechazo ? '❌ Tu tesis ha sido rechazada' : '📝 Tu tesis ha sido revisada';
        $greetingLine = $esRechazo
            ? 'Lamentamos informarte que tu tesis ha sido **rechazada** y no puede continuar el proceso.'
            : 'Tu tesis ha sido revisada por el profesor.';

        $comentarioHeader = $esRechazo ? '**Motivo del rechazo:**' : '**Comentarios del profesor:**';

        // Construimos el correo
        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hola ' . $notifiable->name . '!')
            ->line($greetingLine)
            ->line('**Título:** ' . $this->tesis->titulo)
            ->line('**Estado:** ' . ucfirst($this->tesis->estado));

        if ($this->revision && $this->revision->comentario) {
            $mail->line($comentarioHeader)
                 ->line($this->revision->comentario);
        }

        $mail->action('Ver detalles', url('/tesis/' . $this->tesis->id))
             ->salutation('Gracias, ' . config('app.name'));

        // Logs para debug
        \Log::info('Correo a: ' . $notifiable->email);
        \Log::info('Asunto: ' . ($this->revision->estado === 'rechazado' ? 'Rechazado' : 'Revisado'));
        \Log::info('SMTP salida: ' . config('mail.host') . ':' . config('mail.port'));
        \Log::info('SMTP usuario: ' . config('mail.username'));
        \Log::info('SMTP from: ' . config('mail.from.address'));

        return $mail;
    }
}