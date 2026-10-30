<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de PermissionResource (panel de administración). */
class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
