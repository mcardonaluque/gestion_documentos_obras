<?php

namespace App\Filament\Resources\DateValidationRuleResource\Pages;

use App\Filament\Resources\DateValidationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDateValidationRule extends EditRecord
{
    protected static string $resource = DateValidationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
