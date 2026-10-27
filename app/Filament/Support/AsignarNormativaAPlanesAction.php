<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Models\NormativaPpac;
use App\Models\Planes;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

/**
 * Acción que muestra la lista de planes y copia las fechas y plazos de una
 * normativa PPAC a los planes marcados (una normativa por año y plan).
 */
final class AsignarNormativaAPlanesAction
{
    public static function make(): Action
    {
        $action = Action::make('asignarPlanes');

        return $action
            ->label('Asignar a planes')
            ->icon('heroicon-o-clipboard-document-check')
            ->color('primary')
            ->modalHeading('Asignar fechas y plazos a planes')
            ->modalDescription('Marca los planes a los que se aplican las fechas y plazos de esta normativa. Los planes que ya tienen normativa para ese año aparecen marcados y bloqueados.')
            ->modalSubmitActionLabel('Asignar')
            ->modalWidth('3xl')
            ->fillForm(fn (NormativaPpac $record): array => [
                'planes' => self::planesAsignados($record),
            ])
            ->schema([
                CheckboxList::make('planes')
                    ->label('Planes')
                    ->options(fn (): array => self::opcionesPlanes())
                    ->disableOptionWhen(fn (string $value): bool => in_array(
                        $value,
                        self::planesAsignados($action->getRecord()),
                        true,
                    ))
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(2)
                    ->gridDirection('row')
                    ->required(),
            ])
            ->action(function (array $data, NormativaPpac $record, mixed $livewire): void {
                // Garantiza que se copian los datos que el usuario ve en pantalla.
                if ($livewire instanceof EditRecord) {
                    $livewire->save(shouldRedirect: false, shouldSendSavedNotification: false);
                    $record->refresh();
                }

                // Los planes que ya tienen normativa para el año no se modifican.
                $nuevos = array_values(array_diff($data['planes'] ?? [], self::planesAsignados($record)));

                if ($nuevos === []) {
                    Notification::make()
                        ->warning()
                        ->title('No hay planes nuevos que asignar.')
                        ->send();

                    return;
                }

                $total = $record->assignToPlans($nuevos);

                Notification::make()
                    ->success()
                    ->title("Normativa {$record->ao_plan} asignada a {$total} plan(es).")
                    ->send();
            });
    }

    /**
     * @return list<string>
     */
    private static function planesAsignados(?NormativaPpac $record): array
    {
        if ($record === null) {
            return [];
        }

        return array_values(array_unique(array_filter([
            ...$record->codigosPlanAsignados(),
            trim((string) $record->Codigo_Plan),
        ])));
    }

    /**
     * @return array<string, string>
     */
    private static function opcionesPlanes(): array
    {
        return Planes::query()
            ->orderBy('codigo_plan')
            ->get(['codigo_plan', 'denominacion_plan'])
            ->mapWithKeys(fn (Planes $plan): array => [
                trim((string) $plan->codigo_plan) => trim((string) $plan->codigo_plan) . ' - ' . trim((string) $plan->denominacion_plan),
            ])
            ->all();
    }
}
