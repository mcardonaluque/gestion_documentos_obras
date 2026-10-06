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
        );
    }
}
