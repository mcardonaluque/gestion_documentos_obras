<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\ModeloCeroAprobacion;
use App\Models\ModeloCeroDocumentoAprobacion;
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
    /**
     * Genera un DOCX descargable para el Modelo 0 de aprobación indicado.
     */
    public function download(ModeloCeroAprobacion $modelo): BinaryFileResponse
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

        $processor = new TemplateProcessor($templatePath);
        $this->fillHeader($processor, $modelo);
        $this->fillDocumentRows($processor, $modelo);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'modelo-cero-');

        if ($temporaryPath === false) {
            throw new RuntimeException('No se ha podido crear el archivo temporal del Modelo 0.');
        }

        $outputPath = $temporaryPath.'.docx';
        @unlink($temporaryPath);
        $processor->saveAs($outputPath);

        return response()
            ->download($outputPath, sprintf('modelo-0-%s.docx', $modelo->expediente_id))
            ->deleteFileAfterSend(true);
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
                    "REFERENCIA#{$position}" => (string) $documento->referencia,
                    "CONTENIDO#{$position}" => $documento->contenido,
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
