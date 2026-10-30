<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de UserResource (panel de administración). */
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
