{{-- resources/views/filament/tables/expedientes-con-documentos.blade.php --}}

@php
    use Illuminate\Support\Facades\Storage;
    $records = $this->getTableRecords();
@endphp

<x-filament-tables::table>
    <x-slot name="header">
        <tr>
            <th class="w-8 px-4 py-3"></th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Expediente</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Descripción</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha Inc.</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nº Docs</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Acciones</th>
        </tr>
    </x-slot>

    @foreach($records as $record)
        @php
            $documentos = $record->documentos;
        @endphp

        <tr x-data="{ expanded: false }" 
            class="bg-white border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
            @click="expanded = !expanded">
            
            <td class="w-8 px-4 py-3">
                <x-heroicon-s-chevron-down 
                    x-bind:class="expanded ? 'rotate-180' : ''" 
                    class="w-4 h-4 text-gray-400 transition-transform" />
            </td>

            <td class="px-4 py-3 font-semibold text-gray-900">{{ $record->n_exp }}</td>
            <td class="px-4 py-3 text-gray-700">{{ $record->descripcion }}</td>
            <td class="px-4 py-3 text-gray-600">
                {{ \Carbon\Carbon::parse($record->fecha_incorporacion)->format('d/m/Y') }}
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                    {{ $documentos->count() }}
                </span>
            </td>
            <td class="px-4 py-3">
                <div class="flex space-x-1" @click.stop>
                    <!-- Solo mostramos el icono de subir documento -->
                    <button type="button" 
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'subir-documento-{{ $record->getKey() }}' } }))"
                            class="filament-icon-button flex items-center justify-center text-success-600 hover:text-success-700"
                            title="Subir documento">
                        <x-heroicon-o-paper-clip class="w-4 h-4" />
                    </button>
                    
                    <!-- Icono de editar -->
                    <a href="{{ route('filament.obras.resources.expedientes.edit', $record) }}"
                       class="filament-icon-button flex items-center justify-center text-primary-600 hover:text-primary-700"
                       title="Editar expediente">
                        <x-heroicon-o-pencil class="w-4 h-4" />
                    </a>
                </div>
            </td>
        </tr>

        <tr x-show="expanded" x-collapse>
            <td colspan="6" class="p-0 bg-gray-50">
                @if($documentos->count() > 0)
                    <div class="px-6 py-3 bg-gray-100 border-b">
                        <h4 class="text-sm font-medium text-gray-700">
                            Documentos del Expediente {{ $record->n_exp }} ({{ $documentos->count() }})
                        </h4>
                    </div>
                    <div class="px-6 py-4">
                        <table class="w-full text-sm text-left divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Código</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Referencia</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Archivo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($documentos as $documento)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2">
                                            <div class="font-medium text-gray-900">{{ $documento->descripcion }}</div>
                                            @if($documento->cod_plan)
                                                <div class="text-xs text-gray-500">Plan: {{ $documento->cod_plan }}</div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-gray-700">{{ $documento->cod_documento }}</td>
                                        <td class="px-3 py-2 text-gray-700">
                                            {{ \Carbon\Carbon::parse($documento->fechaincorporacion)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-3 py-2">
                                            @php
                                                $estadoClasses = [
                                                    1 => 'bg-green-100 text-green-800',
                                                    2 => 'bg-yellow-100 text-yellow-800', 
                                                    3 => 'bg-red-100 text-red-800'
                                                ];
                                                $estadoText = [
                                                    1 => 'Activo',
                                                    2 => 'Pendiente',
                                                    3 => 'Rechazado'
                                                ];
                                                $clase = $estadoClasses[$documento->estado] ?? 'bg-gray-100 text-gray-800';
                                                $texto = $estadoText[$documento->estado] ?? 'Desconocido';
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $clase }}">
                                                {{ $texto }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-gray-700">{{ $documento->referencia }}</td>
                                        <td class="px-3 py-2">
                                            @if($documento->archivo_path && Storage::exists($documento->archivo_path))
                                                <a href="{{ Storage::url($documento->archivo_path) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-800"
                                                   title="Descargar PDF">
                                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                                </a>
                                            @else
                                                <x-heroicon-o-exclamation-circle class="w-4 h-4 text-gray-400" title="Sin archivo" />
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center text-gray-500">
                        <x-heroicon-o-document-text class="w-8 h-8 mx-auto text-gray-400 mb-2" />
                        <p class="text-sm">No hay documentos en este expediente</p>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
</x-filament-tables::table>