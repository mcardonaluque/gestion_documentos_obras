@php
    $grupos = [
        'proyecto' => ['label' => 'Documentos de Proyecto', 'docs' => []],
        'aprobacion' => ['label' => 'Documentos de Aprobación', 'docs' => []],
        'cesion' => ['label' => 'Documentos de Cesión', 'docs' => []],
        'contratacion' => ['label' => 'Documentos de contratación', 'docs' => []],
        'ejecucion' => ['label' => 'Documentos de Ejecución', 'docs' => []],
        'justificacion' => ['label' => 'Documentos de Justificación', 'docs' => []],
    ];

    foreach ($documentos as $doc) {
        $fase = strtolower((string) ($doc->tipodocumentos?->fase_doc ?? ''));

        if (str_contains($fase, 'justific')) {
            $grupos['justificacion']['docs'][] = $doc;
        } elseif (str_contains($fase, 'ejecuc')) {
            $grupos['ejecucion']['docs'][] = $doc;
        } elseif (str_contains($fase, 'contrat')) {
            $grupos['contratacion']['docs'][] = $doc;
        } elseif (str_contains($fase, 'cesi')) {
            $grupos['cesion']['docs'][] = $doc;
        } elseif (str_contains($fase, 'aproba')) {
            $grupos['aprobacion']['docs'][] = $doc;
        } else {
            $grupos['proyecto']['docs'][] = $doc;
        }
    }
@endphp

<div class="p-4">
    <h2 class="mb-4 text-lg font-semibold">📂 Documentos del expediente {{ $expediente->expediente_id }}</h2>

    @if($documentos->isEmpty())
        <p class="text-sm text-gray-500">No hay documentos para este expediente.</p>
    @else
        <div class="space-y-4">
            @foreach($grupos as $grupo)
                @if(!empty($grupo['docs']))
                    <div class="border border-gray-200 rounded-lg">
                        <div class="px-3 py-2 text-sm font-semibold bg-gray-50">{{ $grupo['label'] }}</div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="px-3 py-2 text-left">Código</th>
                                        <th class="px-3 py-2 text-left">Descripción</th>
                                        <th class="px-3 py-2 text-left">Fecha</th>
                                        <th class="px-3 py-2 text-left">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grupo['docs'] as $doc)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-3 py-2">{{ $doc->cod_documento }}</td>
                                            <td class="px-3 py-2">{{ $doc->descripcion }}</td>
                                            <td class="px-3 py-2">
                                                {{ $doc->fechaincorporacion
                                                    ? \Carbon\Carbon::parse($doc->fechaincorporacion)->format('d/m/Y')
                                                    : '-' }}
                                            </td>
                                            <td class="px-3 py-2">{{ $doc->estado }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
