<?php

namespace App\Filament\Resources\DateValidationRules\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DateValidationRules\DateValidationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDateValidationRule extends EditRecord
{
    protected static string $resource = DateValidationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
