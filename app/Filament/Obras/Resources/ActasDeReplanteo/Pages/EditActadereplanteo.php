<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo\Pages;

use App\Filament\Obras\Resources\ActasDeReplanteo\ActasdereplanteoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página estándar de edición de actas de replanteo (sin lógica propia). */
class EditActadereplanteo extends EditRecord
{
    protected static string $resource = ActasdereplanteoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
