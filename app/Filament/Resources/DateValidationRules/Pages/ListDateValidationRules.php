<?php

namespace App\Filament\Resources\DateValidationRules\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DateValidationRules\DateValidationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de DateValidationRuleResource (panel de administración). */
class ListDateValidationRules extends ListRecords
{
    protected static string $resource = DateValidationRuleResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
