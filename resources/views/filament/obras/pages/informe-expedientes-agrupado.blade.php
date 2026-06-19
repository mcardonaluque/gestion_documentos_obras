<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section heading="Filtros del informe" description="Configura los criterios de agrupación y cálculo.">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                <div>
                    <label class="mb-1 block text-sm font-medium">Año desde</label>
                    <input
                        type="number"
                        min="2000"
                        max="2100"
                        wire:model="filtros.anio_desde"
                        class="fi-input block w-full rounded-lg border-gray-300"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Año hasta</label>
                    <input
                        type="number"
                        min="2000"
                        max="2100"
                        wire:model="filtros.anio_hasta"
                        class="fi-input block w-full rounded-lg border-gray-300"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Estado</label>
                    <select wire:model="filtros.cod_estado" class="fi-select-input block w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        @foreach ($this->getOpcionesEstado() as $codigo => $descripcion)
                            <option value="{{ $codigo }}">{{ $descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Municipio</label>
                    <select wire:model="filtros.team_id" class="fi-select-input block w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        @foreach ($this->getOpcionesMunicipio() as $teamId => $teamNombre)
                            <option value="{{ $teamId }}">{{ $teamNombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Agrupar por</label>
                    <select wire:model="filtros.agrupacion" class="fi-select-input block w-full rounded-lg border-gray-300">
                        @foreach (\App\Enums\InformeExpedientesAgrupacion::options() as $valor => $etiqueta)
                            <option value="{{ $valor }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                <x-filament::button color="primary" wire:click="aplicarFiltros">
                    Actualizar informe
                </x-filament::button>
                <x-filament::button color="gray" tag="a" href="{{ $this->getUrlImpresion() }}" target="_blank">
                    Abrir versión imprimible
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section heading="Resultado" description="Incluye subtotales por grupo y total general.">
            @if (! $resultado['tiene_resultados'])
                <div class="rounded-lg border border-dashed p-8 text-center text-gray-600">
                    No hay resultados para los filtros seleccionados.
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($resultado['grupos'] as $grupo)
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between bg-gray-50 px-4 py-3">
                                <h3 class="text-sm font-semibold">{{ $grupo['etiqueta'] }}</h3>
                                <div class="text-sm text-gray-700">
                                    Expedientes: <strong>{{ $grupo['total_expedientes'] }}</strong>
                                    | Importe aprobado: <strong>{{ number_format($grupo['total_importe_aprobado'], 2, ',', '.') }} €</strong>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold">Expediente</th>
                                        <th class="px-3 py-2 text-left font-semibold">Nombre obra</th>
                                        <th class="px-3 py-2 text-left font-semibold">Estado</th>
                                        <th class="px-3 py-2 text-left font-semibold">Municipio</th>
                                        <th class="px-3 py-2 text-right font-semibold">Año</th>
                                        <th class="px-3 py-2 text-right font-semibold">Importe aprobado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($grupo['filas'] as $fila)
                                        <tr>
                                            <td class="px-3 py-2">{{ $fila['expediente_id'] }}</td>
                                            <td class="px-3 py-2">{{ $fila['nombre_obra'] }}</td>
                                            <td class="px-3 py-2">{{ $fila['estado'] }}</td>
                                            <td class="px-3 py-2">{{ $fila['municipio'] }}</td>
                                            <td class="px-3 py-2 text-right">{{ $fila['anio_ejecucion'] }}</td>
                                            <td class="px-3 py-2 text-right">{{ number_format($fila['importe_aprobado'], 2, ',', '.') }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="5" class="px-3 py-2 text-right font-semibold">Subtotal {{ $grupo['etiqueta'] }}</th>
                                        <th class="px-3 py-2 text-right font-semibold">{{ number_format($grupo['total_importe_aprobado'], 2, ',', '.') }} €</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endforeach

                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                        <strong>Total general</strong>
                        | Expedientes: <strong>{{ $resultado['total_expedientes'] }}</strong>
                        | Importe aprobado: <strong>{{ number_format($resultado['total_importe_aprobado'], 2, ',', '.') }} €</strong>
                        | Generado: <strong>{{ $resultado['generado_en'] }}</strong>
                    </div>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
