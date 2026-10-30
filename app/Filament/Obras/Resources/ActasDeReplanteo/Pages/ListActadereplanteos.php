<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo\Pages;

use App\Filament\Obras\Resources\ActasDeReplanteo\ActasdereplanteoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de actas de replanteo (sin lógica propia). */
class ListActadereplanteos extends ListRecords
{
    protected static string $resource = ActasdereplanteoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
