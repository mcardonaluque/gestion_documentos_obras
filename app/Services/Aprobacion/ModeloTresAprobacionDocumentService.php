<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\ModeloTresAprobacion;
use App\Services\Templates\WordTemplatePrintService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ModeloTresAprobacionDocumentService
{
    public function __construct(
        private readonly AprobacionExpedienteDocumentService $expedienteDocumentService,
        private readonly WordTemplatePrintService $wordTemplatePrintService,
    ) {}

    public function download(ModeloTresAprobacion $modelo, bool $asPdf = false): BinaryFileResponse
    {
        $documentPath = $this->generate($modelo, $asPdf);
        $extension = $asPdf ? 'pdf' : 'docx';

        return response()->download(
            $documentPath,
            sprintf('modelo-%s-%s.%s', strtolower($modelo->modalidad_presupuesto === 'anticipado' ? '3B' : '3A'), $modelo->expediente_id, $extension),
        );
    }

    public function generate(ModeloTresAprobacion $modelo, bool $asPdf = false): string
    {
        if (trim($modelo->secretario_nombre) === '') {
            throw new RuntimeException('Debe informar el secretario antes de generar el Modelo 3.');
        }

        $modeloCodigo = $modelo->modalidad_presupuesto === 'anticipado' ? '3B' : '3A';
        $templatePath = Storage::disk('local')->path($this->templatePath($modeloCodigo));
        if (! is_file($templatePath)) {
            throw new RuntimeException(sprintf('No se encuentra la plantilla del Modelo %s de aprobación.', $modeloCodigo));
        }

        $processor = new TemplateProcessor($templatePath);
        $processor->setValues([
            'ANUALIDAD' => (string) $modelo->anualidad,
            'SECRETARIO_NOMBRE' => $modelo->secretario_nombre,
            'CATEGORIA_SECRETARIO' => $modelo->categoria_secretario ?? '',
            'ENTIDAD_LOCAL' => $modelo->entidad_local,
            'IMPORTE_APROBADO_AYUNTAMIENTO' => number_format(
                (float) $modelo->importe_aprobado_ayuntamiento,
                2,
                ',',
                '.',
            ),
            'PARTIDA_PRESUPUESTARIA' => $modelo->partida_presupuestaria ?? '',
            'ACTUACION' => $modelo->nombre_obra ?? '',
        ]);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'modelo-tres-');
        if ($temporaryPath === false) {
            throw new RuntimeException('No se ha podido crear el archivo temporal del Modelo 3.');
        }

        $outputPath = $temporaryPath.'.docx';

        try {
            $processor->saveAs($outputPath);
            $generatedPath = $asPdf
                ? $this->wordTemplatePrintService->convertDocxToPdf($outputPath)
                : $outputPath;
            $documentPath = $this->expedienteDocumentService->persist($modelo, $generatedPath, $modeloCodigo, 11);
        } finally {
            @unlink($temporaryPath);
            @unlink($outputPath);
            @unlink($temporaryPath.'.pdf');
        }

        return $documentPath;
    }

    private function templatePath(string $modeloCodigo): string
    {
        $path = config(sprintf('documentos_aprobacion.modelo_tres.%s_template_path', strtolower($modeloCodigo)));
        if (! is_string($path) || trim($path) === '') {
            throw new RuntimeException(sprintf('No se ha configurado la plantilla del Modelo %s de aprobación.', $modeloCodigo));
        }

        return $path;
    }
}
