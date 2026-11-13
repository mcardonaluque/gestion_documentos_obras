<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosTresAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosTresAprobacion\ModeloTresAprobacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModelosTresAprobacion extends ListRecords
{
    protected static string $resource = ModeloTresAprobacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
