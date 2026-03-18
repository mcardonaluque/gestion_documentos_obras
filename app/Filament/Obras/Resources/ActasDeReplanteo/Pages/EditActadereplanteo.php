<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo\Pages;

use App\Filament\Obras\Resources\ActasDeReplanteo\ActadereplanteoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActadereplanteo extends EditRecord
{
    protected static string $resource = ActadereplanteoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
