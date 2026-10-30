<?php

namespace App\Filament\Resources\AyudaTecnicas\Pages;

use App\Filament\Resources\AyudaTecnicas\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de AyudaTecnicaResource (panel de administración). */
class CreateAyudaTecnica extends CreateRecord
{
    protected static string $resource = AyudaTecnicaResource::class;
}
