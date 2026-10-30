<?php

namespace App\Filament\Obras\Resources\PlanSses\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\PlanSses\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página estándar de edición de planes de seguridad y salud (sin lógica propia). */
class EditPlanSs extends EditRecord
{
    protected static string $resource = PlanSsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
