<?php

namespace App\Filament\Resources\DateRuleExecutions\Pages;

use App\Filament\Resources\DateRuleExecutions\DateRuleExecutionResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de DateRuleExecutionResource (panel de administración). */
class ListDateRuleExecutions extends ListRecords
{
    protected static string $resource = DateRuleExecutionResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
