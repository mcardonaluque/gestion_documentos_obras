<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosCeroAprobacion\ModeloCeroAprobacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/**
 * Edita la aprobación y los documentos electrónicos del Modelo 0.
 */
class EditModeloCeroAprobacion extends EditRecord
{
    protected static string $resource = ModeloCeroAprobacionResource::class;

    /**
     * @return array<int, DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
