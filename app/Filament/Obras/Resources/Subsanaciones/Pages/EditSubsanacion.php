<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Pages;

use App\Filament\Obras\Resources\Subsanaciones\SubsanacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Edición de una solicitud de subsanación de documento. */
class EditSubsanacion extends EditRecord
{
    protected static string $resource = SubsanacionResource::class;

    /** Acción de cabecera para eliminar la solicitud. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
