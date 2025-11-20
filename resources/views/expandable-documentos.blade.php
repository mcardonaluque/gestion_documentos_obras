<div class="p-4 bg-gray-50 rounded-lg">
    <h3 class="text-lg font-medium mb-4">Documentos del Expediente: {{ $record->expediente_id }}</h3>
    
    @if($record->documentos->count() > 0)
        <div class="space-y-2">
            @foreach($record->documentos as $documento)
                <div class="p-3 bg-white rounded border">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <strong>Código:</strong> {{ $documento->cod_documento }}
                        </div>
                        <div>
                            <strong>Tipo:</strong> {{ $documento->tipodocumentos->descripcion ?? 'N/A' }}
                        </div>
                        <div>
                            <strong>Fecha:</strong> {{ $documento->fechaincorporacion ? \Carbon\Carbon::parse($documento->fechaincorporacion)->format('d/m/Y') : 'N/A' }}
                        </div>
                        <div>
                            <strong>Estado:</strong> {{ $documento->estado }}
                        </div>
                        @if($documento->descripcion)
                        <div class="md:col-span-4">
                            <strong>Descripción:</strong> {{ $documento->descripcion }}
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No hay documentos asociados a este expediente.</p>
    @endif
</div>