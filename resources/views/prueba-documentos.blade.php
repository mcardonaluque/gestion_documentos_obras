@php
$record = $getRecord();
@endphp

@if ($record && $record->relationLoaded('documentos'))
    <div class="text-sm text-gray-700">
        Documentos: {{ $record->documentos->count() }}
    </div>
@else
    <div class="text-sm text-gray-400">Relación no cargada</div>
@endif