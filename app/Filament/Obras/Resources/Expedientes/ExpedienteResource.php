<?php

namespace App\Filament\Obras\Resources\Expedientes;

use App\Filament\Obras\Resources\Expedientes\Pages\ListExpedientes;
use App\Filament\Obras\Resources\Expedientes\Pages\CreateExpediente;
use App\Filament\Obras\Resources\Expedientes\Pages\ViewExpediente;
// use App\Filament\Obras\Resources\Expedientes\Pages\EditExpediente;
use App\Filament\Shared\Resources\Expedientes\ExpedienteResourceBase;

class ExpedienteResource extends ExpedienteResourceBase
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPages(): array
    {
        return [
            'index' => ListExpedientes::route('/'),
            'create' => CreateExpediente::route('/create'),
            'view' => ViewExpediente::route('/{record}'),
            // 'edit' => EditExpediente::route('/{record}/edit'),
        ];
    }
}
