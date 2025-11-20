<div class="p-4 bg-gray-50 rounded-lg shadow-inner">
    <h3 class="font-semibold text-gray-700 mb-2">Documentos del expediente {{ $record->expediente_id }}</h3>

    @if ($record->documentos->isEmpty())
        <p class="text-sm text-gray-500">No hay documentos asociados.</p>
    @else
        <table class="w-full text-sm border-t border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Descripción</th>
                    <th class="p-2 text-left">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->documentos as $doc)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $doc->id }}</td>
                        <td class="p-2">{{ $doc->descripcion }}</td>
                        <td class="p-2">{{ $doc->fecha ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
