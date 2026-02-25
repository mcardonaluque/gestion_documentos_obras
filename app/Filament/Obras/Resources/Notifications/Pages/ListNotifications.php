<?php

namespace App\Filament\Obras\Resources\Notifications\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\Notifications\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
