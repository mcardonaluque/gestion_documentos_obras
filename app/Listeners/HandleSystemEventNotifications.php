<?php

namespace App\Listeners;

use App\Events\SystemEventOccurred;
use App\Jobs\DispatchNotificationJob;

use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

/**
 * Listener asíncrono encargado de materializar eventos de sistema.
 *
 * Su responsabilidad es:
 * - registrar auditoría del evento
 * - delegar la distribución de notificaciones a un job de cola
 */
class HandleSystemEventNotifications implements ShouldQueue
{
    /**
     * Procesa el evento emitido por el dominio y genera su rastro operativo.
     */
    public function handle(SystemEventOccurred $event): void
    {
        $entityClass = $event->entity ? get_class($event->entity) : null;
        $entityId = $event->entity?->getKey();

        // 1. Registrar auditoría
        EventLog::create([
            'type' => $event->eventType,
            'payload' => array_merge($event->meta, [
                'title' => $event->title,
                'message' => $event->message,
                'notification_type' => $event->type,
                'to_all_users' => $event->toAllUsers,
                'user_ids' => $event->userIds,
                'team_id' => $event->teamId,
                'entity_class' => $entityClass,
                'entity_id' => $entityId,
            ]),
            'tenant_id' => $event->teamId,
            'user_id' => Auth::id(),
        ]);

        // 2. Enviar job con criterios de destinatarios
        DispatchNotificationJob::dispatch(
            title: $event->title,
            message: $event->message,
            type: $event->type,
            toAllUsers: $event->toAllUsers,
            userIds: $event->userIds,
            teamId: $event->teamId,
            data: array_merge($event->meta, [
                'event_type' => $event->eventType,
                'entity_class' => $entityClass,
                'entity_id' => $entityId,
            ]),
        );
    }
}
