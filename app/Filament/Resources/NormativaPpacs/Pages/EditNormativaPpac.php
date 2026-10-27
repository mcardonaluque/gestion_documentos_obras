<?php

declare(strict_types=1);

namespace App\Filament\Resources\NormativaPpacs\Pages;

use App\Filament\Resources\NormativaPpacs\NormativaPpacResource;
use App\Filament\Support\AsignarNormativaAPlanesAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNormativaPpac extends EditRecord
{
    protected static string $resource = NormativaPpacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AsignarNormativaAPlanesAction::make(),
            DeleteAction::make(),
        ];
    }
}
