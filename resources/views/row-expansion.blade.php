{{ json_encode($getLivewire()->expandedRecords) }}
@php
$record = $getRecord();
$livewire = $getLivewire();
$isExpanded = in_array($record->getKey(), $livewire->expandedRecords ?? []);
@endphp

<div class="w-full">
    {{-- Contenido normal visible siempre --}}
    <div class="text-sm text-gray-800 font-medium">
        {{ $record->expediente_id }} — {{ $record->nombre_obra }}
    </div>

    {{-- Contenido expandido --}}
    @if($isExpanded)
        <div class="mt-3 bg-gray-50 border-t border-gray-200 p-3 rounded-md shadow-inner">
            <div class="text-xs text-gray-500 mb-2">📁 Documentos del expediente</div>

            @if($record->documentos->isEmpty())
                <div class="text-gray-400 text-sm">No hay documentos.</div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th>Descripción</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($record->documentos as $doc)
                            <tr class="border-b hover:bg-gray-100">
                                <td>{{ $doc->descripcion }}</td>
                                <td>{{ $doc->tipo ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
</div>
