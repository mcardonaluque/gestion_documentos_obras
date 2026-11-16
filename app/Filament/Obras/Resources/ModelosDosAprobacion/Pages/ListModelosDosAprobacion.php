<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosDosAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosDosAprobacion\ModeloDosAprobacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModelosDosAprobacion extends ListRecords
{
    protected static string $resource = ModeloDosAprobacionResource::class;

    /** @return array<int, CreateAction> */
    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
