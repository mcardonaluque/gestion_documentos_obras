<?php

namespace App\Filament\Resources\DateValidationRules\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DateValidationRules\DateValidationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDateValidationRules extends ListRecords
{
    protected static string $resource = DateValidationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
