<?php

declare(strict_types=1);

namespace App\Filament\Resources\NormativaPpacs\Pages;

use App\Filament\Resources\NormativaPpacs\NormativaPpacResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNormativaPpac extends CreateRecord
{
    protected static string $resource = NormativaPpacResource::class;
}
