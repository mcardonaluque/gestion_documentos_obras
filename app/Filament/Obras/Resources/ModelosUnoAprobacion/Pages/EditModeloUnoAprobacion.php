<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosUnoAprobacion\ModeloUnoAprobacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModeloUnoAprobacion extends EditRecord
{
    protected static string $resource = ModeloUnoAprobacionResource::class;

    /** @return array<int, DeleteAction> */
    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
