<?php

namespace App\Filament\Obras\Resources\PlanSses\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\PlanSses\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
