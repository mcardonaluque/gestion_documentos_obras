<?php

namespace App\Filament\Ayuntamientos\Resources\Documentoexpedientes;

use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages\CreateDocumentoexpediente;
use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages\EditDocumentoexpediente;
use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages\ListDocumentoexpedientes;
use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages\ViewDocumentoexpediente;
use App\Filament\Shared\Resources\Documentoexpedientes\DocumentoexpedienteResourceBase;

/**
 * Recurso de documentos de expediente del panel de ayuntamientos.
 *
 * Hereda formulario, tabla y consulta de DocumentoexpedienteResourceBase
 * (modelo App\Models\DocumentoExpediente, tenant «team») y registra las
 * páginas de listado, creación, edición y vista del panel «ayuntamientos».
 */
class DocumentoexpedienteResource extends DocumentoexpedienteResourceBase
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Documentos del Expediente';

    /** Declara las páginas y rutas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => ListDocumentoexpedientes::route('/'),
            'create' => CreateDocumentoexpediente::route('/create'),
            'edit' => EditDocumentoexpediente::route('/{record}/edit'),
            'view' => ViewDocumentoexpediente::route('/{record}'),
        ];
    }
}
