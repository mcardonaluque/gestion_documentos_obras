<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\Enums\ProcedimientoAlmacenadoObras;
use App\Models\DatosDeInicioDeObras;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Configura la petición de acta de replanteo previo técnico municipal.
 */
final class PeticionActaReplanteoDocumentService
{
    private const TEMPLATE_NAME = 'Petición A.Replanteo previo Técnico Municipal';

    /** @var array<string, string> */
    private const VARIABLE_COLUMNS = [
        'WD_DEFIPLAN' => 'denominacion_plan',
        'WD_LOCALIDAD' => 'nombre_municipio',
        'WD_IMPORTEAPROBADO' => 'importe_aprobado',
        'WD_FORMAEJE' => 'DEN_CONTRATA',
    ];

    public function __construct(
        private readonly ImpresionPlantillaSqlServerService $impresionPlantilla,
    ) {
    }

    /**
     * Genera el PDF usando la fecha presente en el formulario, aunque aún no se haya persistido.
     */
    public function downloadPdf(DatosDeInicioDeObras $obra, ?string $fechaPeticion): BinaryFileResponse
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
            self::VARIABLE_COLUMNS,
            'peticion-acta-replanteo',
            [
                'WD_AOOBRA' => (string) $obra->ao_ejecucion,
                'WD_FECHA' => $fechaPeticion ?? '',
                'WD_NOMBREOBRA' => $this->buildWorkName($obra),
                'WD_REFOBRA' => $this->buildReference($obra),
            ],
        );
    }

    private function buildReference(DatosDeInicioDeObras $obra): string
    {
        return implode('-', [
            trim((string) $obra->Codigo_Plan),
            (string) $obra->numero_obra,
            (string) $obra->subreferencia,
            (string) $obra->ao_ejecucion,
        ]);
    }

    private function buildWorkName(DatosDeInicioDeObras $obra): string
    {
        $parts = [
            trim((string) $obra->nombre_obra1),
            trim((string) $obra->nombre_obra2),
            trim((string) $obra->nombre_obra3),
        ];

        return implode(' ', array_filter($parts, static fn (string $value): bool => $value !== ''));
    }
}
