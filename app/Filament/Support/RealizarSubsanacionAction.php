<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Models\Subsanacion;
use App\Services\Subsanaciones\SubsanacionResolucionService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

/**
 * Acción de línea para realizar (resolver) una solicitud de subsanación desde
 * el portal de Ayuntamientos.
 *
 * Solo es visible en las líneas de la tabla única `subsanaciones` que aún no
 * están subsanadas. Abre el formulario de subsanación (documento aportado y
 * descripción), incorpora el documento al expediente y rellena
 * automáticamente la fecha de subsanación con la fecha actual.
 */
final class RealizarSubsanacionAction
{
    public static function make(): Action
    {
        return Action::make('realizarSubsanacion')
            ->label('Realizar subsanación')
            ->icon('heroicon-o-document-check')
            ->color('success')
            ->modalHeading('Realizar subsanación')
            ->modalDescription(fn (Subsanacion $record): string => 'Motivo de la solicitud: '.$record->motivo_subsanacion)
            ->modalSubmitActionLabel('Subsanar')
            ->visible(fn (Subsanacion $record): bool => $record->fecha_subsanacion === null)
            ->schema([
                FileUpload::make('archivo')
                    ->label('Documento de subsanación')
                    ->helperText('Se incorporará al expediente y la fecha de subsanación se registrará automáticamente con la fecha actual.')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->directory('documentos-expedientes')
                    ->preserveFilenames()
                    ->required(),
                TextInput::make('descripcion')
                    ->label('Descripción del documento')
                    ->maxLength(255),
            ])
            ->action(function (array $data, Subsanacion $record): void {
                app(SubsanacionResolucionService::class)->subsanar($record, $data);

                Notification::make()
                    ->success()
                    ->title('Subsanación realizada')
                    ->body('El documento se ha incorporado al expediente y la subsanación queda resuelta con fecha de hoy.')
                    ->send();
            });
    }
}
