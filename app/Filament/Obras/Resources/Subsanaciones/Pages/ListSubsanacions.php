<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Pages;

use App\Filament\Obras\Resources\Subsanaciones\SubsanacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Listado de solicitudes de subsanación de documentos. */
class ListSubsanacions extends ListRecords
{
    protected static string $resource = SubsanacionResource::class;

    /** Acción de cabecera para registrar una nueva solicitud. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
