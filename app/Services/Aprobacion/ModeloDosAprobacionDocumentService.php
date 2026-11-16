<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\ModeloDosAprobacion;
use App\Services\Templates\WordTemplatePrintService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ModeloDosAprobacionDocumentService
{
    public function __construct(
        private readonly AprobacionExpedienteDocumentService $expedienteDocumentService,
        private readonly WordTemplatePrintService $wordTemplatePrintService,
    ) {}

    public function download(ModeloDosAprobacion $modelo, bool $asPdf = false): BinaryFileResponse
    {
        $documentPath = $this->generate($modelo, $asPdf);

        return response()->download(
            $documentPath,
            sprintf('modelo-2-%s.%s', $modelo->expediente_id, $asPdf ? 'pdf' : 'docx'),
        );
    }

    public function generate(ModeloDosAprobacion $modelo, bool $asPdf = false): string
    {
        if (trim($modelo->secretario_nombre) === '') {
            throw new RuntimeException('Debe informar el secretario antes de generar el Modelo 2.');
        }

        $templatePath = Storage::disk('local')->path($this->templatePath());
        if (! is_file($templatePath)) {
            throw new RuntimeException('No se encuentra la plantilla del Modelo 2 de aprobación.');
        }

        $processor = new TemplateProcessor($templatePath);
        $processor->setValues([
            'PROGRAMA' => $modelo->codigo_plan ?? '',
            'ANUALIDAD' => (string) $modelo->ao_ejecucion,
            'SECRETARIO_NOMBRE' => $modelo->secretario_nombre,
            'ENTIDAD_LOCAL' => $modelo->entidad_local,
            'ACTUACION' => $modelo->nombre_obra ?? '',
            'NORMATIVA_CUMPLIDA' => $modelo->normativa_cumplida ? 'X' : '',
            'TERRENOS_DISPONIBLES' => $modelo->terrenos_disponibles ? 'X' : '',
            'TERRENOS_NO_DISPONIBLES' => $modelo->terrenos_disponibles ? '' : 'X',
            'AUTORIZACIONES_NO_REQUIERE' => $modelo->autorizaciones === 'no_requiere' ? 'X' : '',
            'AUTORIZACIONES_CUENTAN' => $modelo->autorizaciones === 'cuentan' ? 'X' : '',
            'AUTORIZACIONES_TRAMITE' => $modelo->autorizaciones === 'tramite' ? 'X' : '',
        ]);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'modelo-dos-');
        if ($temporaryPath === false) {
            throw new RuntimeException('No se ha podido crear el archivo temporal del Modelo 2.');
        }

        $outputPath = $temporaryPath.'.docx';

        try {
            $processor->saveAs($outputPath);
            $generatedPath = $asPdf
                ? $this->wordTemplatePrintService->convertDocxToPdf($outputPath)
                : $outputPath;

            return $this->expedienteDocumentService->persist($modelo, $generatedPath, 2, 10);
        } finally {
            @unlink($temporaryPath);
            @unlink($outputPath);
            @unlink($temporaryPath.'.pdf');
        }
    }

    private function templatePath(): string
    {
        $path = config('documentos_aprobacion.modelo_dos.template_path');
        if (! is_string($path) || trim($path) === '') {
            throw new RuntimeException('No se ha configurado la plantilla del Modelo 2 de aprobación.');
        }

        return $path;
    }
}
