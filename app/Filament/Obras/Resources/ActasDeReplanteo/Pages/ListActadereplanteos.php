<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo\Pages;

use App\Filament\Obras\Resources\ActasDeReplanteo\ActadereplanteoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActadereplanteos extends ListRecords
{
    protected static string $resource = ActadereplanteoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
