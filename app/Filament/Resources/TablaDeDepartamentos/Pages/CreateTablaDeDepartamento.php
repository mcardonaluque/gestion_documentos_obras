<?php

namespace App\Filament\Resources\TablaDeDepartamentos\Pages;

use App\Filament\Resources\TablaDeDepartamentos\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TablaDeDepartamentoResource (panel de administración). */
class CreateTablaDeDepartamento extends CreateRecord
{
    protected static string $resource = TablaDeDepartamentoResource::class;
}
