{{-- Limita la altura de las listas desplegables de los select para que tengan scroll propio --}}
<style>
    .fi-modal-window .fi-select-input-options-ctn {
        max-height: 16rem;
        overflow-y: auto;
    }
</style>

@if (filled($previewUrl ?? null))
    {{-- Ensancha el modal solo mientras hay un PDF que previsualizar --}}
    <style>
        .fi-modal-window:has(.pdf-temp-preview) {
            max-width: 96vw !important;
            width: 96vw !important;
        }

        .pdf-split:has(.pdf-temp-preview) {
            grid-template-columns: minmax(380px, 38%) minmax(0, 1fr) !important;
            align-items: stretch;
        }

        .pdf-split:has(.pdf-temp-preview) > * {
            min-width: 0;
        }
    </style>

    <div class="pdf-temp-preview" style="display: flex; flex-direction: column; height: 100%; min-height: 500px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
            <div style="font-weight: 600;">Vista previa del PDF (sin guardar)</div>
            <a
                href="{{ $previewUrl }}"
                target="_blank"
                class="inline-flex items-center rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-500"
            >
                Abrir en pestaña nueva
            </a>
        </div>

        <div style="position: relative; flex: 1; min-height: 450px; border: 1px solid #d1d5db; border-radius: 0.75rem; background: #f3f4f6; overflow: hidden;">
            <iframe
                src="{{ $previewUrl }}#toolbar=1&navpanes=0"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                title="Vista previa del PDF"
            ></iframe>
        </div>
    </div>
@endif
