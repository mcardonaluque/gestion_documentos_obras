<?php

namespace App\Http\Controllers;

use App\Models\DocumentoExpediente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
     * Muestra un PDF subido que todavía no se ha asociado a ningún documento.
     */
    public function showTemporary(string $archivo): BinaryFileResponse
    {
        $path = Storage::disk('local')->path('previsualizaciones-pdf/' . basename($archivo));

        abort_unless(is_file($path), 404, 'La vista previa ya no está disponible.');

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="previsualizacion.pdf"',
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
