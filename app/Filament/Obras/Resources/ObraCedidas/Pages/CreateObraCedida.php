<?php

namespace App\Filament\Obras\Resources\ObraCedidas\Pages;

use App\Filament\Obras\Resources\ObraCedidas\ObraCedidaResource;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de obras cedidas; valida fechas con NotifiesAndValidatesDates. */
class CreateObraCedida extends CreateRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = ObraCedidaResource::class;
}
