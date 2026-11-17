<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\SubsanacionesGenerales\Pages;

use App\Filament\Obras\Resources\SubsanacionesGenerales\SubsanacionGeneralResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Listado general de subsanaciones de documentos (panel Obras).
 */
class ListSubsanacionesGenerales extends ListRecords
{
    protected static string $resource = SubsanacionGeneralResource::class;
}
