<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

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



