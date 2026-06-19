<?php

namespace App\Filament\Resources\DateRuleExecutions\Pages;

use App\Filament\Resources\DateRuleExecutions\DateRuleExecutionResource;
use Filament\Resources\Pages\ListRecords;

class ListDateRuleExecutions extends ListRecords
{
    protected static string $resource = DateRuleExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
