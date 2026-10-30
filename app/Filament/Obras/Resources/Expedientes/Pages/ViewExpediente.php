<?php

namespace App\Filament\Obras\Resources\Expedientes\Pages;

use App\Filament\Obras\Resources\Expedientes\ExpedienteResource;
use App\Services\Prorrogas\ProrrogaDeadlineNotifier;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

/**
 * Vista de detalle del expediente.
 *
 * Al cargar el registro consulta {@see ProrrogaDeadlineNotifier} y, si hay
 * plazo de solicitud de prórroga, muestra una notificación persistente
 * (en aviso o en rojo si ya está fuera de plazo).
 */
class ViewExpediente extends ViewRecord
{
    protected static string $resource = ExpedienteResource::class;

    /**
     * Notifica el plazo de solicitud de prórroga del expediente, si existe.
     */
    protected function afterFill(): void
    {
        $deadline = app(ProrrogaDeadlineNotifier::class)->notifyForExpediente($this->getRecord());

        if (! $deadline) {
            return;
        }

        $isExpired = now()->greaterThan($deadline['fecha_maxima_solicitud']);

        Notification::make()
            ->title($isExpired ? 'Solicitud de prórroga fuera de plazo' : 'Plazo para solicitar prórroga')
            ->body(
                "Fase: {$deadline['fase']}. Tipo: {$deadline['tipo']}. "
                . 'Debe solicitarse antes del ' . $deadline['fecha_maxima_solicitud']->format('d/m/Y')
                . ' (fecha límite: ' . $deadline['fecha_limite']->format('d/m/Y') . ').'
            )
            ->color($isExpired ? 'danger' : 'warning')
            ->persistent()
            ->send();
    }
}
