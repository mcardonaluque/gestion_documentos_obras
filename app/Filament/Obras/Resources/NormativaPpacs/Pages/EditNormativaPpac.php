<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\NormativaPpacs\Pages;

use App\Filament\Obras\Resources\NormativaPpacs\NormativaPpacResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNormativaPpac extends EditRecord
{
    protected static string $resource = NormativaPpacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
