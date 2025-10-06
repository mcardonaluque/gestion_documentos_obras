<?php

namespace App\Filament\Obras\Resources\ObrasCedidasResource\Pages;

use App\Filament\Obras\Resources\ObrasCedidasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObrasCedidas extends EditRecord
{
    protected static string $resource = ObrasCedidasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
