<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosUnoAprobacion\ModeloUnoAprobacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModelosUnoAprobacion extends ListRecords
{
    protected static string $resource = ModeloUnoAprobacionResource::class;

    /** @return array<int, CreateAction> */
    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
