<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlazoObraActivos\Pages;

use App\Filament\Resources\PlazoObraActivos\PlazoObraActivoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlazoObraActivo extends EditRecord
{
    protected static string $resource = PlazoObraActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
