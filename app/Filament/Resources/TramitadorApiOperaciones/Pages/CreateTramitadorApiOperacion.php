<?php

declare(strict_types=1);

namespace App\Filament\Resources\TramitadorApiOperaciones\Pages;

use App\Filament\Resources\TramitadorApiOperaciones\TramitadorApiOperacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTramitadorApiOperacion extends CreateRecord
{
    protected static string $resource = TramitadorApiOperacionResource::class;
}
