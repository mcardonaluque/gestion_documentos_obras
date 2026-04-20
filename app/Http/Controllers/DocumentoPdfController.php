<?php

namespace App\Http\Controllers;

use App\Models\DocumentoExpediente;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Controlador encargado de resolver y servir la vista previa de PDFs
 * asociados a los documentos de un expediente.
 *
 * El campo de origen puede contener:
 * - una URL completa accesible por navegador,
 * - una ruta absoluta de Windows,
 * - o una ruta relativa dentro de la aplicación.
 */
class DocumentoPdfController extends Controller
{
    /**
     * Muestra el PDF en línea o redirige al enlace externo cuando procede.
     */
    public function show(string $documento): BinaryFileResponse|RedirectResponse
    {
        $record = DocumentoExpediente::query()->findOrFail($documento);

        $rutaArchivo = (string) ($record->pdf_source ?? '');

        abort_if($rutaArchivo === '', 404, 'El documento no tiene un PDF asociado.');

        if ($record->isPdfUrl()) {
            return redirect()->away($rutaArchivo);
        }

        $pdfPath = $record->resolvePdfPath();

        abort_if($pdfPath === null, 404, 'No se ha encontrado el archivo PDF indicado.');

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"',
        ]);
    }

    /**
     * Descarga el PDF asociado al documento o redirige a la URL externa.
     */
    public function download(string $documento): BinaryFileResponse|RedirectResponse
    {
        $record = DocumentoExpediente::query()->findOrFail($documento);

        $rutaArchivo = (string) ($record->pdf_source ?? '');

        abort_if($rutaArchivo === '', 404, 'El documento no tiene un PDF asociado.');

        if ($record->isPdfUrl()) {
            return redirect()->away($rutaArchivo);
        }

        $pdfPath = $record->resolvePdfPath();

        abort_if($pdfPath === null, 404, 'No se ha encontrado el archivo PDF indicado.');

        return response()->download($pdfPath, basename($pdfPath), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
