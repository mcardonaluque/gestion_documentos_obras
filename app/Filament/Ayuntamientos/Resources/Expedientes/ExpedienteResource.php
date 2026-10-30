<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes;

use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\ListExpedientes;
use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\ViewExpediente;
// use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\CreateExpediente;
// use App\Filament\Ayuntamientos\Resources\Expedientes\Pages\EditExpediente;
use App\Filament\Shared\Resources\Expedientes\ExpedienteResourceBase;

/**
 * Recurso de expedientes del panel de ayuntamientos.
 *
 * Hereda de ExpedienteResourceBase (modelo App\Models\Expediente, tenant
 * «team»). Solo registra las páginas de listado y vista: los ayuntamientos
 * consultan sus expedientes y suben documentación, sin crear ni editar.
 */
class ExpedienteResource extends ExpedienteResourceBase
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    /** Declara las páginas y rutas del recurso. */
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
