<?php

namespace App\Filament\Obras\Resources\Documentoexpedientes;

use App\Filament\Obras\Resources\Documentoexpedientes\Pages\ListDocumentoexpedientes;
use App\Filament\Obras\Resources\Documentoexpedientes\Pages\CreateDocumentoexpediente;
use App\Filament\Obras\Resources\Documentoexpedientes\Pages\EditDocumentoexpediente;
use App\Filament\Obras\Resources\Documentoexpedientes\Pages\ViewDocumentoexpediente;
use App\Filament\Shared\Resources\Documentoexpedientes\DocumentoexpedienteResourceBase;

class DocumentoexpedienteResource extends DocumentoexpedienteResourceBase
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Documentos del Expediente';
    protected static ?string $modelLabel = 'Documento del Expediente';
    protected static ?string $pluralModelLabel = 'Documentos del Expediente';

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
