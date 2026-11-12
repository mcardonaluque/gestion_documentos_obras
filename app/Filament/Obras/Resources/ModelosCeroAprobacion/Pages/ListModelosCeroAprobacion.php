<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosCeroAprobacion\ModeloCeroAprobacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * Lista los Modelos 0 de aprobación registrados.
 */
class ListModelosCeroAprobacion extends ListRecords
{
    protected static string $resource = ModeloCeroAprobacionResource::class;

    /**
     * @return array<int, CreateAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
