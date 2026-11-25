<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion\Pages;

use App\Filament\Obras\Resources\ActasDeRecepcion\ActaderecepcionResource;
use Filament\Actions\DeleteAction;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Resources\Pages\EditRecord;

/** Página estándar de edición de actas de recepción (sin lógica propia). */
class EditActaderecepcion extends EditRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = ActaderecepcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
