<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\DTOs\Templates\DefinicionPlantillaWordHeredada;
use App\Enums\ProcedimientoAlmacenadoObras;
use App\Services\Database\EjecutorProcedimientosAlmacenadosSqlServer;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Genera documentos registrados en RegistroModelos con datos de SQL Server.
 *
 * El servicio obtiene las variables desde RegistroDatos y cada caso de uso
 * declara de forma explícita qué columna devuelta por el procedimiento
 * alimenta cada variable Word.
 */
final class ImpresionPlantillaSqlServerService
{
    public function __construct(
        private readonly TechnicalAssistanceTemplateRepository $repository,
        private readonly EjecutorProcedimientosAlmacenadosSqlServer $ejecutorProcedimientos,
        private readonly WordTemplatePrintService $wordTemplatePrintService,
    ) {
    }

    /**
        * Genera el PDF de un documento heredado configurado en RegistroModelos.
        *
        * Las variables se obtienen exclusivamente de RegistroDatos para la clave
        * de la plantilla. El mapa permite adaptar una variable Word a una columna
        * de salida con un nombre diferente en el procedimiento almacenado.
        *
     * @param array<string, bool|float|int|string|null> $parametrosProcedimiento
     * @param array<string, string> $mapaVariablesColumnas Variable Word => columna devuelta por el procedimiento.
      * @param array<string, string> $valoresVariables Valores calculados con prioridad sobre las columnas.
        * @throws RuntimeException Cuando no existe la plantilla, faltan variables, el procedimiento no devuelve filas o no se encuentra el archivo.
     */
    public function descargarPdf(
        string $nombreDocumento,
        ProcedimientoAlmacenadoObras $procedimiento,
        array $parametrosProcedimiento,
        array $mapaVariablesColumnas,
        string $nombreDescarga,
        array $valoresVariables = [],
    ): BinaryFileResponse {
        $plantilla = $this->repository->findTemplateByName($nombreDocumento);
        $variables = $this->repository->getWordVariables($plantilla);
        $rows = $this->ejecutorProcedimientos->ejecutar($procedimiento, $parametrosProcedimiento);

        if ($variables === []) {
            throw new RuntimeException('La plantilla no tiene variables configuradas en RegistroDatos.');
        }

        if ($rows === []) {
            throw new RuntimeException('El procedimiento no ha devuelto datos para el documento solicitado.');
        }

        return $this->wordTemplatePrintService->downloadPdfFromTemplate(
            $this->resolveTemplatePath($plantilla),
            $this->buildValues($variables, $rows[0], $mapaVariablesColumnas, $valoresVariables),
            $nombreDescarga,
        );
    }

    /**
     * @param list<string> $variables
     * @param array<string, mixed> $data
     * @param array<string, string> $mapaVariablesColumnas
     * @param array<string, string> $valoresVariables
     * @return array<string, string>
     */
    private function buildValues(
        array $variables,
        array $data,
        array $mapaVariablesColumnas,
        array $valoresVariables,
    ): array
    {
        $values = [];

        foreach ($variables as $variable) {
            $column = $mapaVariablesColumnas[$variable] ?? $variable;
            $value = $data[$column] ?? null;
            $values[$variable] = is_scalar($value) || $value instanceof \Stringable ? (string) $value : '';
        }

        // Se añaden aunque no estén en RegistroDatos: la plantilla puede usar nombres que el catálogo no lista.
        foreach ($valoresVariables as $variable => $value) {
            $values[$variable] = $value;
        }

        return $values;
    }

    private function resolveTemplatePath(DefinicionPlantillaWordHeredada $template): string
    {
        $fileName = $template->name . '.dot';
        $paths = [storage_path('app/Modelos_Doc/' . $fileName)];

        if ($template->rutaDestinoModelo !== null) {
            array_unshift($paths, rtrim($template->rutaDestinoModelo, '\\/') . DIRECTORY_SEPARATOR . $fileName);
        }

        foreach ($paths as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        throw new RuntimeException(sprintf('No se ha encontrado el archivo de plantilla "%s".', $fileName));
    }
}
