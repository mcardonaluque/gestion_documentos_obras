<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes;

use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\ListExpedientes;
use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\ViewExpediente;
// use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\CreateExpediente;
// use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\EditExpediente;
use App\Filament\Shared\Resources\Expedientes\ExpedienteResourceBase;

class ExpedienteResource extends ExpedienteResourceBase
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPages(): array
    {
        return [
            'index' => ListExpedientes::route('/'),
            'view' => ViewExpediente::route('/{record}'),
            // 'create' => CreateExpediente::route('/create'),
            // 'edit' => EditExpediente::route('/{record}/edit'),
        ];
    }
}
