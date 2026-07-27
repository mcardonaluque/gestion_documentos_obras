<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlazoObraActivos\Pages;

use App\Filament\Resources\PlazoObraActivos\PlazoObraActivoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlazoObraActivo extends CreateRecord
{
    protected static string $resource = PlazoObraActivoResource::class;
}
