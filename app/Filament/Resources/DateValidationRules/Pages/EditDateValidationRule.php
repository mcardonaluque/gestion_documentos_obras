<?php

namespace App\Filament\Resources\DateValidationRules\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DateValidationRules\DateValidationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de DateValidationRuleResource (panel de administración). */
class EditDateValidationRule extends EditRecord
{
    protected static string $resource = DateValidationRuleResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
