<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\DocumentoExpediente;
use App\Models\ModeloCeroAprobacion;
use App\Models\ModeloTresAprobacion;
use App\Models\ModeloUnoAprobacion;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

final class AprobacionExpedienteDocumentService
{
    public function persist(
        ModeloCeroAprobacion|ModeloUnoAprobacion|ModeloTresAprobacion $modelo,
        string $sourcePath,
        int|string $modeloNumero,
        int $documentoGenericoId,
    ): string {
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

        if (! in_array($extension, ['docx', 'pdf'], true)) {
            throw new RuntimeException('El documento generado debe estar en formato DOCX o PDF.');
        }

        $relativePath = sprintf(
            'documentos-expedientes/aprobacion/modelo-%s-%d.%s',
            $modeloNumero,
            $modelo->getKey(),
            $extension,
        );
        $disk = Storage::disk('public');
        $disk->makeDirectory(dirname($relativePath));
        $destinationPath = $disk->path($relativePath);

        if (! copy($sourcePath, $destinationPath)) {
            throw new RuntimeException('No se ha podido guardar el documento generado en el expediente.');
        }

        try {
            $this->upsertDocumento($modelo, $relativePath, $modeloNumero, $documentoGenericoId);
        } catch (Throwable $exception) {
            $disk->delete($relativePath);

            throw $exception;
        }

        return $destinationPath;
    }

    private function upsertDocumento(
        ModeloCeroAprobacion|ModeloUnoAprobacion|ModeloTresAprobacion $modelo,
        string $relativePath,
        int|string $modeloNumero,
        int $documentoGenericoId,
    ): void {
        $nombreDocumento = sprintf('Anexo III. Modelo %d', $modeloNumero);
        $documento = DocumentoExpediente::query()
            ->where('expediente_id', $modelo->expediente_id)
            ->where('cod_documento', $documentoGenericoId)
            ->where('nombre_documento', $nombreDocumento)
            ->first();

        $data = [
            'Codigo_Plan' => $modelo->codigo_plan,
            'referencia' => $modelo->numero_obra,
            'subreferencia' => $modelo->subreferencia,
            'ao_ejecucion' => $modelo->ao_ejecucion,
            'fechaincorporacion' => now()->toDateString(),
            'cod_documento' => $documentoGenericoId,
            'expediente_id' => $modelo->expediente_id,
            'estado' => 1,
            'descripcion' => $nombreDocumento.' generado desde la plantilla de aprobación.',
            'nombre_documento' => $nombreDocumento,
            'archivo' => $relativePath,
            'team_id' => $modelo->team_id,
            'fase' => 'aprobacion',
        ];

        DocumentoExpediente::withoutEvents(function () use ($data, $documento, $modelo): void {
            if ($documento instanceof DocumentoExpediente) {
                $documento->fill($data)->save();

                return;
            }

            $nuevoDocumento = new DocumentoExpediente;
            $nuevoDocumento->forceFill([
                ...$data,
                'idDocumento' => ((int) (DocumentoExpediente::query()->max('idDocumento') ?? 0)) + 1,
                'nsecuencia' => DocumentoExpediente::nextSequenceForExpediente($modelo->expediente_id),
            ]);
            $nuevoDocumento->save();
        });
    }
}
