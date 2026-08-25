<?php

declare(strict_types=1);

namespace App\Filament\Resources\TramitadorApiOperaciones\Pages;

use App\Filament\Resources\TramitadorApiOperaciones\TramitadorApiOperacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTramitadorApiOperaciones extends ListRecords
{
    protected static string $resource = TramitadorApiOperacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
