<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tesis;

class TesisEnviadaNotification extends Notification
{
    public $tesis;

    public function __construct(Tesis $tesis)
    {
        $this->tesis = $tesis;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('🎓 Nueva Tesis Enviada - Requiere Revisión')
                    ->greeting('Hola ' . $notifiable->name . '!')
                    ->line('Se ha enviado una nueva tesis que requiere tu revisión:')
                    ->line('**Título:** ' . $this->tesis->titulo)
                    ->line('**Grupo:** ' . $this->tesis->grupo->nombre_grupo)
                    ->line('**Fecha de envío:** ' . $this->tesis->created_at->format('d/m/Y H:i'))
                    ->action('Revisar Tesis', url('/tesis/' . $this->tesis->id))
                    ->line('Por favor, revisa la tesis en el sistema cuando tengas un momento.')
                    ->salutation('Saludos,  
Sistema Gestor de Tesis');
    }
}