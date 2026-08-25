<?php

declare(strict_types=1);

namespace App\Filament\Resources\TramitadorApiOperaciones\Pages;

use App\Filament\Resources\TramitadorApiOperaciones\TramitadorApiOperacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTramitadorApiOperacion extends EditRecord
{
    protected static string $resource = TramitadorApiOperacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
