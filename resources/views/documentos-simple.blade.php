@php
    $documentos = $getRecord()->documentos;
@endphp

<div class="border-t border-gray-200 bg-gray-50">
    <div class="p-3">
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <span class="text-sm font-medium text-gray-700">
                    📄 Documentos ({{ $documentos->count() }})
                </span>
                <x-heroicon-s-chevron-down class="w-4 h-4 text-gray-500 group-open:rotate-180 transition-transform" />
            </summary>
            
            <div class="mt-3 space-y-2">
                @foreach($documentos as $documento)
                    <div class="flex items-center justify-between p-2 bg-white border rounded-lg">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">
                                {{ $documento->descripcion }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $documento->cod_documento }} • 
                                {{ \Carbon\Carbon::parse($documento->fechaincorporacion)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($documento->estado === 'aprobado') bg-green-100 text-green-800
                                @elseif($documento->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($documento->estado === 'rechazado') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($documento->estado) }}
                            </span>
                            @if($documento->archivo_path)
                                <a href="{{ Storage::url($documento->path) }}" 
                                   target="_blank"
                                   class="p-1 text-blue-600 hover:text-blue-800">
                                    <x-heroicon-s-arrow-down-tray class="w-4 h-4" />
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </details>
    </div>
</div>