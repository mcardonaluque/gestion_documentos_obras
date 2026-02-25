<div class="p-4">
    <h2 class="text-lg font-semibold mb-4">📂 Documentos del expediente {{ $expediente->expediente_id }}</h2>

    @if($documentos->isEmpty())
        <p class="text-sm text-gray-500">No hay documentos para este expediente.</p>
    @else
        <div class="space-y-2">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left">Código</th>
                        <th class="py-2 text-left">Descripción</th>
                        <th class="py-2 text-left">Fecha</th>
                        <th class="py-2 text-left">Estado</th>
                        <th class="py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documentos as $doc)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">{{ $doc->cod_documento }}</td>
                            <td class="py-2">{{ $doc->descripcion }}</td>
                            <td class="py-2">
                                {{ $doc->fechaincorporacion
                                    ? \Carbon\Carbon::parse($doc->fechaincorporacion)->format('d/m/Y')
                                    : '-' }}
                            </td>
                            <td class="py-2">{{ $doc->estado }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
