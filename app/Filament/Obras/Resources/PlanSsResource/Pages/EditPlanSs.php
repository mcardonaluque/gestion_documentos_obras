<?php

namespace App\Filament\Obras\Resources\PlanSsResource\Pages;

use App\Filament\Obras\Resources\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanSs extends EditRecord
{
    protected static string $resource = PlanSsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
