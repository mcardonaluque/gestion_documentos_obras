<?php

declare(strict_types=1);

namespace App\Services\Prorrogas;

use App\Models\CustomNotification;
use App\Models\Expediente;
use App\Models\PlazoObraActivo;
use App\Services\NotificationService;

final class ProrrogaDeadlineNotifier
{
    /**
     * @return array{fase: string, tipo: string, fecha_limite: \Carbon\Carbon, fecha_maxima_solicitud: \Carbon\Carbon, recipients: int}|null
     */
    public function notifyForExpediente(Expediente $expediente): ?array
    {
        $plazo = $expediente->obraEjecucion?->plazosActivos()
            ->where('activo', true)
            ->orderByDesc('id')
            ->first();

        if (! $plazo instanceof PlazoObraActivo) {
            return null;
        }

        $window = app(ProrrogaRulesService::class)->getRequestWindow($expediente, $plazo);
        $fechaLimite = $window['fecha_limite'];
        $fechaMaxima = $window['fecha_maxima_solicitud'];

        if (! $fechaLimite || ! $fechaMaxima) {
            return null;
        }

        $isCarretera = blank($expediente->obraInicio?->municipio)
            || (int) $expediente->obraInicio?->municipio === 0;
        $recipientIds = $isCarretera
            ? $expediente->assignedUsers()->pluck('users.id')->all()
            : ($expediente->team
                ? $expediente->team->users()->pluck('users.id')->all()
                : []);
        $recipientIds = array_values(array_unique(array_map('intval', $recipientIds)));

        $notificationKey = sprintf(
            'prorroga-deadline:%s:%s:%s',
            $expediente->expediente_id,
            $window['fase'],
            $fechaMaxima->toDateString(),
        );
        $title = now()->greaterThan($fechaMaxima)
            ? 'Solicitud de prórroga fuera de plazo'
            : 'Plazo para solicitar prórroga';
        $message = "Expediente {$expediente->expediente_id}: la prórroga de {$window['tipo']} debe solicitarse antes del {$fechaMaxima->format('d/m/Y')}. La fecha límite aplicable es {$fechaLimite->format('d/m/Y')}.";

        foreach ($recipientIds as $recipientId) {
            $alreadyNotifiedToday = CustomNotification::query()
                ->where('notifiable_id', $recipientId)
                ->where('created_at', '>=', now()->startOfDay())
                ->get()
                ->contains(fn (CustomNotification $notification): bool => ($notification->data['notification_key'] ?? null) === $notificationKey);

            if (! $alreadyNotifiedToday) {
                NotificationService::sendByTargets(
                    $title,
                    $message,
                    now()->greaterThan($fechaMaxima) ? 'danger' : 'warning',
                    false,
                    [$recipientId],
                    null,
                    ['notification_key' => $notificationKey, 'expediente_id' => $expediente->expediente_id],
                );
            }
        }

        return [
            'fase' => $window['fase'],
            'tipo' => $window['tipo'],
            'fecha_limite' => $fechaLimite,
            'fecha_maxima_solicitud' => $fechaMaxima,
            'recipients' => count($recipientIds),
        ];
    }
}
