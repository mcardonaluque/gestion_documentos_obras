<div 
    x-data="{ open: false }" 
    class="w-full border-t border-gray-200 mt-1 pt-1"
>
    <!-- Fila principal clickable -->
    <div 
        @click="open = !open"
        class="cursor-pointer flex items-center justify-between py-2 hover:bg-gray-50"
    >
        <span class="font-medium text-sm text-gray-700">
            📁 {{ $getRecord()->expediente_id }}
        </span>

        <x-heroicon-o-chevron-down 
            x-show="!open" 
            class="w-4 h-4 text-gray-500"
        />
        <x-heroicon-o-chevron-up 
            x-show="open" 
            class="w-4 h-4 text-gray-500"
        />
    </div>

    <!-- Documentos expandibles -->
    <div 
        x-show="open" 
        x-transition 
        class="ml-4 mt-2 bg-gray-50 rounded-lg border border-gray-100 p-3"
    >
        @php
            $documentos = $getRecord()->documentos;
        @endphp

        @if ($documentos->isEmpty())
            <p class="text-sm text-gray-500">No hay documentos asociados.</p>
        @else
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-1 px-2">Código</th>
                        <th class="py-1 px-2">Descripción</th>
                        <th class="py-1 px-2">Fecha</th>
                        <th class="py-1 px-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $doc)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-1 px-2">{{ $doc->cod_documento }}</td>
                            <td class="py-1 px-2">{{ $doc->descripcion }}</td>
                            <td class="py-1 px-2">
                                {{ optional($doc->fechaincorporacion)->format('d/m/Y') }}
                            </td>
                            <td class="py-1 px-2">{{ $doc->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
