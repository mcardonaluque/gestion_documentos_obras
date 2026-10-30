<?php

declare(strict_types=1);

namespace App\Filament\Resources\NormativaPpacs\Pages;

use App\Filament\Resources\NormativaPpacs\NormativaPpacResource;
use App\Filament\Support\AsignarNormativaAPlanesAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de NormativaPpacResource (panel de administración). */
class EditNormativaPpac extends EditRecord
{
    protected static string $resource = NormativaPpacResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            AsignarNormativaAPlanesAction::make(),
            DeleteAction::make(),
        ];
    }
}
