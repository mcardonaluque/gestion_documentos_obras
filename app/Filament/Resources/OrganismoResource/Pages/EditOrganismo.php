<?php

namespace App\Filament\Resources\OrganismoResource\Pages;

use App\Filament\Resources\OrganismoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganismo extends EditRecord
{
    protected static string $resource = OrganismoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
