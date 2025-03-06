<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recuperación de Contraseña - PharmaAsistencia')
            ->greeting('¡Hola!')
            ->line('Estás recibiendo este correo porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta en PharmaAsistencia.')
            ->action('Restablecer Contraseña', url('password/reset', $this->token))
            ->line('Este enlace de restablecimiento de contraseña expirará en 60 minutos.')
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Saludos cordiales,')
            ->salutation('Equipo de PharmaAsistencia');
    }
}
