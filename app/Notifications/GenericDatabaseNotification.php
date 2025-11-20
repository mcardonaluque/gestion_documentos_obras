<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PhpParser\Node\Expr\Cast\Array_;

class GenericDatabaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $title;
    public string $message;
    public string $type;
    
    public ?int $sender_id = null;

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
            'sender_id' =>  auth()->id(),
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

