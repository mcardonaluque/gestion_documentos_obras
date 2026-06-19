<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section
            heading="Inspeccion de fechas materializadas"
            description="Esta pantalla muestra exactamente las filas que el sincronizador ha guardado en expediente_fecha_hitos. Sirve para comprobar que hitos, fechas y origenes se han materializado como esperas."
        >
            <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                <div>
                    <label class="mb-1 block text-sm font-medium">Expediente</label>
                    <input
                        type="text"
                        wire:model="filtros.expediente_id"
                        class="fi-input block w-full rounded-lg border-gray-300"
                        placeholder="Ej. 2024-OB-001"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Hito</label>
                    <select wire:model="filtros.codigo_hito" class="fi-select-input block w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        @foreach ($this->getOpcionesHitos() as $codigo => $descripcion)
                            <option value="{{ $codigo }}">{{ $codigo }} - {{ $descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Tabla origen</label>
                    <select wire:model="filtros.tabla_origen" class="fi-select-input block w-full rounded-lg border-gray-300">
                        <option value="">Todas</option>
                        @foreach ($this->getOpcionesTablas() as $tabla => $etiqueta)
                            <option value="{{ $tabla }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Fecha desde</label>
                    <input
                        type="date"
                        wire:model="filtros.fecha_desde"
                        class="fi-input block w-full rounded-lg border-gray-300"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Fecha hasta</label>
                    <input
                        type="date"
                        wire:model="filtros.fecha_hasta"
                        class="fi-input block w-full rounded-lg border-gray-300"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Limite</label>
                    <input
                        type="number"
                        min="25"
                        max="500"
                        wire:model="filtros.limite"
                        class="fi-input block w-full rounded-lg border-gray-300"
                    />
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                <x-filament::button color="primary" wire:click="aplicarFiltros">
                    Actualizar vista
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section heading="Resultado" description="Cada fila representa un hito ya materializado y su procedencia real.">
            @if (! $resultado['tiene_resultados'])
                <div class="rounded-lg border border-dashed p-8 text-center text-gray-600">
                    No hay hitos materializados con esos filtros.
                </div>
            @else
                <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                    Total encontrados: <strong>{{ $resultado['total'] }}</strong>
                    | Mostrando hasta: <strong>{{ $filtros['limite'] }}</strong>
                    | Generado: <strong>{{ $resultado['generado_en'] }}</strong>
                </div>

                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold">Expediente</th>
                                <th class="px-3 py-2 text-left font-semibold">Codigo</th>
                                <th class="px-3 py-2 text-left font-semibold">Descripcion</th>
                                <th class="px-3 py-2 text-left font-semibold">Fecha</th>
                                <th class="px-3 py-2 text-left font-semibold">Tabla origen</th>
                                <th class="px-3 py-2 text-left font-semibold">Campo origen</th>
                                <th class="px-3 py-2 text-left font-semibold">Team</th>
                                <th class="px-3 py-2 text-left font-semibold">Actualizado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($resultado['filas'] as $fila)
                                <tr>
                                    <td class="px-3 py-2">{{ $fila['expediente_id'] }}</td>
                                    <td class="px-3 py-2">{{ $fila['codigo_hito'] }}</td>
                                    <td class="px-3 py-2">{{ $fila['descripcion_hito'] ?: 'Sin descripcion' }}</td>
                                    <td class="px-3 py-2">{{ $fila['fecha'] }}</td>
                                    <td class="px-3 py-2">{{ $fila['tabla_origen'] }}</td>
                                    <td class="px-3 py-2">{{ $fila['campo_origen'] }}</td>
                                    <td class="px-3 py-2">{{ $fila['team_nombre'] ?: '-' }}</td>
                                    <td class="px-3 py-2">{{ $fila['updated_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
