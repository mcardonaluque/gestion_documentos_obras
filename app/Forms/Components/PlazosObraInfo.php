<?php

namespace App\Forms\Components;

use App\Models\PlazoObraActivo;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;

class PlazosObraInfo extends Fieldset
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(fn (): array => $this->getSchemaComponents());
    }

    protected function getSchemaComponents(): array
    {
        return [
            Section::make('Plazos activos de la obra')
                ->columnSpanFull()
                ->schema([
                    Placeholder::make('plazos_activos_resumen')
                        ->hiddenLabel()
                        ->content(fn () => new HtmlString($this->renderPlazosTable())),
                ]),
        ];
    }

    protected function renderPlazosTable(): string
    {
        $record = $this->getRecord();
        $expedienteId = (string) ($record?->expediente_id ?? '');

        if ($expedienteId === '') {
            return '<div class="p-3 text-sm text-gray-500 border border-gray-300 border-dashed rounded-xl">Sin expediente asociado.</div>';
        }

        $plazos = PlazoObraActivo::query()
            ->where('expediente_id', $expedienteId)
            ->orderByRaw("CASE fase WHEN 'proyecto_memoria' THEN 1 WHEN 'documentacion' THEN 2 WHEN 'ejecucion' THEN 3 WHEN 'justificacion' THEN 4 ELSE 5 END")
            ->orderByDesc('activo')
            ->get();

        if ($plazos->isEmpty()) {
            return '<div class="p-3 text-sm text-gray-500 border border-gray-300 border-dashed rounded-xl">No hay plazos registrados para este expediente.</div>';
        }

        $rows = '';

        foreach ($plazos as $plazo) {
            $fase = match ((string) $plazo->fase) {
                'proyecto_memoria' => 'Proyecto/Memoria',
                'documentacion' => 'Documentación',
                'ejecucion' => 'Ejecución',
                'justificacion' => 'Justificación',
                default => (string) $plazo->fase,
            };

            $estado = (bool) $plazo->activo ? 'Activo' : 'Inactivo';
            $estadoClass = (bool) $plazo->activo
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                : 'bg-gray-50 text-gray-600 border-gray-200';

            $rows .= sprintf(
                '<tr class="border-b last:border-b-0">'
                . '<td class="px-3 py-2 text-sm">%s</td>'
                . '<td class="px-3 py-2 text-sm">%s</td>'
                . '<td class="px-3 py-2 text-sm">%s</td>'
                . '<td class="px-3 py-2 text-sm text-right">%d</td>'
                . '<td class="px-3 py-2 text-sm text-right">%d</td>'
                . '<td class="px-3 py-2 text-sm">%s</td>'
                . '<td class="px-3 py-2 text-sm"><span class="inline-flex items-center px-2 py-0.5 rounded border %s">%s</span></td>'
                . '</tr>',
                e($fase),
                e(optional($plazo->fecha_inicio)->format('d/m/Y H:i') ?? '-'),
                e(optional($plazo->fecha_fin)->format('d/m/Y H:i') ?? '-'),
                (int) $plazo->dias_base,
                (int) $plazo->dias_prorroga_acumulados,
                e((string) $plazo->fuente_ultima_actualizacion),
                e($estadoClass),
                e($estado),
            );
        }

        return '<div class="overflow-x-auto rounded-xl border border-gray-200">'
            . '<table class="min-w-full divide-y divide-gray-200">'
            . '<thead class="bg-gray-50">'
            . '<tr>'
            . '<th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Fase</th>'
            . '<th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Inicio</th>'
            . '<th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Fin</th>'
            . '<th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">Días base</th>'
            . '<th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">Días prórroga</th>'
            . '<th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Fuente</th>'
            . '<th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Estado</th>'
            . '</tr>'
            . '</thead>'
            . '<tbody class="bg-white">'
            . $rows
            . '</tbody>'
            . '</table>'
            . '</div>';
    }
}
