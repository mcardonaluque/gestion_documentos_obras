<?php

namespace App\Filament\Resources\TecnicoObras\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TecnicoObras\TecnicoObraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTecnicoObras extends ListRecords
{
    protected static string $resource = TecnicoObraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
