{{-- resources/views/filament/tables/expedientes-con-documentos.blade.php --}}

@php
    // Obtenemos los registros de la tabla
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

    @if($records->count() > 0)
        @foreach ($records as $record)
            @php
                $documentos = $record->documentos;
            @endphp

            <!-- Fila del expediente -->
            <tr x-data="{ expanded: false }"
                class="bg-white border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
                @click="expanded = !expanded">
                
                <td class="w-8 px-4 py-3">
                    <x-heroicon-s-chevron-down 
                        x-bind:class="expanded ? 'rotate-180' : ''" 
                        class="w-4 h-4 text-gray-400 transition-transform" />
                </td>

                <td class="px-4 py-3">
                    <span class="font-semibold text-gray-900">{{ $record->expediente_id }}</span>
                </td>

                <td class="px-4 py-3">
                    <span class="text-gray-700">{{ $record->descripcion }}</span>
                </td>

                <td class="px-4 py-3">
                    <span class="text-gray-600">
                        {{ \Carbon\Carbon::parse($record->fechaincorporacion)->format('d/m/Y') }}
                    </span>
                </td>

                <td class="px-4 py-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                        {{ $documentos->count() }}
                    </span>
                </td>

                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2" @click.stop>
                        {{ $getTable()->getActions() }}
                    </div>
                </td>
            </tr>

            <!-- Fila expandida con documentos -->
            <template x-if="expanded">
                <tr>
                    <td colspan="6" class="p-0 bg-gray-50">
                        @include('documentos-expandidos', ['record' => $record, 'documentos' => $documentos])
                    </td>
                </tr>
            </template>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                No se encontraron expedientes
            </td>
        </tr>
    @endif
</x-filament-tables::table>