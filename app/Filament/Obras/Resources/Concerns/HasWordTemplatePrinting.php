<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Concerns;

use App\Models\DocumentoGenerico;
use App\Services\Templates\WordTemplatePrintService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait reutilizable para declarar acciones de impresión Word a PDF en Resources.
 *
 * Reduce al mínimo la lógica repetida en Filament y unifica mensajes de error,
 * resolución del documento genérico y descarga final del archivo generado.
 */
trait HasWordTemplatePrinting
{
    /**
     * Crea una acción de Filament lista para imprimir un documento basado en plantilla.
     */
    protected static function makeWordPrintAction(
        string $name,
        string $label,
        string $icon,
        string $documentName,
        ?string $requiredStatePath = null,
        ?string $requiredMessage = null,
        ?string $fallbackTemplatePath = null,
        string $downloadBaseName = 'documento',
    ): Action {
        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->visible(fn (callable $get): bool => $requiredStatePath === null || self::hasPrintableValue($get($requiredStatePath)))
            ->action(function (Model $record, callable $get) use (
                $requiredStatePath,
                $requiredMessage,
                $documentName,
                $fallbackTemplatePath,
                $downloadBaseName
            ) {
                if ($requiredStatePath !== null && ! self::hasPrintableValue($get($requiredStatePath))) {
                    Notification::make()
                        ->title('Error de validación')
                        ->body($requiredMessage ?? 'Debe completar el dato requerido antes de imprimir.')
                        ->danger()
                        ->send();

                    return null;
                }

                $documento = DocumentoGenerico::query()
                    ->where('con_plantilla', true)
                    ->where(function (Builder $query) use ($documentName, $fallbackTemplatePath): void {
                        $query->where('nombre', $documentName)
                            ->orWhere('plantilla', 'like', '%' . $documentName . '%');

                        if (is_string($fallbackTemplatePath) && trim($fallbackTemplatePath) !== '') {
                            $query->orWhere('ruta_plantilla', 'like', '%' . basename(str_replace('\\', '/', $fallbackTemplatePath)) . '%');
                        }
                    })
                    ->first();

                if ($documento === null) {
                    $documento = new DocumentoGenerico([
                        'nombre' => $documentName,
                        'ruta_plantilla' => $fallbackTemplatePath,
                        'con_plantilla' => true,
                    ]);
                }

                $input = [];

                if ($requiredStatePath !== null) {
                    $input[$requiredStatePath] = $get($requiredStatePath);
                }

                try {
                    return app(WordTemplatePrintService::class)->downloadPdf(
                        $documento,
                        $record,
                        $input,
                        $downloadBaseName,
                    );
                } catch (\Throwable $exception) {
                    Notification::make()
                        ->title('No se pudo generar el documento')
                        ->body($exception->getMessage())
                        ->danger()
                        ->send();

                    return null;
                }
            })
            ->requiresConfirmation();
    }

    /**
     * Determina si el valor requerido por la acción está informado y es imprimible.
     */
    private static function hasPrintableValue(string|int|float|bool|null $value): bool
    {
        if (is_string($value)) {
            return trim($value) !== '';
        }

        return $value !== null;
    }
}
