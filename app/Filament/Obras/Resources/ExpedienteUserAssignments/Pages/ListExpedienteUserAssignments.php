<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ExpedienteUserAssignments\Pages;

use App\Filament\Obras\Resources\ExpedienteUserAssignments\ExpedienteUserAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpedienteUserAssignments extends ListRecords
{
    protected static string $resource = ExpedienteUserAssignmentResource::class;

    protected ?string $heading = 'ASIGNACIÓN DE EXPEDIENTES';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
