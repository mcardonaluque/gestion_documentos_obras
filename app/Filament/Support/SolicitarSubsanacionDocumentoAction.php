<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Models\DocumentoExpediente;
use App\Models\Subsanacion;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

/**
 * Acción de línea del portal de Obras para solicitar la subsanación de un
 * documento de expediente.
 *
 * Registra la solicitud directamente en la tabla única `subsanaciones`
 * rellenando automáticamente la fecha de solicitud con la fecha actual (los
 * datos de la obra se completan desde el documento al crear). Mientras exista
 * una solicitud sin subsanar, la acción no se muestra y la fila presenta la
 * leyenda «Subsanación solicitada».
 */
final class SolicitarSubsanacionDocumentoAction
{
    public static function make(): Action
    {
        return Action::make('solicitarSubsanacion')
            ->label('Solicitar subsanación')
            ->icon('heroicon-o-document-plus')
            ->color('warning')
            ->modalHeading('Solicitar subsanación del documento')
            ->modalDescription('La fecha de solicitud de subsanación se registrará automáticamente con la fecha actual.')
            ->modalSubmitActionLabel('Solicitar')
            ->visible(fn (DocumentoExpediente $record): bool => filled($record->expediente_id)
                && ! $record->tieneSubsanacionPendiente())
            ->schema([
                Textarea::make('motivo_subsanacion')
                    ->label('Motivo de la subsanación')
                    ->required()
                    ->maxLength(2000),
                TextInput::make('plazo_subsanacion')
                    ->label('Plazo (días)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(365),
            ])
            ->action(function (array $data, DocumentoExpediente $record): void {
                // El hook creating() completa plan, obra, año y team desde el documento,
                // y el hook saved() sincroniza las fechas espejo del documento.
                Subsanacion::query()->create([
                    'expediente_id' => (string) $record->expediente_id,
                    'documento_id' => (string) $record->getKey(),
                    'motivo_subsanacion' => $data['motivo_subsanacion'],
                    'plazo_subsanacion' => filled($data['plazo_subsanacion'] ?? null)
                        ? (int) $data['plazo_subsanacion']
                        : null,
                    'fecha_solicitud_subsanacion' => now()->toDateString(),
                ]);

                Notification::make()
                    ->success()
                    ->title('Subsanación solicitada')
                    ->body('Se ha registrado la solicitud de subsanación con fecha de hoy.')
                    ->send();
            });
    }
}
