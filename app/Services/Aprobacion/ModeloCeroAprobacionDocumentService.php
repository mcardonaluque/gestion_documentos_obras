<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\ModeloCeroAprobacion;
use App\Models\ModeloCeroDocumentoAprobacion;
use App\Services\Templates\WordTemplatePrintService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Rellena la plantilla DOCX del Anexo III, Modelo 0 de aprobación.
 *
 * La plantilla aporta el texto jurídico y su diseño; este servicio únicamente
 * resuelve las macros de cabecera y clona la fila de documentos declarada.
 */
final class ModeloCeroAprobacionDocumentService
{
    public function __construct(
        private readonly AprobacionExpedienteDocumentService $expedienteDocumentService,
        private readonly WordTemplatePrintService $wordTemplatePrintService,
    ) {}

    /**
     * Genera un DOCX descargable para el Modelo 0 de aprobación indicado.
     */
    public function download(ModeloCeroAprobacion $modelo, bool $asPdf = false): BinaryFileResponse
    {
        $modelo->loadMissing('documentos');
        $templatePath = Storage::disk('local')->path($this->templatePath());

        if (! is_file($templatePath)) {
            throw new RuntimeException(sprintf(
                'No se encuentra la plantilla del Modelo 0 de aprobación en "%s".',
                $this->templatePath(),
            ));
        }

        if ($modelo->documentos->isEmpty()) {
            throw new RuntimeException('Debe incluir al menos un documento antes de generar el Modelo 0 de aprobación.');
        }

        if (trim($modelo->secretario_nombre) === '') {
            throw new RuntimeException(
                'Debe informar el Secretario en TablaDeMunicipios antes de generar el Modelo 0 de aprobación.',
            );
        }

        $processor = new TemplateProcessor($templatePath);
        $this->fillHeader($processor, $modelo);
        $this->fillDocumentRows($processor, $modelo);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'modelo-cero-');

        if ($temporaryPath === false) {
            throw new RuntimeException('No se ha podido crear el archivo temporal del Modelo 0.');
        }

        $outputPath = $temporaryPath.'.docx';

        try {
            $processor->saveAs($outputPath);
            $generatedPath = $asPdf
                ? $this->wordTemplatePrintService->convertDocxToPdf($outputPath)
                : $outputPath;
            $documentPath = $this->expedienteDocumentService->persist($modelo, $generatedPath, 0, 7);
        } finally {
            @unlink($temporaryPath);
            @unlink($outputPath);
            @unlink($temporaryPath.'.pdf');
        }

        $extension = $asPdf ? 'pdf' : 'docx';

        return response()->download($documentPath, sprintf('modelo-0-%s.%s', $modelo->expediente_id, $extension));
    }

    private function fillHeader(TemplateProcessor $processor, ModeloCeroAprobacion $modelo): void
    {
        $processor->setValues([
            'ANUALIDAD' => (string) $modelo->anualidad,
            'SECRETARIO_NOMBRE' => $modelo->secretario_nombre,
            'ENTIDAD_LOCAL' => $modelo->entidad_local,
            'PROGRAMA_DENOMINACION' => (string) $modelo->programa_denominacion,
            'PROYECTO_ACTUACION' => (string) $modelo->proyecto_actuacion,
            'REFERENCIA_ACTUACION' => (string) $modelo->referencia_actuacion,
        ]);
    }

    private function fillDocumentRows(TemplateProcessor $processor, ModeloCeroAprobacion $modelo): void
    {
        $processor->cloneRow('LINEA_ID', $modelo->documentos->count());

        $modelo->documentos
            ->values()
            ->each(function (ModeloCeroDocumentoAprobacion $documento, int $index) use ($processor): void {
                $position = $index + 1;

                $processor->setValues([
                    "LINEA_ID#{$position}" => '',
                    "REFERENCIA#{$position}" => $documento->denominacion,
                    "CONTENIDO#{$position}" => (string) $documento->contenido,
                    "DENOMINACION#{$position}" => $documento->denominacion,
                    "CSV#{$position}" => $documento->csv,
                ]);
            });
    }

    private function templatePath(): string
    {
        $path = config('documentos_aprobacion.modelo_cero.template_path');

        if (! is_string($path) || trim($path) === '') {
            throw new RuntimeException('No se ha configurado la plantilla del Modelo 0 de aprobación.');
        }

        return $path;
    }
}
