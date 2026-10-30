<?php

namespace App\Filament\Obras\Resources\PlanSses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\PlanSses\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de planes de seguridad y salud (sin lógica propia). */
class ListPlanSses extends ListRecords
{
    protected static string $resource = PlanSsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
