<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\PendienteContratacionObras\Pages;

use App\Filament\Obras\Resources\PendienteContratacionObras\PendienteContratacionObraResource;
use Filament\Resources\Pages\EditRecord;

final class EditPendienteContratacionObra extends EditRecord
{
    protected static string $resource = PendienteContratacionObraResource::class;
}
