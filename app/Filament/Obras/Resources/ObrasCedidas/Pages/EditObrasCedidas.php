<?php

namespace App\Filament\Obras\Resources\ObrasCedidas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\ObrasCedidas\ObrasCedidasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObrasCedidas extends EditRecord
{
    protected static string $resource = ObrasCedidasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
