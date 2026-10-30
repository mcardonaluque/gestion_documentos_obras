<?php

namespace App\Filament\Resources\OrganismoResource\Pages;

use App\Filament\Resources\OrganismoResource;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de OrganismoResource (panel de administración). */
class CreateOrganismo extends CreateRecord
{
    protected static string $resource = OrganismoResource::class;
}
