<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\NormativaPpacs\Pages;

use App\Filament\Obras\Resources\NormativaPpacs\NormativaPpacResource;
use App\Filament\Support\AsignarNormativaAPlanesAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/**
 * Página de edición de la normativa PPAC, con acciones de cabecera para
 * asignar la normativa a planes y eliminar el registro.
 */
class EditNormativaPpac extends EditRecord
{
    protected static string $resource = NormativaPpacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AsignarNormativaAPlanesAction::make(),
            DeleteAction::make(),
        ];
    }
}
