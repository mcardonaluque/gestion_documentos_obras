<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlazoObraActivos\Pages;

use App\Filament\Resources\PlazoObraActivos\PlazoObraActivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlazoObraActivos extends ListRecords
{
    protected static string $resource = PlazoObraActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
