@php
    /** @var \App\Models\DocumentoExpediente|null $record */
    $compact = $compact ?? false;
    $rutaArchivo = $record?->pdf_source;
    $tieneArchivo = $record?->hasPdfSource() ?? false;
    $previewUrl = $record?->pdf_preview_url;
    $iframeHeight = $compact ? 'h-[28rem]' : 'h-[75vh]';
@endphp

<div class="space-y-3">
    @if (! $record?->exists)
        <div class="rounded-lg border border-gray-300 bg-gray-50 p-4 text-sm text-gray-700">
            Guarda el documento para habilitar la vista previa del PDF.
        </div>
    @elseif ($tieneArchivo && filled($previewUrl))
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="font-semibold text-gray-600">Visor PDF</div>
                <div class="text-xs text-gray-500 break-all">{{ $rutaArchivo }}</div>
            </div>

            <a
                href="{{ $previewUrl }}"
                target="_blank"
                class="inline-flex items-center rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-500"
            >
                Abrir en nueva pestaña
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border bg-gray-100">
            <iframe
                src="{{ $previewUrl }}#toolbar=1&navpanes=0"
                class="{{ $iframeHeight }} w-full"
                title="Visor PDF"
            ></iframe>
        </div>
    @else
        <div class="rounded-lg border border-warning-300 bg-warning-50 p-4 text-sm text-warning-900">
            No hay una ruta o URL PDF válida informada para este documento.
        </div>
    @endif
</div>
