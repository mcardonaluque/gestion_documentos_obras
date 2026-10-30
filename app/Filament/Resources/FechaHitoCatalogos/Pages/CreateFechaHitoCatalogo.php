<?php

namespace App\Filament\Resources\FechaHitoCatalogos\Pages;

use App\Filament\Resources\FechaHitoCatalogos\FechaHitoCatalogoResource;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de FechaHitoCatalogoResource (panel de administración). */
class CreateFechaHitoCatalogo extends CreateRecord
{
    protected static string $resource = FechaHitoCatalogoResource::class;
}
