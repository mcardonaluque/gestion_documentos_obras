<div class="space-y-4">
    <style>
        .importes-widget-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .importes-widget-table th,
        .importes-widget-table td {
            vertical-align: middle;
        }

        .importes-widget-table th:nth-child(3),
        .importes-widget-table td:nth-child(3),
        .importes-widget-table th:nth-child(5),
        .importes-widget-table td:nth-child(5),
        .importes-widget-table th:nth-child(7),
        .importes-widget-table td:nth-child(7),
        .importes-widget-table th:nth-child(9),
        .importes-widget-table td:nth-child(9),
        .importes-widget-table th:nth-child(11),
        .importes-widget-table td:nth-child(11) {
            padding-left: 1.25rem;
        }

        .importes-widget-table th:nth-child(2),
        .importes-widget-table td:nth-child(2),
        .importes-widget-table th:nth-child(4),
        .importes-widget-table td:nth-child(4),
        .importes-widget-table th:nth-child(6),
        .importes-widget-table td:nth-child(6),
        .importes-widget-table th:nth-child(8),
        .importes-widget-table td:nth-child(8),
        .importes-widget-table th:nth-child(10),
        .importes-widget-table td:nth-child(10) {
            padding-right: 0.75rem;
        }

        .importes-value-box {
            margin-left: auto;
            margin-right: -0.75rem;
        }

        .importes-number-input {
            appearance: textfield;
            -moz-appearance: textfield;
            font-variant-numeric: tabular-nums;
        }

        .importes-number-input::-webkit-outer-spin-button,
        .importes-number-input::-webkit-inner-spin-button {
            margin: 0;
            -webkit-appearance: none;
        }
    </style>

    @if (! $loaded)
        <div class="p-4 text-sm text-gray-600 bg-white border border-gray-300 border-dashed rounded-lg">
            Guarda el expediente para inicializar la gestion de importes.
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            <label class="block text-sm">
                <span class="block mb-1 font-medium text-gray-700">Fase</span>
                <select wire:model.live="stage" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                    @foreach ($stageOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block text-sm">
                <span class="block mb-1 font-medium text-gray-700">Importe aprobado</span>
                <input type="number" step="0.01" min="0" wire:model.live="master.approved_total" @disabled(! $canEditAprobado) class="w-full text-sm border-gray-300 rounded-md shadow-sm" />
            </label>

            <label class="block text-sm">
                <span class="block mb-1 font-medium text-gray-700">Importe a contratar</span>
                <input type="number" step="0.01" min="0" wire:model.live="master.contract_total" @disabled(! $canEditContratar) class="w-full text-sm border-gray-300 rounded-md shadow-sm" />
            </label>

            <label class="block text-sm">
                <span class="block mb-1 font-medium text-gray-700">Importe adjudicado</span>
                <input type="number" step="0.01" min="0" wire:model.live="master.awarded_total" @disabled(! $canEditAdjudicado) class="w-full text-sm border-gray-300 rounded-md shadow-sm" />
            </label>
        </div>

        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full text-sm divide-y divide-gray-200 table-fixed importes-widget-table min-w-420 tabular-nums">
                <colgroup>
                    <col class="w-32">
                    <col class="w-36">
                    <col class="w-40">
                    <col class="w-36">
                    <col class="w-40">
                    <col class="w-36">
                    <col class="w-40">
                    <col class="w-36">
                    <col class="w-40">
                    <col class="w-36">
                    <col class="w-40">
                </colgroup>
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left text-gray-600 whitespace-nowrap">
                            <div class="w-28">Organismo</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-30">% Aprobado</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-34">Importe aprobado</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-30">% Contratar</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-34">Importe contratar</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-30">% Adjudicado</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-34">Importe adjudicado</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-30">% Baja</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-34">Importe baja</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-30">% Ejecutado</div>
                        </th>
                        <th class="px-4 py-3 font-semibold text-right text-gray-600 whitespace-nowrap">
                            <div class="ml-auto text-right w-34">Importe ejecutado</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($rows as $index => $row)
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-900 align-middle whitespace-nowrap">
                                <div class="w-28">{{ $row['organismo'] }}</div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="w-24 ml-auto">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.approved_percent" @disabled(! $canEditAprobado) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="ml-auto w-30">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.approved_amount" @disabled(! $canEditAprobado) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="w-24 ml-auto">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.contract_percent" @disabled(! $canEditContratar) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="ml-auto w-30">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.contract_amount" @disabled(! $canEditContratar) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="w-24 ml-auto">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.awarded_percent" @disabled(! $canEditAdjudicado) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="ml-auto w-30">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.awarded_amount" @disabled(! $canEditAdjudicado) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="w-24 ml-auto text-right text-gray-700 tabular-nums">
                                    @if ((float) ($row['awarded_amount'] ?? 0) > 0)
                                        {{ number_format((float) ($row['drop_percent'] ?? 0), 2, ',', '.') }}%
                                    @else
                                        —
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="ml-auto text-right text-gray-700 w-30 tabular-nums">
                                    @if ((float) ($row['awarded_amount'] ?? 0) > 0)
                                        {{ number_format((float) ($row['drop_amount'] ?? 0), 2, ',', '.') }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="w-24 ml-auto">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.executed_percent" @disabled(! $canEditEjecutado) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right align-middle">
                                <div class="ml-auto w-30">
                                    <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.executed_amount" @disabled(! $canEditEjecutado) class="block w-full text-sm text-right border-gray-300 rounded-md shadow-sm importes-number-input" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 gap-3 p-3 text-sm border border-gray-200 rounded-lg bg-gray-50 md:grid-cols-2 xl:grid-cols-5">
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-md bg-white/70">
                <span class="font-semibold text-gray-700">Aprobado:</span>
                <span class="text-right tabular-nums">{{ number_format((float) ($master['approved_total'] ?? 0), 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-md bg-white/70">
                <span class="font-semibold text-gray-700">Contratar:</span>
                <span class="text-right tabular-nums">{{ number_format((float) ($master['contract_total'] ?? 0), 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-md bg-white/70">
                <span class="font-semibold text-gray-700">Adjudicado:</span>
                <span class="text-right tabular-nums">{{ number_format((float) ($master['awarded_total'] ?? 0), 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-md bg-white/70">
                <span class="font-semibold text-gray-700">Baja:</span>
                <span class="text-right tabular-nums">
                    @if ((float) ($master['awarded_total'] ?? 0) > 0)
                        {{ number_format((float) ($master['drop_total'] ?? 0), 2, ',', '.') }}
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-md bg-white/70">
                <span class="font-semibold text-gray-700">Ejecutado:</span>
                <span class="text-right tabular-nums">{{ number_format((float) ($master['executed_total'] ?? 0), 2, ',', '.') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2 p-3 text-xs bg-white border border-gray-200 rounded-lg lg:grid-cols-4">
            <div>
                <span class="font-semibold text-gray-700">% Aprobacion:</span>
                {{ number_format((float) ($percentageTotals['approved_percent_total'] ?? 0), 2, ',', '.') }}%
            </div>
            <div>
                <span class="font-semibold text-gray-700">% Contratacion:</span>
                {{ number_format((float) ($percentageTotals['contract_percent_total'] ?? 0), 2, ',', '.') }}%
            </div>
            <div>
                <span class="font-semibold text-gray-700">% Adjudicacion/Cesion:</span>
                {{ number_format((float) ($percentageTotals['awarded_percent_total'] ?? 0), 2, ',', '.') }}%
            </div>
            <div>
                <span class="font-semibold text-gray-700">% Ejecucion:</span>
                {{ number_format((float) ($percentageTotals['executed_percent_total'] ?? 0), 2, ',', '.') }}%
            </div>
        </div>

        @php
            $percentageMessages = collect($percentageErrors)->filter()->values();
        @endphp

        @if ($percentageMessages->isNotEmpty())
            <div class="px-3 py-2 text-xs border rounded-lg border-danger-200 bg-danger-50 text-danger-700">
                <ul class="pl-5 space-y-1 list-disc">
                    @foreach ($percentageMessages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-end">
            <button
                type="button"
                wire:click="save"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-500"
            >
                Guardar gestion de importes
            </button>
        </div>
    @endif
</div>
