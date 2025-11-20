<div x-data="{ open: false }" class="w-full">
    <button @click="open = !open" class="flex items-center gap-1 text-primary-600 hover:underline">
        <x-heroicon-o-folder-open class="w-4 h-4" />
        <span x-show="!open">Ver documentos</span>
        <span x-show="open">Ocultar documentos</span>
    </button>

    <div x-show="open" x-transition class="absolute left-0 top-full mt-2 w-[calc(100vw-16rem)] z-50 bg-white shadow-xl border border-gray-200 rounded-lg p-4">
        @php
            $docs = $getRecord()->documentos ?? collect();
        @endphp

        @if($docs->isEmpty())
            <p class="text-sm text-gray-500">No hay documentos asociados.</p>
        @else
            <table class="w-full text-sm text-left border-gray-200 rounded-md">
                <thead>
                    <tr class="border-b">
                        <th class="py-1 px-2">Código</th>
                        <th class="py-1 px-2">Descripción</th>
                        <th class="py-1 px-2">Fecha</th>
                        <th class="py-1 px-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docs as $doc)
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
