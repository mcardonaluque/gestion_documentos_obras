<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


/**
 * Job de cola responsable de distribuir notificaciones internas a destinatarios resueltos.
 *
 * Permite desacoplar la generación de eventos del envío real de avisos y evita
 * penalizar la respuesta HTTP cuando la notificación se dirige a múltiples usuarios.
 */
class DispatchNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Título visible de la notificación. */
    public string $title;
    /** Mensaje descriptivo mostrado al destinatario. */
    public string $message;

    /** Tipo o severidad de la notificación. */
    public string $type;

    /** Indica si la notificación debe difundirse a todos los usuarios. */
    public bool $toAllUsers;

    /** @var array<int, int> Usuarios destinatarios directos. */
    public array $userIds;

    /** Team destinatario cuando la difusión se hace por ayuntamiento o equipo. */
    public ?int $teamId;

    /** @var array<string, mixed> Metadatos adicionales asociados a la notificación. */
    public array $data;

    /**
     * Crea un job serializable con toda la información necesaria para el envío.
     *
     * @param array<int, int> $userIds
     * @param array<string, mixed> $data
     */
    public function __construct(
        string $title,
        string $message,
        string $type,
        bool $toAllUsers = false,
        array $userIds = [],
        ?int $teamId = null,
        array $data = []
    )
    {
        $this->title    = $title;
        $this->message  = $message;
        $this->type     = $type;
        $this->toAllUsers = $toAllUsers;
        $this->userIds = $userIds;
        $this->teamId = $teamId;
        $this->data     = $data;
    }

    /**
     * Ejecuta el envío delegando la resolución final de destinatarios al servicio de notificaciones.
     */
    public function handle(): void
    {
        NotificationService::sendByTargets(
            $this->title,
            $this->message,
            $this->type,
            $this->toAllUsers,
            $this->userIds,
            $this->teamId,
            $this->data
        );
    }
}
