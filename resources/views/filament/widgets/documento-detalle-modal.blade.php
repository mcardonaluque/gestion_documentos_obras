<div class="space-y-6 text-sm">
    @livewire(\App\Filament\Widgets\DocumentoPdfViewerWidget::class, ['record' => $record, 'compact' => false], 'documento-pdf-viewer-' . $record->getKey())

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <div class="font-semibold text-gray-600">ID Documento</div>
            <div>{{ $record->idDocumento ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Código Documento</div>
            <div>{{ $record->cod_documento ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Tipo Documento</div>
            <div>{{ $record->tipodocumentos->nombre ?? '—' }}</div>
        </div>
    </div>

    <div>
        <div class="mb-1 font-semibold text-gray-600">Descripción</div>
        <div class="p-3 border rounded-lg bg-gray-50">
            {!! $record->descripcion ?: '—' !!}
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <div class="font-semibold text-gray-600">Fecha Incorporación</div>
            <div>{{ filled($record->fechaincorporacion) ? \Illuminate\Support\Carbon::parse($record->fechaincorporacion)->format('d/m/Y') : '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Fecha Help</div>
            <div>{{ filled($record->fechaHelp) ? \Illuminate\Support\Carbon::parse($record->fechaHelp)->format('d/m/Y') : '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Año ejecución</div>
            <div>{{ $record->ao_ejecucion ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Estado</div>
            <div>{{ $record->estados->nombre ?? $record->estado ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <div class="font-semibold text-gray-600">Referencia</div>
            <div>{{ $record->referencia ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Subreferencia</div>
            <div>{{ $record->subreferencia ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Nº Registro</div>
            <div>{{ $record->nregistro ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <div class="font-semibold text-gray-600">CSV</div>
            <div>{{ $record->csv ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Notificado</div>
            <div>{{ $record->notificado ? 'Sí' : 'No' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Expediente</div>
            <div>{{ $record->expediente_id ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <div class="font-semibold text-gray-600">Destino</div>
            <div>{{ $record->destinos->destino ?? '—' }}</div>
        </div>
        <div>
            <div class="font-semibold text-gray-600">Procedencia</div>
            <div>{{ $record->procedencias->destino ?? '—' }}</div>
        </div>
    </div>
</div>
