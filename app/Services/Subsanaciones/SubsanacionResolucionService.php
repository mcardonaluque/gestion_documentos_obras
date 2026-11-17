<?php

declare(strict_types=1);

namespace App\Services\Subsanaciones;

use App\Models\DocumentoExpediente;
use App\Models\Subsanacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Resolución de solicitudes de subsanación desde el portal de Ayuntamientos.
 *
 * Trabaja sobre la tabla única `subsanaciones`: incorpora al expediente el
 * documento aportado por el ayuntamiento (clave manual: siguiente idDocumento,
 * como el resto de altas automatizadas) y marca la solicitud como subsanada
 * rellenando automáticamente la fecha de subsanación con la fecha actual.
 */
final class SubsanacionResolucionService
{
    /**
     * Resuelve una subsanación pendiente incorporando el documento aportado.
     *
     * @param  array{archivo: string, descripcion?: string|null}  $data
     */
    public function subsanar(Subsanacion $subsanacion, array $data): void
    {
        DB::connection('Obras')->transaction(function () use ($subsanacion, $data): void {
            $pendiente = Subsanacion::query()
                ->pendientes()
                ->whereKey($subsanacion->getKey())
                ->lockForUpdate()
                ->first();

            if (! $pendiente instanceof Subsanacion) {
                throw ValidationException::withMessages([
                    'archivo' => 'La solicitud de subsanación ya no está pendiente.',
                ]);
            }

            $documento = $this->crearDocumentoSubsanacion($pendiente, $data);

            // El hook saved() de Subsanacion sincroniza las fechas espejo del documento requerido.
            $pendiente->forceFill([
                'documento_subsanacion_id' => (string) $documento->getKey(),
                'fecha_subsanacion' => now()->toDateString(),
            ])->save();
        });
    }

    /**
     * Crea el documento de subsanación en el expediente de la solicitud.
     *
     * @param  array{archivo: string, descripcion?: string|null}  $data
     */
    private function crearDocumentoSubsanacion(Subsanacion $subsanacion, array $data): DocumentoExpediente
    {
        $descripcion = filled($data['descripcion'] ?? null)
            ? (string) $data['descripcion']
            : 'Documento de subsanación';

        $payload = DocumentoExpediente::applyExpedienteDefaults([
            'expediente_id' => $subsanacion->expediente_id,
            'descripcion' => $descripcion,
            'nombre_documento' => $descripcion,
            'fechaincorporacion' => now()->toDateString(),
            // El documento aportado corrige al original: hereda su tipo documental.
            'cod_documento' => $subsanacion->documento?->cod_documento,
            'csv' => $data['archivo'],
            'archivo' => $data['archivo'],
            // Estado documental 1 = «Aprobado» (TBestadosdeDocumentos), igual que
            // el resto de altas automatizadas (AprobacionExpedienteDocumentService).
            'estado' => 1,
            'team_id' => $subsanacion->team_id,
        ]);

        // Igual que AprobacionExpedienteDocumentService: alta automatizada sin
        // disparar el observer de reglas de fechas (DateRuleObserver).
        return DocumentoExpediente::withoutEvents(function () use ($payload): DocumentoExpediente {
            $documento = new DocumentoExpediente;
            // idDocumento no es identity: se asigna manualmente y se evita que
            // Eloquent sobrescriba la clave con el resultado de insertGetId.
            $documento->incrementing = false;
            $documento->fill($payload);
            $documento->forceFill([
                'idDocumento' => ((int) (DocumentoExpediente::query()->max('idDocumento') ?? 0)) + 1,
            ]);
            $documento->save();

            return $documento;
        });
    }
}
