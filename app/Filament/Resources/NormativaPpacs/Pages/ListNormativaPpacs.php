<?php

declare(strict_types=1);

namespace App\Filament\Resources\NormativaPpacs\Pages;

use App\Filament\Resources\NormativaPpacs\NormativaPpacResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNormativaPpacs extends ListRecords
{
    protected static string $resource = NormativaPpacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
