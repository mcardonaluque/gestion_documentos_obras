<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

/**
 * Evento genérico de sistema.
 *
 * Se utiliza para notificar acciones relevantes del dominio sin acoplar
 * el código emisor a la capa de almacenamiento, auditoría o notificación.
 */
class SystemEventOccurred
{
    use Dispatchable, SerializesModels;

    public string $eventType;
    public string $title;
    public string $message;
    public string $type; // info | task | warning | urgent
    public bool $toAllUsers;
    public array $userIds; // destinatarios directos
    public ?int $teamId;   // equipo
    public ?Model $entity; // expediente o documento
    public array $meta;    // datos extra

    /**
     * Crea una nueva notificación de dominio desacoplada.
     *
     * @param array<int, int> $userIds Usuarios destinatarios directos.
     * @param array<string, mixed> $meta Metadatos adicionales para auditoría o UI.
     */
    public function __construct(
        string $eventType,
        string $title,
        string $message,
        string $type,
        bool $toAllUsers = false,
        array $userIds = [],
        ?int $teamId = null,
        ?Model $entity = null,
        array $meta = [],
    ) {
        $this->eventType = $eventType;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->toAllUsers = $toAllUsers;
        $this->userIds = $userIds;
        $this->teamId = $teamId;
        $this->entity = $entity;
        $this->meta = $meta;
    }
}



