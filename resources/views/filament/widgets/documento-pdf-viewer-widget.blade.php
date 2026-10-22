@php
    /** @var \App\Models\DocumentoExpediente|null $record */
    $compact = $compact ?? false;
    $rutaArchivo = $record?->pdf_source;
    $tieneArchivo = $record?->hasPdfSource() ?? false;
    $previewUrl = $record?->pdf_preview_url;
    $downloadUrl = $downloadUrl ?? null;
    $refreshIteration = $refreshIteration ?? 0;
    $iframeHeight = $compact ? 'h-[28rem]' : 'h-[75vh]';
@endphp

<div class="space-y-3">
    @if (! $record?->exists)
        <div class="p-4 text-sm text-gray-700 border border-gray-300 rounded-lg bg-gray-50">
            Guarda el documento para habilitar la vista previa del PDF.
        </div>
    @elseif ($tieneArchivo && filled($previewUrl))
        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="font-semibold text-gray-600">Visor PDF</div>
                <div class="text-xs text-gray-500 break-all">{{ $rutaArchivo }}</div>
            </div>

            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    wire:click="validatePdfSource"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium border rounded-lg border-success-600 text-success-700 hover:bg-success-50"
                >
                    Validar ruta
                </button>

                <button
                    type="button"
                    wire:click="refreshViewer"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Recargar visor
                </button>

                <a
                    href="{{ $previewUrl }}"
                    target="_blank"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-500"
                >
                    Abrir
                </a>

                @if (filled($downloadUrl))
                    <a
                        href="{{ $downloadUrl }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-700"
                    >
                        Descargar
                    </a>
                @endif
            </div>
        </div>

        <div class="overflow-hidden bg-gray-100 border rounded-xl">
            <iframe
                src="{{ $previewUrl }}?v={{ $refreshIteration }}#toolbar=1&navpanes=0"
                class="{{ $iframeHeight }} w-full"
                title="Visor PDF"
            ></iframe>
        </div>
    @else
        <div class="p-4 text-sm border rounded-lg border-warning-300 bg-warning-50 text-warning-900">
            No hay una ruta o URL PDF válida informada para este documento.
        </div>
    @endif
</div>
