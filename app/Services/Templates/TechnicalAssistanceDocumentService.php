<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\Enums\ProcedimientoAlmacenadoObras;
use App\Models\DatosDeInicioDeObras;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Configura la generación de la petición de ayuda técnica.
 *
 * Esta clase no consulta tablas ni ejecuta SQL directamente: declara el
 * documento, procedimiento, parámetros de la obra y equivalencias manuales
 * entre variables Word y columnas de salida.
 */
final class TechnicalAssistanceDocumentService
{
    private const TEMPLATE_NAME = 'Petición Ayuda Técnica';

    /** @var array<string, string> */
    private const LEGACY_VARIABLE_COLUMNS = [
        'WD_DEFIPLAN' => 'denominacion_plan',
        'WD_DEFPLAN' => 'denominacion_plan',
        'WD_LOCALIDAD' => 'nombre_municipio',
        'WD_IMPORTEAPROBADO' => 'importe_aprobado',
        'WD_FORMAEJE' => 'DEN_CONTRATA',
    ];

    public function __construct(
        private readonly ImpresionPlantillaSqlServerService $impresionPlantilla,
    ) {
    }

    /**
     * Genera el PDF de petición de ayuda técnica para una obra concreta.
     */
    public function downloadPdf(DatosDeInicioDeObras $obra): BinaryFileResponse
    {
        return $this->impresionPlantilla->descargarPdf(
            self::TEMPLATE_NAME,
            ProcedimientoAlmacenadoObras::DatosListados,
            [
                'Plan' => (string) $obra->Codigo_Plan,
                'Num' => (int) $obra->numero_obra,
                'SubRef' => (int) $obra->subreferencia,
                'AoEje' => (int) $obra->ao_ejecucion,
            ],
            self::LEGACY_VARIABLE_COLUMNS,
            'peticion-ayuda-tecnica',
            $this->buildComputedValues($obra),
        );
    }

    /**
     * Variables que el procedimiento no devuelve y se calculan desde la obra.
     *
     * @return array<string, string>
     */
    private function buildComputedValues(DatosDeInicioDeObras $obra): array
    {
        $referencia = sprintf(
            '%s-%s-%s/%s',
            trim((string) $obra->Codigo_Plan),
            $obra->numero_obra,
            $obra->subreferencia,
            $obra->ao_ejecucion,
        );

        $nombreObra = trim(implode(' ', array_filter([
            trim((string) $obra->nombre_obra1),
            trim((string) $obra->nombre_obra2),
            trim((string) $obra->nombre_obra3),
        ], static fn (string $part): bool => $part !== '')));

        return [
            'WD_REF' => $referencia,
            'WD_REFOBRA' => $referencia,
            'WD_NOMBREOBRA' => $nombreObra,
            'WD_OBRA' => $nombreObra,
            'WD_TIPOAYUDA' => $this->resolveTipoAyuda($obra),
            'WD_DIRECTECNICA' => $this->resolveDirectecnica($obra),
        ];
    }

    /** Misma regla que el código VB heredado. */
    private function resolveDirectecnica(DatosDeInicioDeObras $obra): string
    {
        $direccion = trim((string) $obra->ayudaTecnica?->departamento_direccion);
        $reservado = trim((string) $obra->planes?->codigo_depar_reservado);

        if ($reservado !== $direccion) {
            return trim((string) $obra->ayudaTecnica?->ayudaD?->DENOMINACION);
        }

        return $direccion === '' || $direccion === '0' ? 'Técnico Ajeno' : 'Técnico Contratado';
    }

    private function resolveTipoAyuda(DatosDeInicioDeObras $obra): string
    {
        $ayuda = $obra->ayudaTecnica;
        $tipos = [];

        if ($this->isYes($ayuda?->AyuTecRed)) {
            $tipos[] = 'redacción';
        }

        if ($this->isYes($ayuda?->AyuTecDir)) {
            $tipos[] = 'dirección';
        }

        return implode(' y ', $tipos);
    }

    private function isYes(mixed $value): bool
    {
        return in_array(mb_strtoupper(trim((string) $value)), ['SI', 'SÍ'], true);
    }
}
