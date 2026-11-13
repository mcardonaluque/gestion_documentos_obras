<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\ModeloUnoAprobacion;
use App\Services\Templates\WordTemplatePrintService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ModeloUnoAprobacionDocumentService
{
    public function __construct(
        private readonly AprobacionExpedienteDocumentService $expedienteDocumentService,
        private readonly WordTemplatePrintService $wordTemplatePrintService,
    ) {}

    public function download(ModeloUnoAprobacion $modelo, bool $asPdf = false): BinaryFileResponse
    {
        if (trim($modelo->secretario_nombre) === '') {
            throw new RuntimeException('Debe informar el secretario antes de generar el Modelo 1 de aprobación.');
        }

        $templatePath = Storage::disk('local')->path($this->templatePath());
        if (! is_file($templatePath)) {
            throw new RuntimeException('No se encuentra la plantilla del Modelo 1 de aprobación.');
        }

        $processor = new TemplateProcessor($templatePath);
        $processor->setValues([
            'ANUALIDAD' => (string) $modelo->ao_ejecucion,
            'SECRETARIO_NOMBRE' => $modelo->secretario_nombre,
            'ENTIDAD_LOCAL' => $modelo->entidad_local,
            'ACTUACION' => (string) $modelo->nombre_obra,
            'FECHA_DOCUMENTO' => $modelo->fecha_documento->format('d/m/Y'),
            'COMPETENCIA_PROPIA' => $modelo->tipo_competencia === 'propia' ? 'X' : '',
            'COMPETENCIA_DELEGADA' => $modelo->tipo_competencia === 'delegada' ? 'X' : '',
            'COMPETENCIA_DISTINTA' => $modelo->tipo_competencia === 'distinta' ? 'X' : '',
        ]);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'modelo-uno-');
        if ($temporaryPath === false) {
            throw new RuntimeException('No se ha podido crear el archivo temporal del Modelo 1.');
        }

        $outputPath = $temporaryPath.'.docx';

        try {
            $processor->saveAs($outputPath);
            $generatedPath = $asPdf
                ? $this->wordTemplatePrintService->convertDocxToPdf($outputPath)
                : $outputPath;
            $documentPath = $this->expedienteDocumentService->persist($modelo, $generatedPath, 1, 9);
        } finally {
            @unlink($temporaryPath);
            @unlink($outputPath);
            @unlink($temporaryPath.'.pdf');
        }

        $extension = $asPdf ? 'pdf' : 'docx';

        return response()->download($documentPath, sprintf('modelo-1-%s.%s', $modelo->expediente_id, $extension));
    }

    private function templatePath(): string
    {
        $path = config('documentos_aprobacion.modelo_uno.template_path');
        if (! is_string($path) || trim($path) === '') {
            throw new RuntimeException('No se ha configurado la plantilla del Modelo 1 de aprobación.');
        }

        return $path;
    }
}
