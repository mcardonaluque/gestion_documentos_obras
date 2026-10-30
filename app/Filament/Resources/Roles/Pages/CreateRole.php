<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de RoleResource (panel de administración). */
class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
}
