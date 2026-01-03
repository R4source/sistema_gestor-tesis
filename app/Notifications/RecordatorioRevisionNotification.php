<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tesis;

class RecordatorioRevisionNotification extends Notification
{
    public $tesis;
    public $diasPendiente;

    public function __construct(Tesis $tesis, $diasPendiente)
    {
        $this->tesis = $tesis;
        $this->diasPendiente = $diasPendiente;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('⏰ Recordatorio: Tesis Pendiente de Revisión')
                    ->greeting('Hola ' . $notifiable->name . '!')
                    ->line('Tienes una tesis pendiente de revisión desde hace ' . $this->diasPendiente . ' días:')
                    ->line('**Título:** ' . $this->tesis->titulo)
                    ->line('**Grupo:** ' . $this->tesis->grupo->nombre_grupo)
                    ->line('**Fecha de envío:** ' . $this->tesis->created_at->format('d/m/Y'))
                    ->action('Revisar Tesis', url('/tesis/' . $this->tesis->id))
                    ->line('Por favor, realiza la revisión lo antes posible.')
                    ->salutation('Saludos,  
Sistema Gestor de Tesis');
    }
}