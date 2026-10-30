<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlazoObraActivos\Pages;

use App\Filament\Resources\PlazoObraActivos\PlazoObraActivoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de PlazoObraActivoResource (panel de administración). */
class EditPlazoObraActivo extends EditRecord
{
    protected static string $resource = PlazoObraActivoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
