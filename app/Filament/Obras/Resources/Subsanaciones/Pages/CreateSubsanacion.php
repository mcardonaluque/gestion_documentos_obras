<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Pages;

use App\Filament\Obras\Resources\Subsanaciones\SubsanacionResource;
use Filament\Resources\Pages\CreateRecord;

/** Alta de una solicitud de subsanación de documento. */
class CreateSubsanacion extends CreateRecord
{
    protected static string $resource = SubsanacionResource::class;
}
