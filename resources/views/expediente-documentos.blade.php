<div x-data="{ open: false }" class="w-full">
    <button @click="open = !open" class="flex items-center gap-1 text-primary-600 hover:underline">
        <x-heroicon-o-folder-open class="shrink-0" style="width: 1rem !important; height: 1rem !important;" />
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
                        <th class="px-2 py-1">Código</th>
                        <th class="px-2 py-1">Descripción</th>
                        <th class="px-2 py-1">Fecha</th>
                        <th class="px-2 py-1">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docs as $doc)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-2 py-1">{{ $doc->cod_documento }}</td>
                            <td class="px-2 py-1">{{ $doc->descripcion }}</td>
                            <td class="px-2 py-1">
                                {{ optional($doc->fechaincorporacion)->format('d/m/Y') }}
                            </td>
                            <td class="px-2 py-1">{{ $doc->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
