<?php

namespace App\Filament\Obras\Resources\PlanSses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\PlanSses\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
