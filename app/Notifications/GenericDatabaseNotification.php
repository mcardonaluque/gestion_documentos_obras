<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificación genérica persistida en base de datos.
 *
 * Está pensada para mostrarse en Filament y reutilizarse desde distintos
 * puntos del sistema sin necesidad de crear una clase por cada aviso simple.
 */
class GenericDatabaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** Título visible de la notificación. */
    public string $title;
    /** Cuerpo descriptivo mostrado al usuario. */
    public string $message;

    /** Severidad o categoría visual: info, warning, urgent, etc. */
    public string $type;

    /**
     * Inicializa la notificación con el contenido mínimo necesario.
     */
    public function __construct(string $title, string $message, string $type)
    {
         $this->title = $title;
         $this->message = $message;     // ✅ Para Filament
         $this->type = $type;

    }

    /**
     * Define los canales por los que se enviará la notificación.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Puedes añadir 'mail', 'broadcast', etc.
    }

    /**
     * Representación para base de datos.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->message,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }

    /**
     * (Opcional) Representación para email.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message);
    }
}

