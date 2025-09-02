<?php

namespace App\Filament\Obras\Resources\PlanSsResource\Pages;

use App\Filament\Obras\Resources\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanSses extends ListRecords
{
    protected static string $resource = PlanSsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
